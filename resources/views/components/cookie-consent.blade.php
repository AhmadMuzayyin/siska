<div
    id="cookie-consent-banner"
    class="fixed inset-x-0 bottom-0 z-50 hidden"
    style="display: none;"
>
    <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
        <div class="flex flex-col items-start justify-between gap-4 rounded-2xl border border-zinc-200 bg-white p-5 shadow-xl dark:border-zinc-700 dark:bg-zinc-800 sm:flex-row sm:items-center">
            <div class="flex items-start gap-3">
                <span class="text-2xl" aria-hidden="true">🍪</span>
                <p class="text-sm text-zinc-600 dark:text-zinc-300">
                    {{ __('Kami menggunakan cookie untuk meningkatkan pengalaman Anda. Dengan melanjutkan, Anda menyetujui') }}
                    <a href="{{ route('cookies') }}" wire:navigate class="font-medium text-emerald-600 underline underline-offset-2 hover:text-emerald-700">{{ __('kebijakan cookie') }}</a>
                    {{ __('kami.') }}
                </p>
            </div>
            <div class="flex shrink-0 gap-2">
                <button
                    id="cookie-consent-reject"
                    type="button"
                    class="rounded-lg border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-600 hover:bg-zinc-50 dark:border-zinc-600 dark:text-zinc-300 dark:hover:bg-zinc-700"
                >
                    {{ __('Tolak') }}
                </button>
                <button
                    id="cookie-consent-accept"
                    type="button"
                    class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700"
                >
                    {{ __('Saya Setuju') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    var COOKIE_KEY = 'cookie_consent';
    var banner = document.getElementById('cookie-consent-banner');

    function getCookie(name) {
        return document.cookie.split('; ').find(function(row){ return row.startsWith(name + '='); });
    }

    function setCookie(name, value, days) {
        var expires = new Date(Date.now() + days * 864e5).toUTCString();
        document.cookie = name + '=' + value + '; expires=' + expires + '; path=/; SameSite=Lax';
    }

    if (!getCookie(COOKIE_KEY)) {
        banner.style.display = 'block';
    }

    document.getElementById('cookie-consent-accept').addEventListener('click', function () {
        setCookie(COOKIE_KEY, 'accepted', 365);
        banner.style.display = 'none';
    });

    document.getElementById('cookie-consent-reject').addEventListener('click', function () {
        setCookie(COOKIE_KEY, 'rejected', 30);
        banner.style.display = 'none';
    });
})();
</script>
