@extends('layouts.store')

@section('title', 'Loja Virtual - LaserLink')

@section('content')
<div x-data="{ mobileMenuOpen: false }">
    <!-- Hero Section / Banners da Home -->
    @php
        // Carregar banners desktop
        $homeBannersJson = \App\Models\Setting::get('home_banners', '[]');
        $homeBanners = json_decode($homeBannersJson, true);
        
        // Suporte para banner único antigo
        if (empty($homeBanners)) {
            $oldBanner = \App\Models\Setting::get('home_banner_image');
            if ($oldBanner) {
                $homeBanners = [$oldBanner];
            }
        }

        // Carregar banners mobile
        $homeBannersMobileJson = \App\Models\Setting::get('home_banners_mobile', '[]');
        $homeBannersMobile = json_decode($homeBannersMobileJson, true);
        
        // Se não houver banners mobile, usar os banners desktop como fallback
        if (empty($homeBannersMobile) && !empty($homeBanners)) {
            $homeBannersMobile = $homeBanners;
        }
    @endphp
    
    @if(!empty($homeBanners) || !empty($homeBannersMobile))
        <!-- Banner Desktop (Horizontal) - Oculto em mobile -->
        @if(!empty($homeBanners))
        <section class="relative z-0 hidden md:block" x-data="{ 
            currentSlide: 0, 
            totalSlides: {{ count($homeBanners) }},
            autoplayInterval: null,
            isTransitioning: false,
            init() {
                @if(count($homeBanners) > 1)
                    this.startAutoplay();
                @endif
            },
            startAutoplay() {
                if (this.autoplayInterval) {
                    clearInterval(this.autoplayInterval);
                }
                this.autoplayInterval = setInterval(() => {
                    if (!this.isTransitioning) {
                        this.nextSlide();
                    }
                }, 5000);
            },
            stopAutoplay() {
                if (this.autoplayInterval) {
                    clearInterval(this.autoplayInterval);
                    this.autoplayInterval = null;
                }
            },
            nextSlide() {
                if (this.isTransitioning) return;
                this.isTransitioning = true;
                this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
                setTimeout(() => {
                    this.isTransitioning = false;
                }, 800);
            },
            prevSlide() {
                if (this.isTransitioning) return;
                this.isTransitioning = true;
                this.currentSlide = this.currentSlide === 0 ? this.totalSlides - 1 : this.currentSlide - 1;
                setTimeout(() => {
                    this.isTransitioning = false;
                }, 800);
            },
            goToSlide(index) {
                if (this.isTransitioning || this.currentSlide === index) return;
                this.isTransitioning = true;
                this.currentSlide = index;
                setTimeout(() => {
                    this.isTransitioning = false;
                }, 800);
            }
        }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="relative overflow-hidden rounded-2xl shadow-xl h-96"
                     @mouseenter="stopAutoplay()" 
                     @mouseleave="startAutoplay()">
                    <!-- Slides Desktop -->
                    @foreach($homeBanners as $index => $banner)
                        @php
                            // Gerar URL correta do banner
                            if (filter_var($banner, FILTER_VALIDATE_URL)) {
                                // Se já é uma URL completa, usar diretamente
                                $bannerUrl = $banner;
                            } elseif (file_exists(public_path('images/' . $banner))) {
                                // Se está em public/images
                                $bannerUrl = asset('images/' . $banner);
                            } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($banner)) {
                                // Se está em storage/app/public
                                $bannerUrl = \Illuminate\Support\Facades\Storage::url($banner);
                            } else {
                                // Fallback: tentar asset
                                $bannerUrl = asset($banner);
                            }
                        @endphp
                        <div x-show="currentSlide === {{ $index }}" 
                             x-transition:enter="transition ease-out duration-700"
                             x-transition:enter-start="opacity-0 scale-105"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-300"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute inset-0 w-full h-full">
                            <img src="{{ $bannerUrl }}" alt="Banner promocional Laser Link - Comunicação Visual {{ $index + 1 }}" class="w-full h-full object-cover rounded-2xl">
                        </div>
                    @endforeach
                    
                    <!-- Setas de Navegação -->
                    @if(count($homeBanners) > 1)
                        <button @click="prevSlide(); stopAutoplay()"
                                class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white p-3 rounded-full shadow-lg transition">
                            <i class="bi bi-chevron-left text-gray-900 text-2xl"></i>
                        </button>
                        <button @click="nextSlide(); stopAutoplay()"
                                class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white p-3 rounded-full shadow-lg transition">
                            <i class="bi bi-chevron-right text-gray-900 text-2xl"></i>
                        </button>
                        
                        <!-- Indicadores -->
                        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2">
                            @foreach($homeBanners as $index => $banner)
                                <button @click="goToSlide({{ $index }}); stopAutoplay()"
                                        class="h-2 rounded-full transition-all duration-300"
                                        :class="currentSlide === {{ $index }} ? 'bg-white w-8' : 'bg-white/50 w-2 hover:bg-white/80'">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </section>
        @endif

        <!-- Banner Mobile (Vertical) - Visível apenas em mobile -->
        @if(!empty($homeBannersMobile))
        <section class="relative z-0 md:hidden" x-data="{ 
            currentSlideMobile: 0, 
            totalSlidesMobile: {{ count($homeBannersMobile) }},
            autoplayIntervalMobile: null,
            isTransitioningMobile: false,
            init() {
                @if(count($homeBannersMobile) > 1)
                    this.startAutoplayMobile();
                @endif
            },
            startAutoplayMobile() {
                if (this.autoplayIntervalMobile) {
                    clearInterval(this.autoplayIntervalMobile);
                }
                this.autoplayIntervalMobile = setInterval(() => {
                    if (!this.isTransitioningMobile) {
                        this.nextSlideMobile();
                    }
                }, 5000);
            },
            stopAutoplayMobile() {
                if (this.autoplayIntervalMobile) {
                    clearInterval(this.autoplayIntervalMobile);
                    this.autoplayIntervalMobile = null;
                }
            },
            nextSlideMobile() {
                if (this.isTransitioningMobile) return;
                this.isTransitioningMobile = true;
                this.currentSlideMobile = (this.currentSlideMobile + 1) % this.totalSlidesMobile;
                setTimeout(() => {
                    this.isTransitioningMobile = false;
                }, 800);
            },
            prevSlideMobile() {
                if (this.isTransitioningMobile) return;
                this.isTransitioningMobile = true;
                this.currentSlideMobile = this.currentSlideMobile === 0 ? this.totalSlidesMobile - 1 : this.currentSlideMobile - 1;
                setTimeout(() => {
                    this.isTransitioningMobile = false;
                }, 800);
            },
            goToSlideMobile(index) {
                if (this.isTransitioningMobile || this.currentSlideMobile === index) return;
                this.isTransitioningMobile = true;
                this.currentSlideMobile = index;
                setTimeout(() => {
                    this.isTransitioningMobile = false;
                }, 800);
            }
        }">
            <div class="max-w-7xl mx-auto px-4 py-4">
                <div class="relative overflow-hidden rounded-2xl shadow-xl"
                     style="height: 400px;"
                     @touchstart="stopAutoplayMobile()">
                    <!-- Slides Mobile -->
                    @foreach($homeBannersMobile as $index => $banner)
                        @php
                            // Gerar URL correta do banner mobile
                            if (filter_var($banner, FILTER_VALIDATE_URL)) {
                                // Se já é uma URL completa, usar diretamente
                                $bannerUrl = $banner;
                            } elseif (file_exists(public_path('images/' . $banner))) {
                                // Se está em public/images
                                $bannerUrl = asset('images/' . $banner);
                            } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($banner)) {
                                // Se está em storage/app/public
                                $bannerUrl = \Illuminate\Support\Facades\Storage::url($banner);
                            } else {
                                // Fallback: tentar asset
                                $bannerUrl = asset($banner);
                            }
                        @endphp
                        <div x-show="currentSlideMobile === {{ $index }}" 
                             x-transition:enter="transition ease-out duration-700"
                             x-transition:enter-start="opacity-0 scale-105"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-300"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute inset-0 w-full h-full">
                            <img src="{{ $bannerUrl }}" alt="Banner mobile Laser Link - Promoções e produtos de comunicação visual {{ $index + 1 }}" class="w-full h-full object-cover rounded-2xl">
                        </div>
                    @endforeach
                    
                    <!-- Setas de Navegação Mobile -->
                    @if(count($homeBannersMobile) > 1)
                        <button @click="prevSlideMobile(); stopAutoplayMobile()"
                                class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white p-2 rounded-full shadow-lg transition z-10">
                            <i class="bi bi-chevron-left text-gray-900 text-xl"></i>
                        </button>
                        <button @click="nextSlideMobile(); stopAutoplayMobile()"
                                class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white p-2 rounded-full shadow-lg transition z-10">
                            <i class="bi bi-chevron-right text-gray-900 text-xl"></i>
                        </button>
                        
                        <!-- Indicadores -->
                        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2">
                            @foreach($homeBannersMobile as $index => $banner)
                                <button @click="goToSlideMobile({{ $index }}); stopAutoplayMobile()"
                                        class="h-2 rounded-full transition-all duration-300"
                                        :class="currentSlideMobile === {{ $index }} ? 'bg-white w-8' : 'bg-white/50 w-2'">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </section>
        @endif
    @endif

    <!-- Benefits Section -->
    <section class="relative z-20" style="margin-top: -50px;">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center w-full px-4 sm:px-8 lg:px-18 py-4 lg:py-12 rounded-xl bg-white gap-6 lg:gap-0" 
                 style="box-shadow: 0px 24px 100px 0 rgba(22,25,50,0.07);">
                
                <!-- Discount -->
                <div class="flex justify-start items-start flex-grow-0 flex-shrink-0 relative gap-4">
                    <svg width="46" height="46" viewBox="0 0 46 46" fill="none" xmlns="http://www.w3.org/2000/svg" 
                         class="flex-grow-0 flex-shrink-0 w-11 h-11 lg:w-[46px] lg:h-[46px] relative" preserveAspectRatio="none">
                        <g clip-path="url(#clip0_1_545)">
                            <path d="M44.1788 10.1498L23.293 0.0669785C23.1079 -0.0223262 22.8922 -0.0223262 22.7072 0.0669785L1.82128 10.1498C1.5884 10.2622 1.44043 10.498 1.44043 10.7566V35.2434C1.44043 35.502 1.5884 35.7378 1.82128 35.8502L22.7071 45.933C22.7996 45.9777 22.8999 46 23.0001 46C23.1002 46 23.2004 45.9777 23.293 45.933L44.1788 35.8502C44.4117 35.7378 44.5597 35.502 44.5597 35.2434V10.7567C44.5597 10.4979 44.4116 10.2623 44.1788 10.1498ZM23.0001 1.42209L42.336 10.7566L36.7309 13.4625C36.6954 13.4355 36.6579 13.4105 36.6168 13.3906L17.4122 4.11965L23.0001 1.42209ZM15.8905 4.88153L35.1982 14.2025L31.2438 16.1115L11.944 6.79439L15.8905 4.88153ZM35.65 15.4808V22.5376L31.9562 24.3208V17.264L35.65 15.4808ZM43.212 34.8206L23.6739 44.2526V21.2623L28.3343 19.0124C28.6695 18.8506 28.81 18.4478 28.6482 18.1126C28.4864 17.7776 28.0836 17.6369 27.7484 17.7988L23.0001 20.0912L21.1317 19.1891C20.7965 19.0272 20.3937 19.1679 20.2319 19.503C20.0701 19.8381 20.2106 20.2409 20.5457 20.4028L22.3262 21.2623V44.2526L2.78809 34.8204V11.8301L17.6662 19.0127C17.7606 19.0583 17.8605 19.0799 17.9587 19.0799C18.2091 19.0799 18.4497 18.9396 18.5659 18.6989C18.7277 18.3637 18.5872 17.9609 18.2521 17.7991L3.66415 10.7566L10.358 7.52509L30.5992 17.2968C30.6022 17.3009 30.6055 17.3046 30.6086 17.3086V25.3945C30.6086 25.6264 30.7278 25.8419 30.9242 25.9652C31.0333 26.0337 31.1577 26.0683 31.2825 26.0683C31.3824 26.0683 31.4826 26.0461 31.5754 26.0013L36.6168 23.5675C36.8497 23.4551 36.9976 23.2194 36.9976 22.9607V14.8303L43.212 11.8302V34.8206Z" fill="#272343"></path>
                            <path d="M8.34864 32.207L5.28362 30.7274C4.94832 30.5654 4.54564 30.7061 4.38383 31.0412C4.22202 31.3764 4.36254 31.7792 4.69765 31.941L7.76268 33.4206C7.8571 33.4663 7.95692 33.4879 8.05512 33.4879C8.3056 33.4879 8.5462 33.3475 8.66237 33.1068C8.82427 32.7716 8.68375 32.3689 8.34864 32.207Z" fill="#272343"></path>
                            <path d="M11.1696 30.371L5.28795 27.5315C4.95275 27.3697 4.54998 27.5102 4.38817 27.8454C4.22645 28.1806 4.36697 28.5834 4.70208 28.7452L10.5837 31.5847C10.6781 31.6302 10.7779 31.6519 10.8761 31.6519C11.1266 31.6519 11.3672 31.5115 11.4834 31.2708C11.6452 30.9355 11.5047 30.5327 11.1696 30.371Z" fill="#272343"></path>
                        </g>
                        <defs>
                            <clipPath id="clip0_1_545"><rect width="46" height="46" fill="white"></rect></clipPath>
                        </defs>
                    </svg>
                    <div class="flex flex-col justify-start items-start flex-grow-0 flex-shrink-0 relative gap-1.5">
                        <p class="flex-grow-0 flex-shrink-0 text-base lg:text-lg font-medium text-left capitalize text-[#272343]">
                            Descontos
                        </p>
                        <p class="flex-grow-0 flex-shrink-0 text-sm lg:text-[15px] text-left text-[#9a9caa]">
                            Promoções semanais
                        </p>
                    </div>
                </div>

                <!-- Free Delivery -->
                <div class="flex justify-start items-center flex-grow-0 flex-shrink-0 relative gap-4">
                    <svg width="64" height="39" viewBox="0 0 64 39" fill="none" xmlns="http://www.w3.org/2000/svg" 
                         class="flex-grow-0 flex-shrink-0 w-12 h-7 lg:w-16 lg:h-[37.95px]" preserveAspectRatio="none">
                        <path d="M13.9634 30.5869C12.8939 30.5869 12.0239 31.4569 12.0239 32.5264C12.0239 33.5959 12.8939 34.4659 13.9634 34.4659C15.0329 34.4659 15.9029 33.5959 15.9029 32.5264C15.9029 31.4569 15.0329 30.5869 13.9634 30.5869Z" fill="#272343"></path>
                        <path d="M54.0445 30.5869C52.975 30.5869 52.105 31.4569 52.105 32.5264C52.105 33.5959 52.975 34.4659 54.0445 34.4659C55.114 34.4659 55.984 33.5959 55.984 32.5264C55.984 31.4569 55.114 30.5869 54.0445 30.5869Z" fill="#272343"></path>
                        <path d="M61.9886 18.1537L54.7706 15.7477L51.397 6.4705C50.9759 5.31237 49.865 4.53425 48.6326 4.53425H39.9516V2.46562C39.9516 1.39612 39.0816 0.526123 38.0121 0.526123H1.9395C0.87 0.526123 0 1.39625 0 2.46575V29.5202C0 30.5897 0.87 31.4597 1.9395 31.4597H8.11375C8.05075 31.806 8.01612 32.1621 8.01612 32.5262C8.01612 35.8057 10.6842 38.4739 13.9637 38.4739C17.2432 38.4739 19.9114 35.8057 19.9114 32.5262C19.9114 32.162 19.8767 31.806 19.8137 31.4597H48.1946C48.1316 31.806 48.097 32.1621 48.097 32.5262C48.097 35.8057 50.7651 38.4739 54.0446 38.4739C57.3241 38.4739 59.9922 35.8057 59.9922 32.5262C59.9922 32.063 59.9372 31.6125 59.8366 31.1795L61.8205 30.1876C63.1647 29.5152 64 28.1636 64 26.6602V20.9444C64 19.6764 63.1917 18.5549 61.9886 18.1537ZM1.875 2.46575C1.875 2.43012 1.90387 2.40112 1.9395 2.40112H38.0121C38.0477 2.40112 38.0766 2.43 38.0766 2.46562V22.5705H1.875V2.46575ZM13.9636 36.5989C11.718 36.5989 9.891 34.7719 9.891 32.5262C9.891 30.2806 11.718 28.4536 13.9636 28.4536C16.2092 28.4536 18.0362 30.2806 18.0362 32.5262C18.0364 34.7719 16.2094 36.5989 13.9636 36.5989ZM38.0766 29.5846H19.1297C18.1044 27.791 16.1735 26.5786 13.9636 26.5786C11.7537 26.5786 9.823 27.791 8.79762 29.5846H1.9395C1.90387 29.5846 1.875 29.5557 1.875 29.5201V24.4455H38.0766V29.5846ZM50.8372 10.4174L52.706 15.5565H50.693L48.8242 10.4174H50.8372ZM39.9516 6.40925H48.6326C49.0795 6.40925 49.4823 6.69137 49.635 7.11125L50.1554 8.54225H39.9516V6.40925ZM39.9516 10.4174H46.8291L48.6979 15.5565H39.9516V10.4174ZM54.0444 36.5989C51.7988 36.5989 49.9717 34.7719 49.9717 32.5262C49.9717 30.2806 51.7988 28.4536 54.0444 28.4536C56.29 28.4536 58.117 30.2806 58.117 32.5262C58.117 34.7719 56.29 36.5989 54.0444 36.5989ZM62.125 22.5704H61.0585C60.5407 22.5704 60.121 22.9901 60.121 23.5079C60.121 24.0256 60.5407 24.4454 61.0585 24.4454H62.125V26.6601C62.125 27.4489 61.6869 28.1577 60.9816 28.5105L59.1258 29.4384C58.0808 27.725 56.194 26.5786 54.0444 26.5786C51.8345 26.5786 49.9038 27.791 48.8784 29.5846H39.9516V24.4455H57.0505C57.5682 24.4455 57.988 24.0257 57.988 23.508C57.988 22.9902 57.5682 22.5705 57.0505 22.5705H39.9516V17.4314H53.8923L61.3957 19.9325C61.832 20.0779 62.125 20.4845 62.125 20.9442V22.5704Z" fill="#272343"></path>
                        <path d="M31.4631 11.6718L24.449 7.66382C23.9996 7.40695 23.4269 7.5632 23.1699 8.0127C22.913 8.46232 23.0691 9.03495 23.5187 9.29182L27.4677 11.5484H8.95361C8.43586 11.5484 8.01611 11.9682 8.01611 12.4859C8.01611 13.0037 8.43586 13.4233 8.95361 13.4233H27.4677L23.5187 15.6799C23.0692 15.9368 22.913 16.5094 23.1699 16.9591C23.3429 17.2618 23.6592 17.4316 23.9847 17.4316C24.1425 17.4316 24.3024 17.3917 24.449 17.3078L31.4631 13.2998C31.7551 13.1329 31.9355 12.8223 31.9355 12.4858C31.9355 12.1493 31.7552 11.8388 31.4631 11.6718Z" fill="#272343"></path>
                    </svg>
                    <div class="flex flex-col justify-start items-start flex-grow-0 flex-shrink-0 relative gap-1.5">
                        <p class="flex-grow-0 flex-shrink-0 text-base lg:text-lg font-medium text-left capitalize text-[#272343]">
                            Entrega Grátis
                        </p>
                        <p class="flex-grow-0 flex-shrink-0 text-sm lg:text-[15px] text-left text-[#9a9caa]">
                            100% grátis para todos os pedidos
                        </p>
                    </div>
                </div>

                <!-- Support -->
                <div class="flex justify-start items-center flex-grow-0 flex-shrink-0 relative gap-4">
                    <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg" 
                         class="flex-grow-0 flex-shrink-0 w-10 h-10 lg:w-[50px] lg:h-[50px] relative" preserveAspectRatio="none">
                        <g clip-path="url(#clip0_1_576)">
                            <path d="M47.5936 14.2852C47.4201 13.9197 46.9829 13.7643 46.6178 13.9379C46.2524 14.1114 46.0969 14.5483 46.2705 14.9138C47.7732 18.077 48.5351 21.4705 48.5351 25.0001C48.5351 31.2866 46.0871 37.1968 41.6418 41.642C37.1967 46.0872 31.2865 48.5353 24.9999 48.5353C21.2717 48.5353 17.7063 47.6892 14.403 46.0205C11.6505 44.6301 9.1639 42.678 7.14476 40.3323L10.0345 41.2956C10.4182 41.4235 10.833 41.2161 10.961 40.8323C11.0889 40.4485 10.8815 40.0338 10.4977 39.9059L5.29749 38.1725C5.05667 38.0923 4.79143 38.1423 4.59661 38.305C4.40179 38.4677 4.30501 38.7196 4.34085 38.9709L5.20755 45.0378C5.25979 45.4031 5.57308 45.6667 5.93177 45.6667C5.96624 45.6667 6.0011 45.6643 6.03626 45.6593C6.43675 45.602 6.71497 45.2311 6.65774 44.8307L6.17415 41.4456C8.29319 43.8655 10.8819 45.8829 13.7426 47.328C17.2526 49.1011 21.0401 50.0001 24.9999 50.0001C31.6777 50.0001 37.9558 47.3996 42.6777 42.6778C47.3995 37.9559 50 31.6778 50 25.0001C50 21.2514 49.1904 17.6464 47.5936 14.2852Z" fill="#272343"></path>
                            <path d="M45.6592 11.0293L44.7925 4.9624C44.7353 4.56191 44.3634 4.28389 43.9639 4.34092C43.5634 4.39814 43.2852 4.76914 43.3424 5.16953L43.8255 8.55117C39.0691 3.10156 32.2791 0 25 0C18.3223 0 12.0442 2.60049 7.32227 7.32227C2.60049 12.0442 0 18.3223 0 25C0 28.7488 0.809668 32.3539 2.40635 35.7149C2.53174 35.9789 2.79443 36.1333 3.06846 36.1333C3.17373 36.1333 3.28076 36.1105 3.38223 36.0622C3.74756 35.8887 3.90303 35.4518 3.72949 35.0863C2.22676 31.9233 1.46484 28.5298 1.46484 25C1.46484 18.7135 3.91289 12.8033 8.35811 8.35811C12.8033 3.91289 18.7135 1.46484 25 1.46484C31.9195 1.46484 38.3694 4.44277 42.8531 9.66709L39.9655 8.70459C39.5815 8.57646 39.1669 8.78408 39.0391 9.16787C38.9112 9.55166 39.1185 9.96641 39.5022 10.0943L44.7024 11.8277C44.7782 11.8529 44.8563 11.8653 44.934 11.8653C45.1031 11.8653 45.2697 11.8067 45.4034 11.6952C45.5982 11.5325 45.695 11.2806 45.6592 11.0293Z" fill="#272343"></path>
                            <path d="M22.9646 32.8787H15.5971C15.8913 30.3152 17.7129 28.5953 19.6318 26.7833C21.6305 24.8962 23.6971 22.9449 23.6971 20.0146C23.6971 17.6116 21.5424 15.6565 18.8939 15.6565C16.2455 15.6565 14.0908 17.6116 14.0908 20.0146C14.0908 20.4191 14.4187 20.747 14.8232 20.747C15.2277 20.747 15.5557 20.4191 15.5557 20.0146C15.5557 18.4193 17.0531 17.1213 18.8939 17.1213C20.7348 17.1213 22.2322 18.4193 22.2322 20.0146C22.2322 22.3134 20.4806 23.9673 18.6262 25.7183C16.5003 27.7255 14.0908 30.0006 14.0908 33.6111C14.0908 34.0156 14.4187 34.3435 14.8232 34.3435H22.9646C23.3691 34.3435 23.6971 34.0156 23.6971 33.6111C23.6971 33.2066 23.3691 32.8787 22.9646 32.8787Z" fill="#272343"></path>
                            <path d="M35.1768 28.1816H34.3436V16.3889C34.3436 15.9844 34.0157 15.6565 33.6111 15.6565C33.2065 15.6565 32.8787 15.9844 32.8787 16.3889V28.1817H26.6654L28.8512 16.524C28.9258 16.1264 28.6639 15.7436 28.2663 15.6691C27.8691 15.595 27.4861 15.8564 27.4115 16.254L25.0631 28.7792C25.0229 28.9933 25.0802 29.2142 25.2193 29.3819C25.3585 29.5496 25.565 29.6465 25.7829 29.6465H32.8787V33.6111C32.8787 34.0155 33.2065 34.3434 33.6111 34.3434C34.0157 34.3434 34.3436 34.0155 34.3436 33.611V29.6464H35.1768C35.5814 29.6464 35.9092 29.3185 35.9092 28.914C35.9092 28.5095 35.5814 28.1816 35.1768 28.1816Z" fill="#272343"></path>
                            <path d="M25 3.91406C24.5955 3.91406 24.2676 4.24199 24.2676 4.64648V7.77773C24.2676 8.18223 24.5955 8.51016 25 8.51016C25.4046 8.51016 25.7324 8.18223 25.7324 7.77773V4.64648C25.7324 4.24199 25.4046 3.91406 25 3.91406Z" fill="#272343"></path>
                            <path d="M25 41.4897C24.5955 41.4897 24.2676 41.8177 24.2676 42.2222V45.3534C24.2676 45.7579 24.5955 46.0858 25 46.0858C25.4046 46.0858 25.7324 45.7579 25.7324 45.3534V42.2222C25.7324 41.8177 25.4046 41.4897 25 41.4897Z" fill="#272343"></path>
                            <path d="M45.3534 24.2676H42.2222C41.8176 24.2676 41.4897 24.5955 41.4897 25C41.4897 25.4045 41.8176 25.7324 42.2222 25.7324H45.3534C45.758 25.7324 46.0858 25.4045 46.0858 25C46.0858 24.5955 45.758 24.2676 45.3534 24.2676Z" fill="#272343"></path>
                            <path d="M7.77773 24.2676H4.64648C4.24199 24.2676 3.91406 24.5955 3.91406 25C3.91406 25.4045 4.24199 25.7324 4.64648 25.7324H7.77773C8.18223 25.7324 8.51016 25.4045 8.51016 25C8.51016 24.5955 8.18223 24.2676 7.77773 24.2676Z" fill="#272343"></path>
                        </g>
                        <defs>
                            <clipPath id="clip0_1_576"><rect width="50" height="50" fill="white"></rect></clipPath>
                        </defs>
                    </svg>
                    <div class="flex flex-col justify-start items-start flex-grow-0 flex-shrink-0 relative gap-1.5">
                        <p class="flex-grow-0 flex-shrink-0 text-base lg:text-lg font-medium text-left capitalize text-[#272343]">
                            Suporte 24/7
                        </p>
                        <p class="flex-grow-0 flex-shrink-0 text-sm lg:text-[15px] text-left text-[#9a9caa]">
                            Cuidamos da sua experiência
                        </p>
                    </div>
                </div>

                <!-- Secure Payment -->
                <div class="flex justify-start items-center flex-grow-0 flex-shrink-0 relative gap-4">
                    <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg" 
                         class="flex-grow-0 flex-shrink-0 w-10 h-10 lg:w-[50px] lg:h-[50px] relative" preserveAspectRatio="none">
                        <g clip-path="url(#clip0_1_605)">
                            <path d="M49.2188 6.95611C49.2017 6.63932 48.9825 6.36949 48.6759 6.28785L25.1704 0.0246826C25.0466 -0.00822752 24.9166 -0.00822752 24.7929 0.0246826L1.32403 6.28795C1.01798 6.36969 0.799031 6.63883 0.781453 6.95514C0.765144 7.24811 0.414461 14.2331 3.16123 23.0639C4.77559 28.2539 7.13222 32.9926 10.1658 37.1484C13.9605 42.3471 18.8277 46.6391 24.632 49.9058C24.7434 49.9685 24.8672 50 24.9912 50C25.1091 50 25.2272 49.9715 25.3347 49.9144C31.1488 46.8262 36.0241 42.643 39.8249 37.481C42.8619 33.3563 45.2212 28.6037 46.8372 23.3552C49.5855 14.4291 49.2351 7.25719 49.2188 6.95611ZM45.4219 22.9736C43.8516 28.0558 41.5654 32.6529 38.6267 36.6376C35.1991 41.2852 30.8648 45.1106 25.7327 48.0236V44.2556C32.3859 40.0553 37.4036 34.1094 40.6473 26.5751C43.3181 20.3717 44.1884 14.4831 44.4482 10.6354C44.4718 10.2858 44.2446 9.96841 43.9059 9.87828L42.4122 9.48033C42.0215 9.37623 41.62 9.60855 41.5159 9.99957C41.4117 10.3904 41.6441 10.7918 42.0351 10.896L42.942 11.1376C42.6415 14.8677 41.7552 20.2971 39.3017 25.9958C36.1947 33.2129 31.4039 38.9148 25.0581 42.9508C24.4211 42.5296 23.7946 42.09 23.191 41.6406C22.8667 41.399 22.4077 41.4661 22.166 41.7906C21.9244 42.115 21.9915 42.574 22.316 42.8156C22.9467 43.2853 23.6019 43.7433 24.2675 44.1828V47.9997C19.1331 44.9282 14.7976 41.0013 11.3689 36.3118C8.43114 32.2938 6.1458 27.7075 4.57627 22.6806C2.31719 15.4447 2.20674 9.34488 2.23018 7.56236L24.2675 1.68112V5.02672L6.10468 9.87398C5.76523 9.96461 5.53789 10.2835 5.56289 10.6339C5.75205 13.281 6.28447 17.3995 7.74726 21.9882C10.1655 29.5743 14.227 35.9031 19.8189 40.7989C19.9579 40.9206 20.1299 40.9803 20.3012 40.9803C20.505 40.9803 20.7078 40.8957 20.8527 40.7303C21.1192 40.4259 21.0884 39.9631 20.7841 39.6967C15.3958 34.9793 11.4792 28.8716 9.14306 21.5434C7.83076 17.4267 7.29033 13.7003 7.07148 11.1325L24.9829 6.35241L38.8691 10.0525C39.26 10.1565 39.6614 9.92427 39.7655 9.53326C39.8696 9.14244 39.6372 8.74097 39.2462 8.63687L25.7326 5.036V1.6906L47.7697 7.56266C47.794 9.37564 47.6883 15.6388 45.4219 22.9736Z" fill="#272343"></path>
                            <path d="M36.0205 17.1149C35.5866 16.6803 35.0093 16.4412 34.3951 16.4412C33.7807 16.4412 33.2035 16.6803 32.77 17.1145L23.4343 26.4497L18.3377 21.3535C17.904 20.9191 17.3266 20.6798 16.7124 20.6798C16.098 20.6798 15.5209 20.919 15.0875 21.353C14.6533 21.7871 14.4139 22.3642 14.4139 22.9783C14.4139 23.5923 14.6531 24.1694 15.0873 24.6033L21.8089 31.3249C22.2427 31.7593 22.8199 31.9986 23.4342 31.9986C24.0486 31.9986 24.6257 31.7594 25.0593 31.3252L36.0199 20.3647C36.4541 19.9308 36.6934 19.3536 36.6934 18.7395C36.6934 18.1254 36.4542 17.5483 36.0205 17.1149ZM34.9841 19.3286L24.0231 30.2896C23.8661 30.4468 23.657 30.5334 23.4342 30.5334C23.2115 30.5334 23.0024 30.4468 22.8451 30.2893L16.123 23.5671C15.9657 23.4099 15.879 23.2008 15.879 22.9782C15.879 22.7555 15.9657 22.5465 16.1235 22.3886C16.2805 22.2314 16.4897 22.1448 16.7124 22.1448C16.9349 22.1448 17.144 22.2314 17.3015 22.3889L22.9165 28.0034C23.2025 28.2895 23.6664 28.2894 23.9523 28.0034L33.8063 18.1499C33.9633 17.9926 34.1725 17.906 34.3952 17.906C34.6178 17.906 34.8268 17.9926 34.9844 18.1503C35.1417 18.3076 35.2285 18.5167 35.2285 18.7393C35.2285 18.9619 35.1417 19.1711 34.9841 19.3286Z" fill="#272343"></path>
                        </g>
                        <defs>
                            <clipPath id="clip0_1_605"><rect width="50" height="50" fill="white"></rect></clipPath>
                        </defs>
                    </svg>
                    <div class="flex flex-col justify-start items-start flex-grow-0 flex-shrink-0 relative gap-1.5">
                        <p class="flex-grow-0 flex-shrink-0 text-base lg:text-lg font-medium text-left capitalize text-[#272343]">
                            Pagamento Seguro
                        </p>
                        <p class="flex-grow-0 flex-shrink-0 text-sm lg:text-[15px] text-left text-[#9a9caa]">
                            100% Pagamento Seguro
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    @if(count($featuredProducts) > 0)
    <section class="py-8" 
             x-data="storeProducts" 
             x-init="init()">
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('storeProducts', () => ({
                    activeFilter: 'todos',
                    products: <?php echo json_encode($featuredProducts); ?>,
                    favorites: [],
                    renderKey: 0,
                    isFiltering: false,
                    
                    init() {
                        // Tornar produtos acessíveis globalmente
                        window.storeProducts = this.products;
                        
                        // Sincronizar com favoritesManager
                        this.syncFavorites();
                        
                        // Escutar mudanças nos favoritos
                        window.addEventListener('favoritesUpdated', () => {
                            this.syncFavorites();
                        });
                        
                        // Inicialização dos dados
                    },
                    
                    syncFavorites() {
                        if (window.favoritesManager) {
                            const favs = window.favoritesManager.getAllFavorites();
                            this.favorites = favs.map(f => f.id);
                        }
                    },
                    
                    filteredProducts() {
                        if (this.activeFilter === 'todos') {
                            return this.products;
                        }
                        
                        if (this.activeFilter === 'novos') {
                            // Filtrar produtos com is_new = true
                            const newProducts = this.products.filter(p => p.is_new);
                            
                            if (newProducts.length > 0) {
                                // Se temos produtos marcados como novos, mostrar até 8
                                return newProducts.slice(0, 8);
                            } else {
                                // Se não há produtos com is_new, mostrar os 8 mais recentes por data
                                const sortedProducts = [...this.products].sort((a, b) => {
                                    const dateA = new Date(a.created_at);
                                    const dateB = new Date(b.created_at);
                                    return dateB - dateA;
                                });
                                return sortedProducts.slice(0, 8);
                            }
                        }
                        
                        if (this.activeFilter === 'destaques') {
                            return this.products.filter(p => p.is_featured);
                        }
                        
                        if (this.activeFilter === 'mais_vendidos') {
                            // Criar uma cópia para não alterar o array original
                            const sortedProducts = [...this.products].sort((a, b) => b.final_price - a.final_price);
                            return sortedProducts.slice(0, 8);
                        }
                        
                        if (this.activeFilter === 'tendencias') {
                            return this.products.filter(p => p.is_new || p.is_on_sale);
                        }
                        
                        return this.products;
                    },
                    
                    isFavorite(productId) {
                        return this.favorites.includes(productId);
                    },
                    
                    toggleFavorite(productId) {
                        if (!window.favoritesManager) return;
                        
                        const product = this.products.find(p => p.id === productId);
                        if (!product) return;
                        
                        // Preparar dados do produto para o favoritesManager
                        const productData = {
                            id: product.id,
                            name: product.name,
                            slug: product.slug,
                            price: product.final_price,
                            image_url: product.featured_image || product.first_image || '{{ url('images/general/callback-image.svg') }}'
                        };
                        
                        window.favoritesManager.toggleFavorite(productData);
                        this.syncFavorites();
                    },
                    
                    addToCart(productId) {
                        // Buscar informações do produto
                        const product = this.products.find(p => p.id === productId);
                        if (!product) return;
                        
                        // Obter carrinho do localStorage
                        let cart = JSON.parse(localStorage.getItem('cart') || '[]');
                        
                        // Verificar se o produto já está no carrinho
                        const existingItem = cart.find(item => item.id === productId);
                        
                        if (existingItem) {
                            // Incrementar quantidade
                            existingItem.quantity += 1;
                        } else {
                            // Usar a imagem como está, já vem com URL completa do controller
                            let imagePath = product.featured_image || product.first_image;
                            
                            // Adicionar novo item
                            cart.push({
                                id: product.id,
                                name: product.name,
                                slug: product.slug,
                                price: product.final_price,
                                image: imagePath,
                                quantity: 1
                            });
                        }
                        
                        // Salvar no localStorage
                        localStorage.setItem('cart', JSON.stringify(cart));
                        
                        // Disparar evento para atualizar o header
                        window.dispatchEvent(new Event('cartUpdated'));
                        
                        // Produto adicionado com sucesso
                    }
                }));
            });
        </script>
        
        <!-- Category Slider -->
        <x-category-slider />
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Title -->
            <p class="text-[32px] font-semibold text-center capitalize text-[#272343] mb-4">
                Nossos produtos
            </p>
            
            <!-- Filter Tabs -->
            <div class="flex flex-wrap justify-center items-center gap-3 mb-12">
                <!-- Todos -->
                        <button @click="isFiltering = true; activeFilter = 'todos'; renderKey++; setTimeout(() => isFiltering = false, 300);"
                        :class="activeFilter === 'todos' 
                            ? 'bg-red-600 text-white shadow-md' 
                            : 'bg-white text-gray-600 border border-gray-300 hover:border-red-300 hover:text-red-600'"
                        class="px-6 py-2.5 rounded-lg font-medium text-sm uppercase transition-all duration-200 transform hover:scale-105">
                    <i class="bi bi-grid-3x3-gap mr-2"></i>Todos
                </button>
                
                <!-- Novos -->
                        <button @click="isFiltering = true; activeFilter = 'novos'; renderKey++; setTimeout(() => isFiltering = false, 300);"
                        :class="activeFilter === 'novos' 
                            ? 'bg-blue-600 text-white shadow-md' 
                            : 'bg-white text-gray-600 border border-gray-300 hover:border-blue-300 hover:text-blue-600'"
                        class="px-6 py-2.5 rounded-lg font-medium text-sm uppercase transition-all duration-200 transform hover:scale-105">
                    <i class="bi bi-star mr-2"></i>Novos
                </button>
                
                <!-- Mais Vendidos -->
                        <button @click="isFiltering = true; activeFilter = 'mais_vendidos'; renderKey++; setTimeout(() => isFiltering = false, 300);"
                        :class="activeFilter === 'mais_vendidos' 
                            ? 'bg-green-600 text-white shadow-md' 
                            : 'bg-white text-gray-600 border border-gray-300 hover:border-green-300 hover:text-green-600'"
                        class="px-6 py-2.5 rounded-lg font-medium text-sm uppercase transition-all duration-200 transform hover:scale-105">
                    <i class="bi bi-fire mr-2"></i>Mais Vendidos
                </button>
                
                <!-- Destaques -->
                        <button @click="isFiltering = true; activeFilter = 'destaques'; renderKey++; setTimeout(() => isFiltering = false, 300);"
                        :class="activeFilter === 'destaques' 
                            ? 'bg-amber-600 text-white shadow-md' 
                            : 'bg-white text-gray-600 border border-gray-300 hover:border-amber-300 hover:text-amber-600'"
                        class="px-6 py-2.5 rounded-lg font-medium text-sm uppercase transition-all duration-200 transform hover:scale-105">
                    <i class="bi bi-award mr-2"></i>Destaques
                </button>
            </div>
            
            <!-- Results Info -->
            <div class="text-center mb-6">
                <p class="text-sm text-gray-500">
                    Mostrando <span class="font-semibold text-gray-900" x-text="filteredProducts().length"></span> produtos
                </p>
            </div>

            <!-- Loading State -->
            <div x-show="isFiltering" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="text-center py-16">
                <x-loading-spinner size="lg" text="Carregando produtos..." />
            </div>

            <!-- Products Grid -->
            <div x-show="!isFiltering"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <template x-for="(product, index) in filteredProducts()" :key="`${product.id}-${renderKey}`">
                <div class="relative group w-full sm:max-w-[300px] sm:mx-auto flex flex-col bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-all duration-300"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-90"
                     x-transition:enter-end="opacity-100 transform scale-100" 
                     x-data="{ 
                         adding: false, 
                         inCart: false, 
                         removing: false,
                         init() {
                             // Verificar se o produto está no carrinho ao carregar
                             const cart = JSON.parse(localStorage.getItem('cart') || '[]');
                             this.inCart = cart.some(item => item.id === product.id);
                         },
                         handleCartClick(productId) {
                             if (!this.inCart) {
                                 // Adicionar ao carrinho
                                 this.adding = true;
                                 
                                 // Buscar produto do componente pai
                                 const product = window.storeProducts?.find(p => p.id === productId);
                                 if (product) {
                                     let cart = JSON.parse(localStorage.getItem('cart') || '[]');
                                     const existingItem = cart.find(item => item.id === productId);
                                     
                                     if (existingItem) {
                                         existingItem.quantity += 1;
                                     } else {
                                         // Usar a imagem como está, já vem com URL completa do controller
                                         let imagePath = product.featured_image || product.first_image;
                                         
                                         cart.push({
                                             id: product.id,
                                             name: product.name,
                                             slug: product.slug,
                                             price: product.final_price,
                                             image: imagePath,
                                             quantity: 1
                                         });
                                     }
                                     
                                     localStorage.setItem('cart', JSON.stringify(cart));
                                     window.dispatchEvent(new Event('cartUpdated'));
                                 }
                                 
                                 setTimeout(() => {
                                     this.adding = false;
                                     this.inCart = true;
                                 }, 800);
                             } else if (this.removing) {
                                 // Remover do carrinho
                                 this.adding = true;
                                 this.removeFromCart(productId);
                                 setTimeout(() => {
                                     this.adding = false;
                                     this.inCart = false;
                                     this.removing = false;
                                 }, 500);
                             }
                         },
                         removeFromCart(productId) {
                             let cart = JSON.parse(localStorage.getItem('cart') || '[]');
                             cart = cart.filter(item => item.id !== productId);
                             localStorage.setItem('cart', JSON.stringify(cart));
                             window.dispatchEvent(new Event('cartUpdated'));
                         }
                     }">
                    <!-- Product Image -->
                    <div class="relative w-full aspect-square mb-4 flex-shrink-0 overflow-hidden">
                        <a :href="'/produto/' + product.slug" class="block w-full h-full">
                            <div x-show="product.featured_image && product.featured_image.trim() !== '' && product.featured_image !== null" class="w-full h-full">
                                <img :src="product.featured_image" 
                                     :alt="`${product.name} - Laser Link`"
                                     class="w-full h-full object-cover bg-white"
                                     onerror="this.src='{{ url('images/general/callback-image.svg') }}'; this.classList.add('object-contain', 'bg-gray-100');">
                            </div>
                            <div x-show="!product.featured_image || product.featured_image.trim() === ''" class="w-full h-full">
                                <img src="{{ url('images/general/callback-image.svg') }}" 
                                     :alt="`${product.name} - Produto personalizado Laser Link`"
                                     class="w-full h-full object-contain bg-gray-100">
                            </div>
                        </a>
                        
                        <!-- Badges -->
                        <div class="absolute left-3 top-3 flex flex-col gap-2 z-10">
                            <span x-show="product.is_new" class="px-2 py-1 rounded-md bg-blue-500 text-white text-xs font-semibold uppercase shadow-md w-fit">
                                Novo
                            </span>
                            
                            <span x-show="product.is_on_sale && product.discount_percentage > 0" class="px-2 py-1 rounded-md bg-red-500 text-white text-xs font-semibold shadow-md w-fit">
                                -<span x-text="product.discount_percentage"></span>%
                            </span>
                            
                            <span x-show="product.is_featured" class="px-2 py-1 rounded-md bg-amber-600 text-white text-xs font-semibold uppercase shadow-md w-fit">
                                Destaque
                            </span>
                            
                            <span x-show="product.stock_quantity == 0" class="px-2 py-1 rounded-md bg-gray-500 text-white text-xs font-semibold uppercase shadow-md w-fit">
                                Esgotado
                            </span>
                        </div>
                        
                        <!-- Favorite Button -->
                        <button @click="toggleFavorite(product.id)" 
                                class="absolute right-3 top-3 w-10 h-10 rounded-md bg-white hover:bg-gray-50 flex items-center justify-center transition-all shadow-sm z-10">
                            <i :class="isFavorite(product.id) ? 'bi bi-heart-fill text-red-500' : 'bi bi-heart text-gray-700'" 
                               class="text-xl transition-all duration-200"></i>
                        </button>
                    </div>
                    
                    <!-- Product Info -->
                    <div class="flex flex-col flex-grow space-y-2 p-4 pt-0">
                        <!-- Title -->
                        <a :href="'/produto/' + product.slug" 
                           class="text-[#272343] text-base font-medium capitalize hover:underline    line-clamp-2 flex items-start">
                            <span x-text="product.name"></span>
                        </a>
                        
                        <!-- Price and Rating -->
                        <div class="flex items-center justify-between gap-2 min-h-[2rem]">
                            <div class="flex items-center gap-2">
                                <p class="text-lg font-semibold text-[#272343]">
                                    R$ <span x-text="(product.final_price || product.price || 0).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>
                                </p>
                                <p x-show="product.is_on_sale && product.original_price" class="text-sm text-[#9a9caa] line-through">
                                    R$ <span x-text="(product.original_price || 0).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>
                                </p>
                            </div>
                            
                            <!-- Rating Stars -->
                            <div class="flex items-center gap-0.5 text-base">
                                <template x-for="i in 5" :key="i">
                                    <i class="bi text-yellow-400" 
                                       :class="i <= Math.floor(product.rating_average || 0) ? 'bi-star-fill' : 
                                               (i - 0.5 <= (product.rating_average || 0) ? 'bi-star-half' : 'bi-star')"></i>
                                </template>
                            </div>
                        </div>
                        
                        <!-- Add to Cart Button Full Width -->
                        <button @click="handleCartClick(product.id)"
                                @mouseenter="if(inCart) removing = true"
                                @mouseleave="removing = false"
                                x-init="window.addEventListener('cartUpdated', () => {
                                    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
                                    inCart = cart.some(item => item.id === product.id);
                                })"
                                class="w-full py-2 rounded-lg text-sm font-medium transition-all flex items-center justify-center gap-2 mt-auto"
                                :class="inCart && !removing ? 'bg-green-500 text-white transition-colors duration-200' : inCart && removing ? 'bg-red-500 text-white transition-colors duration-200' : 'bg-gray-900 hover:bg-black text-white transition-colors duration-200'">
                            <!-- Static state - Adicionar -->
                            <template x-if="!adding && !inCart">
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-cart-plus text-base"></i>
                                    <span>Adicionar ao Carrinho</span>
                                </div>
                            </template>
                            
                            <!-- Loading before adding -->
                            <template x-if="adding">
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-arrow-clockwise text-base animate-spin"></i>
                                    <span>Adicionando...</span>
                                </div>
                            </template>
                            
                            <!-- Added to cart -->
                            <template x-if="!adding && inCart && !removing">
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-cart-check-fill text-base"></i>
                                    <span>Produto Adicionado</span>
                                </div>
                            </template>
                            
                            <!-- Remove on hover -->
                            <template x-if="!adding && inCart && removing">
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-cart-x-fill text-base"></i>
                                    <span>Remover do Carrinho</span>
                                </div>
                            </template>
                        </button>
                    </div>
                </div>
                </template>
            </div>
            
            <!-- Ver Todos os Produtos -->
            <div class="text-center mt-12">
                <a href="{{ route('store.products') }}" 
                   class="inline-flex items-center px-8 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 shadow-md hover:shadow-lg font-medium text-lg">
                    Ver Todos os Produtos
                    <i class="bi bi-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- Recently Viewed Products -->
    <section class="py-12 bg-gray-50" 
             x-data="recentlyViewedProducts()" 
             x-init="init()"
             x-show="recentProducts.length > 0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-[#272343]">Produtos Vistos Recentemente</h2>
                    <p class="text-sm text-[#9a9caa] mt-2">Continue de onde parou</p>
                </div>
                <!-- <button @click="clearRecentProducts()" 
                        class="text-sm text-red-600 hover:text-red-700 font-medium transition-colors">
                    <i class="bi bi-trash mr-1"></i>Limpar histórico
                </button> -->
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <template x-for="product in recentProducts" :key="product.id">
                    <div class="relative group w-full sm:max-w-[300px] sm:mx-auto flex flex-col bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow"
                         x-data="{
                            adding: false, 
                            inCart: false, 
                            removing: false,
                            init() {
                                const cart = JSON.parse(localStorage.getItem('cart') || '[]');
                                this.inCart = cart.some(item => item.id === product.id || item.product_id === product.id);
                            },
                            handleCartClick() {
                                if (!this.inCart) {
                                    this.adding = true;
                                    
                                    let cart = JSON.parse(localStorage.getItem('cart') || '[]');
                                    const existingItem = cart.find(item => item.id === product.id);
                                    
                                    if (existingItem) {
                                        existingItem.quantity += 1;
                                    } else {
                                        cart.push({
                                            id: product.id,
                                            product_id: product.id,
                                            name: product.name,
                                            slug: product.slug,
                                            price: product.price,
                                            image: product.image,
                                            quantity: 1
                                        });
                                    }
                                    
                                    localStorage.setItem('cart', JSON.stringify(cart));
                                    window.dispatchEvent(new Event('cartUpdated'));
                                    
                                    setTimeout(() => {
                                        this.adding = false;
                                        this.inCart = true;
                                    }, 800);
                                } else if (this.removing) {
                                    this.adding = true;
                                    let cart = JSON.parse(localStorage.getItem('cart') || '[]');
                                    cart = cart.filter(item => item.id !== product.id && item.product_id !== product.id);
                                    localStorage.setItem('cart', JSON.stringify(cart));
                                    window.dispatchEvent(new Event('cartUpdated'));
                                    
                                    setTimeout(() => {
                                        this.adding = false;
                                        this.inCart = false;
                                        this.removing = false;
                                    }, 500);
                                }
                            }
                         }"
                         x-init="window.addEventListener('cartUpdated', () => {
                            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
                            inCart = cart.some(item => item.id === product.id || item.product_id === product.id);
                         })">
                        
                        <!-- Product Image -->
                        <div class="relative w-full aspect-square mb-4 flex-shrink-0 overflow-hidden">
                            <a :href="'/produto/' + product.slug" class="block w-full h-full">
                                <img :src="product.image" 
                                     :alt="product.name + ' - Laser Link'" 
                                     class="w-full h-full object-cover bg-white"
                                     onerror="this.src='{{ url('images/general/callback-image.svg') }}'; this.classList.add('object-contain', 'bg-gray-100');">
                            </a>
                            
                            <!-- Favorite Button -->
                            <button @click.prevent="
                                    if (window.favoritesManager) {
                                        window.favoritesManager.toggleFavorite(product);
                                        $dispatch('favorites-updated');
                                    }
                                " 
                                x-data="{
                                    updateFavIcon() {
                                        if (window.favoritesManager) {
                                            const icon = $el.querySelector('i');
                                            const isFav = window.favoritesManager.isFavorite(product.id);
                                            icon.className = isFav 
                                                ? 'bi bi-heart-fill text-red-500 text-xl transition-all duration-200' 
                                                : 'bi bi-heart text-gray-700 text-xl transition-all duration-200';
                                        }
                                    }
                                }"
                                x-init="
                                    setTimeout(() => updateFavIcon(), 100);
                                    window.addEventListener('favoritesUpdated', () => updateFavIcon());
                                "
                                class="absolute right-3 top-3 w-10 h-10 rounded-md bg-white hover:bg-gray-50 flex items-center justify-center transition-all shadow-sm z-10">
                                <i class="bi bi-heart text-gray-700 text-xl transition-all duration-200"></i>
                            </button>
                            
                            <!-- Recently Viewed Badge -->
                            <div class="absolute left-3 top-3 z-10">
                                <span class="px-2 py-1 rounded-md bg-purple-500 text-white text-xs font-semibold uppercase shadow-md">
                                    Visto recentemente
                                </span>
                            </div>
                        </div>
                        
                        <!-- Product Info -->
                        <div class="flex flex-col flex-grow space-y-2 p-4 pt-0">
                            <!-- Title -->
                            <a :href="'/produto/' + product.slug" 
                               class="text-base font-medium capitalize hover:underline line-clamp-2 flex items-start text-[#272343]"
                               x-text="product.name">
                            </a>
                            
                            <!-- Price -->
                            <div class="flex items-center justify-between gap-2 min-h-[2rem]">
                                <p class="text-lg font-semibold text-[#272343]">
                                    R$ <span x-text="(product.price || 0).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>
                                </p>
                            </div>
                            
                            <!-- Add to Cart Button -->
                            <button @click="handleCartClick()"
                                    @mouseenter="if(inCart) removing = true"
                                    @mouseleave="removing = false"
                                    class="w-full py-2 rounded-lg text-sm font-medium transition-all flex items-center justify-center gap-2 mt-auto"
                                    :class="inCart && !removing ? 'bg-green-500 text-white transition-colors duration-200' : inCart && removing ? 'bg-red-500 text-white transition-colors duration-200' : 'bg-gray-900 hover:bg-black text-white transition-colors duration-200'">
                                <!-- Static state - Adicionar -->
                                <template x-if="!adding && !inCart">
                                    <div class="flex items-center gap-2">
                                        <i class="bi bi-cart-plus text-base"></i>
                                        <span>Adicionar ao Carrinho</span>
                                    </div>
                                </template>
                                
                                <!-- Loading before adding -->
                                <template x-if="adding">
                                    <div class="flex items-center gap-2">
                                        <i class="bi bi-arrow-clockwise text-base animate-spin"></i>
                                        <span>Adicionando...</span>
                                    </div>
                                </template>
                                
                                <!-- Added to cart -->
                                <template x-if="!adding && inCart && !removing">
                                    <div class="flex items-center gap-2">
                                        <i class="bi bi-cart-check-fill text-base"></i>
                                        <span>Produto Adicionado</span>
                                    </div>
                                </template>
                                
                                <!-- Remove on hover -->
                                <template x-if="!adding && inCart && removing">
                                    <div class="flex items-center gap-2">
                                        <i class="bi bi-cart-x-fill text-base"></i>
                                        <span>Remover do Carrinho</span>
                                    </div>
                                </template>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </section>
    
