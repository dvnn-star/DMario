<script setup lang="ts">
import { ref, computed, watch, onMounted } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import Header from '@/components/landingpage/header.vue';
import { useForm } from '@inertiajs/vue3';
import JsonLd from '@/components/JsonLd.vue';
import AlertDialog from '@/components/ui/alert-dialog/AlertDialog.vue';
import AlertDialogTrigger from '@/components/ui/alert-dialog/AlertDialogTrigger.vue';
import AlertDialogContent from '@/components/ui/alert-dialog/AlertDialogContent.vue';
import AlertDialogHeader from '@/components/ui/alert-dialog/AlertDialogHeader.vue';
import AlertDialogTitle from '@/components/ui/alert-dialog/AlertDialogTitle.vue';
import AlertDialogDescription from '@/components/ui/alert-dialog/AlertDialogDescription.vue';
import AlertDialogFooter from '@/components/ui/alert-dialog/AlertDialogFooter.vue';
import AlertDialogCancel from '@/components/ui/alert-dialog/AlertDialogCancel.vue';
import AlertDialogAction from '@/components/ui/alert-dialog/AlertDialogAction.vue';
import { route } from 'ziggy-js';

interface Tables {
  id: number;
  table_number: string;
  qr_code_path: string;
  status: string; // 'available', 'reserved', 'occupied'
}

interface ExistingReservation {
  table_id: number;
  reservation_time: string; // Format: "YYYY-MM-DD HH:MM:SS"
}

const props = defineProps<{
  Tables: Tables[];
  existingReservations?: ExistingReservation[]; // Props opsional dari backend jika ada
}>();

const today = new Date().toISOString().split('T')[0];

const nextWeek = new Date();
nextWeek.setDate(nextWeek.getDate() + 7);
const maxDate = nextWeek.toISOString().split('T')[0];

const timeSlots = [
  '10:00', '11:00', '12:00', '13:00', '14:00', 
  '15:00', '16:00', '17:00', '18:00', '19:00', 
  '20:00', '21:00', '22:00'
];

const selectedTable = ref<number | null>(null);

// State menyimpan meja ter-booking spesifik per slot waktu: { "2026-07-23_19:00": [1, 3] }
const bookedTablesMap = ref<Record<string, number[]>>({});

const reservationForm = useForm({
  name: '',
  guests: 1,
  time: '',
  date: '',
  table_id: null as number | null,
});

// Key kombinasi tanggal & jam aktif saat ini
const currentSlotKey = computed(() => {
  if (reservationForm.date && reservationForm.time) {
    return `${reservationForm.date}_${reservationForm.time}`;
  }
  return null;
});

// Pengecekan apakah meja di-disable khusus pada slot waktu pilihan user
const isTableDisabled = (table: Tables) => {
  // 1. Status permanen dari DB (misal sedang maintenance)
  if (table.status !== 'available') return true;

  // 2. Pengecekan ketersediaan meja khusus pada Tanggal & Jam yang dipilih
  if (currentSlotKey.value) {
    const bookedList = bookedTablesMap.value[currentSlotKey.value] || [];
    if (bookedList.includes(table.id)) {
      return true;
    }
  }

  return false;
};

// Jika user mengubah tanggal/jam dan meja yang terpilih ternyata ter-booking di slot baru, batalkan pilihan
watch([() => reservationForm.date, () => reservationForm.time], () => {
  if (selectedTable.value) {
    const targetTable = props.Tables.find(t => t.id === selectedTable.value);
    if (targetTable && isTableDisabled(targetTable)) {
      selectedTable.value = null;
    }
  }
});

// Populate data reservasi eksisting jika backend mengirimkannya di props awal
onMounted(() => {
  if (props.existingReservations) {
    props.existingReservations.forEach(res => {
      const [date, fullTime] = res.reservation_time.split(' ');
      if (date && fullTime) {
        const time = fullTime.substring(0, 5); // Ambil format "HH:MM"
        const key = `${date}_${time}`;
        if (!bookedTablesMap.value[key]) {
          bookedTablesMap.value[key] = [];
        }
        bookedTablesMap.value[key].push(res.table_id);
      }
    });
  }
});

