<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Selamat Datang - Indiegologi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --indiegologi-primary: #0C2C5A;
            --indiegologi-light-bg: #F5F7FA;
            --indiegologi-dark-text: #212529;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--indiegologi-light-bg);
            color: var(--indiegologi-dark-text);
        }
        .onboarding-container {
            min-height: 100vh;
        }
        .card {
            border-radius: 16px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }
        .card-body { padding: 3rem; }
        .question-step { display: none; } /* Sembunyikan semua step by default */
        .question-step.active { display: block; } /* Tampilkan hanya yang aktif */
        h2 { color: var(--indiegologi-primary); font-weight: 700; }
        .form-check-label {
            width: 100%;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }
        .form-check-input:checked + .form-check-label {
            border-color: var(--indiegologi-primary);
            background-color: rgba(12, 44, 90, 0.05);
            box-shadow: 0 0 0 2px rgba(12, 44, 90, 0.2);
        }
        .form-check-input {
            display: none;
        }
        .progress-bar {
            background-color: var(--indiegologi-primary);
        }
    </style>
</head>
<body>
    <div class="container onboarding-container d-flex align-items-center justify-content-center py-5">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('onboarding.store') }}" method="POST">
                        @csrf
                        
                        <div id="step-1" class="question-step active">
                            <h2 class="mb-2">Selamat Datang, {{ Auth::user()->name }}!</h2>
                            <p class="text-muted mb-4">Apa tujuan utama Anda bergabung dengan Indiegologi?</p>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="main_goal" id="goal1" value="mencari_inspirasi" onclick="nextStep(2, 'A')">
                                <label class="form-check-label" for="goal1">Saya ingin mencari inspirasi & wawasan baru.</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="main_goal" id="goal2" value="menggunakan_layanan" onclick="nextStep(2, 'B')">
                                <label class="form-check-label" for="goal2">Saya tertarik untuk menggunakan layanan konsultasi.</label>
                            </div>
                        </div>

                        <div id="step-2A" class="question-step">
                            <h2 class="mb-4">Topik apa yang paling menarik bagi Anda?</h2>
                            <p class="text-muted mb-4">Pilih maksimal 3.</p>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="interests[]" value="bisnis" id="interest1">
                                <label class="form-check-label" for="interest1">Bisnis & Startup</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="interests[]" value="pengembangan_diri" id="interest2">
                                <label class="form-check-label" for="interest2">Pengembangan Diri & Karir</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="interests[]" value="desain_kreatif" id="interest3">
                                <label class="form-check-label" for="interest3">Desain & Kreativitas</label>
                            </div>
                            <button type="button" class="btn btn-dark w-100 mt-3" onclick="nextStep(3, null)">Lanjutkan</button>
                        </div>

                        <div id="step-2B" class="question-step">
                            <h2 class="mb-4">Layanan apa yang paling Anda butuhkan saat ini?</h2>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="service_need" id="service1" value="konsultasi_karir" onclick="nextStep(3, null)">
                                <label class="form-check-label" for="service1">Konsultasi Karir & Pengembangan Diri</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="service_need" id="service2" value="bimbingan_skripsi" onclick="nextStep(3, null)">
                                <label class="form-check-label" for="service2">Bimbingan Tugas Akhir / Skripsi</label>
                            </div>
                             <div class="form-check">
                                <input class="form-check-input" type="radio" name="service_need" id="service3" value="lainnya" onclick="nextStep(3, null)">
                                <label class="form-check-label" for="service3">Lainnya</label>
                            </div>
                        </div>

                        <div id="step-3" class="question-step text-center">
                            <h2 class="mb-3">Terima Kasih!</h2>
                            <p class="text-muted mb-4">Anda siap untuk memulai perjalanan Anda di Indiegologi. Klik selesai untuk masuk ke dashboard.</p>
                            <button type="submit" class="btn btn-primary w-50">Selesai</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentStep = 1;

        function nextStep(step, branch) {
            // Sembunyikan semua step
            document.querySelectorAll('.question-step').forEach(el => el.classList.remove('active'));

            let nextStepId = `step-${step}`;
            if (branch) {
                nextStepId += branch;
            }

            // Tampilkan step berikutnya
            document.getElementById(nextStepId).classList.add('active');
            currentStep = step;
        }
    </script>
</body>
</html>