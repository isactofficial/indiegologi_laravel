<!doctype html>
<html>
<head>
<title>BotMan Widget</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/botman-web-widget/build/assets/css/chat.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Custom CSS untuk membuat pola lebih transparan -->
    <style>
        /* Override pola topografi agar lebih transparan */
        body {
            background-color: white !important;
        }
        
        /* Jika ada background image, buat lebih transparan */
        body::before,
        .chat-container::before,
        .message-area::before {
            opacity: 0.03 !important; /* Sangat transparan */
        }
        
        /* Override semua elemen yang mungkin memiliki background pattern */
        * {
            background-image: none !important;
        }
        
        /* Jika tetap ingin ada pola tapi sangat samar */
        .topographic-pattern,
        [class*="topographic"],
        [class*="pattern"] {
            opacity: 0.05 !important;
            filter: contrast(0.1) !important;
        }
        
        /* Pastikan area chat putih bersih */
        .chat-window,
        .message-container,
        .chat-messages {
            background-color: rgba(255, 255, 255, 0.98) !important;
        }
        
        /* Override inline styles yang mungkin ada */
        [style*="background-image"] {
            background-image: none !important;
            background: white !important;
        }
    </style>
</head>
<body>
<script id="botmanWidget" src='https://cdn.jsdelivr.net/npm/botman-web-widget/build/js/chat.js'></script>

<!-- Script tambahan untuk override pola setelah widget dimuat -->
<script>
    // Override background patterns setelah widget dimuat
    document.addEventListener('DOMContentLoaded', function() {
        // Tunggu widget selesai dimuat
        setTimeout(function() {
            // Cari dan override semua elemen dengan background pattern
            const allElements = document.querySelectorAll('*');
            allElements.forEach(function(element) {
                const computedStyle = window.getComputedStyle(element);
                
                // Jika ada background image, buat sangat transparan atau hilangkan
                if (computedStyle.backgroundImage && computedStyle.backgroundImage !== 'none') {
                    element.style.backgroundImage = 'none';
                    element.style.backgroundColor = 'rgba(255, 255, 255, 0.98)';
                }
                
                // Override opacity untuk pola yang masih muncul
                if (element.className && (
                    element.className.includes('pattern') || 
                    element.className.includes('topographic') ||
                    element.className.includes('background')
                )) {
                    element.style.opacity = '0.02';
                }
            });
        }, 1500); // Tunggu 1.5 detik
        
        // Observer untuk mendeteksi perubahan DOM
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList') {
                    mutation.addedNodes.forEach(function(node) {
                        if (node.nodeType === 1) { // Element node
                            const style = window.getComputedStyle(node);
                            if (style.backgroundImage && style.backgroundImage !== 'none') {
                                node.style.backgroundImage = 'none';
                                node.style.backgroundColor = 'white';
                            }
                        }
                    });
                }
            });
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    });
</script>
</body>
</html>