// Alert State
const customAlert = ref<{
  show: boolean;
  title: string;
  message: string;
  type: 'success' | 'error' | 'warning';
  callback?: () => void;
}>({
  show: false,
  title: '',
  message: '',
  type: 'success',
});

function showAlert(title: string, message: string, type: 'success' | 'error' | 'warning', callback?: () => void) {
  customAlert.value = { show: true, title, message, type, callback };
}

function handleCloseAlert() {
  const cb = customAlert.value.callback;
  customAlert.value.show = false;
  if (cb) cb();
}

function StoreForm() {
  if (!reservationForm.name || !reservationForm.time || !reservationForm.date || !selectedTable.value) {
    showAlert('Data Belum Lengkap', 'Mohon lengkapi semua field sebelum melanjutkan konfirmasi.', 'warning');
    return;
  }

  reservationForm.table_id = selectedTable.value;
  
  const targetTable = props.Tables.find(t => t.id === selectedTable.value);
  const tableNumberDisplay = targetTable ? targetTable.table_number : selectedTable.value;

  reservationForm.post(route('reservation.store'), {
    preserveScroll: true,
    onSuccess: () => {
      const PhoneNumber = '6282268822307';
      const Message = `Halo, Saya ingin mengkonfirmasikan bahwa reservasi saya sudah selesai dengan detail:\n- Meja: ${tableNumberDisplay}\n- Tanggal: ${reservationForm.date}\n- Jam: ${reservationForm.time} WIB\n- Nama: ${reservationForm.name}\n- Jumlah Tamu: ${reservationForm.guests} Orang.\n\nTerima kasih!`;
      const URL = `https://wa.me/${PhoneNumber}?text=${encodeURIComponent(Message)}`;

      showAlert(
        'Reservasi Berhasil!', 
        'Data reservasi kamu sudah tersimpan. Klik tombol di bawah untuk mengonfirmasi ke staf kami via WhatsApp.', 
        'success',
        () => {
          window.open(URL, '_blank');
          reservationForm.reset();
          selectedTable.value = null;
        }
      );
    },
    onError: (errors) => {
      // PERBAIKAN UTAMA: Daftarkan meja ke slot ini sebagai 'Booked' jika gagal
      if (selectedTable.value && currentSlotKey.value) {
        const key = currentSlotKey.value;
        if (!bookedTablesMap.value[key]) {
          bookedTablesMap.value[key] = [];
        }
        if (!bookedTablesMap.value[key].includes(selectedTable.value)) {
          bookedTablesMap.value[key].push(selectedTable.value);
        }
        // Batalkan seleksi meja karena meja tersebut kini nonaktif
        selectedTable.value = null;
      }

      const errorMsg = errors.table_id || errors.reservation_time || 'Meja sudah dipesan orang lain pada jam dan tanggal tersebut. Silakan pilih meja lain.';
      showAlert('Reservasi Gagal', errorMsg, 'error');
    }
  });
}

const selectTable = (table: Tables) => {
  if (!isTableDisabled(table)) {
    selectedTable.value = table.id;
  }
};

const appUrl = usePage().props.appUrl as string;

const breadcrumbSchema = computed(() => ({
  '@context': 'https://schema.org',
  '@type': 'BreadcrumbList',
  'itemListElement': [
    { '@type': 'ListItem', 'position': 1, 'name': 'Home', 'item': appUrl },
    { '@type': 'ListItem', 'position': 2, 'name': 'Reservasi' },
  ],
}));
</script>

