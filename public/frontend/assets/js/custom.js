(function($) {
    'use strict';

    // Mean Menu JS
    jQuery('.mean-menu').meanmenu({ 
        meanScreenWidth: "991"
    });

    // Navbar Area
    $(window).on('scroll', function() {
        if ($(this).scrollTop() >150){  
            $('.navbar-area').addClass("sticky-nav");
        }
        else{
            $('.navbar-area').removeClass("sticky-nav");
        }
    });

    // Sidebar Modal JS
	$(".burger-menu").on('click',  function() {
		$('.sidebar-modal').toggleClass('active');
	});
	$(".sidebar-modal-close-btn").on('click',  function() {
		$('.sidebar-modal').removeClass('active');
    });

    // Image Banner Slider
    $(document).ready(function () {
        $(".banner-slider").owlCarousel({
            items: 1,
            loop: true,
            autoplay: true,
            autoplayTimeout: 3000,
            smartSpeed: 800,
            dots: false,
            nav: false
        });
    });     

    // Others Option For Responsive JS
	$(".side-nav-responsive .dot-menu").on("click", function(){
		$(".side-nav-responsive .container .container").toggleClass("active");
    });

    // Tabs Single Page
    $('.tab ul.tabs').addClass('active').find('> li:eq(0)').addClass('current');
    $('.tab ul.tabs li a').on('click', function (g) {
         var tab = $(this).closest('.tab'), 
         index = $(this).closest('li').index();
         tab.find('ul.tabs > li').removeClass('current');
         $(this).closest('li').addClass('current');
         tab.find('.tab_content').find('div.tabs_item').not('div.tabs_item:eq(' + index + ')').slideUp();
         tab.find('.tab_content').find('div.tabs_item:eq(' + index + ')').slideDown();
         g.preventDefault();
    });



    // Datetimepicker
$(document).ready(function() {
    // Get all datepicker elements
    var $checkinPicker = $('input[name="check_in"]');
    var $checkoutPicker = $('input[name="check_out"]');
    
    // Set default dates (today and tomorrow) only if fields are empty
    var today = new Date();
    var tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    
    // Format date to dd-mm-yyyy
    function formatDate(date) {
        var day = String(date.getDate()).padStart(2, '0');
        var month = String(date.getMonth() + 1).padStart(2, '0');
        var year = date.getFullYear();
        return day + '-' + month + '-' + year;
    }
    
    // Set default values only if inputs are empty
    if (!$checkinPicker.val()) {
        $checkinPicker.val(formatDate(today));
    }
    if (!$checkoutPicker.val()) {
        $checkoutPicker.val(formatDate(tomorrow));
    }
    
    // Function to fix arrows - will be called multiple times
    function fixDatepickerArrows($datepicker) {
        var $header = $datepicker.find('.ui-datepicker-header');
        var $prevBtn = $datepicker.find('.ui-datepicker-prev');
        var $nextBtn = $datepicker.find('.ui-datepicker-next');
        
        // Make sure header has relative positioning
        $header.css('position', 'relative');
        
        // Style and show the navigation buttons
        $prevBtn.show().css({
            'position': 'absolute',
            'left': '10px',
            'top': '50%',
            'transform': 'translateY(-50%)',
            'width': '28px',
            'height': '28px',
            'background': '#fff',
            'border': '1px solid #ddd',
            'border-radius': '50%',
            'cursor': 'pointer',
            'z-index': '1001',
            'display': 'flex',
            'align-items': 'center',
            'justify-content': 'center',
            'text-decoration': 'none'
        });
        
        $nextBtn.show().css({
            'position': 'absolute',
            'right': '10px',
            'top': '50%',
            'transform': 'translateY(-50%)',
            'width': '28px',
            'height': '28px',
            'background': '#fff',
            'border': '1px solid #ddd',
            'border-radius': '50%',
            'cursor': 'pointer',
            'z-index': '1001',
            'display': 'flex',
            'align-items': 'center',
            'justify-content': 'center',
            'text-decoration': 'none'
        });
        
        // Replace content with custom icons
        $prevBtn.html('<i class="bx bx-chevron-left" style="font-size: 16px; color: #666;"></i>');
        $nextBtn.html('<i class="bx bx-chevron-right" style="font-size: 16px; color: #666;"></i>');
        
        // Remove existing hover handlers to avoid duplicates
        $prevBtn.off('mouseenter mouseleave');
        $nextBtn.off('mouseenter mouseleave');
        
        // Add hover effects
        $prevBtn.add($nextBtn).hover(
            function() {
                $(this).css({
                    'background': '#f0f0f0',
                    'border-color': '#DC1C6C'
                });
                $(this).find('i').css('color', '#DC1C6C');
            },
            function() {
                $(this).css({
                    'background': '#fff',
                    'border-color': '#ddd'
                });
                $(this).find('i').css('color', '#666');
            }
        );
    }
    
    // Simple datepicker settings
    var commonSettings = {
        dateFormat: 'dd-mm-yy',
        autoclose: true,
        minDate: 0,
        showOtherMonths: true,
        selectOtherMonths: true,
        changeMonth: false,
        changeYear: false,
        numberOfMonths: 1,
        dayNamesMin: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
        monthNames: ['January', 'February', 'March', 'April', 'May', 'June',
                    'July', 'August', 'September', 'October', 'November', 'December'],
        showButtonPanel: false,
        
        beforeShow: function(input, inst) {
            // Initial fix when datepicker opens
            setTimeout(function() {
                fixDatepickerArrows(inst.dpDiv);
            }, 50);
        },
        
        onChangeMonthYear: function(year, month, inst) {
            // Fix arrows when month/year changes
            setTimeout(function() {
                fixDatepickerArrows(inst.dpDiv);
            }, 50);
        }
    };

    // ===== PERUBAHAN #1: Update selector untuk exclude beach-visit-date =====
    // Initialize datepickers dengan selector yang lebih spesifik
    // Sebelumnya: $('.dt_picker, input[name="check_in"], input[name="check_out"]').datepicker(commonSettings);
    $('.dt_picker:not(.beach-visit-date), input[name="check_in"], input[name="check_out"]').datepicker(commonSettings);
    
    // Additional event listener to catch any DOM changes
    $(document).on('DOMNodeInserted', '.ui-datepicker', function() {
        var $this = $(this);
        if ($this.hasClass('ui-datepicker') && $this.is(':visible')) {
            setTimeout(function() {
                fixDatepickerArrows($this);
            }, 10);
        }
    });
    
    // Alternative approach using MutationObserver for modern browsers
    if (window.MutationObserver) {
        var observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList') {
                    $(mutation.addedNodes).each(function() {
                        if ($(this).hasClass && $(this).hasClass('ui-datepicker')) {
                            setTimeout(function() {
                                fixDatepickerArrows($(mutation.addedNodes));
                            }, 10);
                        }
                    });
                }
            });
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }

    // Initialize check-in datepicker with additional constraints
    $checkinPicker.datepicker('option', {
        onSelect: function(selectedDate) {
            var checkinDate = $checkinPicker.datepicker('getDate');
            
            if (checkinDate) {
                var minCheckoutDate = new Date(checkinDate);
                minCheckoutDate.setDate(minCheckoutDate.getDate() + 1);
                
                $checkoutPicker.datepicker('option', 'minDate', minCheckoutDate);
                
                var currentCheckoutDate = $checkoutPicker.datepicker('getDate');
                if (currentCheckoutDate && currentCheckoutDate <= checkinDate) {
                    $checkoutPicker.datepicker('setDate', minCheckoutDate);
                }
            }
            
            // Fix arrows after selection
            setTimeout(function() {
                var $dp = $checkinPicker.datepicker('widget');
                if ($dp.is(':visible')) {
                    fixDatepickerArrows($dp);
                }
            }, 10);
        }
    });

    // Initialize check-out datepicker with additional constraints
    $checkoutPicker.datepicker('option', {
        onSelect: function(selectedDate) {
            var checkoutDate = $checkoutPicker.datepicker('getDate');
            
            if (checkoutDate) {
                var maxCheckinDate = new Date(checkoutDate);
                maxCheckinDate.setDate(maxCheckinDate.getDate() - 1);
                
                $checkinPicker.datepicker('option', 'maxDate', maxCheckinDate);
            }
            
            // Fix arrows after selection
            setTimeout(function() {
                var $dp = $checkoutPicker.datepicker('widget');
                if ($dp.is(':visible')) {
                    fixDatepickerArrows($dp);
                }
            }, 10);
        }
    });
    
    // Check if there are initial values and enforce constraints
    var initialCheckinDate = $checkinPicker.datepicker('getDate');
    var initialCheckoutDate = $checkoutPicker.datepicker('getDate');
    
    if (initialCheckinDate && initialCheckoutDate) {
        if (initialCheckinDate >= initialCheckoutDate) {
            var fixedCheckoutDate = new Date(initialCheckinDate);
            fixedCheckoutDate.setDate(fixedCheckoutDate.getDate() + 1);
            $checkoutPicker.datepicker('setDate', fixedCheckoutDate);
        }
    }
    
    // Apply initial constraints based on current values
    if (initialCheckinDate) {
        var minCheckoutDate = new Date(initialCheckinDate);
        minCheckoutDate.setDate(minCheckoutDate.getDate() + 1);
        $checkoutPicker.datepicker('option', 'minDate', minCheckoutDate);
    }
    
    if (initialCheckoutDate) {
        var maxCheckinDate = new Date(initialCheckoutDate);
        maxCheckinDate.setDate(maxCheckinDate.getDate() - 1);
        $checkinPicker.datepicker('option', 'maxDate', maxCheckinDate);
    }
    
    // Format promo code to uppercase
    $('input[name="promo_code"]').on('input', function() {
        $(this).val($(this).val().toUpperCase());
    });
    
    // ===== PERUBAHAN #2: Ganti form validation yang lama dengan yang baru =====
    // Form validation - HANYA untuk hotel booking forms
    // HAPUS code validation yang lama ini:
    /*
    $('form').on('submit', function(e) {
        var checkinVal = $checkinPicker.val();
        var checkoutVal = $checkoutPicker.val();
        
        if (!checkinVal || !checkoutVal) {
            e.preventDefault();
            alert('Please select both check-in and check-out dates');
            return false;
        }
    });
    */
    
    // GANTI dengan code validation yang baru ini:
    $('form.hotel-booking-form, form:has(input[name="check_in"])').on('submit', function(e) {
        // Only validate if this form has check-in/check-out fields
        var $checkinInput = $(this).find('input[name="check_in"]');
        var $checkoutInput = $(this).find('input[name="check_out"]');
        
        // Skip validation if these fields don't exist (not a hotel booking form)
        if ($checkinInput.length === 0 || $checkoutInput.length === 0) {
            return true; // Allow form submission
        }
        
        var checkinVal = $checkinInput.val();
        var checkoutVal = $checkoutInput.val();
        
        if (!checkinVal || !checkoutVal) {
            e.preventDefault();
            alert('Please select both check-in and check-out dates');
            return false;
        }
    });
});

