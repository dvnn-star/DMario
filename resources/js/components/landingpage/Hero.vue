<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
</script>

<template>
  <!-- min-h-[100dvh] guarantees dynamic full height across mobile browsers -->
  <section class="relative min-h-[100dvh] w-full flex items-center bg-[#0a0a0b] text-zinc-100 overflow-hidden py-16 sm:py-24 md:py-0">
    
    <!-- Background Image & Gradient Overlay -->
    <div class="absolute inset-0 z-0">
      <div class="absolute inset-0 bg-gradient-to-r from-[#0a0a0b] via-[#0a0a0b]/80 to-[#0a0a0b]/40 md:via-[#0a0a0b]/60 md:to-transparent z-10"></div>
      <img 
        src="bg.webp" 
        class="w-full h-full object-cover opacity-60"
        alt="Pemandangan sunset di D'Mario Sunset Resto & Cafe, Tanjung Uban"
      />
    </div>

    <!-- Content Container -->
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 relative z-20 w-full">
      <div class="max-w-2xl">
        <Motion
          :initial="{ opacity: 0, x: -50 }"
          :animate="{ opacity: 1, x: 0 }"
        >
          <span class="text-orange-500 font-bold tracking-[0.25em] uppercase text-xs sm:text-sm mb-3 sm:mb-4 block">
            The Golden Experience
          </span>
          
          <!-- Scaled font sizes up for mobile screens -->
          <h1 class="text-5xl sm:text-7xl md:text-8xl lg:text-9xl font-black leading-[0.95] mb-6 sm:mb-8">
            D'MARIO <br/>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-rose-600">
              SUNSET.
            </span>
          </h1>
        </Motion>

        <Motion
          :initial="{ opacity: 0 }"
          :animate="{ opacity: 1 }"
          :transition="{ delay: 0.3 }"
        >
          <p class="text-lg sm:text-xl text-zinc-400 leading-relaxed mb-8 sm:mb-12 max-w-lg">
            Satu-satunya tempat di mana rasa bertemu dengan cakrawala. Pesan hidangan favoritmu langsung dari meja, tanpa merusak momen emasmu.
          </p>
        </Motion>

        <!-- Dynamic Stacked CTA Buttons for Mobile -->
        <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 w-full sm:w-auto">
          <Link 
            :href="route('menu')"
            class="w-full sm:w-auto inline-flex justify-center items-center px-8 sm:px-10 py-4 sm:py-5 bg-orange-600 hover:bg-orange-500 text-white font-bold rounded-full transition-all hover:scale-105 active:scale-95 shadow-[0_0_30px_rgba(234,88,12,0.3)] text-center text-base sm:text-lg"
          >
            Eksplor Menu
          </Link>
          
          <Link 
            :href="route('reservation')"
            class="w-full sm:w-auto inline-flex justify-center items-center px-8 sm:px-10 py-4 sm:py-5 bg-white/5 hover:bg-white/10 backdrop-blur-md border border-white/10 text-white font-bold rounded-full transition-all text-center text-base sm:text-lg"
          >
            Reservasi Meja
          </Link>
        </div>
      </div>
    </div>

  </section>
</template>