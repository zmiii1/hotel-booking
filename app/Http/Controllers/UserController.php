<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Hotel;
use App\Models\RoomType;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Helpers\WhatsAppHelper;

class UserController extends Controller
{
    public function HomePage()
    {
        return view('frontend.index');
    }
    
    public function TanjungLesungBeachHotel()
    {
        return view('frontend.room.tanjung_lesung');
    }
    
    public function KalicaaVilla()
    {
        return view('frontend.room.kalicaa_villa');
    }
    
    public function LaddaBayVillage()
    {
        return view('frontend.room.ladda_bay');
    }
    
    public function LalassaBeachClub()
    {
        return view('frontend.room.lalassa_beach');
    }

    public function RoomDetails(){

        return view('frontend.room.room_details');
    }
    
    public function Mice()
    {
        return view('frontend.pages.mice');
    }
    
    public function Activities()
    {
        return view('frontend.pages.activities');
    }
    
    public function Beach()
    {
        return view('frontend.pages.beach.beach');
    }

    public function BeachCart()
    {
        return view('frontend.pages.beach.beach_cart');
    }
    
    public function BeachCheckout()
    {
        return view('frontend.pages.beach.beach_checkout');
    }

    public function ContactUs()
    {
        return view('frontend.pages.contact');
    }

    /**
     * Handle contact form submission
     */
    public function submitContactForm(Request $request)
{
    try {
        // Validate the form data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|min:2',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string|min:10|max:5000'
        ], [
            'name.required' => 'Full name is required',
            'name.min' => 'Name must be at least 2 characters',
            'email.required' => 'Email address is required',
            'email.email' => 'Please enter a valid email address',
            'message.required' => 'Message is required',
            'message.min' => 'Message must be at least 10 characters',
            'message.max' => 'Message cannot exceed 5000 characters'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please check your form and try again.');
        }

        $contactData = [
            'name' => trim($request->name),
            'email' => trim($request->email),
            'phone' => $request->phone ? trim($request->phone) : null,
            'message' => trim($request->message),
            'submitted_at' => now(),
            'ip_address' => $request->ip()
        ];

        // Send email to hotel management
        $this->sendContactEmail($contactData);
        
        Log::info('Contact form processed successfully', [
            'name' => $contactData['name'],
            'email' => $contactData['email']
        ]);
        
        return redirect()->back()->with([
            'success' => 'Thank you for your message! We will get back to you within 24 hours.',
            'sent' => true
        ]);

    } catch (\Exception $e) {
        Log::error('Contact form submission error: ' . $e->getMessage());
        
        return redirect()->back()
            ->withInput()
            ->with('error', 'Sorry, there was an error sending your message. Please try again or contact us directly via WhatsApp or phone.');
    }
}
    
    /**
     * Send contact email to hotel management
     */
    private function sendContactEmail($contactData)
{
    $hotelEmail = 'info@tanjunglesung.com';
    $backupEmail = 'reservation@tanjunglesung.com';
    
    // GANTI NAMA VARIABLE UNTUK MENGHINDARI KONFLIK
    $emailData = [
        'contactName' => $contactData['name'],           // GANTI dari 'name'
        'contactEmail' => $contactData['email'],         // GANTI dari 'email'  
        'contactPhone' => $contactData['phone'] ?? 'Not provided', // GANTI dari 'phone'
        'contactMessage' => $contactData['message'],     // GANTI dari 'message'
        'submittedAt' => $contactData['submitted_at']->format('d M Y, H:i A'), // GANTI dari 'submitted_at'
        'ipAddress' => $contactData['ip_address']        // GANTI dari 'ip_address'
    ];
    
    $emailSubject = 'New Contact Form Submission - Tanjung Lesung Resort';
    
    try {
        Log::info('Attempting to send contact email', [
            'to' => $hotelEmail,
            'from' => $contactData['email']
        ]);
        
        // Send to main email
        Mail::send('emails.contact-form', $emailData, function($mail) use ($hotelEmail, $emailSubject, $contactData) {
            $mail->to($hotelEmail)
                 ->subject($emailSubject)
                 ->replyTo($contactData['email'], $contactData['name'])
                 ->from(config('mail.from.address'), config('mail.from.name'));
        });
        
        Log::info('Contact email sent successfully to hotel');
        
        // Send confirmation email to customer
        $this->sendCustomerConfirmation($contactData);
        
    } catch (\Exception $e) {
        Log::error('Primary email sending failed', [
            'error' => $e->getMessage(),
            'to' => $hotelEmail
        ]);
        
        // Try backup email
        try {
            Mail::send('emails.contact-form', $emailData, function($mail) use ($backupEmail, $emailSubject, $contactData) {
                $mail->to($backupEmail)
                     ->subject($emailSubject . ' (Backup)')
                     ->replyTo($contactData['email'], $contactData['name'])
                     ->from(config('mail.from.address'), config('mail.from.name'));
            });
            
            Log::info('Contact email sent successfully to backup email');
            
        } catch (\Exception $e2) {
            Log::error('Backup email also failed', [
                'error' => $e2->getMessage(),
                'to' => $backupEmail
            ]);
            throw new \Exception('Failed to send email to both primary and backup addresses: ' . $e2->getMessage());
        }
    }
}
    
    /**
     * Send confirmation email to customer
     */
    private function sendCustomerConfirmation($contactData)
{
    $confirmationData = [
        'customerName' => $contactData['name'],          // GANTI dari 'name'
        'customerMessage' => $contactData['message'],    // GANTI dari 'message'
        'submittedAt' => $contactData['submitted_at']->format('d M Y, H:i A') // GANTI dari 'submitted_at'
    ];
    
    $confirmationSubject = 'Thank you for contacting Tanjung Lesung Resort';
    
    try {
        Log::info('Attempting to send customer confirmation email', [
            'to' => $contactData['email']
        ]);
        
        Mail::send('emails.contact-confirmation', $confirmationData, function($mail) use ($contactData, $confirmationSubject) {
            $mail->to($contactData['email'], $contactData['name'])
                 ->subject($confirmationSubject)
                 ->from(config('mail.from.address'), config('mail.from.name'));
        });
        
        Log::info('Customer confirmation email sent successfully');
        
    } catch (\Exception $e) {
        Log::error('Customer confirmation email failed', [
            'error' => $e->getMessage(),
            'to' => $contactData['email']
        ]);
    }
}


    public function RoomAddons()
    {
        return view('frontend.room.room_addons');
    }

    public function BookingInformation()
    {
        return view('frontend.room.booking_information');
    }
}