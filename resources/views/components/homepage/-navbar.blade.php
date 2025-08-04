<nav class="navbar navbar-expand-lg bg-white py-3 sticky-top shadow-sm">
    <div class="container">
        {{-- Logo --}}
        <a class="navbar-brand fw-bold fs-4" href="{{ route('homepage') }}">
            Indiegologi
        </a>

        {{-- Tombol Toggle untuk Mobile --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Menu Navigasi --}}
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active fw-medium" aria-current="page" href="{{ route('homepage') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-medium" href="#">Layanan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-medium" href="#">Artikel</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-medium" href="#">Tentang Kami</a>
                </li>
            </ul>

            {{-- Tombol Aksi di Kanan --}}
            <a href="#" class="btn btn-primary rounded-pill fw-medium px-4">
                Konsultasi Gratis
            </a>
        </div>
    </div>
</nav>