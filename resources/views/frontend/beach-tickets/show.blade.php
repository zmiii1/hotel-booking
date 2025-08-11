@extends('frontend.main')

@section('main')

<link rel="stylesheet" href="{{ asset('frontend/assets/css/beach-tickets.css') }}">

<style>
    .operating-hours-notice {
        background: linear-gradient(135deg, #ea66d2 0%, #e0339e 100%);
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 14px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .operating-hours-notice i {
        font-size: 24px;
        opacity: 0.9;
    }
    
    .operating-hours-text {
        flex: 1;
    }
    
    .operating-hours-text strong {
        display: block;
        font-size: 16px;
        margin-bottom: 4px;
    }
    
    .time-warning {
        background: #fff3cd;
        color: #856404;
        padding: 10px 15px;
        border-radius: 6px;
        margin-top: 10px;
        display: none;
        font-size: 13px;
        border-left: 4px solid #ffc107;
    }
    
    .time-warning.show {
        display: block;
        animation: slideDown 0.3s ease-out;
    }
    
    .time-warning i {
        margin-right: 8px;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .btd-form-label {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .btd-form-label .info-tooltip {
        color: #6c757d;
        cursor: help;
        font-size: 14px;
    }
    
    .visit-date-wrapper {
        position: relative;
    }
    
    .date-helper-text {
        font-size: 12px;
        color: #6c757d;
        margin-top: 5px;
    }
    
    .beach-closed-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        display: none;
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }
    
    .beach-closed-overlay.show {
        display: flex;
    }
    
    .beach-closed-modal {
        background: white;
        padding: 30px;
        border-radius: 12px;
        max-width: 400px;
        text-align: center;
        animation: modalPop 0.3s ease-out;
    }
    
    @keyframes modalPop {
        from {
            transform: scale(0.8);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }
    
    .beach-closed-modal h3 {
        color: #dc3545;
        margin-bottom: 15px;
    }
    
    .beach-closed-modal p {
        color: #495057;
        margin-bottom: 20px;
    }
    
    .beach-closed-modal button {
        background: #DC1C6C;
        color: white;
        border: none;
        padding: 10px 30px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
    }
    
    .beach-closed-modal button:hover {
        background: #b5165a;
    }
</style>

<div class="btd-container">
    <div class="btd-row">
        <div class="btd-col btd-col-6">
            <div class="btd-image-wrapper">
                <img src="{{ $ticket->image_url }}" class="btd-image" alt="{{ $ticket->name }}">
            </div>
        </div>
        
        <div class="btd-col btd-col-6">
            <div class="btd-content">
                <h2 class="btd-title">{{ $ticket->name }}</h2>
                <p class="btd-price" data-price="{{ $ticket->price }}">{{ $ticket->formatted_price }}</p>
                
                <!-- Operating Hours Notice -->
                <div class="operating-hours-notice">
                    <i class="bx bx-time-five"></i>
                    <div class="operating-hours-text">
                        <strong>Beach Operating Hours</strong>
                        <span>Open daily from 8:00 AM to 5:00 PM</span>
                    </div>
                </div>
                
                <form action="{{ route('beach-tickets.checkout') }}" method="POST" id="ticketForm">
                    @csrf
                    <div class="btd-form-group">
                        <label for="additionalRequest" class="btd-form-label">Any additional request?</label>
                        <textarea class="btd-form-control" id="additionalRequest" name="additional_request" rows="3" placeholder="Enter your request here..."></textarea>
                    </div>
                    
                    <div class="btd-form-group">
                        <label for="visitDate" class="btd-form-label">
                            Date of your visit?
                            <i class="bx bx-info-circle info-tooltip" title="Select your visit date. Beach is open from 8 AM to 5 PM."></i>
                        </label>
                        <div class="visit-date-wrapper">
                            <input type="date" class="btd-form-control" id="visitDate" name="visit_date" required>
                            <div class="date-helper-text">Please arrive before 4:00 PM for the best experience</div>
                        </div>
                        
                        <!-- Time Warning for Same Day Booking -->
                        <div id="timeWarning" class="time-warning">
                            <i class="bx bx-info-circle"></i>
                            <span id="warningText"></span>
                        </div>
                    </div>
                    
                    <div class="btd-form-group">
                        <label for="quantity" class="btd-form-label">Quantity</label>
                        <div class="btd-quantity-wrapper">
                            <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1">
                        </div>
                    </div>
                    
                    <button type="submit" class="btd-btn" id="checkoutBtn">Checkout</button>
                </form>
                
                <div class="btd-benefits-box">
                    <h5 class="btd-benefits-title">Benefits:</h5>
                    <ul class="btd-benefits-list">
                        @foreach($ticket->benefits as $benefit)
                            <li>{{ $benefit->benefit_name }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    @if($relatedTickets->count() > 0)
    <div class="btd-related-section">
        <h3 class="btd-related-title">Related Products</h3>
        
        <div class="btd-row">
            @foreach($relatedTickets as $relatedTicket)
            <div class="btd-col btd-col-4">
                <div class="btd-related-card">
                    <img src="{{ $relatedTicket->image_url }}" class="btd-related-img" alt="{{ $relatedTicket->name }}">
                    <div class="btd-related-body">
                        <h5 class="btd-related-title-card">{{ $relatedTicket->name }}</h5>
                        <p class="btd-related-price">{{ $relatedTicket->formatted_price }}</p>
                        <div class="btd-btn-wrapper">
                            <a href="{{ route('beach-tickets.show', $relatedTicket->id) }}" class="btd-btn-select">Select Option</a> 
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Beach Closed Modal -->
<div id="beachClosedOverlay" class="beach-closed-overlay">
    <div class="beach-closed-modal">
        <h3>⚠️ Beach is Closed</h3>
        <p id="closedMessage">The beach is currently closed. Please select tomorrow or another date for your visit.</p>
        <button onclick="closeBeachModal()">Got it</button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const visitDateInput = document.getElementById('visitDate');
    const timeWarning = document.getElementById('timeWarning');
    const warningText = document.getElementById('warningText');
    const checkoutBtn = document.getElementById('checkoutBtn');
    const ticketForm = document.getElementById('ticketForm');
    
    // Beach operating hours (24-hour format)
    const BEACH_OPEN_HOUR = 8;  // 8 AM
    const BEACH_CLOSE_HOUR = 17; // 5 PM
    const LAST_ENTRY_HOUR = 16;  // 4 PM (last recommended entry)
    
    // Get current date and time
    const now = new Date();
    const currentHour = now.getHours();
    const currentMinutes = now.getMinutes();
    const currentTime = currentHour + (currentMinutes / 60);
    
    // Format dates for comparison
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    const todayString = formatDate(today);
    
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    const tomorrowString = formatDate(tomorrow);
    
    // Function to format date as YYYY-MM-DD
    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
    
    // Set minimum date based on current time
    function setMinimumDate() {
        if (currentTime >= BEACH_CLOSE_HOUR) {
            // Beach is closed for today, set minimum to tomorrow
            visitDateInput.min = tomorrowString;
            visitDateInput.value = tomorrowString;
            
            // Show info that today is no longer available
            showTimeWarning('Beach is closed for today. Bookings are available starting tomorrow.');
        } else if (currentTime >= LAST_ENTRY_HOUR) {
            // Still open but past recommended entry time
            visitDateInput.min = todayString;
            visitDateInput.value = todayString;
            showTimeWarning('Limited time remaining today. We recommend booking for tomorrow for the full experience.');
        } else {
            // Normal hours
            visitDateInput.min = todayString;
            visitDateInput.value = todayString;
        }
    }
    
    // Show time warning
    function showTimeWarning(message) {
        warningText.textContent = message;
        timeWarning.classList.add('show');
    }
    
    // Hide time warning
    function hideTimeWarning() {
        timeWarning.classList.remove('show');
    }
    
    // Validate selected date
    function validateSelectedDate() {
        const selectedDate = new Date(visitDateInput.value);
        selectedDate.setHours(0, 0, 0, 0);
        
        // Check if selecting today
        if (selectedDate.getTime() === today.getTime()) {
            if (currentTime >= BEACH_CLOSE_HOUR) {
                // Beach is closed
                showBeachClosedModal('The beach is closed for today (after 5:00 PM). Please select tomorrow or another date.');
                visitDateInput.value = tomorrowString;
                return false;
            } else if (currentTime >= LAST_ENTRY_HOUR) {
                // Warning for late booking
                showTimeWarning(`Note: It's currently ${formatTime(currentHour, currentMinutes)}. The beach closes at 5:00 PM. Limited time for visit today.`);
            } else if (currentTime >= BEACH_OPEN_HOUR) {
                // Normal hours
                const timeRemaining = BEACH_CLOSE_HOUR - currentTime;
                const hours = Math.floor(timeRemaining);
                const minutes = Math.floor((timeRemaining - hours) * 60);
                showTimeWarning(`Beach visit available today. Approximately ${hours}h ${minutes}m remaining until closing.`);
            } else {
                // Before opening hours
                showTimeWarning('Beach opens at 8:00 AM. Your ticket will be valid from opening time.');
            }
        } else {
            // Future date selected - no warnings needed
            hideTimeWarning();
        }
        
        return true;
    }
    
    // Format time for display
    function formatTime(hour, minute) {
        const period = hour >= 12 ? 'PM' : 'AM';
        const displayHour = hour > 12 ? hour - 12 : (hour === 0 ? 12 : hour);
        const displayMinute = String(minute).padStart(2, '0');
        return `${displayHour}:${displayMinute} ${period}`;
    }
    
    // Show beach closed modal
    function showBeachClosedModal(message) {
        document.getElementById('closedMessage').textContent = message;
        document.getElementById('beachClosedOverlay').classList.add('show');
    }
    
    // Close beach closed modal
    window.closeBeachModal = function() {
        document.getElementById('beachClosedOverlay').classList.remove('show');
    }
    
    // Form submission validation
    ticketForm.addEventListener('submit', function(e) {
        const selectedDate = new Date(visitDateInput.value);
        selectedDate.setHours(0, 0, 0, 0);
        
        // Final validation before submission
        if (selectedDate.getTime() === today.getTime() && currentTime >= BEACH_CLOSE_HOUR) {
            e.preventDefault();
            showBeachClosedModal('Cannot book for today as the beach is now closed. Please select another date.');
            visitDateInput.value = tomorrowString;
            return false;
        }
        
        // Add a hidden field with booking time for backend validation
        const bookingTimeInput = document.createElement('input');
        bookingTimeInput.type = 'hidden';
        bookingTimeInput.name = 'booking_time';
        bookingTimeInput.value = now.toISOString();
        ticketForm.appendChild(bookingTimeInput);
    });
    
    // Event listener for date change
    visitDateInput.addEventListener('change', validateSelectedDate);
    
    // Initialize on load
    setMinimumDate();
    validateSelectedDate();
});
</script>

@endsection