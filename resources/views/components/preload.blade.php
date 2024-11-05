<div>
    <div id="preloader" class="fixed inset-0 z-50 flex items-center justify-center bg-white">
        <div class="relative w-8 sm:w-12 md:w-16 h-8 sm:h-12 md:h-16">
            <div class="absolute w-full h-full border-[3px] sm:border-[4px] md:border-5 border-gray-200 rounded-full">
            </div>
            <div
                class="absolute w-full h-full border-[2px] sm:border-[3px] md:border-5 border-black rounded-full animate-spin border-t-transparent">
            </div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                <svg class="w-1 h-1 sm:w-3 sm:h-3 md:w-3 md:h-3 text-black" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="M12 4V2m0 20v-2m8-8h2M2 12h2m13.657-5.657l1.414-1.414M4.929 19.071l1.414-1.414m0-11.314L4.929 4.929m14.142 14.142l-1.414-1.414" />
                </svg>
            </div>
        </div>
    </div>
    <script>
        window.addEventListener('load', function() {
            const preloader = document.getElementById('preloader');
            setTimeout(() => {
                preloader.style.opacity = '0';
                setTimeout(() => {
                    preloader.style.display = 'none';
                }, 300);
            }, 500);
        });
    </script>
</div>
