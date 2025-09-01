<?php

namespace App\Services;

use App\Models\ContactSubmission;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use App\Mail\ContactFormSubmission;

class ContactService
{
    /**
     * Handle contact form submission
     */
    public function handleSubmission(array $data): ContactSubmission
    {
        // Store the submission in database
        $submission = ContactSubmission::create($data);
        
        // Send email notification to admin
        $this->sendEmailNotification($submission);
        
        return $submission;
    }
    
    /**
     * Send email notification to admin
     */
    private function sendEmailNotification(ContactSubmission $submission): void
    {
        try {
            $adminEmail = config('mail.admin_email', 'admin@cms.com');
            
            Mail::to($adminEmail)->send(new ContactFormSubmission($submission));
            
        } catch (\Exception $e) {
            // Log the error but don't throw it to avoid failing the API response
            \Log::error('Failed to send contact form email: ' . $e->getMessage());
        }
    }
}
