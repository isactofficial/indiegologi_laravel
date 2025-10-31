<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body p-3">
        <div class="text-center mb-4">
            <div class="position-relative d-inline-block">
                @if(auth()->user()->userProfile && auth()->user()->userProfile->profile_photo)
                    <img src="{{ asset('storage/' . auth()->user()->userProfile->profile_photo) }}" 
                         alt="{{ auth()->user()->name }}" 
                         class="rounded-circle" 
                         style="width: 80px; height: 80px; object-fit: cover;">
                @else
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" 
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-user text-muted fs-4"></i>
                    </div>
                @endif
            </div>
            <h6 class="mt-3 mb-1 fw-bold">{{ auth()->user()->name }}</h6>
            <p class="text-muted small">{{ auth()->user()->email }}</p>
        </div>

        <nav class="nav flex-column">
            <a class="nav-link py-2 px-3 mb-2 rounded-3 {{ request()->routeIs('profile.edit') ? 'active' : '' }}" 
               href="{{ route('profile.edit') }}">
                <i class="fas fa-user-edit me-2"></i>
                Edit Profil
            </a>
            
            <a class="nav-link py-2 px-3 mb-2 rounded-3 {{ request()->routeIs('profile.event-bookings') ? 'active' : '' }}" 
               href="{{ route('profile.event-bookings') }}">
                <i class="fas fa-calendar-check me-2"></i>
                Booking Event Saya
            </a>
            
            <a class="nav-link py-2 px-3 mb-2 rounded-3 {{ request()->routeIs('profile') ? 'active' : '' }}" 
               href="{{ route('profile') }}">
                <i class="fas fa-id-card me-2"></i>
                Profil Saya
            </a>

            <!-- You can add more menu items here as needed -->
        </nav>
    </div>
</div>

<style>
.nav-link {
    color: #6c757d;
    border: 1px solid transparent;
    transition: all 0.3s ease;
}

.nav-link:hover {
    color: var(--indiegologi-primary);
    background-color: rgba(12, 44, 90, 0.05);
    border-color: rgba(12, 44, 90, 0.1);
}

.nav-link.active {
    color: white;
    background-color: var(--indiegologi-primary);
    border-color: var(--indiegologi-primary);
}

.nav-link i {
    width: 20px;
    text-align: center;
}
</style>