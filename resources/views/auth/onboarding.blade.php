<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Selamat Datang - Indiegologi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --indiegologi-primary: #0C2C5A;
            --indiegologi-light-bg: #F5F7FA;
            --indiegologi-dark-text: #212529;
            --indiegologi-light-text: #ffffff;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--indiegologi-light-bg);
            color: var(--indiegologi-dark-text);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 2rem 0;
        }
        .card {
            border-radius: 16px;
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            width: 100%;
            max-width: 600px;
        }
        .card-body { padding: 3rem; }
        .question-step {
            display: none;
            animation: fadeIn 0.5s ease-in-out;
        }
        .question-step.active { display: block; }
        h2 {
            font-family: 'Playfair Display', serif;
            color: var(--indiegologi-primary);
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 0.75rem;
        }
        .form-check-label {
            width: 100%;
            padding: 1rem;
            border: 1px solid #e0e6ed;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            display: flex;
            align-items: center;
        }
        .form-check-label:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.07);
        }
        .form-check-input:checked + .form-check-label {
            border-color: var(--indiegologi-primary);
            background-color: rgba(12, 44, 90, 0.05);
            box-shadow: 0 0 0 2px rgba(12, 44, 90, 0.2);
            transform: translateY(-3px);
        }
        .form-check-input { display: none; }
        .progress {
            height: 8px;
            border-radius: 0;
            background-color: #e9ecef;
        }
        .progress-bar {
            background-color: var(--indiegologi-primary);
            transition: width 0.4s ease-in-out;
        }
        .btn-primary {
            background-color: var(--indiegologi-primary);
            border-color: var(--indiegologi-primary);
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: #082142;
            border-color: #082142;
        }
        .btn-outline-secondary {
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @media (max-width: 576px) {
            body { align-items: flex-start; }
            .card-body { padding: 2rem 1.5rem; }
            h2 { font-size: 1.5rem; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8 col-xl-7">
                <div class="card">
                    <div class="progress">
                        <div class="progress-bar" id="progressBar" role="progressbar"></div>
                    </div>
                    <div class="card-body">
                        <form id="onboardingForm" action="{{ route('onboarding.store') }}" method="POST">
                            @csrf

                            <div id="step-1" class="question-step text-center">
                                <h2 class="mb-2">Selamat Datang, {{ Auth::user()->name }}!</h2>
                                <p class="text-muted mb-4">Untuk siapa sesi terapi yang Anda cari?</p>
                                <div class="form-check mb-3 text-start"><input class="form-check-input" type="radio" name="therapy_for" id="for_self" value="Untuk Diri Sendiri"><label class="form-check-label" for="for_self">Untuk saya sendiri</label></div>
                                <div class="form-check mb-3 text-start"><input class="form-check-input" type="radio" name="therapy_for" id="for_couple" value="Untuk Pasangan"><label class="form-check-label" for="for_couple">Untuk saya dan pasangan saya</label></div>
                                <div class="form-check text-start"><input class="form-check-input" type="radio" name="therapy_for" id="for_child" value="Untuk Anak"><label class="form-check-label" for="for_child">Untuk anak remaja saya</label></div>
                            </div>

                            <div id="step-2" class="question-step text-center">
                                <h2 class="mb-4">Apakah Anda pernah mengikuti terapi sebelumnya?</h2>
                                <div class="form-check mb-3 text-start"><input class="form-check-input" type="radio" name="previous_therapy" id="prev_yes" value="Ya"><label class="form-check-label" for="prev_yes">Ya</label></div>
                                <div class="form-check text-start"><input class="form-check-input" type="radio" name="previous_therapy" id="prev_no" value="Belum Pernah"><label class="form-check-label" for="prev_no">Belum pernah</label></div>
                            </div>

                            <div id="step-3" class="question-step text-center">
                                <h2 class="mb-4">Apakah Anda memiliki preferensi spesifik untuk terapis?</h2>
                                <div class="form-check mb-3 text-start"><input class="form-check-input" type="radio" name="therapist_preference" id="pref_spiritual" value="Pendekatan Religius/Spiritual"><label class="form-check-label" for="pref_spiritual">Terapis dengan pendekatan religius/spiritual tertentu</label></div>
                                <div class="form-check mb-3 text-start"><input class="form-check-input" type="radio" name="therapist_preference" id="pref_universal" value="Universal"><label class="form-check-label" for="pref_universal">Universal (semua agama baik)</label></div>
                                <div class="form-check text-start"><input class="form-check-input" type="radio" name="therapist_preference" id="pref_none" value="Tidak Ada Preferensi"><label class="form-check-label" for="pref_none">Tidak ada preferensi khusus</label></div>
                            </div>

                            <div id="step-3a" class="question-step text-center">
                                <h2 class="mb-4">Apa kepercayaan Anda?</h2>
                                <div class="form-check mb-3 text-start"><input class="form-check-input" type="radio" name="belief" id="belief_kristiani" value="Kristiani"><label class="form-check-label" for="belief_kristiani">Kristiani</label></div>
                                <div class="form-check mb-3 text-start"><input class="form-check-input" type="radio" name="belief" id="belief_lain" value="Lain-lain"><label class="form-check-label" for="belief_lain">Lain-lain</label></div>
                            </div>

                            <div id="step-4" class="question-step text-center">
                                <h2 class="mb-4">Apa harapan utama Anda dari sesi terapi?</h2>
                                <p class="text-muted mb-4">Pilih yang paling menggambarkan Anda.</p>
                                <div class="form-check mb-3 text-start"><input class="form-check-input" type="radio" name="therapy_goal" id="goal_listen" value="Mendengarkan"><label class="form-check-label" for="goal_listen">Seseorang yang lebih banyak mendengarkan saya</label></div>
                                <div class="form-check mb-3 text-start"><input class="form-check-input" type="radio" name="therapy_goal" id="goal_skills" value="Belajar Keterampilan Baru"><label class="form-check-label" for="goal_skills">Seseorang yang membantu saya belajar keterampilan baru</label></div>
                                <div class="form-check text-start"><input class="form-check-input" type="radio" name="therapy_goal" id="goal_discuss" value="Bisa Berdiskusi"><label class="form-check-label" for="goal_discuss">Seseorang yang bisa berdiskusi dengan saya</label></div>
                            </div>

                            <div id="step-4a" class="question-step text-center">
                                <h2 class="mb-4">Lebih spesifik, apa yang Anda harapkan?</h2>
                                <div class="form-check mb-3 text-start"><input class="form-check-input" type="radio" name="listening_detail" id="listen_didengarkan" value="Didengarkan Saja"><label class="form-check-label" for="listen_didengarkan">Didengarkan saja</label></div>
                                <div class="form-check mb-3 text-start"><input class="form-check-input" type="radio" name="listening_detail" id="listen_feedback" value="Mendapatkan Feedback"><label class="form-check-label" for="listen_feedback">Mendapatkan feedback</label></div>
                                <div class="form-check text-start"><input class="form-check-input" type="radio" name="listening_detail" id="listen_solusi" value="Mendapatkan Solusi"><label class="form-check-label" for="listen_solusi">Mendapatkan solusi</label></div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-5">
                                <button type="button" id="prevBtn" class="btn btn-outline-secondary">Kembali</button>
                                <button type="button" id="nextBtn" class="btn btn-primary">Lanjutkan</button>
                                <button type="submit" id="submitBtn" class="btn btn-primary" style="display: none;">Selesai & Lanjutkan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const steps = document.querySelectorAll('.question-step');
            const progressBar = document.getElementById('progressBar');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const submitBtn = document.getElementById('submitBtn');
            const form = document.getElementById('onboardingForm');

            // objek untuk mengelola alur langkah
            const stepFlow = {
                'step-1': { next: 'step-2' },
                'step-2': { next: 'step-3' },
                'step-3': {
                    conditional: true,
                    field: 'therapist_preference',
                    paths: {
                        'Pendekatan Religius/Spiritual': 'step-3a',
                        'default': 'step-4'
                    }
                },
                'step-3a': { next: 'step-4' },
                'step-4': {
                    conditional: true,
                    field: 'therapy_goal',
                    paths: {
                        'Mendengarkan': 'step-4a',
                        'default': 'submit'
                    }
                },
                'step-4a': { next: 'submit' }
            };

            let stepHistory = ['step-1'];

            function showStep(stepId) {
                steps.forEach(step => step.classList.remove('active'));
                document.getElementById(stepId).classList.add('active');
                updateUI();
            }

            function isLastStep(stepId) {
                const flow = stepFlow[stepId];

                // Jika langkah ini secara langsung menuju submit
                if (flow.next === 'submit') {
                    return true;
                }

                if (flow.conditional) {
                    const choice = document.querySelector(`input[name="${flow.field}"]:checked`);

                    if (!choice) {
                        return false;
                    }
                    const nextStepId = flow.paths[choice.value] || flow.paths.default;
                    if (nextStepId === 'submit') {
                        return true;
                    }
                }
                return false;
            }

            function updateUI() {
                const currentStepId = stepHistory[stepHistory.length - 1];

                // Logika progress bar
                const totalMainSteps = 4;
                let currentStepNumber = 0;
                if (currentStepId.includes('1')) currentStepNumber = 1;
                if (currentStepId.includes('2')) currentStepNumber = 2;
                if (currentStepId.includes('3')) currentStepNumber = 3;
                if (currentStepId.includes('4')) currentStepNumber = 4;

                const progress = ((currentStepNumber -1) / totalMainSteps) * 100;
                progressBar.style.width = `${progress}%`;

                // Menampilkan/menyembunyikan tombol kembali
                prevBtn.style.display = (stepHistory.length > 1) ? 'inline-block' : 'none';

                // Logika menampilkan tombol Lanjutkan atau Selesai
                if (isLastStep(currentStepId)) {
                    nextBtn.style.display = 'none';
                    submitBtn.style.display = 'inline-block';
                } else {
                    nextBtn.style.display = 'inline-block';
                    submitBtn.style.display = 'none';
                }
            }

            // Menambahkan event listener ke semua radio button untuk update UI secara real-time
            document.querySelectorAll('input[type="radio"]').forEach(radio => {
                radio.addEventListener('change', updateUI);
            });

            function navigateNext() {
                const currentStepId = stepHistory[stepHistory.length - 1];
                const currentStepElement = document.getElementById(currentStepId);
                const choice = currentStepElement.querySelector('input:checked');

                if (!choice) {
                    alert('Silakan pilih salah satu opsi.');
                    return;
                }

                const flow = stepFlow[currentStepId];
                let nextStepId;

                if (flow.conditional) {
                    nextStepId = flow.paths[choice.value] || flow.paths.default;
                } else {
                    nextStepId = flow.next;
                }

                if (nextStepId === 'submit') {
                    form.submit();
                } else {
                    stepHistory.push(nextStepId);
                    showStep(nextStepId);
                }
            }

            function navigateBack() {
                if (stepHistory.length > 1) {
                    stepHistory.pop();
                    showStep(stepHistory[stepHistory.length - 1]);
                }
            }

            nextBtn.addEventListener('click', navigateNext);
            prevBtn.addEventListener('click', navigateBack);

            showStep('step-1');
        });
    </script>
</body>
</html>
