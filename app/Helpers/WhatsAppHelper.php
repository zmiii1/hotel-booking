<?php

namespace App\Helpers;

class WhatsAppHelper
{
    // Hotel main hotline
    private static $mainHotline = '6285255717166';
    private static $alternativeHotline = '6285255717166';
    
    /**
     * Generate WhatsApp link for MICE/Meeting Room booking
     */
    public static function generateMeetingRoomLink($roomType, $price, $capacity = null)
    {
        $message = "🏢 *Meeting Room Booking Request*\n\n";
        $message .= "Hi! I'd like to book: *{$roomType}*\n";
        $message .= "Price: {$price}\n";
        
        if ($capacity) {
            $message .= "Capacity: {$capacity}\n";
        }
        
        $message .= "\nPlease provide information about:\n";
        $message .= "• Available dates and time slots\n";
        $message .= "• Facilities included\n";
        $message .= "• Catering options\n";
        $message .= "• Booking procedures\n";
        $message .= "• Payment terms\n\n";
        $message .= "Thank you for your assistance! 📝";
        
        return "https://wa.me/" . self::$mainHotline . "?text=" . urlencode($message);
    }
    
    /**
     * Generate WhatsApp link for Wedding packages
     */
    public static function generateWeddingLink($packageName, $price)
    {
        $message = "💍 *Wedding Package Inquiry*\n\n";
        $message .= "Hi! I'm interested in: *{$packageName}*\n";
        $message .= "Price: {$price}\n\n";
        $message .= "Could you please send details about:\n";
        $message .= "• Complete wedding package inclusions\n";
        $message .= "• Venue decoration options\n";
        $message .= "• Catering menu choices\n";
        $message .= "• Photography/videography services\n";
        $message .= "• Available dates\n";
        $message .= "• Payment plans\n\n";
        $message .= "Looking forward to planning our special day! 💕";
        
        return "https://wa.me/" . self::$mainHotline . "?text=" . urlencode($message);
    }
    
    /**
     * Generate WhatsApp link for activities
     */
    public static function generateActivityLink($activityName, $price = null, $duration = null, $category = 'activity')
    {
        $message = "🏖️ *Tanjung Lesung Activity Booking*\n\n";
        $message .= "Hi! I'm interested in: *{$activityName}*\n";
        
        if ($duration) {
            $message .= "Duration: {$duration}\n";
        }
        
        if ($price) {
            $message .= "Price: {$price}\n";
        }
        
        $message .= "\nCould you please provide:\n";
        $message .= "• Availability schedule\n";
        $message .= "• Booking process\n";
        $message .= "• Safety requirements\n";
        $message .= "• Group size options\n";
        $message .= "• What's included\n\n";
        $message .= "Thank you! 😊";
        
        return "https://wa.me/" . self::$mainHotline . "?text=" . urlencode($message);
    }
    
    /**
     * Generate WhatsApp link for trip packages
     */
    public static function generatePackageLink($packageName, $description = null)
    {
        $message = "🏝️ *Trip Package Inquiry*\n\n";
        $message .= "Hi! I want to book: *{$packageName}*\n";
        
        if ($description) {
            $message .= "Package: {$description}\n";
        }
        
        $message .= "\nPlease send me details about:\n";
        $message .= "• Complete itinerary\n";
        $message .= "• Pricing and packages\n";
        $message .= "• What's included/excluded\n";
        $message .= "• Departure schedule\n";
        $message .= "• Group size requirements\n";
        $message .= "• Booking terms\n\n";
        $message .= "Looking forward to this adventure! 🌊";
        
        return "https://wa.me/" . self::$mainHotline . "?text=" . urlencode($message);
    }
    
