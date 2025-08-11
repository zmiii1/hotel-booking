<?php

namespace Database\Seeders;

use App\Models\BeachTicket;
use App\Models\TicketBenefit;
use Illuminate\Database\Seeder;

class BeachTicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Lalassa Beach Club Regular Ticket
        $lalassaRegular = BeachTicket::create([
            'name' => 'Regular Ticket - Lalassa Beach Club',
            'description' => 'Regular entrance ticket for Lalassa Beach Club',
            'price' => 100000,
            'beach_name' => 'lalassa',
            'ticket_type' => 'regular',
            'image_url' => 'frontend/assets/img/lalassa-beach.jpg',
            'active' => true
        ]);
        
        // Add benefits for Lalassa Regular
        $this->addBenefits($lalassaRegular->id, [
            'Ticket Entrance',
            'Valid for 1 Person',
            'Access to Beach Area',
            'Access to Swimming Pool'
        ]);
        
        // Create Lalassa Beach Club Bundling Ticket
        $lalassaBundling = BeachTicket::create([
            'name' => 'Bundling Ticket - Lalassa Beach Club',
            'description' => 'Bundling ticket including meal and drink for Lalassa Beach Club',
            'price' => 150000,
            'beach_name' => 'lalassa',
            'ticket_type' => 'bundling',
            'image_url' => 'frontend/assets/img/lalassa-beach.jpg',
            'active' => true
        ]);
        
        // Add benefits for Lalassa Bundling
        $this->addBenefits($lalassaBundling->id, [
            'Ticket Entrance',
            'Valid for 1 Person',
            'Access to Beach Area',
            'Access to Swimming Pool',
            'Voucher Meal',
            'Voucher Drink'
        ]);
        
        // Create Bodur Beach Regular Ticket
        $bodurRegular = BeachTicket::create([
            'name' => 'Regular Ticket - Bodur Beach',
            'description' => 'Regular entrance ticket for Bodur Beach',
            'price' => 100000,
            'beach_name' => 'bodur',
            'ticket_type' => 'regular',
            'image_url' => 'frontend/assets/img/bodur-beach.jpeg',
            'active' => true
        ]);
        
        // Add benefits for Bodur Regular
        $this->addBenefits($bodurRegular->id, [
            'Ticket Entrance',
            'Valid for 1 Person',
            'Access to Beach Area'
        ]);
        
        // Create Bodur Beach Bundling Ticket
        $bodurBundling = BeachTicket::create([
            'name' => 'Bundling Ticket - Bodur Beach',
            'description' => 'Bundling ticket including meal and drink for Bodur Beach',
            'price' => 150000,
            'beach_name' => 'bodur',
            'ticket_type' => 'bundling',
            'image_url' => 'frontend/assets/img/bodur-beach.jpeg',
            'active' => true
        ]);
        
        // Add benefits for Bodur Bundling
        $this->addBenefits($bodurBundling->id, [
            'Ticket Entrance',
            'Valid for 1 Person',
            'Access to Beach Area',
            'Voucher Meal',
            'Voucher Drink'
        ]);
    }
    
    /**
     * Helper function to add benefits to a ticket
     *
     * @param int $ticketId
     * @param array $benefits
     * @return void
     */
    private function addBenefits($ticketId, $benefits)
    {
        foreach ($benefits as $benefit) {
            TicketBenefit::create([
                'beach_ticket_id' => $ticketId,
                'benefit_name' => $benefit
            ]);
        }
    }
}