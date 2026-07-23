<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard, StockMenu } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
import { Pencil, Trash2, Utensils, Plus } from 'lucide-vue-next';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Stock Menu',
        href: StockMenu().url,
    },
];

const props = defineProps<{
    MenuItems: {
        id: number;
        name: string;
        price: number;
        description: string;
    }[];
}>();


const Update = (Item:any) =>{
    console.log('Update', Item);
};
const Delete = (Item:any) =>{
    console.log('Delete', Item);
};
console.log(props.MenuItems);
</script>

<template>
    <Head title="Stock Menu" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-slate-100 tracking-tight">Daftar Menu</h1>
                    <p class="text-slate-500 mt-1">Kelola stok dan harga menu restoran Anda.</p>
                </div>
                <button class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2.5 rounded-xl font-bold transition-all shadow-lg shadow-orange-200 flex items-center gap-2 w-fit">
                    <Plus :size="20" /> Tambah Menu
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <div v-for="item in props.MenuItems" :key="item.id"
                    class="group bg-white rounded-[2rem] overflow-hidden border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    
                    <div class="relative h-48 bg-slate-100 overflow-hidden">
                        <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-orange-100 to-orange-50 text-orange-300">
                            <Utensils :size="48" stroke-width="1.5" />
                        </div>
                        
                        <div class="absolute top-3 right-3 flex flex-col gap-2 translate-x-12 group-hover:translate-x-0 transition-transform duration-300">
                            <button @click="Update( item.id)" 
                                class="p-2.5 bg-white/90 backdrop-blur-md text-blue-600 rounded-xl shadow-sm hover:bg-blue-600 hover:text-white transition-all">
                                <Pencil :size="18" />
                            </button>
                            <button @click="Delete( item.id)"
                                class="p-2.5 bg-white/90 backdrop-blur-md text-red-600 rounded-xl shadow-sm hover:bg-red-600 hover:text-white transition-all">
                                <Trash2 :size="18" />
                            </button>
                        </div>

                        <div class="absolute bottom-3 left-3">
                            <span class="bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-full text-emerald-700 font-black text-sm shadow-sm">
                                Rp.{{ item.price }}
                            </span>
                        </div>
                    </div>

                    <div class="p-5">
                        <div class="flex justify-between items-start mb-2">
                            <h2 class="text-lg font-bold text-slate-800 group-hover:text-orange-600 transition-colors">
                                {{ item.name }}
                            </h2>
                        </div>
                        <p class="text-sm text-slate-500 line-clamp-2 leading-relaxed h-10">
                            {{ item.description || 'Deskripsi rasa yang belum terdefinisikan...' }}
                        </p>
                        
                        <div class="mt-4 flex items-center justify-between">
                            <div class="flex -space-x-2">
                         
                            </div>
                            <button class="text-xs font-bold text-slate-400 uppercase tracking-widest hover:text-orange-500 transition-colors">
                                Details
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="!props.MenuItems.length"
                class="flex flex-col items-center justify-center py-20 bg-slate-50 rounded-[3rem] border-2 border-dashed border-slate-200">
                <div class="p-6 bg-white rounded-full shadow-sm mb-4">
                    <Utensils :size="40" class="text-slate-300" />
                </div>
                <h3 class="text-xl font-bold text-slate-800">Dapur Kosong</h3>
                <p class="text-slate-500">Belum ada menu yang terdaftar di sini.</p>
            </div>
        </div>
    </AppLayout>
</template>