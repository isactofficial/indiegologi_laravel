<footer style="background-color: #0C2C5A; color: white; padding: 3rem 0 1rem 0; border-top: 1px solid #999;">
    <div class="container">
        <div class="row" style="display: flex; flex-wrap: wrap; gap: 2rem;">

            <div class="col-md-4" style="flex: 1; min-width: 250px;">
            {{-- Mengganti H4 dengan tag IMG --}}
            <img src="{{ asset('assets/img/logo_revisi_1_white.png') }}" alt="Indiegologi Logo" style="height: 150px; width: auto; margin-bottom: 1rem;">
                <p style="font-size: 14px;">
                    Ruang aman untuk eksplorasi diri dengan pendekatan holistik yang menggabungkan psikologi, logika,
                    dan intuisi.
                </p>
                <div style="margin-top: 1rem;">
                    {{-- Ikon ditautkan ke URL yang sesuai --}}
                    <a href="https://www.instagram.com/indiegologi/" target="_blank" style="margin-right: 10px; color: white;"><i class="bi bi-instagram"></i></a>
                    <a href="https://wa.me/6282220955595" target="_blank" style="margin-right: 10px; color: white;"><i class="bi bi-whatsapp"></i></a>
                    <a href="mailto:temancerita@indiegologi.com" style="margin-right: 10px; color: white;"><i class="bi bi-envelope"></i></a>
                </div>
            </div>

            <div class="col-md-4" style="flex: 1; min-width: 200px;">
                <h6 style="font-weight: bold; margin-bottom: 1rem;">Menu</h6>
                <p style="margin-bottom: 0.3rem;">
                    <a href="{{ route('front.index') }}" style="color: white; text-decoration: none; transition: color 0.3s ease;" 
                       onmouseover="this.style.color='#ccc'" onmouseout="this.style.color='white'">
                        Beranda
                    </a>
                </p>
                <p style="margin-bottom: 0.3rem;">
                    <a href="{{ route('front.articles') }}" style="color: white; text-decoration: none; transition: color 0.3s ease;" 
                       onmouseover="this.style.color='#ccc'" onmouseout="this.style.color='white'">
                        Artikel
                    </a>
                </p>
                <p style="margin-bottom: 0.3rem;">
                    <a href="{{ route('front.sketch') }}" style="color: white; text-decoration: none; transition: color 0.3s ease;" 
                       onmouseover="this.style.color='#ccc'" onmouseout="this.style.color='white'">
                        Painting
                    </a>
                </p>
                <p style="margin-bottom: 0.3rem;">
                    <a href="{{ route('front.layanan') }}" style="color: white; text-decoration: none; transition: color 0.3s ease;" 
                       onmouseover="this.style.color='#ccc'" onmouseout="this.style.color='white'">
                        Layanan
                    </a>
                </p>
                <p style="margin-bottom: 0.3rem;">
                    <a href="{{ route('front.contact') }}" style="color: white; text-decoration: none; transition: color 0.3s ease;" 
                       onmouseover="this.style.color='#ccc'" onmouseout="this.style.color='white'">
                        Kontak Kami
                    </a>
                </p>
            </div>

            <div class="col-md-4" style="flex: 1; min-width: 200px;">
                <h6 style="font-weight: bold; margin-bottom: 1rem;">Kontak</h6>
                {{-- Informasi kontak diperbarui dengan link WhatsApp --}}
                <p style="margin-bottom: 0.3rem;">
                    <a href="https://wa.me/6282220955595" target="_blank" style="color: white; text-decoration: none;">
                        +62 822-2095-5595
                    </a>
                </p>
                <p style="margin-bottom: 0.3rem;">
                    <a href="mailto:temancerita@indiegologi.com" style="color: white; text-decoration: none;">
                        temancerita@indiegologi.com
                    </a>
                </p>
                <p style="margin-bottom: 0.3rem;">Yogyakarta, Indonesia</p>
            </div>

        </div>

        <hr style="margin-top: 2rem; border-color: #aaa;">

        <div style="text-align: center; font-size: 14px; margin-top: 1rem;">
            &copy; 2025 INDIEGOLOGI by INDIEGOLOGI Official
        </div>
    </div>
</footer>