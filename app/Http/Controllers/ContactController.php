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
    public function index(Request $request)
    {
        $query = Contact::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        $contacts = $query->paginate(10);

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
        $validated = $this->validateContact($request);

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
        $validated = $this->validateContact($request);

        // Handle delete checkbox
        if ($request->has('delete_attachment') && $contact->attachment) {
            if (Storage::disk('public')->exists($contact->attachment)) {
                Storage::disk('public')->delete($contact->attachment);
            }
            $contact->attachment = null;
        }

        // Handle new file upload
        if ($request->hasFile('attachment')) {
            if ($contact->attachment && Storage::disk('public')->exists($contact->attachment)) {
                Storage::disk('public')->delete($contact->attachment);
            }

            $filePath = $request->file('attachment')->store('contact', 'public');
            $validated['attachment'] = $filePath;
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

    /**
     * Validate incoming contact form data for both store and update methods.
     *
     * Required fields:
     * - name
     * - phone
     * - email - Email address format
     * - description
     * - attachment (optional)
     *
     * Phone validation accepts:
     * - 10-digit numbers without separators (e.g., 9876543210)
     * - Numbers with spaces or hyphens (e.g., 123-456-7890, 123 456 7890)
     * - International formats (e.g., +91 9876543210)
     * - Optional brackets around area code (e.g., (123) 456-7890)
     *
     * Disallowed:
     * - Letters or special characters
     * - Numbers shorter than 10 digits
     * - Dot separators (e.g., 123.456.7890)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed> Validated input data
     */
    protected function validateContact(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'phone' => [
                'required',
                'string',
                'max:20',
                'regex:/^(\+?\d{1,4}[\s-]?)?(\(?\d{3}\)?[\s-]?)?\d{3}[\s-]?\d{4}$/'
            ],
            'email' => 'required|email',
            'description' => 'required|string',
            'attachment' => 'nullable|mimes:jpg,jpeg,png|max:2048',
        ]);
    }
}
