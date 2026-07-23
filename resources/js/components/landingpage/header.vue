<script setup lang="ts">
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const isMenuOpen = ref(false);

const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
};
</script>

<template>
    <header class="fixed top-0 left-0 z-50 w-full bg-black/40 backdrop-blur-md border-b border-white/10 text-white">
        <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 sm:px-6">
            <!-- Brand -->
            <Link :href="route('landingpage')" class="text-xl font-serif font-bold tracking-tight text-white hover:opacity-90 transition-opacity">
                Dmario
            </Link>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex items-center gap-8 text-sm font-medium capitalize">
                <Link :href="route('landingpage')" class="hover:text-gray-300 transition-colors">Tentang Kami</Link>
                <Link :href="route('menu')" class="hover:text-gray-300 transition-colors">Menu</Link>
                <Link :href="route('reservation')" class="hover:text-gray-300 transition-colors">Reservation</Link>
                <Link :href="route('gallery')" class="hover:text-gray-300 transition-colors">Gallery</Link>
            </nav>

            <!-- Mobile Menu Toggle Button -->
            <button
                @click="toggleMenu"
                type="button"
                class="inline-flex md:hidden items-center justify-center p-2 rounded-md text-gray-200 hover:text-white hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                aria-controls="mobile-menu"
                :aria-expanded="isMenuOpen"
            >
                <span class="sr-only">Toggle Menu</span>
                <!-- Dynamic SVG Icon (Hamburger / Close) -->
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path v-if="!isMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Mobile Menu Dropdown -->
        <Transition
            enter-active-class="transition duration-150 ease-out"
            enter-from-class="opacity-0 -translate-y-2"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition duration-100 ease-in"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 -translate-y-2"
        >
            <div v-if="isMenuOpen" id="mobile-menu" class="md:hidden bg-black/90 backdrop-blur-xl border-b border-white/10 px-4 pt-2 pb-4 space-y-1">
                <Link
                    :href="route('landingpage')"
                    class="block px-3 py-2 rounded-md text-base font-medium hover:bg-white/10 transition-colors"
                    @click="isMenuOpen = false"
                >
                    Tentang Kami
                </Link>
                <Link
                    :href="route('menu')"
                    class="block px-3 py-2 rounded-md text-base font-medium hover:bg-white/10 transition-colors"
                    @click="isMenuOpen = false"
                >
                    Menu
                </Link>
                <Link
                    :href="route('reservation')"
                    class="block px-3 py-2 rounded-md text-base font-medium hover:bg-white/10 transition-colors"
                    @click="isMenuOpen = false"
                >
                    Reservation
                </Link>
                <Link
                    :href="route('gallery')"
                    class="block px-3 py-2 rounded-md text-base font-medium hover:bg-white/10 transition-colors"
                    @click="isMenuOpen = false"
                >
                    Gallery
                </Link>
            </div>
        </Transition>
    </header>
</template>