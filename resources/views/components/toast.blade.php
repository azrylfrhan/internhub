<div id="toast-container" class="fixed top-4 right-4 z-[9999] space-y-3 max-w-sm w-full pointer-events-none"></div>

<script>
(function() {
    if (window.__toastInit) return;
    window.__toastInit = true;

    const icons = {
        success: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
        error: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
        warning: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4v2M7.08 6.47a7 7 0 1 1 9.84 0"></path></svg>',
        info: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
    };

    const styles = {
        success: 'bg-emerald-600/95 border-emerald-400/70 text-white shadow-emerald-500/30',
        error: 'bg-red-600/95 border-red-400/70 text-white shadow-red-500/30',
        warning: 'bg-amber-500/95 border-amber-300/80 text-white shadow-amber-400/30',
        info: 'bg-blue-600/95 border-blue-400/70 text-white shadow-blue-500/30'
    };

    function getContainer() {
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'fixed top-4 right-4 z-[9999] space-y-3 max-w-sm w-full pointer-events-none';
            document.body.appendChild(container);
        }
        return container;
    }

    window.showToast = function(type, message, options = {}) {
        const toastType = styles[type] ? type : 'info';
        const duration = options.duration || 3500;
        const container = getContainer();
        const toast = document.createElement('div');

        toast.className = `pointer-events-auto flex gap-3 items-start border rounded-xl px-4 py-3 shadow-lg backdrop-blur ${styles[toastType]} dark:bg-opacity-95 bg-opacity-95`;
        toast.innerHTML = `
            <div class="flex-shrink-0 mt-0.5">${icons[toastType] || icons.info}</div>
            <div class="flex-1 text-sm leading-relaxed">${escapeHtml(message || '')}</div>
            <button type="button" aria-label="Tutup" class="flex-shrink-0 text-white/80 hover:text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        `;

        const removeToast = () => {
            if (toast.dataset.removed) return;
            toast.dataset.removed = 'true';
            toast.classList.add('animate-fade-out');
            setTimeout(() => toast.remove(), 150);
        };

        toast.querySelector('button').addEventListener('click', removeToast);
        container.appendChild(toast);

        const timer = setTimeout(removeToast, duration);
        toast.addEventListener('mouseenter', () => clearTimeout(timer));
        toast.addEventListener('mouseleave', () => setTimeout(removeToast, duration));
    };

    function escapeHtml(unsafe) {
        return unsafe.replace(/[&<>"']/g, function(match) {
            const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
            return map[match];
        });
    }
})();
</script>

<style>
@keyframes fadeOutToast {
    from { opacity: 1; transform: translateY(0); }
    to { opacity: 0; transform: translateY(-10px); }
}
.animate-fade-out {
    animation: fadeOutToast 0.15s ease forwards;
}
</style>

@if (session('status'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showToast('success', "{{ addslashes(session('status')) }}");
        });
    </script>
@endif

@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showToast('error', "{{ addslashes($errors->first()) }}");
        });
    </script>
@endif
