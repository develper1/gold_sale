<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\Product;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons = Coupon::with('product')->get();
        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        return view('admin.coupons.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'code' => 'required|unique:coupons,code',
            'description' => 'required',
            'valid_from' => 'required',
            'valid_to' => 'required',
            // 'discount' => 'required|numeric',
            // 'discount_type' => 'required|in:percent,dollar',
            'free_shipping' => 'nullable|boolean',
            'free_service_fee' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ];

        $discountType = $request->input('discount_type');
        if (in_array($discountType, ['percent', 'dollar'])) {
            $rules['discount'] = 'required|numeric|min:0';
            if ($discountType === 'percent') {
                $rules['discount'] .= '|max:100';
            }
        }

        $validated = $request->validate($rules);

        $hasBenefit = $request->boolean('free_shipping') || $request->boolean('free_service_fee')
            || (in_array($discountType, ['percent', 'dollar']) && $request->filled('discount'));
        if (!$hasBenefit) {
            return redirect()->back()->withInput()->withErrors(['discount_type' => 'Please select at least one benefit: Free Shipping, Free Service Fee, or a Percent/Dollar discount.']);
        }

        $data = $request->only(['code', 'description', 'valid_from', 'valid_to', 'free_shipping', 'free_service_fee', 'is_active']);
        $data['free_shipping'] = $request->boolean('free_shipping');
        $data['free_service_fee'] = $request->boolean('free_service_fee');
        $data['is_active'] = $request->boolean('is_active');

        if (in_array($discountType, ['percent', 'dollar'])) {
            $data['discount'] = $request->input('discount');
            $data['discount_type'] = $discountType;
        } else {
            $data['discount'] = null;
            $data['discount_type'] = null;
        }

        Coupon::create($data);
        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        $products = Product::all();
        return view('admin.coupons.edit', compact('coupon', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);
        $rules = [
            'code' => 'required|unique:coupons,code,' . $coupon->id,
            'description' => 'required',
            'valid_from' => 'required',
            'valid_to' => 'required',
            'free_shipping' => 'nullable|boolean',
            'free_service_fee' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ];

        $discountType = $request->input('discount_type');
        if (in_array($discountType, ['percent', 'dollar'])) {
            $rules['discount'] = 'required|numeric|min:0';
            if ($discountType === 'percent') {
                $rules['discount'] .= '|max:100';
            }
        }

        $validated = $request->validate($rules);

        $hasBenefit = $request->boolean('free_shipping') || $request->boolean('free_service_fee')
            || (in_array($discountType, ['percent', 'dollar']) && $request->filled('discount'));
        if (!$hasBenefit) {
            return redirect()->back()->withInput()->withErrors(['discount_type' => 'Please select at least one benefit: Free Shipping, Free Service Fee, or a Percent/Dollar discount.']);
        }

        $data = $request->only(['code', 'description', 'valid_from', 'valid_to']);
        $data['free_shipping'] = $request->boolean('free_shipping');
        $data['free_service_fee'] = $request->boolean('free_service_fee');
        $data['is_active'] = $request->boolean('is_active');

        if (in_array($discountType, ['percent', 'dollar'])) {
            $data['discount'] = $request->input('discount');
            $data['discount_type'] = $discountType;
        } else {
            $data['discount'] = null;
            $data['discount_type'] = null;
        }

        $coupon->update($data);
        return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'Coupon deleted!');
    }
}
