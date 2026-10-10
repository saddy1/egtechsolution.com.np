<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $data = $request->validate([
            'name'    => ['required','string','max:255'],
            'email'   => ['required','email','max:255'],
            'phone'   => ['nullable','string','max:40'],
            'subject' => ['required','string','max:255'],
            'message' => ['required','string','max:5000'],
        ]);

        // Send to your team (add more recipients if you want)
        try {
            Mail::raw(
                "New contact message from {$data['name']}\n\n".
                "Email: {$data['email']}\nPhone: ".($data['phone'] ?? '—')."\n".
                "Subject: {$data['subject']}\n\n".
                "Message:\n{$data['message']}",
                function ($mail) use ($data) {
                    $mail->to('egtechsolution1@gmail.com')
                        ->cc('sadanand@ioepc.edu.np')
                        ->subject('📨 Contact: '.$data['subject'].' — '.$data['name']);
                }
            );
        } catch (\Throwable $e) {
            return back()->withErrors(['send' => 'Failed to send your message. Please try again.'])
                         ->withInput();
        }

        return back()->with('success', 'Thanks! Your message has been sent. We’ll get back to you soon.');
    }
}
