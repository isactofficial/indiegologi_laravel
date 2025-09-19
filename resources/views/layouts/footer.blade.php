<footer style="background-color: #0C2C5A; color: white; padding: 3rem 0 1rem 0; border-top: 1px solid #999;">
    <div class="container">
        <div class="row" style="display: flex; flex-wrap: wrap; gap: 2rem;">

            <div class="col-md-4" style="flex: 1; min-width: 250px;">
                <h4 style="font-weight: bold; margin-bottom: 1rem;">Indiegologi</h4>
                <p style="font-size: 14px;">
                    Ruang aman untuk eksplorasi diri dengan pendekatan holistik yang menggabungkan psikologi, logika,
                    dan intuisi.
                </p>
                <div style="margin-top: 1rem;">
                    {{-- Ikon ditautkan ke URL yang sesuai --}}
                    <a href="https://www.instagram.com/indiegologi/" target="_blank" style="margin-right: 10px; color: white;"><i class="bi bi-instagram"></i></a>
                    <a href="https://wa.me/6282220955595" target="_blank" style="margin-right: 10px; color: white;"><i class="bi bi-whatsapp"></i></a>
                    <a href="mailto:indiegologiofficial@gmail.com" style="margin-right: 10px; color: white;"><i class="bi bi-envelope"></i></a>
                </div>
            </div>

            <div class="col-md-4" style="flex: 1; min-width: 200px;">
                <h6 style="font-weight: bold; margin-bottom: 1rem;">Layanan</h6>
                @if(isset($servicesForFooter) && $servicesForFooter->count() > 0)
                    @foreach($servicesForFooter as $service)
                        <p style="margin-bottom: 0.3rem;">{{ $service->title }}</p>
                    @endforeach
                @else
                    {{-- Tampilan jika tidak ada layanan yang tersedia --}}
                    <p style="margin-bottom: 0.3rem;">Layanan akan segera hadir.</p>
                @endif
            </div>

            <div class="col-md-4" style="flex: 1; min-width: 200px;">
                <h6 style="font-weight: bold; margin-bottom: 1rem;">Kontak</h6>
                {{-- Informasi kontak diperbarui --}}
                <p style="margin-bottom: 0.3rem;">+62 822-2095-5595</p>
                <p style="margin-bottom: 0.3rem;">indiegologiofficial@gmail.com</p>
                <p style="margin-bottom: 0.3rem;">Yogyakarta, Indonesia</p>
            </div>

        </div>

        <hr style="margin-top: 2rem; border-color: #aaa;">

        <div style="text-align: center; font-size: 14px; margin-top: 1rem;">
            &copy; 2025 INDIEGOLOGI by INDIEGOLOGI Official
        </div>
    </div>
</footer>
