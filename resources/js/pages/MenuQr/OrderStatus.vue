<script setup lang="ts">
import { onMounted, onUnmounted, computed } from 'vue';
import { Head, router, Link } from '@inertiajs/vue3';
import Header from '@/components/landingpage/header.vue';

interface MenuItem {
    id: number;
    name: string;
    price: number;
}

interface OrderDetail {
    id: number;
    quantity: number;
    price: number;
    note?: string;
    menu_item: MenuItem;
}

interface Table {
    id: number;
    table_number: number;
}

interface Order {
    id: number;
    status: 'pending' | 'processing' | 'completed' | 'cancelled';
    payment_method: string;
    total_price: number;
    created_at: string;
    table: Table;
    order_details: OrderDetail[];
}

const props = defineProps<{
    order: Order;
}>();

// --- AUTO-POLLING STATUS PESANAN ---
// Halaman akan otomatis mengecek status terbaru ke server setiap 10 detik
let pollInterval: ReturnType<typeof setInterval> | null = null;

onMounted(() => {
    pollInterval = setInterval(() => {
        // Jangan poll jika pesanan sudah selesai atau dibatalkan
        if (props.order.status !== 'completed' && props.order.status !== 'cancelled') {
            router.reload({ only: ['order'] });
        }
    }, 10000);
});

onUnmounted(() => {
    if (pollInterval) clearInterval(pollInterval);
});

// --- HELPER UI ---
const formatRupiah = (val: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0
    }).format(val);
};

const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric',
        month: 'short',
        hour: '2-digit',
        minute: '2-digit'
    }).format(date);
};

const statusInfo = computed(() => {
    switch (props.order.status) {
        case 'pending':
            return {
                title: 'Pesanan Diterima',
                desc: 'Pesanan Anda telah masuk dan menunggu konfirmasi kasir.',
                color: 'text-amber-500',
                bgColor: 'bg-amber-500/10',
                borderColor: 'border-amber-500/30',
                step: 1
            };
        case 'processing':
            return {
                title: 'Sedang Disiapkan',
                desc: 'Koki kami sedang menyajikan makanan dan minuman Anda.',
                color: 'text-blue-500',
                bgColor: 'bg-blue-500/10',
                borderColor: 'border-blue-500/30',
                step: 2
            };
        case 'completed':
            return {
                title: 'Pesanan Selesai',
                desc: 'Selamat menikmati hidangan Anda! Terima kasih telah berkunjung.',
                color: 'text-emerald-500',
                bgColor: 'bg-emerald-500/10',
                borderColor: 'border-emerald-500/30',
                step: 3
            };
        case 'cancelled':
            return {
                title: 'Pesanan Dibatalkan',
                desc: 'Mohon maaf, pesanan ini telah dibatalkan oleh pihak resto.',
                color: 'text-rose-500',
                bgColor: 'bg-rose-500/10',
                borderColor: 'border-rose-500/30',
                step: 0
            };
        default:
            return {
                title: 'Menunggu Status',
                desc: 'Memuat status pesanan...',
                color: 'text-zinc-400',
                bgColor: 'bg-zinc-800',
                borderColor: 'border-zinc-700',
                step: 1
            };
    }
});
</script>

<template>
    <Head title="Status Pesanan" />

    <div class="min-h-screen bg-[#050505] text-[#e5e5e5] font-sans antialiased selection:bg-orange-600/30 pb-20">
        <Header />

        <main class="pt-28 px-6 max-w-3xl mx-auto space-y-8">
            <!-- Header Status -->
            <div class="text-center space-y-3">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border bg-white/[0.02] border-white/10 text-xs font-bold uppercase tracking-widest text-orange-500">
                    Order #{{ props.order.id }} &bull; Meja {{ props.order.table?.table_number }}
                </div>
                <h1 class="text-4xl md:text-5xl font-black uppercase tracking-tight">Status Pesanan</h1>
                <p class="text-zinc-500 text-xs">{{ formatDate(props.order.created_at) }}</p>
            </div>

            <!-- Card Indicator Status Utama -->
            <div :class="['p-6 md:p-8 rounded-[2rem] border backdrop-blur-md space-y-6', statusInfo.bgColor, statusInfo.borderColor]">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 :class="['text-2xl font-black uppercase tracking-tight', statusInfo.color]">
                            {{ statusInfo.title }}
                        </h2>
                        <p class="text-sm text-zinc-300 mt-1 max-w-md">
                            {{ statusInfo.desc }}
                        </p>
                    </div>
                    <div v-if="props.order.status !== 'completed' && props.order.status !== 'cancelled'" class="relative flex h-4 w-4">
                        <span :class="['animate-ping absolute inline-flex h-full w-full rounded-full opacity-75', statusInfo.color.replace('text-', 'bg-')]"></span>
                        <span :class="['relative inline-flex rounded-full h-4 w-4', statusInfo.color.replace('text-', 'bg-')]"></span>
                    </div>
                </div>

                <!-- Progress Tracker sederhada -->
                <div v-if="props.order.status !== 'cancelled'" class="grid grid-cols-3 gap-2 pt-4 border-t border-white/10">
                    <div :class="['h-2 rounded-full transition-all duration-500', statusInfo.step >= 1 ? 'bg-orange-500' : 'bg-white/10']"></div>
                    <div :class="['h-2 rounded-full transition-all duration-500', statusInfo.step >= 2 ? 'bg-orange-500' : 'bg-white/10']"></div>
                    <div :class="['h-2 rounded-full transition-all duration-500', statusInfo.step >= 3 ? 'bg-emerald-500' : 'bg-white/10']"></div>
                </div>
            </div>

            <!-- Detail Rincian Item Pesanan -->
            <div class="bg-white/[0.02] border border-white/[0.05] rounded-[2rem] p-6 md:p-8 space-y-6">
                <h3 class="text-lg font-bold uppercase tracking-wider text-white border-b border-white/[0.05] pb-4">
                    Rincian Item
                </h3>

                <div class="space-y-4">
                    <div v-for="item in props.order.order_details" :key="item.id" class="flex justify-between items-start text-sm">
                        <div class="space-y-1">
                            <div class="font-bold text-white flex items-center gap-2">
                                <span class="text-orange-500">{{ item.quantity }}x</span>
                                <span>{{ item.menu_item?.name ?? 'Item Menu' }}</span>
                            </div>
                            <p v-if="item.note" class="text-xs text-zinc-500 italic">
                                Note: {{ item.note }}
                            </p>
                        </div>
                        <div class="font-mono text-zinc-400">
                            {{ formatRupiah(item.price * item.quantity) }}
                        </div>
                    </div>
                </div>

                <div class="border-t border-white/[0.05] pt-4 space-y-2">
                    <div class="flex justify-between text-xs text-zinc-400">
                        <span>Metode Pembayaran</span>
                        <span class="uppercase font-bold text-white">{{ props.order.payment_method }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-black text-white pt-2 border-t border-white/[0.05]">
                        <span>Total Bayar</span>
                        <span class="text-orange-500 font-mono">{{ formatRupiah(props.order.total_price) }}</span>
                    </div>
                </div>
            </div>

            <!-- Navigation Button -->
            <div class="text-center pt-4">
                <button 
                    @click="router.reload()" 
                    class="bg-white/5 border border-white/10 hover:bg-white/10 text-white text-xs uppercase font-bold tracking-widest px-6 py-3 rounded-xl transition-all mr-2">
                    Refresh Status
                </button>
            </div>
        </main>
    </div>
</template>