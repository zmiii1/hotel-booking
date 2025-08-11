<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $price
 * @property string $beach_name
 * @property string $ticket_type
 * @property string|null $image_url
 * @property int $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TicketBenefit> $benefits
 * @property-read int|null $benefits_count
 * @property-read mixed $formatted_price
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TicketOrder> $orders
 * @property-read int|null $orders_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BeachTicket active()
 * @method static \Database\Factories\BeachTicketFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BeachTicket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BeachTicket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BeachTicket query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BeachTicket whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BeachTicket whereBeachName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BeachTicket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BeachTicket whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BeachTicket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BeachTicket whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BeachTicket whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BeachTicket wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BeachTicket whereTicketType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BeachTicket whereUpdatedAt($value)
 */
	class BeachTicket extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $hotel_id
 * @property int|null $rooms_id
 * @property int|null $room_type_id
 * @property string|null $room_type_name
 * @property string|null $check_in
 * @property string|null $check_out
 * @property string $adults
 * @property string $child
 * @property float $total_night
 * @property float $actual_price
 * @property float $subtotal
 * @property int $discount
 * @property float $total_amount
 * @property int|null $package_id
 * @property int|null $promo_code_id
 * @property string $package_price
 * @property string $addon_total
 * @property string $payment_method
 * @property string $transaction_id
 * @property string $payment_status
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $country
 * @property string|null $additional_request
 * @property int|null $consent_marketing
 * @property string $code
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RoomAddOns> $addons
 * @property-read int|null $addons_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BookingRoomList> $assign_rooms
 * @property-read int|null $assign_rooms_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RoomBookedDate> $booked_dates
 * @property-read int|null $booked_dates_count
 * @property-read \App\Models\Hotel|null $hotel
 * @property-read \App\Models\RoomPackage|null $package
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \App\Models\PromoCode|null $promoCode
 * @property-read \App\Models\Room|null $room
 * @property-read \App\Models\RoomType|null $roomType
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BookingRoomList> $room_lists
 * @property-read int|null $room_lists_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereActualPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereAdditionalRequest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereAddonTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereAdults($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereCheckIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereCheckOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereChild($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereConsentMarketing($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereHotelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking wherePackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking wherePackagePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking wherePromoCodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereRoomTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereRoomTypeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereRoomsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereTotalNight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereUpdatedAt($value)
 */
	class Booking extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $booking_id
 * @property int|null $room_id
 * @property int|null $room_number_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Booking|null $booking
 * @property-read \App\Models\Room|null $room
 * @property-read \App\Models\RoomNumber|null $room_number
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingRoomList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingRoomList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingRoomList query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingRoomList whereBookingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingRoomList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingRoomList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingRoomList whereRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingRoomList whereRoomNumberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookingRoomList whereUpdatedAt($value)
 */
	class BookingRoomList extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $rooms_id
 * @property string|null $facilities_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Facilities newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Facilities newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Facilities query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Facilities whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Facilities whereFacilitiesName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Facilities whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Facilities whereRoomsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Facilities whereUpdatedAt($value)
 */
	class Facilities extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $slug
 * @property string $location
 * @property string|null $description
 * @property string $contact_info
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RoomType> $roomTypes
 * @property-read int|null $room_types_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hotel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hotel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hotel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hotel whereContactInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hotel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hotel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hotel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hotel whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hotel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hotel whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hotel whereUpdatedAt($value)
 */
	class Hotel extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $rooms_id
 * @property string|null $multi_images
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MultiImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MultiImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MultiImage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MultiImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MultiImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MultiImage whereMultiImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MultiImage whereRoomsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MultiImage whereUpdatedAt($value)
 */
	class MultiImage extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $booking_code
 * @property string|null $payment_id
 * @property string $external_id
 * @property string|null $payment_method
 * @property string|null $notes
 * @property string|null $channel_code
 * @property string $payment_status
 * @property numeric $amount
 * @property string|null $checkout_url
 * @property string|null $receipt_path
 * @property string|null $bank_name
 * @property string|null $account_name
 * @property string|null $transfer_date
 * @property string|null $transfer_amount
 * @property \Illuminate\Support\Carbon|null $paid_at
 * @property \Illuminate\Support\Carbon|null $expired_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Booking $booking
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereAccountName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereBookingCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereChannelCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCheckoutUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereExternalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereReceiptPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereTransferAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereTransferDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereUpdatedAt($value)
 */
	class Payment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $code
 * @property string|null $description
 * @property string $discount_type
 * @property float $discount_value
 * @property float|null $min_purchase
 * @property float|null $max_discount
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property int|null $max_uses
 * @property int $used_count
 * @property bool $is_active
 * @property string $applies_to
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BeachTicket> $beachTickets
 * @property-read int|null $beach_tickets_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Room> $rooms
 * @property-read int|null $rooms_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCode query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCode whereAppliesTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCode whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCode whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCode whereDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCode whereDiscountValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCode whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCode whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCode whereMaxDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCode whereMaxUses($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCode whereMinPurchase($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCode whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCode whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCode whereUsedCount($value)
 */
	class PromoCode extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $room_type_id
 * @property string|null $description
 * @property string|null $room_capacity
 * @property int|null $guests_total
 * @property string|null $image
 * @property string|null $price
 * @property string|null $size
 * @property string|null $bed_type
 * @property int $discount
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Facilities> $facilities
 * @property-read int|null $facilities_count
 * @property-read mixed $final_price
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MultiImage> $multiImages
 * @property-read int|null $multi_images_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PromoCode> $promoCodes
 * @property-read int|null $promo_codes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RoomNumber> $room_numbers
 * @property-read int|null $room_numbers_count
 * @property-read \App\Models\RoomType|null $type
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereBedType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereGuestsTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereRoomCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereRoomTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereUpdatedAt($value)
 */
	class Room extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property float $price
 * @property float|null $normal_price
 * @property string $category
 * @property string|null $image
 * @property bool $is_prepayment_required
 * @property string $for_guests_type
 * @property int|null $guest_count
 * @property bool $is_included
 * @property bool $status
 * @property string $price_type
 * @property bool $is_bestseller
 * @property bool $is_sale
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Booking> $bookings
 * @property-read int|null $bookings_count
 * @property-read mixed $category_name
 * @property-read mixed $guest_type_text
 * @property-read mixed $price_type_text
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RoomPackage> $packages
 * @property-read int|null $packages_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomAddOns newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomAddOns newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomAddOns query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomAddOns whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomAddOns whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomAddOns whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomAddOns whereForGuestsType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomAddOns whereGuestCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomAddOns whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomAddOns whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomAddOns whereIsBestseller($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomAddOns whereIsIncluded($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomAddOns whereIsPrepaymentRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomAddOns whereIsSale($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomAddOns whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomAddOns whereNormalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomAddOns wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomAddOns wherePriceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomAddOns whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomAddOns whereUpdatedAt($value)
 */
	class RoomAddOns extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $booking_id
 * @property int|null $room_id
 * @property int|null $room_number_id
 * @property string|null $book_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Booking|null $booking
 * @property-read \App\Models\Room|null $room
 * @property-read \App\Models\RoomNumber|null $room_number
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomBookedDate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomBookedDate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomBookedDate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomBookedDate whereBookDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomBookedDate whereBookingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomBookedDate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomBookedDate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomBookedDate whereRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomBookedDate whereRoomNumberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomBookedDate whereUpdatedAt($value)
 */
	class RoomBookedDate extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $rooms_id
 * @property int $room_type_id
 * @property string|null $room_num
 * @property int|null $capacity
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RoomBookedDate> $booked_dates
 * @property-read int|null $booked_dates_count
 * @property-read \App\Models\Room|null $room
 * @property-read \App\Models\RoomType|null $room_type
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomNumber newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomNumber newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomNumber query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomNumber whereCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomNumber whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomNumber whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomNumber whereRoomNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomNumber whereRoomTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomNumber whereRoomsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomNumber whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomNumber whereUpdatedAt($value)
 */
	class RoomNumber extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string|null $description
 * @property array<array-key, mixed>|null $inclusions
 * @property array<array-key, mixed>|null $amenities
 * @property string $price_adjustment
 * @property bool $is_default
 * @property bool $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RoomAddOns> $addons
 * @property-read int|null $addons_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Booking> $bookings
 * @property-read int|null $bookings_count
 * @property-read mixed $amenities_array
 * @property-read mixed $inclusions_array
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomPackage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomPackage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomPackage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomPackage whereAmenities($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomPackage whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomPackage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomPackage whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomPackage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomPackage whereInclusions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomPackage whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomPackage whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomPackage wherePriceAdjustment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomPackage whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomPackage whereUpdatedAt($value)
 */
	class RoomPackage extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $hotel_id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RoomNumber> $availableRoomNumbers
 * @property-read int|null $available_room_numbers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Booking> $bookings
 * @property-read int|null $bookings_count
 * @property-read mixed $gallery_images
 * @property-read mixed $main_facilities
 * @property-read mixed $main_image
 * @property-read \App\Models\Hotel $hotel
 * @property-read \App\Models\Room|null $room
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RoomNumber> $roomNumbers
 * @property-read int|null $room_numbers_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomType whereHotelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomType whereUpdatedAt($value)
 */
	class RoomType extends \Eloquent {}
}

namespace App\Models{
/**
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff query()
 */
	class Staff extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $beach_ticket_id
 * @property string $benefit_name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\BeachTicket|null $ticket
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketBenefit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketBenefit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketBenefit ordered()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketBenefit query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketBenefit whereBeachTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketBenefit whereBenefitName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketBenefit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketBenefit whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketBenefit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketBenefit whereUpdatedAt($value)
 */
	class TicketBenefit extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read mixed $last_order
 * @property-read int|null $orders_count
 * @property-read mixed $total_spent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TicketOrder> $orders
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketCustomer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketCustomer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketCustomer query()
 */
	class TicketCustomer extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $beach_ticket_id
 * @property string $order_code
 * @property string $customer_name
 * @property string $customer_email
 * @property string $customer_phone
 * @property int|null $cashier_id
 * @property \Illuminate\Support\Carbon $visit_date
 * @property int $quantity
 * @property string|null $additional_request
 * @property string|null $subtotal
 * @property string $discount
 * @property int|null $promo_code_id
 * @property string $total_price
 * @property string|null $amount_tendered
 * @property string|null $payment_method
 * @property string $payment_status
 * @property string|null $transaction_id
 * @property \Illuminate\Support\Carbon|null $paid_at
 * @property bool $is_offline_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TicketBenefit> $benefits
 * @property-read int|null $benefits_count
 * @property-read \App\Models\User|null $cashier
 * @property-read mixed $formatted_total_price
 * @property-read mixed $formatted_visit_date
 * @property-read mixed $payment_method_badge
 * @property-read mixed $status_badge
 * @property-read \App\Models\TicketPayment|null $payment
 * @property-read \App\Models\PromoCode|null $promoCode
 * @property-read \App\Models\BeachTicket|null $ticket
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketOrder whereAdditionalRequest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketOrder whereAmountTendered($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketOrder whereBeachTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketOrder whereCashierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketOrder whereCustomerEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketOrder whereCustomerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketOrder whereCustomerPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketOrder whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketOrder whereIsOfflineOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketOrder whereOrderCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketOrder wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketOrder wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketOrder wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketOrder wherePromoCodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketOrder whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketOrder whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketOrder whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketOrder whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketOrder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketOrder whereVisitDate($value)
 */
	class TicketOrder extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $ticket_order_id
 * @property string $order_code
 * @property string|null $payment_id
 * @property string|null $external_id
 * @property string $payment_status
 * @property string $amount
 * @property string|null $checkout_url
 * @property \Illuminate\Support\Carbon|null $expired_at
 * @property \Illuminate\Support\Carbon|null $paid_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\TicketOrder $order
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketPayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketPayment whereCheckoutUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketPayment whereExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketPayment whereExternalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketPayment whereOrderCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketPayment wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketPayment wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketPayment wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketPayment whereTicketOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketPayment whereUpdatedAt($value)
 */
	class TicketPayment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $username
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $photo
 * @property string $role
 * @property string $status
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 */
	class User extends \Eloquent {}
}

