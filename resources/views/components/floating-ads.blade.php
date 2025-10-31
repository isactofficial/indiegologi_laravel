@props([
    'topAdImage' => 'assets/img/PROMOTION_WEBSITE.jpg',
    'topAdLink' => '#',
    'bottomAdImage' => 'assets/img/KONSULTASI_GRATIS.jpg',
    'bottomAdLink' => '/layanan'
])

<style>
    /* ===== [START] CSS IKLAN POPUP SISI KANAN (REVISI) ===== */
    .floating-ad-container {
        position: fixed;
        right: 0;
        /* Menempel di kanan */
        top: 50%;
        transform: translateY(-50%);
        width: 465px;
        /* Sesuaikan lebar iklan */
        height: 630px;
        z-index: 1050;
        transition: transform 0.25s ease, opacity 0.25s ease;
        /* Faster transition */
        display: flex;
        flex-direction: column;
        align-items: center;
        overflow: hidden;
    }

    .floating-ad-container.hidden {
        transform: translateY(-50%) translateX(100%);
        /* Geser ke kanan saat ditutup */
        opacity: 0;
        pointer-events: none;
    }

    .floating-ad-container .ad-slot.top-ad {
        height: 66.67%;
        width: 100%;
        position: relative;
        display: flex;
        flex-direction: column;
    }

    .floating-ad-container .ad-slot.bottom-ad {
        height: 33.33%;
        width: 100%;
        position: relative;
        display: flex;
        flex-direction: column;
    }

    .floating-ad-container .close-ad-btn {
        background-color: #f1f1f1;
        border: 1px solid #ddd;
        border-bottom: none;
        border-radius: 5px 5px 0 0;
        padding: 5px 10px;
        font-size: 12px;
        font-family: Arial, sans-serif;
        cursor: pointer;
        width: 100%;
        text-align: center;
        color: #333;
        margin-bottom: -1px;
        flex-shrink: 0;
        z-index: 1;
    }

    .floating-ad-container .ad-link {
        display: block;
        line-height: 0;
        width: 100%;
        height: 100%;
        flex-grow: 1;
    }

    .floating-ad-container .ad-image {
        width: 100%;
        height: 100%;
        border-radius: 0;
        /* Sudut tajam */
        box-shadow: -2px 0 15px rgba(0, 0, 0, 0.15);
        border: 1px solid #ddd;
        border-top: none;
        object-fit: cover;
        /* <-- Mengisi wadah sambil mempertahankan rasio aspek (menghasilkan "zoom") */
        object-position: center;
    }

    /* Mobile responsive styles */
    @media (max-width: 991.98px) {
        .floating-ad-container {
            width: 400px;
            height: 540px;
            right: 10px;
        }
    }

    @media (max-width: 767.98px) {
        .floating-ad-container {
            width: 300px;
            height: 405px;
            right: 5px;
        }

    }

    /* ===== [END] CSS IKLAN POPUP SISI KANAN (REVISI) ===== */
</style>

<div id="popupAdContainer" class="floating-ad-container">
    <button id="closePopupAd" class="close-ad-btn">TUTUP IKLAN</button>
    <div class="ad-slot top-ad">
        <a href="{{ $topAdLink }}" target="_blank" rel="noopener noreferrer" class="ad-link">
            <img src="{{ asset($topAdImage) }}" alt="Iklan promosi 1" class="ad-image">
        </a>
    </div>

    <div class="ad-slot bottom-ad">
        <a href="{{ $bottomAdLink }}" target="_blank" rel="noopener noreferrer" class="ad-link">
            <img src="{{ asset($bottomAdImage) }}" alt="Iklan promosi 2" class="ad-image">
        </a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const adContainer = document.getElementById('popupAdContainer');
        const closeButton = document.getElementById('closePopupAd');

        if (adContainer && closeButton) {
            closeButton.addEventListener('click', function() {
                adContainer.classList.add('hidden');
            });
        }
    });
</script>