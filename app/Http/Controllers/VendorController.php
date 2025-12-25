<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VendorRequest; // আপনি যদি ডাটাবেজে সেভ করতে চান
use Illuminate\Support\Facades\Mail;

class VendorController extends Controller
{
    /**
     * ভেন্ডর রেজিস্ট্রেশন ডাটা স্টোর করা
     */
    public function store(Request $request)
    {
        // ১. ভ্যালিডেশন
        $request->validate([
            'shop_name'     => 'required|string|max:255',
            'owner_name'    => 'required|string|max:255',
            'email'         => 'required|email|max:255',
            'phone'         => 'required|string|max:20',
            'business_type' => 'required|string',
            'description'   => 'required|string|min:20',
        ]);

        // ২. ডাটা গুছিয়ে নেওয়া
        $data = $request->only([
            'shop_name', 'owner_name', 'email', 'phone', 'business_type', 'description'
        ]);

        // ৩. ইমেইল নোটিফিকেশন (অ্যাডমিনকে জানানো)
        try {
            Mail::send([], [], function ($message) use ($data) {
                $message->to('admin@shophub.com') // আপনার অ্যাডমিন ইমেইল
                    ->subject('New Vendor Partnership Request: ' . $data['shop_name'])
                    ->html("
                        <div style='font-family: sans-serif; padding: 20px; border: 1px solid #eee;'>
                            <h2 style='color: #4f46e5;'>New Vendor Request Received</h2>
                            <p><strong>Shop Name:</strong> {$data['shop_name']}</p>
                            <p><strong>Owner:</strong> {$data['owner_name']}</p>
                            <p><strong>Email:</strong> {$data['email']}</p>
                            <p><strong>Phone:</strong> {$data['phone']}</p>
                            <p><strong>Category:</strong> " . ucfirst($data['business_type']) . "</p>
                            <p><strong>About Business:</strong><br>{$data['description']}</p>
                            <hr>
                            <p style='font-size: 12px; color: #666;'>This message was sent from ShopHub Vendor Portal.</p>
                        </div>
                    ");
            });
        } catch (\Exception $e) {
        }
        return back()->with('success', 'Your partnership request has been submitted. We will contact you soon!');
    }
}
