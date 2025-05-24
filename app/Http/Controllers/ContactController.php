<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function create()
    {
        return view('contact.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'user_type' => 'required|in:student,staff,visitor',
            'message' => 'required|string',
        ]);

        // Ensure at least one contact method is provided
        if (empty($validated['email']) && empty($validated['phone'])) {
            return back()->withErrors([
                'email' => 'Please provide either an email or phone number.',
                'phone' => 'Please provide either an email or phone number.',
            ])->withInput();
        }

        // Create the contact message
        Contact::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'user_type' => $validated['user_type'],
            'message' => $validated['message'],
            'is_read' => false,
        ]);

        return redirect()->route('home')
            ->with('success', 'Your message has been sent successfully. We will get back to you soon.');
    }
}