<template>
  <AlertDialog>
    <div class="min-h-screen bg-[#0d0d0d] text-white font-sans relative">
      <Head title="Reservasi Meja" />
      <JsonLd :schema="breadcrumbSchema" />
      <Header />

      <main class="max-w-6xl mx-auto px-6 pt-32 pb-20">
        <div class="mb-12">
          <h1 class="text-4xl md:text-6xl font-bold uppercase tracking-tighter">
            Pesan <span class="text-orange-600">Meja Anda.</span>
          </h1>
          <p class="text-gray-500 mt-4 max-w-xl font-light">
            Pilih lokasi terbaik untuk menikmati senja. Pastikan waktu dan jumlah tamu sesuai untuk kenyamanan maksimal.
          </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

          <!-- Pemilihan Meja -->
          <div class="lg:col-span-2">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-sm font-bold uppercase tracking-widest text-orange-500">
                Pilih Nomor Meja 
                <span v-if="reservationForm.time && reservationForm.date" class="text-xs text-gray-400 font-normal">
                  ({{ reservationForm.date }} @ {{ reservationForm.time }} WIB)
                </span>
              </h2>
              <div class="flex gap-4 text-[10px] uppercase tracking-widest text-gray-500">
                <div class="flex items-center gap-2"><span class="w-2 h-2 bg-orange-600 rounded-full"></span> Terpilih</div>
                <div class="flex items-center gap-2"><span class="w-2 h-2 bg-white/10 rounded-full"></span> Tersedia</div>
                <div class="flex items-center gap-2"><span class="w-2 h-2 bg-red-900/50 rounded-full"></span> Tidak Tersedia</div>
              </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
              <button 
                v-for="table in Tables" 
                :key="table.id" 
                @click="selectTable(table)"
                :disabled="isTableDisabled(table)" 
                :class="[
                  'relative aspect-square rounded-2xl border transition-all duration-500 flex flex-col items-center justify-center gap-1 group',
                  selectedTable === table.id
                    ? 'bg-orange-600 border-orange-600 shadow-[0_0_30px_rgba(234,88,12,0.3)] scale-105 z-10'
                    : 'bg-[#161616] border-white/5 hover:border-orange-500/40',
                  isTableDisabled(table) ? 'opacity-20 grayscale cursor-not-allowed border-red-900/20' : ''
                ]"
              >
                <span class="text-[10px] font-bold opacity-40 uppercase tracking-tighter">Table</span>
                <span class="text-3xl font-black">{{ table.table_number }}</span>

                <!-- Badge Booked jika meja terisi/disabled -->
                <div v-if="isTableDisabled(table)" class="absolute inset-0 flex items-center justify-center bg-black/60 rounded-2xl">
                  <span class="text-[10px] font-bold tracking-widest uppercase rotate-12 border border-red-500/40 text-red-400 px-2 py-0.5 rounded">
                    Booked
                  </span>
                </div>
              </button>
            </div>
          </div>

          <!-- Form Detail Reservasi -->
          <div class="bg-[#161616] rounded-3xl p-8 border border-white/5 h-fit sticky top-32">
            <h3 class="text-xl font-bold mb-8">Detail Reservasi</h3>

            <form @submit.prevent class="space-y-6">
              <div>
                <label for="reservation-name" class="text-[10px] uppercase tracking-[0.2em] text-gray-500 mb-2 block">Nama Lengkap</label>
                <input 
                  id="reservation-name"
                  v-model="reservationForm.name" 
                  type="text"
                  class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 focus:border-orange-600 focus:ring-0 transition-all outline-none text-white"
                  placeholder="Atas nama..."
                >
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label for="reservation-guests" class="text-[10px] uppercase tracking-[0.2em] text-gray-500 mb-2 block">Jumlah Tamu</label>
                  <input 
                    id="reservation-guests"
                    v-model="reservationForm.guests" 
                    type="number"
                    class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 outline-none focus:border-orange-600 transition-all text-white" 
                    min="1"
                  >
                </div>

                <div>
                  <label for="reservation-date" class="text-[10px] uppercase tracking-[0.2em] text-gray-500 mb-2 block">Tanggal</label>
                  <input 
                    id="reservation-date"
                    :min="today" 
                    :max="maxDate" 
                    v-model="reservationForm.date" 
                    type="date"
                    class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 outline-none text-white [color-scheme:dark] focus:border-orange-600 transition-all"
                  >
                </div>
              </div>

              <!-- Selection Slot Waktu Per Jam -->
              <div>
                <label for="reservation-time" class="text-[10px] uppercase tracking-[0.2em] text-gray-500 mb-2 block">Waktu Reservasi (Slot 1 Jam)</label>
                <select 
                  id="reservation-time"
                  v-model="reservationForm.time" 
                  class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 outline-none text-white focus:border-orange-600 transition-all cursor-pointer"
                >
                  <option value="" disabled class="bg-[#161616] text-gray-400">-- Pilih Jam --</option>
                  <option 
                    v-for="slot in timeSlots" 
                    :key="slot" 
                    :value="slot" 
                    class="bg-[#161616] text-white py-2"
                  >
                    {{ slot }} WIB
                  </option>
                </select>
              </div>

              <div class="pt-6 border-t border-white/5">
                <div class="flex justify-between mb-4 text-sm font-light">
                  <span class="text-gray-500">Meja Terpilih:</span>
                  <span class="text-orange-500 font-bold">
                    {{ selectedTable ? 'Meja ' + Tables.find(t => t.id === selectedTable)?.table_number : 'Belum memilih' }}
                  </span>
                </div>

                <AlertDialogTrigger as-child>
                  <button 
                    :disabled="!selectedTable || !reservationForm.time || !reservationForm.date || !reservationForm.name"
                    class="w-full bg-orange-600 hover:bg-orange-700 disabled:bg-gray-800 disabled:text-gray-600 text-white font-bold py-4 rounded-xl transition-all shadow-lg shadow-orange-600/20 uppercase tracking-widest text-xs"
                  >
                    Konfirmasi Reservasi
                  </button>
                </AlertDialogTrigger>

                <AlertDialogContent class="bg-[#161616] border-white/10 text-white">
                  <AlertDialogHeader>
                    <AlertDialogTitle class="text-lg font-bold">Apakah Sudah Yakin?</AlertDialogTitle>
                    <AlertDialogDescription class="text-gray-400 text-sm">
                      Lanjut ke konfirmasi dan menyimpan data reservasi Anda?
                    </AlertDialogDescription>
                  </AlertDialogHeader>
                  <AlertDialogFooter>
                    <AlertDialogCancel class="bg-transparent border border-white/10 text-gray-300 hover:bg-white/5">Batal</AlertDialogCancel>
                    <AlertDialogAction @click="StoreForm()" class="bg-orange-600 hover:bg-orange-700 text-white">Lanjutkan</AlertDialogAction>
                  </AlertDialogFooter>
                </AlertDialogContent>
              </div>
            </form>
          </div>

        </div>
      </main>

      <!-- Custom Alert Modal -->
      <Transition
        enter-active-class="transition duration-200 ease-out"
        enter-from-class="opacity-0 scale-95"
        enter-to-class="opacity-100 scale-100"
        leave-active-class="transition duration-150 ease-in"
        leave-from-class="opacity-100 scale-100"
        leave-to-class="opacity-0 scale-95"
      >
        <div v-if="customAlert.show" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
          <div class="bg-[#161616] border border-white/10 rounded-2xl max-w-sm w-full p-6 shadow-2xl text-center space-y-4">
            
            <div class="mx-auto flex items-center justify-center w-12 h-12 rounded-full"
                 :class="{
                   'bg-emerald-500/10 text-emerald-500': customAlert.type === 'success',
                   'bg-rose-500/10 text-rose-500': customAlert.type === 'error',
                   'bg-amber-500/10 text-amber-500': customAlert.type === 'warning'
                 }">
              <svg v-if="customAlert.type === 'success'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
              <svg v-else-if="customAlert.type === 'error'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
              <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            </div>

            <div>
              <h3 class="text-lg font-bold text-white">{{ customAlert.title }}</h3>
              <p class="text-xs text-gray-400 mt-2 leading-relaxed">{{ customAlert.message }}</p>
            </div>

            <button 
              @click="handleCloseAlert"
              class="w-full py-3 px-4 rounded-xl text-xs font-bold uppercase tracking-widest text-white transition-all"
              :class="{
                'bg-emerald-600 hover:bg-emerald-700': customAlert.type === 'success',
                'bg-rose-600 hover:bg-rose-700': customAlert.type === 'error',
                'bg-amber-600 hover:bg-amber-700': customAlert.type === 'warning'
              }"
            >
              {{ customAlert.type === 'success' ? 'Lanjut ke WhatsApp' : 'Mengerti' }}
            </button>
          </div>
        </div>
      </Transition>

    </div>
  </AlertDialog>
</template>

<style scoped>
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
</style>