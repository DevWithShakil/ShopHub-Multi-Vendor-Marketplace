<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'user_message' => $request->message,
        ];

        Mail::send([], [], function ($message) use ($data) {
            $message->to('admin@shophub.com')
                ->subject('New Contact Message: ' . $data['subject'])
                ->html("
                    <h3>New Message from ShopHub</h3>
                    <p><strong>Name:</strong> {$data['name']}</p>
                    <p><strong>Email:</strong> {$data['email']}</p>
                    <p><strong>Message:</strong><br>{$data['user_message']}</p>
                ");
        });

        return back()->with('success', 'Your message has been sent successfully!');
    }
}
