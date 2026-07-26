<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import Header from '@/components/landingpage/header.vue';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from '@/components/ui/alert-dialog';

interface Table {
    id: number;
    name: string;
    identifier: string;
    table_number: number;
}

interface MenuItem {
    id: number;
    name: string;
    description: string;
    price: number;
    category: {
        id: number;
        name: string;
    };
}

const props = defineProps<{
    table: Table;
    menuItems: MenuItem[];
}>();

// --- LOGIKA KERANJANG (CART) ---
const cart = ref<Record<number, number>>({});
const isLoaded = ref(false);

onMounted(() => {
    const savedCart = localStorage.getItem(`cart_table_${props.table.table_number}`);
    if (savedCart) {
        try {
            cart.value = JSON.parse(savedCart);
        } catch (e) {
            cart.value = {};
        }
    }
    isLoaded.value = true;
});

// Watcher dengan Guard isLoaded
watch(cart, (newCart) => {
    if (isLoaded.value) {
        localStorage.setItem(`cart_table_${props.table.table_number}`, JSON.stringify(newCart));
    }
}, { deep: true });

const addToCart = (itemId: number) => {
    cart.value[itemId] = (cart.value[itemId] || 0) + 1;
};

const removeFromCart = (itemId: number) => {
    if (cart.value[itemId] > 1) {
        cart.value[itemId]--;
    } else {
        delete cart.value[itemId];
    }
};

const totalItems = computed(() => {
    return Object.values(cart.value).reduce((sum, qty) => sum + qty, 0);
});

const totalPrice = computed(() => {
    return props.menuItems.reduce((sum, item) => {
        const qty = cart.value[item.id] || 0;
        return sum + (item.price * qty);
    }, 0);
});

// Helper Format Rupiah
const formatRupiah = (val: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0
    }).format(val);
};

// --- LOGIKA CHECKOUT (INERTIA) ---
const checkoutForm = useForm({
    payment_method: 'cash', // Default
    items: [] as Array<{ id: number; quantity: number }>
});

const handleCheckout = () => {
    // Convert objek cart ke format array yang diterima backend validation
    checkoutForm.items = Object.entries(cart.value).map(([id, quantity]) => ({
        id: Number(id),
        quantity: Number(quantity)
    }));

    checkoutForm.post('/checkout', {
        onSuccess: () => {
            // Bersihkan local storage saat pesanan sukses dikirim
            localStorage.removeItem(`cart_table_${props.table.table_number}`);
            cart.value = {};
        },
        onError: (errors) => {
            alert(errors.table || errors.error || 'Gagal memproses pesanan.');
        }
    });
};

// --- LOGIKA UI ---
const groupedMenu = computed(() => {
    return props.menuItems.reduce((acc, item) => {
        const catName = item.category?.name || 'Lainnya';
        if (!acc[catName]) acc[catName] = [];
        acc[catName].push(item);
        return acc;
    }, {} as Record<string, MenuItem[]>);
});

const scrollToCategory = (id: string) => {
    const el = document.getElementById(id);
    if (el) window.scrollTo({ top: el.offsetTop - 100, behavior: 'smooth' });
};
</script>

