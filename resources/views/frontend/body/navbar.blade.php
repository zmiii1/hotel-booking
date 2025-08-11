<div class="navbar-area">
    <!-- Menu For Mobile Device -->
    <div class="mobile-nav">
        <a href="{{ url('/') }}" class="logo">
            <img src="{{ asset('frontend/assets/img/logotl') }}" class="logo-one" alt="Logo">
        </a>
    </div>

    <!-- Menu For Desktop Device -->
    <div class="main-nav">
        <div class="container">
            <nav class="navbar navbar-expand-md navbar-light ">
                <a class="navbar-logo" href="{{ url('/') }}">
                    <img src="{{ asset('frontend/assets/img/logotl (2).png') }}" class="logo-one" alt="Logo">
                </a>

                <div class="collapse navbar-collapse mean-menu" id="navbarSupportedContent">
                    <ul class="navbar-nav m-auto">
                        <li class="nav-item">
                            <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                                Home
                            </a>
                        </li>

                        <li class="nav-item">
                            @php
                                $hotels = [
                                    'tanjung.lesung' => [
                                        'name' => 'Tanjung Lesung Beach Hotel',
                                        'url' => route('tanjung.lesung'),
                                        'active' => request()->routeIs('tanjung.lesung')
                                    ],
                                    'kalicaa.villa' => [
                                        'name' => 'Kalicaa Villa',
                                        'url' => route('kalicaa.villa'),
                                        'active' => request()->routeIs('kalicaa.villa')
                                    ],
                                    'ladda.bay' => [
                                        'name' => 'Ladda Bay Village',
                                        'url' => route('ladda.bay'),
                                        'active' => request()->routeIs('ladda.bay')
                                    ],
                                    'lalassa.beach' => [
                                        'name' => 'Lalassa Beach Club',
                                        'url' => route('lalassa.beach'),
                                        'active' => request()->routeIs('lalassa.beach')
                                    ]
                                ];
                                
                                // Determine active hotel
                                $activeHotel = collect($hotels)->firstWhere('active', true);
                            @endphp
                            
                            <a href="#" class="nav-link">
                                {{ $activeHotel['name'] ?? 'Hotel Group' }}
                                <i class='bx bx-chevron-down'></i>
                            </a>
                            <ul class="dropdown-menu">
                                @foreach($hotels as $key => $hotel)
                                    <li class="nav-item">
                                        <a href="{{ $hotel['url'] }}" class="nav-link {{ $hotel['active'] ? 'active' : '' }}">
                                            {{ $hotel['name'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ url('/mice') }}" class="nav-link {{ request()->is('mice') ? 'active' : '' }}">
                                MICE
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url('/activities') }}" class="nav-link {{ request()->is('activities') ? 'active' : '' }}">
                                Activities
                            </a>
                        </li>

                        <li class="nav-item">
                            {{-- FIX: Changed the route check to match the actual route --}}
                            <a href="{{route('beach-tickets.index')}}" class="nav-link {{ request()->routeIs('beach-tickets.index') || request()->is('beach-tickets*') ? 'active' : '' }}">
                                Beach
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url('/contact') }}" class="nav-link {{ request()->is('contact') ? 'active' : '' }}">
                                Contact Us
                            </a>
                        </li>

                        <li class="nav-item-btn">
                            <a href="{{ route('room.reservation') }}" class="btn btn-check-availability w-100">Book Now</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</div>