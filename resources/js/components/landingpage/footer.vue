<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Instagram, Contact, MapPin } from 'lucide-vue-next';
import { route } from 'ziggy-js';


</script>
<template>
  <footer class="pt-24 pb-12 bg-[#050505] border-t border-zinc-900 text-zinc-500">
    <div class="max-w-7xl mx-auto px-6">
      <div class="grid md:grid-cols-4 gap-12 mb-16">
        <div class="col-span-2">
          <h2 class="text-3xl font-black text-white mb-6 tracking-tighter">
            D'MARIO <span class="text-orange-600 italic">SUNSET.</span>
          </h2>
          <p class="max-w-sm mb-8 leading-relaxed">Destinasi kuliner terbaik untuk menikmati perpisahan hari dengan
            keindahan langit dan rasa yang tak terlupakan.</p>
        </div>

        <div>
          <h4 class="text-white font-bold mb-6">Navigasi</h4>
          <ul class="space-y-4 text-sm">
            <li>
              <Link :href="route('menu')" class="hover:text-orange-500 transition-colors cursor-pointer">Menu Digital
              </Link>
            </li>
            <li>
              <Link :href="route('reservation')" class="hover:text-orange-500 transition-colors cursor-pointer">
                Reservasi</Link>
            </li>
            <li class="flex items-center gap-2 text-orange-500 font-medium">
              <MapPin class="w-4 h-4" /> Lokasi Kami
            </li>
          </ul>
        </div>

        <div>
          <h4 class="text-white font-bold mb-6">Jam Operasional</h4>
          <ul class="space-y-4 text-sm">
            <li>Setiap Hari: 11:00 - 22:00</li>
            <li>Golden Hour: 17:30 - 18:30</li>
            <li class="text-orange-500 font-medium italic">*Waktu terbaik berkunjung</li>
          </ul>
        </div>
      </div>

      <div
        class="w-full h-80 rounded-2xl overflow-hidden  opacity-80 hover:grayscale-0 hover:opacity-100 transition-all duration-500 border border-zinc-800 mb-20">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.083744851614!2d104.2268653114319!3d1.0994764988853418!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d983fcfd524cb7%3A0xf06e408f7dfa278a!2sD&#39;Mario%20Sunset%20Resto!5e0!3m2!1sen!2sid!4v1770824444749!5m2!1sen!2sid"
          width="100%" height="450" style="border:0;" allowfullscreen="true" loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>

      <div class="pt-12 border-t border-zinc-900 text-center text-xs tracking-widest uppercase">
        © {{ new Date().getFullYear() }} D'Mario Sunset Resto. Built for the Golden Hour.
      </div>
    </div>
  </footer>
</template>