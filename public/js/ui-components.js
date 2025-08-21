let messageTimeout;
let loaderTimeout;


function showMessage(type, message) {
    const messagePopup = document.getElementById('messagePopup');
    const messageText = document.getElementById('messageText');

    clearTimeout(messageTimeout);

    messagePopup.classList.remove('show', 'hide', 'success', 'error', 'normal');

    switch (type) {
        case 'success':
            messagePopup.classList.add('success');
            break;
        case 'error':
            messagePopup.classList.add('error');
            break;
        case 'normal':
        default:
            messagePopup.classList.add('normal');
            break;
    }

    messageText.textContent = message;
    messagePopup.classList.add('show');
    messageTimeout = setTimeout(() => {
        hideMessagePopup();
    }, 3000);
}

function hideMessagePopup() {
    const messagePopup = document.getElementById('messagePopup');

    messagePopup.classList.remove('show');
    messagePopup.classList.add('hide');

    messagePopup.addEventListener('animationend', function handler() {
        messagePopup.classList.remove('hide');
        this.removeEventListener('animationend', handler);
    }, { once: true });
}

function showSuccess(message) {
    showMessage('success', message);
}

function showError(message) {
    showMessage('error', message);
}

function showLoader() {
    const loaderOverlay = document.getElementById('loaderOverlay');
    clearTimeout(loaderTimeout);
    loaderOverlay.classList.add('show');
}

function hideLoader() {
    const loaderOverlay = document.getElementById('loaderOverlay');
    loaderOverlay.classList.remove('show');
}