<template>
    <div class="min-h-screen bg-[#050505] text-[#e5e5e5] font-sans antialiased selection:bg-orange-600/30">
        <Head :title="`Menu — Meja ${props.table.table_number}`" />
        <Header />

        <!-- Header Meja -->
        <header class="pt-28 pb-12 px-6 max-w-7xl mx-auto flex flex-col md:flex-row md:items-end justify-between gap-8">
            <div class="space-y-2">
                <div class="flex items-center gap-3">
                    <span class="h-[1px] w-8 bg-orange-600"></span>
                    <span class="text-[10px] uppercase tracking-[0.4em] text-orange-600 font-bold">D'Mario Selection</span>
                </div>
                <h1 class="text-6xl md:text-8xl font-black tracking-tighter uppercase leading-none">
                    Menu<span class="text-orange-600">.</span>
                </h1>
            </div>

            <div class="flex items-center gap-4 bg-white/[0.03] border border-white/[0.05] p-6 rounded-[2rem] backdrop-blur-md">
                <div class="w-12 h-12 rounded-full border border-orange-600/50 flex items-center justify-center">
                    <span class="text-orange-500 font-bold text-xs">No</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-[9px] uppercase tracking-widest text-gray-500">Meja Anda</span>
                    <span class="text-3xl font-black tracking-tighter text-white">{{ props.table.table_number }}</span>
                </div>
            </div>
        </header>

        <!-- Kategori Navigasi Sticky -->
        <nav class="sticky top-0 z-50 bg-[#050505]/90 backdrop-blur-xl border-b border-white/[0.03]">
            <div class="max-w-7xl mx-auto px-6 flex gap-10 py-6 overflow-x-auto [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">
                <button v-for="(_, category) in groupedMenu" :key="category"
                    @click="scrollToCategory(String(category))"
                    class="text-[11px] font-bold uppercase tracking-[0.3em] text-gray-500 hover:text-orange-500 transition-all duration-500 whitespace-nowrap relative group">
                    {{ category }}
                    <span class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-0 h-[2px] bg-orange-600 transition-all duration-500 group-hover:w-full"></span>
                </button>
            </div>
        </nav>

        <!-- Daftar Menu -->
        <main class="max-w-7xl mx-auto px-6 py-24 pb-40">
            <div v-for="(items, category) in groupedMenu" :key="category" :id="String(category)" class="mb-32">
                <div class="mb-16 space-y-4">
                    <h2 class="text-4xl font-bold tracking-tight uppercase">{{ category }}</h2>
                    <div class="h-1 w-20 bg-orange-600"></div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-24 gap-y-16">
                    <div v-for="item in items" :key="item.id"
                        class="group relative flex flex-col justify-between border-b border-white/[0.05] pb-8">
                        <div class="space-y-4">
                            <div class="flex justify-between items-start">
                                <h3 class="text-xl font-bold tracking-tight group-hover:text-orange-500 transition-colors duration-500 uppercase flex-1">
                                    {{ item.name }}
                                </h3>
                                <span class="text-orange-500 font-bold text-xl ml-6 tabular-nums">
                                    {{ formatRupiah(item.price) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 font-light leading-relaxed max-w-md italic">
                                {{ item.description }}
                            </p>
                        </div>

                        <div class="mt-8 flex items-center justify-between">
                            <span class="text-[9px] uppercase tracking-widest text-white/20">Chef Selection</span>

                            <div class="flex items-center gap-4">
                                <button v-if="cart[item.id]" @click="removeFromCart(item.id)"
                                    class="w-8 h-8 rounded-full border border-white/10 flex items-center justify-center hover:bg-white/10 transition-colors">
                                    -
                                </button>
                                <button @click="addToCart(item.id)"
                                    class="flex items-center gap-3 text-[10px] font-bold uppercase tracking-widest text-white bg-white/5 border border-white/10 px-6 py-3 rounded-full hover:bg-orange-600 transition-all duration-500">
                                    <span v-if="cart[item.id]" class="text-orange-200">{{ cart[item.id] }}x</span>
                                    <span>{{ cart[item.id] ? 'Add More' : 'Add to Order' }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Floating Cart Bar & Modal Checkout -->
        <transition enter-active-class="transition duration-500 ease-out"
            enter-from-class="transform translate-y-20 opacity-0"
            enter-to-class="transform translate-y-0 opacity-100"
            leave-active-class="transition duration-300 ease-in"
            leave-from-class="transform translate-y-0 opacity-100"
            leave-to-class="transform translate-y-20 opacity-0">

            <div v-if="totalItems > 0" class="fixed bottom-8 left-1/2 -translate-x-1/2 z-[60] w-full max-w-md px-6">
                <div class="bg-orange-600 rounded-[2rem] p-4 shadow-[0_20px_50px_rgba(234,88,12,0.4)] flex items-center justify-between border border-orange-400/30 backdrop-blur-md">
                    <div class="flex items-center gap-4">
                        <div class="bg-white text-orange-600 w-12 h-12 rounded-2xl flex items-center justify-center font-black text-xl">
                            {{ totalItems }}
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[9px] uppercase tracking-widest font-bold text-orange-200">Total Est. Price</span>
                            <span class="font-black text-lg text-white tracking-tighter">{{ formatRupiah(totalPrice) }}</span>
                        </div>
                    </div>

                    <!-- Scope Dialog Hanya Pada Tombol Checkout -->
                    <AlertDialog>
                        <AlertDialogTrigger as-child>
                            <button class="bg-black text-white px-8 py-4 rounded-2xl text-[10px] font-bold uppercase tracking-[0.2em] hover:bg-zinc-900 transition-all active:scale-95">
                                Checkout
                            </button>
                        </AlertDialogTrigger>
                        <AlertDialogContent class="bg-zinc-950 border-zinc-800 text-white">
                            <AlertDialogHeader>
                                <AlertDialogTitle>Konfirmasi Pesanan</AlertDialogTitle>
                                <AlertDialogDescription class="text-zinc-400">
                                    Pesanan Anda untuk <strong>Meja {{ props.table.table_number }}</strong> dengan total 
                                    <strong>{{ formatRupiah(totalPrice) }}</strong>.
                                </AlertDialogDescription>
                            </AlertDialogHeader>

                            <!-- Pilihan Metode Pembayaran -->
                            <div class="my-4 space-y-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-zinc-300">Pilih Metode Pembayaran</label>
                                <div class="grid grid-cols-3 gap-2">
                                    <button type="button" 
                                        @click="checkoutForm.payment_method = 'cash'"
                                        :class="['p-3 rounded-xl border text-xs font-bold transition-all', checkoutForm.payment_method === 'cash' ? 'border-orange-500 bg-orange-500/10 text-orange-500' : 'border-zinc-800 bg-zinc-900 text-zinc-400']">
                                        Tunai / Cash
                                    </button>
                                    <button type="button" 
                                        @click="checkoutForm.payment_method = 'qris'"
                                        :class="['p-3 rounded-xl border text-xs font-bold transition-all', checkoutForm.payment_method === 'qris' ? 'border-orange-500 bg-orange-500/10 text-orange-500' : 'border-zinc-800 bg-zinc-900 text-zinc-400']">
                                        QRIS
                                    </button>
                                    <button type="button" 
                                        @click="checkoutForm.payment_method = 'transfer'"
                                        :class="['p-3 rounded-xl border text-xs font-bold transition-all', checkoutForm.payment_method === 'transfer' ? 'border-orange-500 bg-orange-500/10 text-orange-500' : 'border-zinc-800 bg-zinc-900 text-zinc-400']">
                                        Transfer
                                    </button>
                                </div>
                            </div>

                            <AlertDialogFooter>
                                <AlertDialogCancel class="bg-zinc-900 text-white border-zinc-800 hover:bg-zinc-800">Batal</AlertDialogCancel>
                                <AlertDialogAction 
                                    @click="handleCheckout"
                                    :disabled="checkoutForm.processing"
                                    class="bg-orange-600 hover:bg-orange-500 text-white font-bold">
                                    {{ checkoutForm.processing ? 'Memproses...' : 'Kirim Pesanan' }}
                                </AlertDialogAction>
                            </AlertDialogFooter>
                        </AlertDialogContent>
                    </AlertDialog>
                </div>
            </div>
        </transition>
    </div>
</template>