// Guest Modal JavaScript Functions

// Global variables
const maxGuestsPerRoom = 7; // Maximum guests per room
let guestCounts = { adults: 1, children: 0 };

// Initialize guest modal functionality
document.addEventListener('DOMContentLoaded', function() {
    initializeGuestModal();
    
    // Delegate counter button clicks
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('counter-btn')) {
            const type = e.target.getAttribute('data-type');
            const action = e.target.getAttribute('data-action');
            
            if (action === 'increment') {
                incrementCount(type);
            } else if (action === 'decrement') {
                decrementCount(type);
            }
        }
    });
    
    // Done button
    const doneBtn = document.getElementById('guestDoneBtn');
    if (doneBtn) {
        doneBtn.addEventListener('click', function() {
            closeGuestDropdown();
        });
    }
});

// Initialize guest modal
function initializeGuestModal() {
    // Toggle guest dropdown
    const guestsDisplay = document.getElementById('guestsDisplay');
    if (guestsDisplay) {
        guestsDisplay.addEventListener('click', function() {
            toggleGuestDropdown();
        });
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const guestsField = document.querySelector('.guests-field');
        if (guestsField && !guestsField.contains(event.target)) {
            closeGuestDropdown();
        }
    });

    // Initialize button states
    updateButtonStates();
}

