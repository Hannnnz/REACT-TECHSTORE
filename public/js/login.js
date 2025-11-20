document.addEventListener('DOMContentLoaded', () => {

    const showLoginBtn = document.getElementById('show-login-btn');
    const showRegisterBtn = document.getElementById('show-register-btn');
    const modalWrapper = document.getElementById('modal-wrapper');
    const closeBtn = document.getElementById('close-btn');
    
    const signUpButton = document.getElementById('signUp');
    const signInButton = document.getElementById('signIn');
    const container = document.getElementById('container');

    
    showLoginBtn.addEventListener('click', () => {
        modalWrapper.classList.remove('hidden');
        container.classList.remove('right-panel-active'); 
    });

    showRegisterBtn.addEventListener('click', () => {
        modalWrapper.classList.remove('hidden');
        container.classList.add('right-panel-active'); 
    });

    closeBtn.addEventListener('click', () => {
        modalWrapper.classList.add('hidden');
    });

    modalWrapper.addEventListener('click', (e) => {
        if (e.target === modalWrapper) {
            modalWrapper.classList.add('hidden');
        }
    });

    
    signUpButton.addEventListener('click', () => {
        container.classList.add('right-panel-active');
    });

    signInButton.addEventListener('click', () => {
        container.classList.remove('right-panel-active');
    });
});