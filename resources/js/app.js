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
    const colors = {
        success: '#48bb78', // green
        error: '#f56565',   // red
        warning: '#ed8936', // orange
        info: '#4299e1',    // blue
    };

    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.textContent = message;
    toast.style.cssText = `
        position: relative;
        background: ${colors[type] || colors.success};
        color: white;
        padding: 0.75rem 1rem;
        margin-top: 0.5rem;
        border-radius: 0.375rem;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        opacity: 0;
        transform: translateX(120%);
        transition: opacity 0.4s ease, transform 0.4s ease;
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
        background: rgba(255,255,255,0.8);
        width: 100%;
        transition: width ${duration}ms linear;
    `;
    toast.appendChild(progress);

    // Animate entrance and progress
    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translateX(0)';
            progress.style.width = '0%';
        });
    });

    // Start exit animation a bit before removal
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(120%)';
    }, duration - 400); // fade out 0.4s before total duration

    // Remove element after exit transition completes
    setTimeout(() => {
        toast.remove();
    }, duration);
};
