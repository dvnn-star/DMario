<script setup lang="ts">
import Header from '@/components/landingpage/header.vue';
import Hero from '@/components/landingpage/Hero.vue';
import About from '@/components/landingpage/about.vue';
import Recommend from '@/components/landingpage/Recommend.vue';
import Footer from '@/components/landingpage/footer.vue';

// 1. Tipe data sesuai dengan yang dikirim dari Controller
interface MenuItem {
  id: string | number;
  title: string;
  tag: string;
  description: string | null;
  price: string;
  image: string;
}

// 2. Tangkap props 'bestSellers' yang dikirim dari Inertia Controller
defineProps<{
  bestSellers: MenuItem[];
}>();
</script>

<template>
  <Header />
  <Hero />
  <About />
  
  <!-- 3. Passing props 'bestSellers' ke komponen Recommend -->
  <Recommend :best-sellers="bestSellers" />
  
  <Footer />
</template>