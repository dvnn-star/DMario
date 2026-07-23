<script setup lang="ts">
import { computed, ref } from 'vue';
import Header from '@/components/landingpage/header.vue';

interface MenuItem {
  id: number;
  name: string;
  description: string;
  price: number;
  image_path?: string;
  is_available: boolean;
  category: {
    name: string;
  };
}

const props = defineProps<{
  menuItems: MenuItem[]
}>();

const groupedMenu = computed(() => {
  if (!props.menuItems) return {};
  return props.menuItems.reduce((acc, item) => {
    const catName = item.category?.name || 'Lainnya';
    if (!acc[catName]) acc[catName] = [];
    acc[catName].push(item);
    return acc;
  }, {} as Record<string, MenuItem[]>);
});

const scrollToCategory = (id: string) => {
  const element = document.getElementById(id);
  if (element) {
    const offset = 100; // Offset sedikit lebih besar untuk kenyamanan visual
    const bodyRect = document.body.getBoundingClientRect().top;
    const elementRect = element.getBoundingClientRect().top;
    const elementPosition = elementRect - bodyRect;
    const offsetPosition = elementPosition - offset;

    window.scrollTo({
      top: offsetPosition,
      behavior: 'smooth'
    });
  }
};
const navRef = ref<HTMLElement | null>(null);

const scrollNav = (direction: 'left' | 'right') => {
  if (!navRef.value) return;
  const scrollAmount = 300; // Jarak geser setiap klik
  navRef.value.scrollBy({
    left: direction === 'left' ? -scrollAmount : scrollAmount,
    behavior: 'smooth'
  });
};
</script>

<template>
  <div class="min-h-screen bg-[#0d0d0d] text-white font-sans selection:bg-orange-500/30">
    <Header />

    <header class="pt-40 pb-20 px-6 text-center">
      <h1 class="text-5xl md:text-7xl font-bold tracking-tight">
        D'MARIO <span class="text-orange-600 italic">MENU.</span>
      </h1>
      <p class="mt-4 text-gray-400 max-w-2xl mx-auto font-light leading-relaxed">
        Jelajahi cita rasa otentik di bawah langit senja. Pilih hidangan favoritmu untuk melengkapi momen tak
        terlupakan.
      </p>
    </header>
    <nav class="sticky top-0 z-50 bg-[#0d0d0d]/90 backdrop-blur-xl border-b border-white/5 px-4 group">
      <div class="max-w-6xl mx-auto relative flex items-center">

        <button @click="scrollNav('left')"
          class="absolute left-[-50px] z-10 p-2 bg-[#0d0d0d]/80 border border-white/10 rounded-full hover:bg-orange-600 transition-all  group-hover:opacity-100 hidden md:block">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>

        <div ref="navRef"
          class="flex gap-4 overflow-x-auto py-6 no-scrollbar justify-start md:justify-center w-full scroll-smooth select-none touch-pan-x">
          <button v-for="(_, category) in groupedMenu" :key="category" @click="scrollToCategory(String(category))"
            class="px-6 py-2 rounded-full text-[10px] font-bold uppercase tracking-[0.2em] border border-white/10 hover:bg-orange-600 hover:border-orange-600 transition-all duration-300 whitespace-nowrap">
            {{ category }}
          </button>
        </div>

        <button @click="scrollNav('right')"
          class="absolute right-[-50px] z-10 p-2 bg-[#0d0d0d]/80 border border-white/10 rounded-full hover:bg-orange-600 transition-all opacity-0 group-hover:opacity-100 hidden md:block">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </button>

      </div>
    </nav>

    <main class="max-w-6xl mx-auto px-6 py-24">
      <div v-for="(items, category) in groupedMenu" :key="category" :id="String(category)" class="mb-32">

        <div class="mb-16">
          <span class="text-orange-600 text-[10px] font-bold tracking-[0.4em] uppercase">Selection</span>
          <h2 class="text-4xl font-bold mt-2 tracking-tight">{{ category }}</h2>
          <div class="w-16 h-1 bg-orange-600 mt-6"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
          <div v-for="item in items" :key="item.id"
            class="group bg-[#121212] rounded-3xl p-6 flex gap-6 hover:bg-[#161616] border border-white/5 transition-all duration-500 shadow-xl"
            :class="{ 'opacity-30 grayscale': !item.is_available }">
            <div class="relative w-32 h-32 md:w-40 md:h-40 flex-shrink-0 overflow-hidden rounded-2xl bg-zinc-900">
              <img v-if="item.image_path" :src="item.image_path"
                class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" />
              <div v-else
                class="w-full h-full flex items-center justify-center text-[10px] text-zinc-700 uppercase tracking-widest">
                No Image</div>

              <div v-if="!item.is_available" class="absolute inset-0 bg-black/80 flex items-center justify-center">
                <span
                  class="text-[10px] font-black uppercase tracking-[0.2em] border-2 border-orange-600 text-orange-600 px-3 py-1 -rotate-12">Sold
                  Out</span>
              </div>
            </div>

            <div class="flex flex-col justify-between py-1 flex-1">
              <div>
                <h3 class="font-bold text-xl group-hover:text-orange-500 transition-colors uppercase tracking-tight">
                  {{ item.name }}
                </h3>
                <p class="text-sm text-gray-500 mt-3 line-clamp-3 leading-relaxed font-light">
                  {{ item.description }}
                </p>
              </div>

              <div class="mt-6 flex items-center gap-2">
                <span class="text-2xl font-black text-white tracking-tighter">
                  {{ (item.price / 1000).toFixed(0) }}
                </span>
                <span class="text-xs text-orange-600 font-bold uppercase tracking-widest">K IDR</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <footer class="py-24 text-center border-t border-white/5 bg-[#080808]">
      <div class="opacity-30 hover:opacity-100 transition-opacity duration-500">
        <p class="text-[10px] text-gray-500 tracking-[0.5em] uppercase">D'Mario Sunset Resto & Cafe</p>
        <p class="text-[9px] text-gray-700 mt-4 tracking-widest">EST. 2024 — TANJUNG UBAN</p>
      </div>
    </footer>
  </div>
</template>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
  display: none;
}

.no-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}

/* Smooth Scroll behavior global */
html {
  scroll-behavior: smooth;
}
</style>