    /**
     * Generate WhatsApp link for golf booking
     */
    public static function generateGolfLink($holes, $facility = 'Salaka Golf')
    {
        $message = "⛳ *Golf Booking Request*\n\n";
        $message .= "Hi! I'd like to book:\n";
        $message .= "Facility: *{$facility}*\n";
        $message .= "Type: *{$holes} Driving Range*\n\n";
        $message .= "Please let me know:\n";
        $message .= "• Available time slots\n";
        $message .= "• Pricing details\n";
        $message .= "• Equipment rental options\n";
        $message .= "• Booking procedure\n\n";
        $message .= "Thanks for your assistance! 🏌️‍♂️";
        
        return "https://wa.me/" . self::$mainHotline . "?text=" . urlencode($message);
    }
    
    /**
     * Generate WhatsApp link for water activities
     */
    public static function generateWaterActivityLink($activityName)
    {
        $message = "🏄‍♀️ *Water Activity Booking*\n\n";
        $message .= "Halo! Saya tertarik dengan *{$activityName}* at Lalassa Beach\n\n";
        
        return "https://wa.me/" . self::$mainHotline . "?text=" . urlencode($message);
    }
    
    /**
     * Generate WhatsApp link for land activities (MTB, etc.)
     */
    public static function generateLandActivityLink($activityName, $duration, $price)
    {
        $message = "🚴‍♂️ *Land Activity Booking*\n\n";
        $message .= "Halo! Saya ingin mengetahui detail: *{$activityName}*\n";
        
        return "https://wa.me/" . self::$mainHotline . "?text=" . urlencode($message);
    }
    
    /**
     * Generate general inquiry WhatsApp link
     */
    public static function generateGeneralInquiry($subject = 'General Inquiry')
    {
        $message = "👋 *Tanjung Lesung Resort*\n\n";
        $message .= "Halo! Saya mempunyai pertanyaan terkait {$subject}.\n\n";
        
        return "https://wa.me/" . self::$mainHotline . "?text=" . urlencode($message);
    }
    
    /**
     * Generate contact form notification for admin
     */
    public static function generateContactNotification($contactData)
    {
        $message = "📝 *New Contact Form Message*\n\n";
        $message .= "Name: {$contactData['name']}\n";
        $message .= "Email: {$contactData['email']}\n";
        
        if (isset($contactData['phone']) && !empty($contactData['phone'])) {
            $message .= "Phone: {$contactData['phone']}\n";
        }
        
        $message .= "Message: {$contactData['message']}\n\n";
        $message .= "Received at: " . now()->format('d M Y H:i') . "\n";
        $message .= "Please follow up promptly! 📞";
        
        return "https://wa.me/" . self::$alternativeHotline . "?text=" . urlencode($message);
    }
    
    /**
     * Get phone numbers for display
     */
    public static function getMainHotline()
    {
        return self::$mainHotline;
    }
    
    public static function getAlternativeHotline()
    {
        return self::$alternativeHotline;
    }
    
    /**
     * Format phone number for display
     */
    public static function formatPhoneForDisplay($phone)
    {
        // Convert 628xxx to +62 8xxx format
        if (strpos($phone, '62') === 0) {
            return '+' . substr($phone, 0, 2) . ' ' . substr($phone, 2, 3) . '-' . substr($phone, 5, 4) . '-' . substr($phone, 9);
        }
        return $phone;
    }
    
    /**
     * Generate hotel room inquiry link
     */
    public static function generateHotelRoomLink($hotelName, $roomType = null)
    {
        $message = "🏨 *Hotel Room Inquiry*\n\n";
        $message .= "Hi! I'm interested in staying at: *{$hotelName}*\n";
        
        if ($roomType) {
            $message .= "Room Type: {$roomType}\n";
        }
        
        $message .= "\nCould you please provide:\n";
        $message .= "• Room availability\n";
        $message .= "• Pricing information\n";
        $message .= "• Facilities included\n";
        $message .= "• Booking procedures\n";
        $message .= "• Special packages available\n\n";
        $message .= "Thank you! 🏖️";
        
        return "https://wa.me/" . self::$mainHotline . "?text=" . urlencode($message);
    }
}