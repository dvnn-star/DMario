<script setup lang="ts">
interface MenuItem {
  id: string | number;
  title: string;
  tag: string;
  description: string | null;
  price: string;
  image: string;
}

// Menerima data hasil query where('is_recommended', true) dari Inertia Controller
defineProps<{
  bestSellers: MenuItem[];
}>();
</script>

<template>
  <section class="py-16 md:py-24 bg-gradient-to-b from-[#0d1b2a] to-[#050505] text-zinc-100 overflow-hidden relative">
    
    <!-- Background Glow Effect -->
    <div class="absolute top-1/3 -right-20 w-80 h-80 bg-orange-600/10 blur-[100px] rounded-full pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6">
      
      <!-- Section Header -->
      <div class="relative mb-12 md:mb-16">
        <h2 class="text-[12vw] sm:text-[10vw] md:text-[8vw] font-black leading-none opacity-5 absolute -top-8 sm:-top-12 -left-4 sm:-left-6 select-none uppercase tracking-tighter pointer-events-none">
          Signature
        </h2>
        <div class="relative z-10 flex flex-col md:flex-row md:items-end justify-between gap-4 md:gap-8">
          <div>
            <span class="text-orange-500 font-bold tracking-[0.3em] uppercase text-xs mb-2 block">Best Sellers</span>
            <h3 class="text-3xl sm:text-4xl md:text-5xl font-light tracking-tighter">Pilihan <span class="font-black italic text-orange-600">Terbaik.</span></h3>
          </div>
          <p class="max-w-xs text-zinc-500 text-xs sm:text-sm leading-relaxed border-l border-zinc-800 pl-4">
            Setiap hidangan adalah cerita tentang bumbu lokal yang diolah dengan teknik internasional.
          </p>
        </div>
      </div>

      <!-- State: Empty Data -->
      <div v-if="!bestSellers || bestSellers.length === 0" class="text-center py-16 bg-zinc-900/30 rounded-3xl border border-white/5">
        <p class="text-zinc-500 text-sm italic">Belum ada menu rekomendasi yang ditampilkan saat ini.</p>
      </div>

      <!-- Grid Layout Data dari Database -->
      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8 relative z-10">
        <div 
          v-for="(item, index) in bestSellers" 
          :key="item.id"
          class="group flex flex-col justify-between bg-zinc-900/50 backdrop-blur-xl rounded-[2rem] border border-white/10 p-5 hover:border-orange-500/40 transition-all duration-300 hover:-translate-y-1 shadow-xl"
        >
          <!-- Card Top Section (Image & Tag) -->
          <div>
            <div class="relative w-full aspect-[4/3] rounded-[1.5rem] overflow-hidden mb-5 bg-zinc-800">
              <img 
                :src="item.image" 
                :alt="item.title"
                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" 
                loading="lazy"
              />
              <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
              
              <!-- Tag & Badge Overlay -->
              <div class="absolute top-3 left-3 right-3 flex justify-between items-center">
                <span class="px-3 py-1 rounded-full bg-black/60 backdrop-blur-md border border-orange-500/40 text-orange-400 text-[10px] font-bold uppercase tracking-wider">
                  {{ item.tag || 'Recommended' }}
                </span>
                <span class="text-xs font-mono font-semibold text-zinc-400 bg-black/60 backdrop-blur-md px-2.5 py-1 rounded-full border border-white/10">
                  {{ String(index + 1).padStart(2, '0') }}
                </span>
              </div>
            </div>

            <!-- Card Body -->
            <h4 class="text-xl font-black tracking-tight uppercase mb-2 group-hover:text-orange-400 transition-colors">
              {{ item.title }}
            </h4>
            <p class="text-xs sm:text-sm text-zinc-400 leading-relaxed italic mb-6 line-clamp-2">
              "{{ item.description || 'Tidak ada deskripsi tersedia.' }}"
            </p>
          </div>

          <!-- Card Footer (Price & Button) -->
          <div class="pt-4 flex items-center justify-between border-t border-zinc-800/60 mt-auto">
            <div>
              <span class="text-[10px] text-zinc-500 uppercase font-medium block">Harga</span>
              <span class="text-2xl font-bold text-white">{{ item.price }}</span>
            </div>
            <button class="px-5 py-2.5 bg-orange-600 hover:bg-orange-500 text-white text-xs font-bold rounded-full shadow-lg shadow-orange-600/20 transition-all active:scale-95">
              Pesan
            </button>
          </div>
        </div>
      </div>

    </div>
  </section>
</template>