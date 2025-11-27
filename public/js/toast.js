document.addEventListener("DOMContentLoaded", () => {
    const notifSound = document.getElementById('notifSound');
    const toastContainer = document.getElementById('toast-container');

    if (!toastContainer) return; 

    function playSound() {
        if (!notifSound) return;
        notifSound.currentTime = 0;
        notifSound.play().catch(() => {});
    }

    window.showToast = function(message, type = 'success') {
        playSound();

        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;

        const icon =
            type === 'success' ? 'fa-check-circle' :
            type === 'error'   ? 'fa-times-circle' :
                                 'fa-info-circle';

        toast.innerHTML = `
            <div style="display:flex; align-items:center;">
                <i class="fas ${icon}" style="margin-right: 10px;"></i>
                <span>${message}</span>
            </div>
            <button class="close-toast">&times;</button>
        `;

        // Close button logic
        toast.querySelector('.close-toast').addEventListener('click', () => {
            toast.style.animation = "fadeOut 0.4s forwards";
            setTimeout(() => toast.remove(), 400);
        });

        // Auto remove
        setTimeout(() => {
            toast.style.animation = "fadeOut 0.4s forwards";
            setTimeout(() => toast.remove(), 400);
        }, 4000);

        toastContainer.appendChild(toast);
    };
});
