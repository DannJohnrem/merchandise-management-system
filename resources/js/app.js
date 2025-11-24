// resources/js/app.js


// =====================
// Custom toast function
// =====================
function getToastContainer() {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.style.position = 'fixed';
        container.style.top = '1rem';
        container.style.right = '1rem';
        container.style.display = 'flex';
        container.style.flexDirection = 'column';
        container.style.gap = '0.5rem';
        container.style.zIndex = 9999;
        document.body.appendChild(container);
    }
    return container;
}

window.toast = (message, type = 'success', duration = 4000) => {
    const colors = {
        success: '#48bb78',
        error: '#f56565',
        warning: '#ed8936',
        info: '#4299e1',
    };

    const container = getToastContainer();

    const toastEl = document.createElement('div');
    toastEl.textContent = message;
    toastEl.style.cssText = `
        position: relative;
        background: ${colors[type] || colors.success};
        color: white;
        padding: 0.75rem 1rem;
        border-radius: 0.375rem;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        opacity: 0;
        transform: translateX(120%);
        transition: opacity 0.4s ease, transform 0.4s ease;
        overflow: hidden;
        min-width: 220px;
    `;

    const progress = document.createElement('div');
    progress.style.cssText = `
        position: absolute;
        bottom: 0;
        left: 0;
        height: 4px;
        background: rgba(255,255,255,0.8);
        width: 100%;
        transition: width ${duration}ms linear;
    `;
    toastEl.appendChild(progress);

    container.appendChild(toastEl);

    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            toastEl.style.opacity = '1';
            toastEl.style.transform = 'translateX(0)';
            progress.style.width = '0%';
        });
    });

    setTimeout(() => {
        toastEl.style.opacity = '0';
        toastEl.style.transform = 'translateX(120%)';
    }, duration - 400);

    setTimeout(() => toastEl.remove(), duration);
};
