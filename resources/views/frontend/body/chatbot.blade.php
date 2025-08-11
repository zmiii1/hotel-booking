{{-- resources/views/frontend/body/chatbot.blade.php --}}

<!-- Simple iframe - hanya menampilkan chatbot button -->
<iframe 
    id="tanjung-lesung-chatbot-iframe"
    src="http://localhost:3000" 
    style="
        position: fixed; 
        bottom: 0;
        right: 0;
        width: 100px; 
        height: 100px; 
        border: none;
        background: transparent;
        z-index: 9999;
        pointer-events: none;
    ">
</iframe>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const iframe = document.getElementById('tanjung-lesung-chatbot-iframe');
    
    // Make iframe clickable
    iframe.style.pointerEvents = 'all';
    
    // Expand iframe when chatbot opens
    window.addEventListener('message', function(event) {
        if (event.data.type === 'chatbot_opened') {
            iframe.style.width = '400px';
            iframe.style.height = '650px';
        } else if (event.data.type === 'chatbot_closed') {
            iframe.style.width = '100px';
            iframe.style.height = '100px';
        }
    });
});
</script>