// Toggle guest dropdown
function toggleGuestDropdown() {
    const dropdown = document.getElementById('guestDropdown');
    const display = document.getElementById('guestsDisplay');
    
    if (dropdown && display) {
        if (dropdown.classList.contains('show')) {
            dropdown.classList.remove('show');
            display.classList.remove('active');
        } else {
            dropdown.classList.add('show');
            display.classList.add('active');
        }
    }
}

// Close guest dropdown
function closeGuestDropdown() {
    const dropdown = document.getElementById('guestDropdown');
    const display = document.getElementById('guestsDisplay');
    
    if (dropdown && display) {
        dropdown.classList.remove('show');
        display.classList.remove('active');
    }
}

// Get total guests
function getTotalGuests() {
    return guestCounts.adults + guestCounts.children;
}

// Check if we can add more guests
function canAddMoreGuests(guestType) {
    const currentTotal = getTotalGuests();
    if (currentTotal >= maxGuestsPerRoom) return false;
    
    // Special case: must have at least 1 adult
    if (guestType === 'adults') {
        return true;
    } else {
        // For children, check if adding one would exceed max and there's at least 1 adult
        return (currentTotal < maxGuestsPerRoom) && (guestCounts.adults >= 1);
    }
}

// Increment count function
function incrementCount(guestType) {
    if (canAddMoreGuests(guestType)) {
        guestCounts[guestType]++;
    }
    
    updateCounterDisplay(guestType);
    updateGuestsDisplay();
    updateButtonStates();
}

