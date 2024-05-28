class ToastManager {
    constructor(containerId) {
        this.container = document.getElementById(containerId);
        if (!this.container) {
            throw new Error(`Container with id ${containerId} not found`);
        }
    }

    show(message, title = '', type = 'info') {
        const toastId = `toast-${Date.now()}`;
        const toastHtml = `
            <div id="${toastId}" class="toast text-bg-secondary" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
                <div class="toast-header text-bg-${type}">
                    <strong class="me-auto">${title}</strong>
                    <button type="button" class="btn-close btn-close-black" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            </div>
        `;

        this.container.insertAdjacentHTML('beforeend', toastHtml);
        const toastElement = document.getElementById(toastId);
        const bootstrapToast = new bootstrap.Toast(toastElement);
        bootstrapToast.show();

        // Optional: Entfernen des Toasts aus dem DOM nach dem Ausblenden
        toastElement.addEventListener('hidden.bs.toast', () => {
            toastElement.remove();
        });
    }
}
