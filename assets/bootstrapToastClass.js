class ToastManager {
    constructor(containerId) {
        this.container = document.getElementById(containerId);
        if (!this.container) {
            throw new Error(`Container with id ${containerId} not found`);
        }
    }

    show(message, title = '', type = 'info', delay = 5000) {
        const toastId = `toast-${Date.now()}`;
        const toastHtml = `
            <div id="${toastId}" class="toast text-bg-${type}" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="${delay}">
                <div class="toast-header">
                    <strong class="me-auto">${title}</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
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