// Decrement count function
function decrementCount(guestType) {
    if (guestType === 'adults') {
        // Must have at least 1 adult, and can't decrement if it would leave children without adults
        if (guestCounts.adults > 1 || 
            (guestCounts.adults === 1 && guestCounts.children === 0)) {
            guestCounts.adults--;
        }
    } else if (guestType === 'children') {
        if (guestCounts.children > 0) {
            guestCounts.children--;
        }
    }
    
    updateCounterDisplay(guestType);
    updateGuestsDisplay();
    updateButtonStates();
}

// Update counter display
function updateCounterDisplay(guestType) {
    const element = document.getElementById(`${guestType}Count-1`);
    if (element) {
        element.textContent = guestCounts[guestType];
    }
}

// Update button states (enable/disable)
function updateButtonStates() {
    const currentTotal = getTotalGuests();
    
    // Adults buttons
    const adultsMinusBtn = document.querySelector(`[data-type="adults"][data-action="decrement"]`);
    const adultsPlusBtn = document.querySelector(`[data-type="adults"][data-action="increment"]`);
    
    if (adultsMinusBtn) {
        adultsMinusBtn.disabled = guestCounts.adults <= 1 || 
                                (guestCounts.adults === 1 && guestCounts.children > 0);
    }
    if (adultsPlusBtn) {
        adultsPlusBtn.disabled = currentTotal >= maxGuestsPerRoom;
    }
    
    // Children buttons
    const childrenMinusBtn = document.querySelector(`[data-type="children"][data-action="decrement"]`);
    const childrenPlusBtn = document.querySelector(`[data-type="children"][data-action="increment"]`);
    
    if (childrenMinusBtn) {
        childrenMinusBtn.disabled = guestCounts.children <= 0;
    }
    if (childrenPlusBtn) {
        childrenPlusBtn.disabled = currentTotal >= maxGuestsPerRoom || guestCounts.adults === 0;
    }
}

