$(document).ready(function () {
    const storedName = getCookie('chatName');
    if (storedName) {
        $('#name').val(storedName);
    }

    $('#chatForm').submit(function (e) {
        e.preventDefault();
        const name = $('#name').val();
        const message = $('#message').val();
        const timestamp = new Date().toLocaleTimeString();
        $.post('app.php?route=api/CreateMessage', {name, message, timestamp});
        setCookie('chatName', name);
        $('#message').val('');
    });

    const eventSource = new EventSource('app.php?route=api/Streaming');
    eventSource.onmessage = function (event) {
        if (event.data) {
            const data = JSON.parse(event.data);
            const message = `<li class="message"><span class="sender">${data.name}:</span>${data.message}<small class="timestamp">${data.timestamp}</small></li>`;
            $('#chatMessages').append(message + '');
            scrollDown();
        }
    };

    eventSource.onerror = function (error) {
        console.error('Chyba SSE:', error);
        eventSource.close();
    };


    //helpers
    function setCookie(name, value) {
        document.cookie = `${name}=${value}; expires=Sun, 31 Dec 2030 12:00:00 UTC; path=/`;
    }

    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2)
            return parts.pop().split(';').shift();
    }

    function scrollDown() {
        window.scrollBy(0, document.body.scrollHeight);
    }
});
