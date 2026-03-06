<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;

class ContactInquiryController extends Controller
{
    public function index()
    {
        $inquiries = ContactInquiry::orderBy('created_at', 'desc')->get();
        return view('admin.contact-inquiries.index', compact('inquiries'));
    }

    public function show($id)
    {
        $inquiry = ContactInquiry::findOrFail($id);
        return view('admin.contact-inquiries.show', compact('inquiry'));
    }
}
