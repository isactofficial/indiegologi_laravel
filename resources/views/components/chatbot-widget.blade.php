<style>
    /* ... Keep all your existing CSS styles here. They are fine. ... */
    :root { --chatbot-primary: #0C2C5A; --chatbot-accent: #F4B704; }
    .chatbot-fab {
        position: fixed;
        bottom: 25px;
        right: 25px;
        width: 60px;
        height: 60px;
        background-color: var(--chatbot-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
        z-index: 999;
    }
    .chatbot-fab:hover { transform: scale(1.1); }
    .chatbot-fab i { color: white; font-size: 24px; }
    .chatbot-window {
        position: fixed;
        bottom: 100px;
        right: 25px;
        width: 350px;
        max-width: 90vw;
        height: 500px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transform: scale(0.5);
        opacity: 0;
        pointer-events: none;
        transform-origin: bottom right;
        transition: all 0.3s ease-out;
        z-index: 1000;
    }
    .chatbot-window.open {
        transform: scale(1);
        opacity: 1;
        pointer-events: auto;
    }
    .chatbot-header {
        background: var(--chatbot-primary);
        color: white;
        padding: 1rem;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .chatbot-close-btn { cursor: pointer; opacity: 0.8; }
    .chatbot-close-btn:hover { opacity: 1; }
    .chatbot-messages {
        flex-grow: 1;
        padding: 1rem;
        overflow-y: auto;
    }
    .message {
        margin-bottom: 1rem;
        display: flex;
        max-width: 85%;
    }
    .message-content {
        padding: 0.75rem 1rem;
        border-radius: 12px;
        font-size: 0.9rem;
    }
    .user-message {
        background-color: #f1f3f5;
        color: #333;
        margin-left: auto;
        border-bottom-right-radius: 0;
    }
    .bot-message {
        background-color: var(--chatbot-primary);
        color: white;
        border-bottom-left-radius: 0;
    }
    .chatbot-input {
        border-top: 1px solid #eee;
        padding: 0.75rem;
    }
    .chatbot-input input {
        border-radius: 20px;
        border: 1px solid #ccc;
    }
</style>

<div id="botman-widget"></div>

<script>
    var botmanWidget = {
        frameEndpoint: '{{ url('/botman/chat') }}', // This URL is not needed for the web driver
        introMessage: 'Halo! Saya Mindie. Ada yang bisa saya bantu?', // Pesan sapaan pertama
        placeholderText: 'Ketik pesan Anda...',
        title: 'Tanya Mindie!',
        mainColor: '#0C2C5A', // Sesuaikan dengan warna primer Anda
        bubbleBackground: '#0C2C5A',
        desktopHeight: 500,
        desktopWidth: 350,
        aboutText: '',
        userId: '{{ md5(session()->getId()) }}',
        chatServer: '/botman', // Ini adalah rute yang akan menangani permintaan BotMan
    };
</script>

<script src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script>
