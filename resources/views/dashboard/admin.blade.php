@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    {{-- Welcome Banner --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded-4 shadow-sm p-4" style="border: 1px solid rgba(12, 44, 90, 0.1);">
                <div class="d-flex align-items-center">
                    <div class="d-flex justify-content-center align-items-center rounded-circle me-4" style="width: 80px; height: 80px; background-color: rgba(12, 44, 90, 0.1);">
                        <i class="fas fa-user-circle fs-2" style="color: #0C2C5A;"></i>
                    </div>
                    <div>
                        <h2 class="fs-3 fw-bold mb-1" style="color: #0C2C5A;">Welcome, {{ Auth::user()->name }}!</h2>
                        <p class="text-muted mb-0">Here's what's happening with your articles and sketches today on Indiegologi.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Statistics --}}
    <div class="row mb-4">
        @php
            // Variabel $totalArticles, $totalSketches, dll. sudah dikirim dari controller
            $mainColor = '#0C2C5A';
            $stats = [
                ['Artikel', $publishedArticles, 'newspaper'], // Menggunakan publishedArticles sesuai controller
                ['Sketsa', $publishedSketches, 'palette'],   // Menggunakan publishedSketches sesuai controller
                ['Layanan', $totalServices, 'handshake'],
                ['Pengguna', $totalUsers, 'users']
            ];
        @endphp

        @foreach($stats as $stat)
        <div class="col-md-3 mb-3">
            <div class="bg-white rounded-3 shadow-sm p-4 h-100" style="border: 1px solid {{ $mainColor }}1A;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-semibold m-0" style="color: #343a40;">Total {{ $stat[0] }}</h5>
                    <div class="rounded-circle p-3 d-flex justify-content-center align-items-center" style="width: 60px; height: 60px; background-color: {{ $mainColor }}; box-shadow: 0 4px 12px {{ $mainColor }}30;">
                        <i class="fas fa-{{ $stat[2] }}" style="color: #fff; font-size: 1.5rem;"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-start display-5" style="color: {{ $mainColor }};">{{ $stat[1] }}</h3>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Views Statistics (Article & Sketch Views from their own table) --}}
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="bg-white rounded-3 shadow-sm p-3 h-100" style="border: 1px solid rgba(12, 44, 90, 0.1);">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="fw-semibold m-0 fs-6" style="color: #343a40;">Total Views (Articles)</h5>
                    <div class="rounded-circle p-2" style="background-color: rgba(12, 44, 90, 0.1);">
                        <i class="fas fa-eye" style="color: #0C2C5A; font-size: 1.25rem;"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-center fs-2" style="color: #0C2C5A;">{{ $articles->sum('views') }}</h3>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="bg-white rounded-3 shadow-sm p-3 h-100" style="border: 1px solid rgba(12, 44, 90, 0.1);">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="fw-semibold m-0 fs-6" style="color: #343a40;">Total Views (Sketches)</h5>
                    <div class="rounded-circle p-2" style="background-color: rgba(12, 44, 90, 0.1);">
                        <i class="fas fa-eye" style="color: #0C2C5A; font-size: 1.25rem;"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-center fs-2" style="color: #0C2C5A;">{{ $sketches->sum('views') }}</h3>
            </div>
        </div>
    </div>
    
    {{-- [BARU] Page Visits and Chart Section --}}
    <div class="row mb-4">
        {{-- Page Visits & Chart --}}
        <div class="col-lg-8 mb-4">
            {{-- Page Visits List --}}
            <div class="bg-white rounded-3 shadow-sm p-4 mb-4" style="border: 1px solid rgba(12, 44, 90, 0.1);">
                <h5 class="fw-bold mb-3" style="color: #0C2C5A;"><i class="fas fa-list me-2"></i>Page Visits</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">Homepage <span class="badge bg-primary rounded-pill" style="background-color: #0C2C5A !important;">{{ $homepageVisits }}</span></li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">Articles <span class="badge bg-primary rounded-pill" style="background-color: #0C2C5A !important;">{{ $articleVisits }}</span></li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">Sketches <span class="badge bg-primary rounded-pill" style="background-color: #0C2C5A !important;">{{ $sketchVisits }}</span></li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">Layanan <span class="badge bg-primary rounded-pill" style="background-color: #0C2C5A !important;">{{ $layananVisits }}</span></li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">Contact <span class="badge bg-primary rounded-pill" style="background-color: #0C2C5A !important;">{{ $contactVisits }}</span></li>
                </ul>
            </div>

            {{-- Page Visit Statistics Chart --}}
            <div class="bg-white rounded-3 shadow-sm p-4" style="border: 1px solid rgba(12, 44, 90, 0.1);">
                <h5 class="fw-bold mb-3" style="color: #0C2C5A;"><i class="fas fa-chart-bar me-2"></i>Page Visit Statistics Chart</h5>
                <canvas id="pageVisitChart"></canvas>
            </div>
        </div>

        {{-- Total Visits --}}
        <div class="col-lg-4 mb-4">
            <div class="bg-white rounded-3 shadow-sm p-4 h-100" style="border: 1px solid rgba(12, 44, 90, 0.1);">
                <h5 class="fw-bold text-center mb-3" style="color: #0C2C5A;"><i class="fas fa-globe-asia me-2"></i>Total Visits</h5>
                <div class="display-4 fw-bold text-center my-3" style="color: #0C2C5A;">{{ $totalVisits }}</div>
                <ul class="list-group list-group-flush mt-4">
                   <li class="list-group-item d-flex justify-content-between align-items-center"><i class="fas fa-calendar-day me-2 text-muted"></i> Today <span class="fw-bold" style="color: #0C2C5A;">{{ $todayVisits }}</span></li>
                   <li class="list-group-item d-flex justify-content-between align-items-center"><i class="fas fa-calendar-week me-2 text-muted"></i> This Week <span class="fw-bold" style="color: #0C2C5A;">{{ $weekVisits }}</span></li>
                   <li class="list-group-item d-flex justify-content-between align-items-center"><i class="fas fa-calendar-alt me-2 text-muted"></i> This Month <span class="fw-bold" style="color: #0C2C5A;">{{ $monthVisits }}</span></li>
                   <li class="list-group-item d-flex justify-content-between align-items-center"><i class="fas fa-calendar-check me-2 text-muted"></i> This Year <span class="fw-bold" style="color: #0C2C5A;">{{ $yearVisits }}</span></li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Library Chart.js untuk membuat grafik --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('pageVisitChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Homepage', 'Articles', 'Sketches', 'Layanan', 'Contact'],
                    datasets: [{
                        label: 'Page Visits',
                        data: [
                            {{ $homepageVisits }},
                            {{ $articleVisits }},
                            {{ $sketchVisits }},
                            {{ $layananVisits }},
                            {{ $contactVisits }}
                        ],
                        backgroundColor: [
                            'rgba(12, 44, 90, 0.8)',
                            'rgba(12, 44, 90, 0.7)',
                            'rgba(12, 44, 90, 0.6)',
                            'rgba(12, 44, 90, 0.5)',
                            'rgba(12, 44, 90, 0.4)'
                        ],
                        borderColor: 'rgba(12, 44, 90, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: { y: { beginAtZero: true } },
                    plugins: { legend: { display: false } }
                }
            });
        }
    });
</script>
@endpush