// Update main guests display
function updateGuestsDisplay() {
    const guestsText = document.getElementById('guestsText');
    if (guestsText) {
        const adultText = guestCounts.adults === 1 ? 'adult' : 'adults';
        const childText = guestCounts.children === 1 ? 'child' : 'children';
        guestsText.textContent = `${guestCounts.adults} ${adultText}, ${guestCounts.children} ${childText}`;
    }
}

// ICON JS
document.addEventListener('DOMContentLoaded', function() {
    // Facility to icon mapping
    const facilityIcons = {
        'Digital TV': 'bx bx-tv',
        'Smart TV': 'bx bx-tv',
        'Wi-Fi': 'bx bx-wifi',
        'Air conditioning': 'bx  bx-air-conditioner',
        'Coffee machine' : 'bx bx-coffee',
        'Refrigerator' : 'bx bx-fridge',
        'Waterheater' : 'bx bx-water',
        'In-room telephone' : 'bx bx-phone',
        'Shower' : 'bx bx-shower',
        'Bathroom' : 'bx bx-bath',
        'Slippers' : 'bx bx-slippers',
        'Closet' : 'bx bx-closet',
        'Mirror' : 'bx bx-mirror',
        'Wardrobe' : 'bx bx-wardrobe',
        'Clothes rack' : 'bx bx-hanger',
        'Chair' : 'bx bx-chair',
        'Bedside table' : 'bx bx-bed',
        'Garden view' : 'bx bx-leaf',
        'Double bed' : 'bx bx-bed',
        'Toiletries' : 'bx bx-spa',
        'Two twin beds or one double bed' : 'bx bx-bed',
        'Private pool' : 'bx bx-swim',
        'Drinking water' : 'bx bx-water',
        'Dining area' : 'bx bx-dish',
        'Sofa' : 'bx bx-sofa',
        'Mineral water' : 'bx bx-water',
        'Tea and coffee kit' : 'bx bx-coffee-togo',
        'Kettle' : 'bx bx-coffee',
        'Mini-Kitchen' : 'bx bx-fridge',
        'Canopy' : 'bx bx-shade'
    };
    
    // Apply icons to all facility items
    document.querySelectorAll('.facility-item').forEach(item => {
        const facilityName = item.dataset.facility;
        const iconClass = facilityIcons[facilityName] || 'bx bx-check';
        const iconElement = item.querySelector('.facility-icon');
        if (iconElement) {
            iconElement.className = `facility-icon ${iconClass}`;
        }
    });
});



    // WOW JS
    new WOW().init();

    // Count Time JS
	function makeTimer() {
		var endTime = new Date("October 30, 2022 17:00:00 PDT");			
		var endTime = (Date.parse(endTime)) / 1000;
		var now = new Date();
		var now = (Date.parse(now) / 1000);
		var timeLeft = endTime - now;
		var days = Math.floor(timeLeft / 86400); 
		var hours = Math.floor((timeLeft - (days * 86400)) / 3600);
		var minutes = Math.floor((timeLeft - (days * 86400) - (hours * 3600 )) / 60);
		var seconds = Math.floor((timeLeft - (days * 86400) - (hours * 3600) - (minutes * 60)));
		if (hours < "10") { hours = "0" + hours; }
		if (minutes < "10") { minutes = "0" + minutes; }
		if (seconds < "10") { seconds = "0" + seconds; }
		$("#days").html(days + "<span>Days</span>");
		$("#hours").html(hours + "<span>Hours</span>");
		$("#minutes").html(minutes + "<span>Minutes</span>");
		$("#seconds").html(seconds + "<span>Seconds</span>");
	}
    setInterval(function() { makeTimer(); }, 300);
        
    // AJAX MailChimp
    $(".newsletter-form").ajaxChimp({
        url: "https://envyTheme.us20.list-manage.com/subscribe/post?u=60e1ffe2e8a68ce1204cd39a5&amp;id=42d6d188d9", // Your url MailChimp
        callback: callbackFunction
    });

})(jQuery);
