<div id="chatbot-widget" style="position: fixed; bottom: 20px; right: 20px; z-index: 1000;">
    <button id="chatbot-toggle" style="
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0C2C5A, #1a4480);
        border: none;
        color: white;
        font-size: 24px;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(12, 44, 90, 0.3);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    ">
        <i class="bi bi-chat-dots-fill"></i>
    </button>

    <div id="chatbot-container" style="
        position: absolute;
        bottom: 70px;
        right: 0;
        width: 350px;
        height: 500px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        display: none;
        flex-direction: column;
        overflow: hidden;
        border: 1px solid #e1e5e9;
    ">
        <div style="
            background: linear-gradient(135deg, #0C2C5A, #1a4480);
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        ">
            <div>
                <h6 style="margin: 0; font-weight: 600;">Indiegologi Assistant</h6>
                <small style="opacity: 0.9;">Online - Siap membantu Anda</small>
            </div>
            <button id="chatbot-close" style="
                background: none;
                border: none;
                color: white;
                font-size: 18px;
                cursor: pointer;
                padding: 5px;
                opacity: 0.8;
                transition: opacity 0.3s ease;
            ">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div id="chatbot-messages" style="
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            background: #f8f9fa;
            display: flex;
            flex-direction: column;
            gap: 10px;
        ">
            <div style="
                background: white;
                padding: 12px 15px;
                border-radius: 15px 15px 15px 5px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                max-width: 85%;
                align-self: flex-start;
            ">
                <p style="margin: 0; font-size: 14px; line-height: 1.4;">
                    Halo! Selamat datang di Indiegologi. Saya siap membantu Anda.
                    Klik <a href="#" class="chatbot-command">help</a> untuk melihat perintah yang tersedia.
                </p>
            </div>
        </div>

        <div style="
            padding: 15px 20px;
            border-top: 1px solid #e1e5e9;
            background: white;
        ">
            <div style="display: flex; gap: 10px; align-items: center;">
                <input
                    type="text"
                    id="chatbot-input"
                    placeholder="Ketik pesan Anda..."
                    style="
                        flex: 1;
                        padding: 10px 15px;
                        border: 1px solid #ddd;
                        border-radius: 25px;
                        outline: none;
                        font-size: 14px;
                        transition: border-color 0.3s ease;
                    "
                >
                <button id="chatbot-send" style="
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                    background: #0C2C5A;
                    border: none;
                    color: white;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: background-color 0.3s ease;
                ">
                    <i class="bi bi-send-fill"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
#chatbot-toggle:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(12, 44, 90, 0.4);
}

#chatbot-close:hover {
    opacity: 1;
}

#chatbot-input:focus {
    border-color: #0C2C5A;
}

#chatbot-send:hover {
    background: #1a4480;
}

#chatbot-messages::-webkit-scrollbar {
    width: 6px;
}

#chatbot-messages::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

#chatbot-messages::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

#chatbot-messages::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

.message-user {
    background: #0C2C5A !important;
    color: white !important;
    border-radius: 15px 15px 5px 15px !important;
    align-self: flex-end !important;
    max-width: 85% !important;
}

.message-bot {
    background: white !important;
    border-radius: 15px 15px 15px 5px !important;
    align-self: flex-start !important;
    max-width: 85% !important;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
}

/* ADDED: Styling untuk link perintah yang bisa diklik */
.message-bot a.chatbot-command {
    color: #0C2C5A;
    font-weight: bold;
    text-decoration: underline;
    cursor: pointer;
}
.message-bot a.chatbot-command:hover {
    color: #1a4480;
}
.chatbot-command {
    color: #0C2C5A;
    font-weight: bold;
    text-decoration: underline;
    cursor: pointer;
}
.chatbot-command:hover {
    color: #1a4480;
}

.typing-indicator {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 12px 15px;
    background: white;
    border-radius: 15px 15px 15px 5px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    max-width: 85%;
    align-self: flex-start;
}

.typing-dots {
    display: flex;
    gap: 3px;
}

.typing-dots span {
    width: 6px;
    height: 6px;
    background: #999;
    border-radius: 50%;
    animation: typing 1.4s infinite ease-in-out;
}

.typing-dots span:nth-child(1) { animation-delay: -0.32s; }
.typing-dots span:nth-child(2) { animation-delay: -0.16s; }

