<div id="botman-widget"></div>

<script>
    var botmanWidget = {
        frameEndpoint: '{{ url('/botman/chat') }}',
        introMessage: 'Halo! Saya Mindie. Ada yang bisa saya bantu?',
        title: 'Tanya Mindie!',
        mainColor: '#0C2C5A',
        bubbleBackground: '#0C2C5A',
        aboutText: '',
        userId: '{{ md5(session()->getId()) }}',
        chatServer: '/botman'
    };
</script>
<script src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script>