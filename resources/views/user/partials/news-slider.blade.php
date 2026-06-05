@if(isset($beritas) && count($beritas) > 0)
<!-- Carousel Box Slider (Full Width) -->
<div class="relative w-full h-[350px] md:h-[450px] overflow-hidden group/slider border-b border-slate-200/60 shadow-md">
    
    <!-- Slider Track -->
    <div id="newsSliderTrack" class="flex w-full h-full transition-transform duration-500 ease-out">
        @foreach($beritas as $berita)
        <div class="min-w-full w-full h-full flex-shrink-0 relative overflow-hidden group">
            @if($berita->foto)
                <img src="{{ asset('storage/' . $berita->foto) }}" alt="Foto Berita" class="w-full h-full object-cover brightness-[0.65] transition-transform duration-700 group-hover:scale-105">
            @else
                <div class="w-full h-full flex items-center justify-center bg-slate-900 brightness-[0.65]">
                    <i data-lucide="image" class="w-12 h-12 text-slate-600 opacity-50"></i>
                </div>
            @endif
            
            <!-- Absolute Text Overlay -->
            <div class="absolute bottom-0 left-0 right-0 p-6 md:p-12 bg-gradient-to-t from-black/95 via-black/50 to-transparent text-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <span class="text-xs md:text-sm text-slate-300 font-bold mb-2 flex items-center gap-1.5">
                        <i data-lucide="calendar" class="w-4 h-4"></i> {{ $berita->created_at->format('d M Y') }}
                    </span>
                    <h4 class="font-extrabold text-white text-lg md:text-3xl leading-snug line-clamp-2 mb-4">
                        {{ $berita->judul }}
                    </h4>
                    
                    <div id="konten-berita-{{ $berita->id }}" class="hidden">{{ $berita->konten }}</div>
                    <button onclick="bukaModalBerita('{{ $berita->id }}', '{{ addslashes($berita->judul) }}', '{{ $berita->created_at->format('d M Y') }}', '{{ $berita->foto ? asset('storage/'.$berita->foto) : '' }}')" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs md:text-sm rounded-xl transition-all shadow-md focus:outline-none flex items-center gap-1.5">
                        Selengkapnya <i data-lucide="arrow-right-circle" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Manual Navigation Arrows -->
    @if(count($beritas) > 1)
    <button id="prevBtn" class="absolute left-6 top-1/2 -translate-y-1/2 w-12 h-12 bg-black/40 hover:bg-black/70 text-white rounded-full flex items-center justify-center backdrop-blur-sm z-30 transition-all duration-300 transform hover:scale-110 focus:outline-none shadow-lg cursor-pointer">
        <i data-lucide="chevron-left" class="w-6 h-6"></i>
    </button>
    <button id="nextBtn" class="absolute right-6 top-1/2 -translate-y-1/2 w-12 h-12 bg-black/40 hover:bg-black/70 text-white rounded-full flex items-center justify-center backdrop-blur-sm z-30 transition-all duration-300 transform hover:scale-110 focus:outline-none shadow-lg cursor-pointer">
        <i data-lucide="chevron-right" class="w-6 h-6"></i>
    </button>
    @endif

</div>
@endif

@if(isset($beritas) && count($beritas) > 1)
<!-- Script Carousel Autoplay & Navigasi Manual -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const track = document.getElementById('newsSliderTrack');
        if (!track) return;
        const slides = track.children;
        const slideCount = slides.length;
        if (slideCount <= 1) return;

        let currentIndex = 0;
        let slideInterval;
        const intervalTime = 2000; // 2 Detik

        function updateSlide() {
            track.style.transform = `translateX(-${currentIndex * 100}%)`;
        }

        function nextSlide() {
            currentIndex = (currentIndex + 1) % slideCount;
            updateSlide();
        }

        function prevSlide() {
            currentIndex = (currentIndex - 1 + slideCount) % slideCount;
            updateSlide();
        }

        function startAutoplay() {
            slideInterval = setInterval(nextSlide, intervalTime);
        }

        function resetAutoplay() {
            clearInterval(slideInterval);
            startAutoplay();
        }

        const btnPrev = document.getElementById('prevBtn');
        const btnNext = document.getElementById('nextBtn');

        if (btnPrev) {
            btnPrev.addEventListener('click', () => {
                prevSlide();
                resetAutoplay();
            });
        }

        if (btnNext) {
            btnNext.addEventListener('click', () => {
                nextSlide();
                resetAutoplay();
            });
        }

        startAutoplay();
        
        // Initialize Lucide icons for dynamically added navigation buttons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endif
