@extends('layouts.app')

@section('content')
<section class="py-5 pt-lg-0 mt-5">
    <div class="container">
        {{-- Tombol kembali dengan animasi fade-right --}}
        <div class="d-flex justify-content-between pt-5" data-aos="fade-right">
            <a href="{{ route('front.index') }}" class="btn px-4 py-2"
                style="background-color: #D6E4FF; color: #0C2C5A; border-radius: 8px;">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>
        {{-- Header dengan animasi fade-down --}}
        <div class="text-center mb-5 p-4 rounded-4" data-aos="fade-down">
            <h1 class="fw-bold mb-3 article-text" style="color: #0C2C5A;">Mari Terhubung dengan Indiegologi</h1>
            <p class="text-muted w-75 mx-auto article-text">Punya pertanyaan, ide kolaborasi, atau ingin berbagi cerita? Tim Indiegologi siap melayani Anda. Kami adalah ruang aman untuk eksplorasi diri, dengan visi holistik untuk kesejahteraan mental Anda.</p>
        </div>

        <div class="row g-4">
            {{-- Kolom formulir dengan animasi fade-right --}}
            <div class="col-lg-7 mb-4 mb-lg-0" data-aos="fade-right" data-aos-delay="200">
                <div class="p-4 p-lg-5 rounded-4 h-100" style="background-color: #ffffff; box-shadow: 0 10px 30px rgba(12, 44, 90, 0.08);">
                    <h4 class="fw-semibold mb-4 article-text" style="color: #0C2C5A;">Kirimkan Pesan Kepada Kami</h4>
                    <form>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-floating mb-1">
                                    <input type="text" class="form-control border-0" style="background-color: #f4f4f4;" id="nameInput" placeholder="Your name" required>
                                    <label for="nameInput" class="text-muted article-text">Nama Anda</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-1">
                                    <input type="email" class="form-control border-0" style="background-color: #f4f4f4;" id="emailInput" placeholder="you@example.com" required>
                                    <label for="emailInput" class="text-muted">Email</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-1">
                                    <input type="text" class="form-control border-0" style="background-color: #f4f4f4;" id="subjectInput" placeholder="How can we help you?">
                                    <label for="subjectInput" class="text-muted">Subjek</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-1">
                                    <textarea class="form-control border-0" style="background-color: #f4f4f4; height: 140px;" id="messageInput" placeholder="Tell us more about your inquiry..." required></textarea>
                                    <label for="messageInput" class="text-muted">Pesan Anda</label>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <button class="btn btn-lg px-4 py-2 rounded-pill text-white d-flex align-items-center" style="background: #0C2C5A; border: none; box-shadow: 0 4px 12px rgba(12, 44, 90, 0.25); transition: all 0.3s ease;">
                                    <span>Kirim Pesan</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-send ms-2" viewBox="0 0 16 16">
                                        <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576 6.636 10.07Zm6.787-8.201L1.591 6.602l4.339 2.76 7.494-7.493Z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Kolom info kontak dengan animasi fade-left --}}
            <div class="col-lg-5" data-aos="fade-left" data-aos-delay="200">
                <div class="p-4 p-lg-4 rounded-4 mb-4" style="background-color: #ffffff; box-shadow: 0 10px 30px rgba(12, 44, 90, 0.08);">
                    <h4 class="fw-semibold mb-4 article-text" style="color: #0C2C5A;">Lokasi Kami</h4>
                    {{-- Detail lokasi lainnya --}}
                    <div class="d-flex mb-4 align-items-start">
                        <div class="me-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="background-color: #D6E4FF; width: 50px; height: 50px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#0C2C5A" class="bi bi-building" viewBox="0 0 16 16"><path d="M4 2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v10.5a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5V2.5Z"/><path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2Zm10-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1Z"/></svg>
                            </div>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1" style="color: #0C2C5A;">Kantor Pusat Indiegologi</h6>
                            <p class="mb-1 text-muted">Jl. Sadar Dusun I Kampung Padang,<br>Riau - 28557</p>
                        </div>
                    </div>
                    <div class="d-flex mb-4 align-items-start">
                        <div class="me-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="background-color: #D6E4FF; width: 50px; height: 50px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#0C2C5A" class="bi bi-building-fill" viewBox="0 0 16 16"><path d="M3 0a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h3v-3.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V16h3a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1H3Zm1 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5ZM4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM7.5 5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM4.5 8h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5Z"/></svg>
                            </div>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1" style="color: #0C2C5A;">Kantor Cabang Indiegologi</h6>
                            <p class="mb-1 text-muted">Ngadiwinatan NG I/1106 Yogyakarta,<br>DIY 55261</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start">
                        <div class="me-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="background-color: #D6E4FF; width: 50px; height: 50px;">
                                {{-- PERUBAHAN: Ikon diubah menjadi WhatsApp --}}
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#0C2C5A" class="bi bi-whatsapp" viewBox="0 0 16 16">
                                    <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1" style="color: #0C2C5A;">Telepon/WhatsApp</h6>
                            <p class="mb-1">
                                <a href="https://wa.me/6281234567890" target="_blank" class="text-decoration-none text-muted" style="transition: color 0.3s ease;">+62 812-3456-7890</a>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-4 p-lg-4 rounded-4" style="background-color: #ffffff; box-shadow: 0 10px 30px rgba(12, 44, 90, 0.08);">
                    <h4 class="fw-semibold mb-4 article-text" style="color: #0C2C5A;">Terhubung dengan Kami</h4>
                    <div class="d-flex flex-column gap-3">
                        {{-- Sosial media links --}}
                        <a href="https://www.instagram.com/indiegologi/" class="d-flex align-items-center text-decoration-none p-3 rounded-3" style="transition: all 0.3s ease; ">
                            <div class="me-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="background-color: #D6E4FF; width: 50px; height: 50px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#0C2C5A" class="bi bi-instagram" viewBox="0 0 16 16"><path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"/></svg>
                                </div>
                            </div>
                            <span class="fw-medium" style="color: #424242;">indiegologi_official</span>
                        </a>
                        <a href="https://www.linkedin.com/company/indiegologi/" class="d-flex align-items-center text-decoration-none p-3 rounded-3" style="transition: all 0.3s ease; ">
                            <div class="me-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="background-color: #D6E4FF; width: 50px; height: 50px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#0C2C5A" class="bi bi-linkedin" viewBox="0 0 16 16"><path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248-.822 0-1.359.54-1.359 1.248 0 .694.521 1.248 1.327 1.248h.016zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016a5.54 5.54 0 0 1 .016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225h2.4z"/></svg>
                                </div>
                            </div>
                            <span class="fw-medium" style="color: #424242;">Indiegologi Official</span>
                        </a>
                        <a href="mailto:indiegologiofficial@gmail.com" class="d-flex align-items-center text-decoration-none p-3 rounded-3" style="transition: all 0.3s ease; ">
                            <div class="me-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="background-color: #D6E4FF; width: 50px; height: 50px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#0C2C5A" class="bi bi-envelope" viewBox="0 0 16 16"><path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/></svg>
                                </div>
                            </div>
                            <span class="fw-medium" style="color: #424242;">indiegologiofficial@gmail.com</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
{{-- STYLE UNTUK ANIMASI AOS --}}
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

<style>
/* Custom hover effects */
.hover-link:hover {
    opacity: 1 !important;
    transition: all 0.3s ease;
}
.hover-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(12, 44, 90, 0.15);
    transition: all 0.3s ease;
}
.form-control:focus {
    box-shadow: 0 0 0 3px rgba(12, 44, 90, 0.15);
    border-color: #0C2C5A;
}
.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(12, 44, 90, 0.3);
}
/* Smooth transitions */
.form-control, .btn, a {
    transition: all 0.3s ease;
}
</style>
@endpush

@push('scripts')
{{-- SCRIPT UNTUK ANIMASI AOS --}}
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 900,
        easing: 'ease-in-out-sine',
        once: false,
        offset: 120,
    });

    let lastScrollTop = 0;
    const allAosElements = document.querySelectorAll('[data-aos]');

    window.addEventListener('scroll', function() {
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        if (scrollTop < lastScrollTop) {
            allAosElements.forEach(function(element) {
                if (element.getBoundingClientRect().top > window.innerHeight) {
                    element.classList.remove('aos-animate');
                }
            });
        }
        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
    }, false);
</script>
@endpush
