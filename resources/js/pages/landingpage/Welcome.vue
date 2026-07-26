<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import Header from '@/components/landingpage/header.vue';
import Hero from '@/components/landingpage/Hero.vue';
import About from '@/components/landingpage/about.vue';
import Recommend from '@/components/landingpage/Recommend.vue';
import Footer from '@/components/landingpage/footer.vue';
import JsonLd from '@/components/JsonLd.vue';
import { computed } from 'vue';

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

const appUrl = usePage().props.appUrl as string;

// JSON-LD: Restaurant + WebSite schema
const restaurantSchema = computed(() => ({
  '@context': 'https://schema.org',
  '@graph': [
    {
      '@type': 'Restaurant',
      '@id': `${appUrl}/#restaurant`,
      'name': "D'Mario Sunset Resto & Cafe",
      'image': `${appUrl}/dmario.jpeg`,
      'url': appUrl,
      'telephone': '+6282268822307',
      'servesCuisine': ['Indonesian', 'Western'],
      'address': {
        '@type': 'PostalAddress',
        'addressLocality': 'Tanjung Uban',
        'addressRegion': 'Kepulauan Riau',
        'addressCountry': 'ID',
      },
      'openingHoursSpecification': {
        '@type': 'OpeningHoursSpecification',
        'dayOfWeek': ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
        'opens': '11:00',
        'closes': '22:00',
      },
      'hasMenu': `${appUrl}/menu`,
      'acceptsReservations': 'True',
    },
    {
      '@type': 'WebSite',
      '@id': `${appUrl}/#website`,
      'name': "D'Mario Sunset Resto & Cafe",
      'url': appUrl,
    },
  ],
}));
</script>

<template>
  <Head title="D'Mario Sunset Resto & Cafe — Tanjung Uban" />
  <JsonLd :schema="restaurantSchema" />

  <Header />
  <Hero />
  <About />
  
  <!-- 3. Passing props 'bestSellers' ke komponen Recommend -->
  <Recommend :best-sellers="bestSellers" />
  
  <Footer />
</template>