(function () {
    if (window.__globalNotificationsInit) return;
    window.__globalNotificationsInit = true;

    var ICONS = {
        success: '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0"></path></svg>',
        error: '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0"></path></svg>',
        info: '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0"></path></svg>'
    };

    var STYLES = {
        success: 'border-green-200 bg-green-600 text-white',
        error: 'border-red-200 bg-red-600 text-white',
        info: 'border-blue-200 bg-blue-600 text-white'
    };

    function escapeHtml(input) {
        return String(input || '').replace(/[&<>"']/g, function (ch) {
            var map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return map[ch] || ch;
        });
    }

    function getContainer() {
        var container = document.getElementById('global-toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'global-toast-container';
            container.className = 'fixed top-4 right-4 z-[9999] w-full max-w-sm space-y-3 px-2 pointer-events-none';
            document.body.appendChild(container);
        }
        return container;
    }

    function removeToast(toast) {
        if (!toast || toast.dataset.removing === '1') return;
        toast.dataset.removing = '1';
        toast.classList.add('opacity-0', 'translate-x-6');
        setTimeout(function () {
            if (toast.parentNode) toast.parentNode.removeChild(toast);
        }, 220);
    }

    function showMessage(message, type) {
        var toastType = STYLES[type] ? type : 'info';
        var container = getContainer();
        var toast = document.createElement('div');

        toast.className = 'pointer-events-auto flex items-start gap-3 rounded-xl border px-4 py-3 shadow-lg backdrop-blur-sm transition-all duration-200 ease-out translate-x-6 opacity-0 ' + STYLES[toastType];
        toast.innerHTML =
            '<div class="mt-0.5 shrink-0">' + ICONS[toastType] + '</div>' +
            '<div class="min-w-0 flex-1 text-sm leading-relaxed break-words">' + escapeHtml(message) + '</div>' +
            '<button type="button" aria-label="Tutup notifikasi" class="shrink-0 text-white/90 hover:text-white">' +
                '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>' +
            '</button>';

        container.appendChild(toast);

        requestAnimationFrame(function () {
            toast.classList.remove('opacity-0', 'translate-x-6');
        });

        var timeoutId = setTimeout(function () {
            removeToast(toast);
        }, 4000);

        var closeButton = toast.querySelector('button');
        if (closeButton) {
            closeButton.addEventListener('click', function () {
                clearTimeout(timeoutId);
                removeToast(toast);
            });
        }
    }

    window.showMessage = showMessage;
    window.showToast = function (type, message) {
        showMessage(message, type);
    };
})();
