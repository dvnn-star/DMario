<script setup lang="ts">
import Header from '@/components/landingpage/header.vue';
import { ref, computed } from 'vue';

interface GalleryItem {
  id: number;
  title: string;
  category: 'Interior' | 'Moment'; //
  image_path: string;
  size: 'large' | 'tall' | 'standard';
}

const galleryItems = ref<GalleryItem[]>([
  { id: 2, title: 'Golden Hour at Deck', category: 'Moment', size: 'standard', image_path: '/foto1.jpeg' },
  { id: 5, title: 'Main Dining Hall', category: 'Interior', size: 'standard', image_path: '/foto2.jpg' },
]);

const activeFilter = ref('All');

const filteredItems = computed(() => {
  if (activeFilter.value === 'All') return galleryItems.value;
  return galleryItems.value.filter(item => item.category === activeFilter.value);
});
</script>

<template>
    <Header/>
  <section id="gallery" class="py-24 bg-[#0a0a0a] overflow-hidden">
    <div class="max-w-7xl mx-auto px-6">
      
      <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-8">
        <div class="max-w-xl">
          <span class="text-orange-600 font-bold tracking-[0.3em] text-[10px] uppercase block mb-4">Visual Experience</span>
          <h2 class="text-5xl md:text-6xl font-serif text-white italic">Capturing <span class="not-italic text-gray-400">the Golden Hour.</span></h2>
        </div>
        
        <div class="flex gap-8 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500">
          <button v-for="cat in ['All', 'Interior', 'Moment']" 
                  :key="cat"
                  @click="activeFilter = cat"
                  :class="activeFilter === cat ? 'text-orange-500' : 'hover:text-white transition-colors'">
            {{ cat }}
          </button>
        </div>
      </div>

      <div class="columns-1 sm:columns-2 lg:columns-3 gap-6 space-y-6">
        <div v-for="item in filteredItems" 
             :key="item.id"
             class="relative group cursor-pointer overflow-hidden rounded-2xl bg-[#111111] break-inside-avoid shadow-2xl transition-all duration-700 hover:shadow-orange-900/10">
          
          <img :src="item.image_path" 
               :alt="item.title"
               class="w-full h-auto object-cover grayscale-[0.3] group-hover:grayscale-0 group-hover:scale-105 transition-all duration-[1.5s] ease-out" />

          <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-8">
            <span class="text-orange-500 text-[9px] font-bold uppercase tracking-[0.3em] mb-2 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
              {{ item.category }}
            </span>
            <h4 class="text-white font-serif italic text-xl transform translate-y-4 group-hover:translate-y-0 transition-transform duration-700 delay-75">
              {{ item.title }}
            </h4>
          </div>

          <div class="absolute inset-0 border border-white/0 group-hover:border-white/10 transition-colors duration-700 pointer-events-none rounded-2xl"></div>
        </div>
      </div>

    </div>
  </section>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap');
.font-serif { font-family: 'Playfair Display', serif; }
</style>