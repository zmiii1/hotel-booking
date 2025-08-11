@extends('frontend.main')

@section('main')

<link rel="stylesheet" href="{{ asset('frontend/assets/css/beach-tickets.css') }}">

<!-- Beach Ticket Section -->
<div class="bt-container">
    <div class="bt-title-wrapper">
        <h2 class="bt-title">Beach Ticket</h2>
    </div>

    <div class="bt-row">
        <!-- Lalassa Beach Regular Tickets -->
        @if($lalassaRegular->count() > 0)
            @foreach($lalassaRegular as $ticket)
            <div class="bt-col">
                <div class="bt-card">
                    <img src="{{ $ticket->image_url }}" class="bt-card-img" alt="{{ $ticket->name }}">
                    <div class="bt-card-body">
                        <h5 class="bt-card-title">{{ $ticket->name }}</h5>
                        <p class="bt-price">{{ $ticket->formatted_price }}</p>
                        <div class="bt-btn-wrapper">
                            <a href="{{ route('beach-tickets.show', $ticket->id) }}" class="bt-btn">Select Option</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endif

        <!-- Lalassa Beach Bundling Tickets -->
        @if($lalassaBundling->count() > 0)
            @foreach($lalassaBundling as $ticket)
            <div class="bt-col">
                <div class="bt-card">
                    <img src="{{ $ticket->image_url }}" class="bt-card-img" alt="{{ $ticket->name }}">
                    <div class="bt-card-body">
                        <h5 class="bt-card-title">{{ $ticket->name }}</h5>
                        <p class="bt-price">{{ $ticket->formatted_price }}</p>
                        <div class="bt-btn-wrapper">
                            <a href="{{ route('beach-tickets.show', $ticket->id) }}" class="bt-btn">Select Option</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endif

        <!-- Bodur Beach Regular Tickets -->
        @if($bodurRegular->count() > 0)
            @foreach($bodurRegular as $ticket)
            <div class="bt-col">
                <div class="bt-card">
                    <img src="{{ $ticket->image_url }}" class="bt-card-img" alt="{{ $ticket->name }}">
                    <div class="bt-card-body">
                        <h5 class="bt-card-title">{{ $ticket->name }}</h5>
                        <p class="bt-price">{{ $ticket->formatted_price }}</p>
                        <div class="bt-btn-wrapper">
                            <a href="{{ route('beach-tickets.show', $ticket->id) }}" class="bt-btn">Select Option</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endif

        <!-- Bodur Beach Bundling Tickets -->
        @if($bodurBundling->count() > 0)
            @foreach($bodurBundling as $ticket)
            <div class="bt-col">
                <div class="bt-card">
                    <img src="{{ $ticket->image_url }}" class="bt-card-img" alt="{{ $ticket->name }}">
                    <div class="bt-card-body">
                        <h5 class="bt-card-title">{{ $ticket->name }}</h5>
                        <p class="bt-price">{{ $ticket->formatted_price }}</p>
                        <div class="bt-btn-wrapper">
                            <a href="{{ route('beach-tickets.show', $ticket->id) }}" class="bt-btn">Select Option</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>
</div>
@endsection