<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Mail\ContactSubmitted;
use App\Mail\ContactSubmittedAdmin;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contacts = Contact::all();
        return view('contacts.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('contacts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email',
            'description' => 'required|string',
            'attachment' => 'nullable|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('contact', 'public');
            $validated['attachment'] = $path;
        }

        $contact = Contact::create($validated);

        // Send email to customer
        Mail::to($contact->email)->send(new ContactSubmitted($contact));

        // Send email to admin
        Mail::to('contact@laraveldemo.com')->send(new ContactSubmittedAdmin($contact));

        return redirect()->route('contacts.index')->with('success', 'Contact created and email sent successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        return view('contacts.show', compact('contact'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        return view('contacts.edit', compact('contact'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        $validated = $request->validate(
            [
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'email' => 'required|email',
                'description' => 'required|string',
                'attachment' => 'nullable|mimes:jpg,jpeg,png|max:2048'
            ]
        );

        if ($request->hasFile('attachment')) {
            if ($contact->attachment) {
                Storage::disk('public')->delete($contact->attachment);
            }
            $file = $request->file('attachment');
            $path = $file->store('contact', 'public');
            $validated['attachment'] = $path;
        }

        $contact->update($validated);
        return redirect()->route('contacts.index')->with('success', 'Contact updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect()->route('contacts.index')->with('success', 'Contact deleted successfully.');
    }
}
