<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderCanceledNotification;
use App\Mail\OrderDeliveredNotification;
use App\Mail\OrderPartiallyRefundedNotification;
use App\Mail\OrderProcessedNotification;
use App\Mail\OrderRefundedNotification;
use App\Mail\OrderShippedNotification;
use App\Mail\PaymentReceivedNotification;
use App\Models\Order;
use App\Services\RefundService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = \App\Models\Order::with(['items', 'items.product.images', 'refunds'])->findOrFail($id);
        $refundService      = app(RefundService::class);
        $canRefundViaPayPal = $refundService->canRefundViaPayPal($order);
        $canRefundViaStripe = $refundService->canRefundViaStripe($order);
        $canRefundViaApi    = $canRefundViaPayPal || $canRefundViaStripe;
        return view('admin.orders.show', compact('order', 'canRefundViaPayPal', 'canRefundViaStripe', 'canRefundViaApi'));
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,processed,shipped,delivered,refunded,partially_refunded,canceled,ach_pending,ach_failed',
            'shipping_method' => 'nullable|string|max:255',
            'tracking_number' => 'nullable|string|max:255',
        ]);

        $order = Order::findOrFail($id);

        // If coming from the index table and user selected "shipped",
        // send them to the detailed view to fill in shipping details first.
        if ($request->status === 'shipped' && $request->input('from') === 'index') {
            return redirect()
                ->to(route('admin.orders.show', $order->id) . '?status=shipped#shipping-section')
                ->with('info', 'To mark this order as shipped, please enter the shipping method and tracking number below.');
        }

        if ($request->status === 'partially_refunded') {
            return redirect()
                ->to(route('admin.orders.show', $order->id) . '#refunds')
                ->with('info', 'To issue a partial refund, please enter the amount in the Partial Refund section below.');
        }

        $previousStatus = $order->status;
        $order->status = $request->status;

        if ($request->status === 'shipped') {
            $request->validate([
                'shipping_method' => 'required|string|max:255',
                'tracking_number' => 'required|string|max:255',
            ]);
            $order->shipping_method = $request->input('shipping_method');
            $order->tracking_number = $request->input('tracking_number');
        }

        $order->save();

        if ($previousStatus !== $request->status) {
            try {
                if ($request->status === 'paid' && $previousStatus === 'pending') {
                    Mail::to($order->billing_email)->send(new PaymentReceivedNotification($order));
                } elseif ($request->status === 'processed') {
                    Mail::to($order->billing_email)->send(new OrderProcessedNotification($order));
                } elseif ($request->status === 'shipped') {
                    Mail::to($order->billing_email)->send(new OrderShippedNotification($order));
                } elseif ($request->status === 'delivered') {
                    Mail::to($order->billing_email)->send(new OrderDeliveredNotification($order));
                } elseif ($request->status === 'refunded') {
                    Mail::to($order->billing_email)->send(new OrderRefundedNotification($order));
                } elseif ($request->status === 'canceled') {
                    Mail::to($order->billing_email)->send(new OrderCanceledNotification($order));
                } elseif ($request->status === 'partially_refunded') {
                    $amount = (float) ($order->refunded_amount ?? 0);
                    Mail::to($order->billing_email)->send(new OrderPartiallyRefundedNotification($order, $amount));
                }
            } catch (\Throwable $e) {
                Log::error('Order status email failed', [
                    'order_id' => $order->id,
                    'status' => $request->status,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }

        return back()->with('success', 'Order status updated to ' . $order->status . '.');
    }

    public function updateNotes(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:5000',
        ]);

        $order = Order::findOrFail($id);
        $order->admin_notes = $request->input('admin_notes');
        $order->save();

        return back()->with('success', 'Admin notes saved.');
    }

    /**
     * Cancel order without processing a refund.
     */
    public function cancelOrder(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if ($order->status === 'canceled') {
            return redirect()->route('admin.orders.show', $order->id)->with('info', 'Order is already canceled.');
        }

        try {
            $order->status = 'canceled';
            $order->save();

            Mail::to($order->billing_email)->send(new OrderCanceledNotification($order));
        } catch (\Throwable $e) {
            Log::error('Order cancel operation failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
            return redirect()->route('admin.orders.show', $order->id)->with('error', 'Failed to cancel order.');
        }

        return redirect()->route('admin.orders.show', $order->id)->with('success', 'Order canceled.');
    }

    /**
     * Full or partial refund via PayPal.
     */
    public function refund(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $refundService = app(RefundService::class);

        $amount = $request->input('amount');
        $reason = $request->input('reason');

        try {
            if ($amount === null || $amount === '') {
                $result = $refundService->fullRefund($order, $reason);
            } else {
                $request->validate([
                    'amount' => 'required|numeric|min:0.01',
                ]);
                $result = $refundService->partialRefund($order, (float) $amount, $reason);
            }

            if ($result['success']) {
                try {
                    $order->refresh();
                    if ($amount === null || $amount === '') {
                        Mail::to($order->billing_email)->send(new OrderRefundedNotification($order));
                    } else {
                        Mail::to($order->billing_email)->send(new OrderPartiallyRefundedNotification($order, (float) $amount));
                    }
                } catch (\Throwable $e) {
                    Log::error('Refund notification email failed', ['order_id' => $order->id, 'error' => $e->getMessage()]);
                }
                if ($request->expectsJson()) {
                    return response()->json(['success' => true, 'message' => $result['message']]);
                }
                return redirect()->route('admin.orders.show', $order->id)->with('success', $result['message']);
            }

            $errorMsg = is_string($result['error'] ?? null) ? $result['error'] : 'Refund failed.';
            $redirect = redirect()->route('admin.orders.show', $order->id)->with('error', $errorMsg);
            if (!empty($result['debug'])) {
                $redirect->with('refund_debug', $result['debug']);
            }
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMsg,
                    'debug' => $result['debug'] ?? null,
                ], 422);
            }
            return $redirect;
        } catch (\Throwable $e) {
            \Log::error('Refund error: ' . $e->getMessage(), ['order_id' => $order->id, 'trace' => $e->getTraceAsString()]);
            $msg = 'An unexpected error occurred. The refund may have been processed—check PayPal and order status.';
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 500);
            }
            return redirect()->route('admin.orders.show', $order->id)->with('error', $msg);
        }
    }
} 