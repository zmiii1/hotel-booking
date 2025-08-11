<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BeachTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class BeachTicketController extends Controller
{
    public function index()
    {
        try {
            // Ambil semua tiket aktif berdasarkan kategori
            $lalassaRegular = BeachTicket::active()
                ->where('beach_name', 'lalassa')
                ->where('ticket_type', 'regular')
                ->orderBy('name')
                ->get();
                
            $lalassaBundling = BeachTicket::active()
                ->where('beach_name', 'lalassa')
                ->where('ticket_type', 'bundling')
                ->orderBy('name')
                ->get();
                
            $bodurRegular = BeachTicket::active()
                ->where('beach_name', 'bodur')
                ->where('ticket_type', 'regular')
                ->orderBy('name')
                ->get();
                
            $bodurBundling = BeachTicket::active()
                ->where('beach_name', 'bodur')
                ->where('ticket_type', 'bundling')
                ->orderBy('name')
                ->get();

            // Debug log
            Log::info('Beach tickets loaded', [
                'lalassa_regular_count' => $lalassaRegular->count(),
                'lalassa_bundling_count' => $lalassaBundling->count(),
                'bodur_regular_count' => $bodurRegular->count(),
                'bodur_bundling_count' => $bodurBundling->count(),
            ]);

            // PENTING: Gunakan path view yang sesuai dengan struktur folder
            return view('frontend.beach-tickets.index', compact(
                'lalassaRegular', 
                'lalassaBundling', 
                'bodurRegular', 
                'bodurBundling'
            ));
        } catch (\Exception $e) {
            Log::error('Error loading beach tickets: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            
            // Fallback ke view error atau redirect
            return redirect()->back()->with('error', 'Unable to load beach tickets.');
        }
    }

    public function show($id)
    {
        try {
            $ticket = BeachTicket::with('benefits')->findOrFail($id);
            
            $relatedTickets = BeachTicket::active()
                ->where('id', '!=', $ticket->id)
                ->orderBy('beach_name')
                ->orderBy('ticket_type')
                ->get();
            
            Session::put('selected_ticket', $ticket->id);
            
            return view('frontend.beach-tickets.show', compact('ticket', 'relatedTickets'));
        } catch (\Exception $e) {
            Log::error('Error showing beach ticket: ' . $e->getMessage(), [
                'ticket_id' => $id,
            ]);
            
            return redirect()->route('beach-tickets.index')
                ->with('error', 'The requested ticket could not be found.');
        }
    }

    public function checkout(Request $request)
    {
        $ticketId = Session::get('selected_ticket');
        
        if (!$ticketId) {
            return redirect()->route('beach-tickets.index')
                ->with('error', 'No ticket selected. Please select a ticket first.');
        }
        
        try {
            $ticket = BeachTicket::with('benefits')->findOrFail($ticketId);
            
            $visitDate = $request->visit_date ?? now()->format('Y-m-d');
            $quantity = $request->quantity ?? 1;
            $additionalRequest = $request->additional_request ?? '';
            
            if ($quantity < 1) {
                return redirect()->back()
                    ->with('error', 'Quantity must be at least 1.');
            }
            
            $totalPrice = $ticket->price * $quantity;
            
            Session::put('checkout_data', [
                'ticket_id' => $ticket->id,
                'visit_date' => $visitDate,
                'quantity' => $quantity,
                'additional_request' => $additionalRequest,
                'total_price' => $totalPrice
            ]);
            
            return view('frontend.beach-tickets.checkout', compact(
                'ticket', 
                'visitDate', 
                'quantity', 
                'totalPrice'
            ));
        } catch (\Exception $e) {
            Log::error('Error loading checkout page: ' . $e->getMessage(), [
                'ticket_id' => $ticketId,
            ]);
            
            return redirect()->route('beach-tickets.index')
                ->with('error', 'An error occurred during checkout. Please try again.');
        }
    }
}