</div>
@endsection

@push('scripts')
<script>
    // Recently Viewed Products Manager
    function recentlyViewedProducts() {
        return {
            recentProducts: [],
            maxProducts: 4,
            
            init() {
                this.loadRecentProducts();
                
                // Escutar evento quando um produto é visualizado
                window.addEventListener('productViewed', (event) => {
                    this.addProduct(event.detail);
                });
            },
            
            loadRecentProducts() {
                const stored = localStorage.getItem('recentlyViewed');
                if (stored) {
                    try {
                        this.recentProducts = JSON.parse(stored);
                    } catch (e) {
                        this.recentProducts = [];
                    }
                }
            },
            
            addProduct(product) {
                // Remover produto se já existir
                this.recentProducts = this.recentProducts.filter(p => p.id !== product.id);
                
                // Adicionar no início
                this.recentProducts.unshift({
                    id: product.id,
                    name: product.name,
                    slug: product.slug,
                    price: product.price,
                    image: product.image,
                    viewed_at: new Date().toISOString()
                });
                
                // Limitar ao máximo de produtos
                if (this.recentProducts.length > this.maxProducts) {
                    this.recentProducts = this.recentProducts.slice(0, this.maxProducts);
                }
                
                // Salvar no localStorage
                localStorage.setItem('recentlyViewed', JSON.stringify(this.recentProducts));
            },
            
            clearRecentProducts() {
                if (confirm('Deseja limpar o histórico de produtos visualizados?')) {
                    this.recentProducts = [];
                    localStorage.removeItem('recentlyViewed');
                }
            }
        };
    }
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Alpine.js gerencia os favoritos automaticamente
</script>
@endpush
