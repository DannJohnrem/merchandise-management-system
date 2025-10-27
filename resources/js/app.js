if (!document.getElementById('toast-container')) {
    const container = document.createElement('div');
    container.id = 'toast-container';
    container.style.position = 'fixed';
    container.style.top = '1rem';
    container.style.right = '1rem';
    container.style.zIndex = 9999;
    document.body.appendChild(container);
}

window.toast = (message, type = 'success', duration = 4000) => {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.textContent = message;
    toast.style.cssText = `
        position: relative;
        background: ${type === 'error' ? '#f56565' : '#48bb78'};
        color: white;
        padding: 0.75rem 1rem;
        margin-top: 0.5rem;
        border-radius: 0.375rem;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.3s ease;
        overflow: hidden;
        min-width: 220px;
    `;
    container.appendChild(toast);

    // Add progress bar
    const progress = document.createElement('div');
    progress.style.cssText = `
        position: absolute;
        bottom: 0;
        left: 0;
        height: 4px;
        background: rgba(255,255,255,0.7);
        width: 100%;
        transition: width ${duration}ms linear;
    `;
    toast.appendChild(progress);

    // Animate toast in
    requestAnimationFrame(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translateX(0)';
        progress.style.width = '0%';
    });

    // Remove toast after duration
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';
        toast.addEventListener('transitionend', () => toast.remove());
    }, duration);
};
