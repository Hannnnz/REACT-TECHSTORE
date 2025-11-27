document.addEventListener('DOMContentLoaded', () => {

    const showLoginBtn = document.getElementById('show-login-btn');
    const modalWrapper = document.getElementById('modal-wrapper');
    const closeBtn = document.getElementById('close-btn');

    // Open the login modal
    showLoginBtn.addEventListener('click', () => {
        modalWrapper.classList.remove('hidden');
    });

    // Close modal when clicking the X button
    closeBtn.addEventListener('click', () => {
        modalWrapper.classList.add('hidden');
    });

    // Close modal when clicking outside the modal content
    modalWrapper.addEventListener('click', (e) => {
        if (e.target === modalWrapper) {
            modalWrapper.classList.add('hidden');
        }
    });
});
