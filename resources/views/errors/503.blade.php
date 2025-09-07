<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>503 Service Unavailable - Layanan Tidak Tersedia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,600,700,800&display=swap" rel="stylesheet">
    <style>
        :root {
            --kamcup-primary: #0C2C5A;
            --kamcup-secondary: #0C2C5A;
            --kamcup-accent: #f4b704;
            --text-dark: #212529;
            --text-light: #ffffff;
            --text-muted: #6c757d;
        }
        body { background-color: #f8f9fa; display: flex; justify-content: center; align-items: center; min-height: 100vh; text-align: center; font-family: 'Montserrat', sans-serif; padding: 20px; }
        .container-error { background-color: #ffffff; padding: 40px; border-radius: 12px; box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08); max-width: 500px; width: 100%; border-top: 5px solid var(--kamcup-primary); }
        h1 { font-size: 6rem; font-weight: 800; color: var(--kamcup-accent); margin-bottom: 10px; line-height: 1; }
        h2 { font-size: 2.2rem; color: var(--kamcup-secondary); margin-bottom: 20px; font-weight: 700; }
        p { font-size: 1.1rem; color: var(--text-dark); margin-bottom: 15px; line-height: 1.6; }
        .cta-button { display: inline-block; background-color: var(--kamcup-primary); color: var(--text-light); padding: 12px 25px; border-radius: 50px; text-decoration: none; font-weight: 600; transition: background-color 0.3s ease, transform 0.2s ease; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .cta-button:hover { background-color: var(--kamcup-secondary); transform: translateY(-2px); color: var(--text-light); }
        .footer-text { margin-top: 30px; font-size: 0.9rem; color: var(--text-muted); }
        @media (max-width: 768px) { .container-error { padding: 30px; } h1 { font-size: 4.5rem; } h2 { font-size: 1.8rem; } p { font-size: 1rem; } }
        @media (max-width: 480px) { .container-error { padding: 20px; } h1 { font-size: 3.5rem; } h2 { font-size: 1.5rem; } .cta-button { padding: 10px 20px; } }
    </style>
</head>
<body>
    <div class="container-error">
        <h1>503</h1>
        <h2>Oops! Layanan Tidak Tersedia</h2>
        <p>Server kami sedang sibuk atau dalam perbaikan saat ini. Kami sedang bekerja keras untuk segera menyelesaikannya.</p>
        <p>Mohon coba lagi dalam beberapa menit.</p>
        <a href="{{ url('/') }}" class="cta-button">Coba Muat Ulang</a>
        <p class="footer-text">Kami berkomitmen pada pertumbuhan dan semangat kompetisi yang sehat.</p>
    </div>
</body>
</html>
