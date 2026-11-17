@if (getSetting('preloader', 'enabled') === 'enabled')
    <script>
        (function() {
            if (sessionStorage.getItem('dashboard_loaded')) {
                document.documentElement.classList.add('no-preloader');
            }
        })();
    </script>
    <style>
        .no-preloader #dashboard-preloader {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            pointer-events: none !important;
        }
    </style>

    <div id="dashboard-preloader"
        class="fixed inset-0 z-[99999] bg-[#0a0a0c] flex items-center justify-center overflow-hidden transition-opacity duration-200 ease-out pointer-events-auto">
        <!-- Background Gradient Orbs -->
        <div
            class="absolute top-[-10%] left-[-10%] w-[50vh] h-[50vh] bg-accent-primary/10 rounded-full blur-[120px] animate-blob">
        </div>
        <div
            class="absolute bottom-[-10%] right-[-10%] w-[50vh] h-[50vh] bg-purple-500/10 rounded-full blur-[120px] animate-blob animation-delay-2000">
        </div>

        <div class="relative flex flex-col items-center">
            <!-- Loader Animation -->
            <div class="relative w-24 h-24 mb-8">
                {{-- Outer Ring --}}
                <div class="absolute inset-0 border-[2px] border-white/5 rounded-full"></div>
                <div
                    class="absolute inset-0 border-[2px] border-transparent border-t-accent-primary border-r-accent-primary/50 rounded-full animate-spin-slow shadow-[0_0_20px_rgba(var(--color-accent-primary),0.2)]">
                </div>

                {{-- Inner Ring --}}
                <div class="absolute inset-4 border-[2px] border-white/5 rounded-full"></div>
                <div
                    class="absolute inset-4 border-[2px] border-transparent border-b-purple-500 border-l-purple-500/50 rounded-full animate-spin-reverse shadow-[0_0_20px_rgba(168,85,247,0.2)]">
                </div>

                {{-- Center Core --}}
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-1.5 h-1.5 bg-white rounded-full animate-ping"></div>
                </div>
            </div>

            <!-- Text & Progress -->
            <div class="flex flex-col items-center space-y-3 z-10">
                <h2 class="text-xl font-bold text-white tracking-[0.2em] uppercase">
                    {{ getSetting('name') }}
                </h2>

                <div class="flex items-center gap-2">
                    <div class="h-[1.5px] w-8 bg-gradient-to-r from-transparent to-accent-primary animate-pulse"></div>
                    <p class="text-text-secondary text-[9px] uppercase tracking-[0.25em] font-medium">
                        {{ __('Loading') }}
                    </p>
                    <div class="h-[1.5px] w-8 bg-gradient-to-l from-transparent to-accent-primary animate-pulse"></div>
                </div>
            </div>
        </div>
    </div>


    {{-- floating buy/purchase button  --}}


    <style>
        @keyframes spin-slow {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes spin-reverse {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(-360deg);
            }
        }

        .animate-spin-slow {
            animation: spin-slow 3s linear infinite;
        }

        .animate-spin-reverse {
            animation: spin-reverse 2s cubic-bezier(0.4, 0, 0.2, 1) infinite;
        }

        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }

            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }

        .animate-blob {
            animation: blob 10s infinite;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }
    </style>

    <script>
        (function() {
            const preloader = document.getElementById('dashboard-preloader');
            if (!preloader) return;

            // If already loaded in this session, hide immediately without any flash
            if (sessionStorage.getItem('dashboard_loaded')) {
                preloader.style.display = 'none';
                return;
            }

            function dismiss() {
                if (!preloader || preloader.style.display === 'none') return;
                preloader.style.opacity = '0';
                preloader.style.pointerEvents = 'none';
                setTimeout(() => {
                    preloader.style.display = 'none';
                    try { sessionStorage.setItem('dashboard_loaded', '1'); } catch(e) {}
                }, 200);
            }

            // Dismiss as soon as DOM is ready or on load (zero artificial delay)
            if (document.readyState === 'interactive' || document.readyState === 'complete') {
                dismiss();
            } else {
                document.addEventListener('DOMContentLoaded', dismiss, { once: true });
                window.addEventListener('load', dismiss, { once: true });
            }

            // Fallback safety (max 1.2s instead of 5s)
            setTimeout(dismiss, 1200);

            // Handle Back/Forward Cache
            window.addEventListener('pageshow', function(event) {
                if (event.persisted) {
                    dismiss();
                }
            });
        })();
    </script>

    {{-- Floating Buy/Purchase Button --}}
    @if (config('app.env') === 'sandbox')
        <a href="https://lozand.com" target="_blank"
            class="fixed top-2/3 md:top-1/2 right-0 md:right-8 -translate-y-1/2 z-[9999] group flex items-center justify-center p-0.5 rounded-2xl bg-gradient-to-br from-accent-primary via-purple-500 to-accent-secondary shadow-[0_0_20px_rgba(var(--color-accent-primary),0.3)] hover:shadow-[0_0_30px_rgba(var(--color-accent-primary),0.5)] transition-all duration-500 hover:scale-105 active:scale-95 animate-float-attention">
            <div
                class="relative flex items-center gap-3 px-6 py-3 rounded-[14px] bg-[#0a0a0c]/80 backdrop-blur-xl transition-colors group-hover:bg-[#0a0a0c]/40 overflow-hidden">
                {{-- Animated Background Glow --}}
                <div
                    class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:animate-shimmer">
                </div>

                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="8" cy="21" r="1" />
                    <circle cx="19" cy="21" r="1" />
                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                </svg>
                <span
                    class="text-sm font-bold text-white tracking-wide uppercase hidden md:flex">{{ __('Purchase Now') }}</span>

                {{-- Pulse Rings --}}
                <div
                    class="absolute inset-0 rounded-[14px] ring-1 ring-white/20 group-hover:ring-white/40 transition-all">
                </div>
            </div>
        </a>
    @endif

    <style>
        @keyframes float-attention {

            0%,
            100% {
                transform: translateY(-50%) translateX(0);
            }

            25% {
                transform: translateY(calc(-50% - 8px)) translateX(2px);
            }

            50% {
                transform: translateY(-50%) translateX(-2px);
            }

            75% {
                transform: translateY(calc(-50% + 4px)) translateX(1px);
            }
        }

        @keyframes shimmer {
            100% {
                transform: translateX(100%);
            }
        }

        .animate-float-attention {
            animation: float-attention 4s ease-in-out infinite;
        }

        .group-hover\:animate-shimmer:hover .animate-shimmer,
        .group:hover .group-hover\:animate-shimmer {
            animation: shimmer 1.5s infinite;
        }

        .group:hover [class*="group-hover:animate-shimmer"] {
            animation: shimmer 1.5s infinite;
        }
    </style>
@endif
