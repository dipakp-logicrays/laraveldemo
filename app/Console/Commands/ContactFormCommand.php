<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contact;
use App\Http\Controllers\ContactController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactSubmitted;
use App\Mail\ContactSubmittedAdmin;

class ContactFormCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contact';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Submit a new contact form entry via command line';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $name = $this->ask('Enter name');
        $phone = $this->ask('Enter phone');
        $email = $this->ask('Enter email');
        $description = $this->ask('Enter description');

        $data = [
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'description' => $description,
        ];

        // Validate manually
        $validator = validator($data, [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            $this->error('Validation failed:');
            foreach ($validator->errors()->all() as $message) {
                $this->line(" - $message");
            }
            return;
        }

        // Store contact using model (avoiding controller directly)
        $contact = Contact::create($data);

        // Send mail to customer
        Mail::to($contact->email)->send(new ContactSubmitted($contact));

        // Send mail to admin
        Mail::to('contact@laraveldemo.com')->send(new ContactSubmittedAdmin($contact));

        $this->info('Contact created and emails sent successfully.');
    }
}
