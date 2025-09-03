<style>
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

<div id="chatbot-container">
    <div class="chatbot-fab" id="chatbot-fab">
        <i class="fas fa-comment-dots"></i>
    </div>
    <div class="chatbot-window" id="chatbot-window">
        <div class="chatbot-header">
            <span>Tanya Mindie!</span>
            <span class="chatbot-close-btn" id="chatbot-close-btn">&times;</span>
        </div>
        <div class="chatbot-messages" id="chatbot-messages">
            </div>
        <div class="chatbot-input">
            <form id="chatbot-form">
                <input type="text" class="form-control" id="chatbot-input-field" placeholder="Ketik pesan Anda..." autocomplete="off">
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fab = document.getElementById('chatbot-fab');
    const window = document.getElementById('chatbot-window');
    const closeBtn = document.getElementById('chatbot-close-btn');
    const form = document.getElementById('chatbot-form');
    const inputField = document.getElementById('chatbot-input-field');
    const messagesContainer = document.getElementById('chatbot-messages');

    fab.addEventListener('click', () => window.classList.toggle('open'));
    closeBtn.addEventListener('click', () => window.classList.remove('open'));

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const messageText = inputField.value.trim();
        if (messageText === '') return;

        appendMessage(messageText, 'user');
        inputField.value = '';

        fetch("{{ route('chatbot.sendMessage') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message: messageText })
        })
        .then(response => response.json())
        .then(data => {
            if (data.reply) {
                appendMessage(data.reply, 'bot');
            } else {
                appendMessage('Maaf, terjadi kesalahan.', 'bot');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            appendMessage('Tidak dapat terhubung ke server.', 'bot');
        });
    });

    function appendMessage(text, type) {
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message');
        
        const contentDiv = document.createElement('div');
        contentDiv.classList.add('message-content');
        contentDiv.classList.add(type === 'user' ? 'user-message' : 'bot-message');
        contentDiv.textContent = text;
        
        messageDiv.appendChild(contentDiv);
        messagesContainer.appendChild(messageDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Pesan sapaan pertama dari bot
    appendMessage('Halo! Saya Mindie. Ada yang bisa saya bantu?', 'bot');
});
</script>