@keyframes typing {
    0%, 80%, 100% {
        transform: scale(0.8);
        opacity: 0.5;
    }
    40% {
        transform: scale(1);
        opacity: 1;
    }
}

@media (max-width: 768px) {
    #chatbot-container {
        width: 300px !important;
        height: 450px !important;
    }

    #chatbot-widget {
        bottom: 15px !important;
        right: 15px !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatbotToggle = document.getElementById('chatbot-toggle');
    const chatbotContainer = document.getElementById('chatbot-container');
    const chatbotClose = document.getElementById('chatbot-close');
    const chatbotInput = document.getElementById('chatbot-input');
    const chatbotSend = document.getElementById('chatbot-send');
    const chatbotMessages = document.getElementById('chatbot-messages');

    // Toggle chat window
    chatbotToggle.addEventListener('click', function() {
        if (chatbotContainer.style.display === 'none' || chatbotContainer.style.display === '') {
            chatbotContainer.style.display = 'flex';
            chatbotInput.focus();
        } else {
            chatbotContainer.style.display = 'none';
        }
    });

    // Close chat window
    chatbotClose.addEventListener('click', function() {
        chatbotContainer.style.display = 'none';
    });

    // Send message on Enter key
    chatbotInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });

    // Send message on button click
    chatbotSend.addEventListener('click', sendMessage);

    chatbotMessages.addEventListener('click', function(e) {
        // Cek apakah elemen yang diklik memiliki kelas 'chatbot-command'
        if (e.target.classList.contains('chatbot-command')) {
            e.preventDefault(); // Mencegah link default
            const command = e.target.textContent;

            // Tampilkan perintah sebagai pesan pengguna
            addMessage(command, 'user');

            // Kirim perintah ke bot
            sendRequestToBot(command);
        }
    });

    function sendMessage() {
        const message = chatbotInput.value.trim();
        if (message === '') return;

        // Add user message
        addMessage(message, 'user');
        chatbotInput.value = '';

        // Send to BotMan
        sendRequestToBot(message);
    }

    // MODIFIED: Fungsi untuk mengirim request ke bot dipisahkan
    function sendRequestToBot(message) {
        // Show typing indicator
        showTypingIndicator();

        fetch('/botman', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                driver: 'web',
                userId: 'web-user-' + Date.now(),
                message: message
            })
        })
        .then(response => response.json())
        .then(data => {
            hideTypingIndicator();
            if (data.messages && data.messages.length > 0) {
                data.messages.forEach(msg => {
                    addMessage(msg.text, 'bot');
                });
            } else {
                addMessage('Maaf, terjadi kesalahan. Silakan coba lagi.', 'bot');
            }
        })
        .catch(error => {
            hideTypingIndicator();
            console.error('Error:', error);
            addMessage('Maaf, terjadi kesalahan koneksi. Silakan coba lagi.', 'bot');
        });
    }

    function addMessage(text, sender) {
        const messageDiv = document.createElement('div');
        messageDiv.style.cssText = `
            padding: 12px 15px;
            margin: 5px 0;
            border-radius: 15px;
            font-size: 14px;
            line-height: 1.4;
            white-space: pre-wrap;
            word-wrap: break-word;
        `;

        if (sender === 'user') {
            messageDiv.className = 'message-user';
        } else {
            messageDiv.className = 'message-bot';
        }

        // Format text with basic markdown, tidak akan mengganggu HTML
        const formattedText = text
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/\*(.*?)\*/g, '<em>$1</em>')
            .replace(/`(.*?)`/g, '<code style="background:#f1f1f1;padding:2px 4px;border-radius:3px;">$1</code>');

        // Menggunakan innerHTML agar tag <a> dari backend bisa dirender
        messageDiv.innerHTML = formattedText;
        chatbotMessages.appendChild(messageDiv);
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    }

    function showTypingIndicator() {
        const typingDiv = document.createElement('div');
        typingDiv.id = 'typing-indicator';
        typingDiv.className = 'typing-indicator';
        typingDiv.innerHTML = `
            <span style="font-size: 12px; color: #666;">Mengetik</span>
            <div class="typing-dots">
                <span></span>
                <span></span>
                <span></span>
            </div>
        `;
        chatbotMessages.appendChild(typingDiv);
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    }

    function hideTypingIndicator() {
        const typingIndicator = document.getElementById('typing-indicator');
        if (typingIndicator) {
            typingIndicator.remove();
        }
    }
});
</script>
