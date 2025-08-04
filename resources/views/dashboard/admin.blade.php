@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded-4 shadow-sm p-4" style="border: 1px solid rgba(0, 97, 122, 0.1);">
                <div class="d-flex align-items-center">
                    <div class="d-flex justify-content-center align-items-center rounded-circle me-4" style="width: 80px; height: 80px; background-image: linear-gradient(135deg, #f4b70420, #cb278620);">
                        <i class="fas fa-user-circle fs-2" style="color: #00617a;"></i>
                    </div>
                    <div>
                        <h2 class="fs-3 fw-bold mb-1" style="color: #00617a;">Welcome, {{ Auth::user()->name }}!</h2>
                        <p class="text-muted mb-0">Here's what's happening with your articles and sketches today on Indiegologi.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        @foreach([
            ['Artikel', $totalArticles, 'Artikel Dipublikasikan', $publishedArticles, 'newspaper', '#cb2786'],
            ['Sketsa', $totalSketches, 'Sketsa Dipublikasikan', $publishedSketches, 'palette', '#00617a'],
            ['Layanan', $totalServices, 'Booking', $totalBookings, 'handshake', '#f4b704'],
            ['Pengguna', $totalUsers, 'Verified Users', 0, 'users', '#cb2786']
        ] as $stat)
        <div class="col-md-3 mb-3">
            <div class="bg-white rounded-3 shadow-sm p-4" style="border: 1px solid {{ $stat[5] }}1A;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-semibold m-0" style="color: #343a40;">Total {{ $stat[0] }}</h5>
                    <div class="rounded-circle p-3" style="background-color: {{ $stat[5] }}; box-shadow: 0 4px 12px {{ $stat[5] }}30;">
                        <i class="fas fa-{{ $stat[4] }}" style="color: #fff; font-size: 1.5rem;"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-center" style="color: {{ $stat[5] }};">{{ $stat[1] }}</h3>
                <div class="progress mt-2" style="height: 6px; background-color: {{ $stat[5] }}20;">
                    <div class="progress-bar" role="progressbar" style="width: 100%; background-color: {{ $stat[5] }};" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="bg-white rounded-3 shadow-sm p-3" style="border: 1px solid #cb27861A;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="fw-semibold m-0 fs-6" style="color: #343a40;">Total Views (Articles)</h5>
                    <div class="rounded-circle p-2" style="background-color: #cb278620;">
                        <i class="fas fa-eye" style="color: #cb2786; font-size: 1.25rem;"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-center fs-4">{{ $articles->sum('views') }}</h3>
                <div class="progress mt-2" style="height: 4px; background-color: #cb278620;">
                    <div class="progress-bar" role="progressbar" style="width: 100%; background-color: #cb2786;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="bg-white rounded-3 shadow-sm p-3" style="border: 1px solid #00617a1A;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="fw-semibold m-0 fs-6" style="color: #343a40;">Total Views (Sketches)</h5>
                    <div class="rounded-circle p-2" style="background-color: #00617a20;">
                        <i class="fas fa-eye" style="color: #00617a; font-size: 1.25rem;"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-center fs-4">{{ $sketches->sum('views') }}</h3>
                <div class="progress mt-2" style="height: 4px; background-color: #00617a20;">
                    <div class="progress-bar" role="progressbar" style="width: 100%; background-color: #00617a;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
