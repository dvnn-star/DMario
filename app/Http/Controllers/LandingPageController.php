<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\reservation;
use App\Models\table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class LandingPageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bestSellers = MenuItem::query()
            ->where('is_available', true)
            ->where('is_recommended', true) // Filter hanya item yang direkomendasikan
            ->latest()                        // Mengurutkan dari yang terbaru
            ->take(5)                        // Limit 5 item
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => strtoupper($item->name),
                    'tag' => 'Recommended',
                    'description' => $item->description,

                    // Format harga: misal 185000 -> 185K, jika di bawah 1000 -> Rp 500
                    'price' => ($item->price >= 1000)
                        ? number_format($item->price / 1000, 0).'K'
                        : 'Rp '.number_format($item->price, 0, ',', '.'),

                    // Generates URL lengkap dari storage, atau fallback ke default image
                    'image' => $item->image_path
                        ? Storage::url($item->image_path)
                        : 'https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=2069',
                ];
            });

        return Inertia::render('landingpage/Welcome', [
            'bestSellers' => $bestSellers,
        ]);
    }

    public function menu()
    {
        // Menyimpan query menu beserta relasi kepingan kategori ke cache (misal: 24 jam)
        $menuItems = Cache::remember('menu_items_with_categories', now()->addDay(), function () {
            return MenuItem::with('category')->latest()->get();
        });

        return Inertia::render('landingpage/menu', [
            'menuItems' => $menuItems,
        ]);
    }

    public function reservation(table $table)
    {
        $tables = $table::all();

        return Inertia::render('landingpage/reservation', [
            'Tables' => $tables,
        ]);
    }

    public function gallery()
    {
        return Inertia::render('landingpage/gallery');
    }

    public function ShowMenuQr(Request $request, string $identifier)
    {
        // 1. Cari meja berdasarkan UUID (Gunakan Table kapital)
        $table = table::where('identifier', $identifier)->firstOrFail();

        // 2. WAJIB: Simpan meja ke Session Server demi keamanan checkout nanti
        $request->session()->put('table', [
            'id' => $table->id,
            'table_number' => $table->table_number,
            'identifier' => $table->identifier,
        ]);

        // 3. Ambil menu + kategori (Tambahkan filter stok/ketersediaan jika ada)
        $menuItems = MenuItem::with('category')
            // ->where('is_available', true) // Aktifkan jika ada kolom ketersediaan
            ->get();

        // 4. Render tampilan Inertia
        return Inertia::render('MenuQr/ShowMenu', [
            'table' => [
                'id' => $table->id,
                'table_number' => $table->table_number,
            ],
            'menuItems' => $menuItems,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    // reservation store method
    public function store(Request $request)
    {
        // 1. Gabungkan format waktu
        $datetime = $request->date.' '.$request->time;

        // 2. Jalankan Validasi
        // Jika gagal, Laravel otomatis me-throw ValidationException dan
        // me-redirect kembali sambil membawa array errors ke frontend (Vue/React).
        $request->validate([
            'name' => 'required|string|max:255',
            'guests' => 'required|integer|min:1',
            'date' => [
                'required',
                'date',
                'after_or_equal:today',
                'before_or_equal:'.now()->addDays(7)->format('Y-m-d'),
            ],
            'time' => 'required',
            'table_id' => [
                'required',
                'exists:tables,id',
                function ($attribute, $value, $fail) use ($datetime) {
                    $isBooked = reservation::where('table_id', $value)
                        ->where('reservation_time', $datetime)
                        ->whereIn('status', ['pending', 'confirmed'])
                        ->exists();

                    if ($isBooked) {
                        $fail('Meja sudah dipesan pada tanggal dan jam tersebut.');
                    }
                },
            ],
        ]);

        // 3. Simpan
        reservation::create([
            'customer_name' => $request->name,
            'reservation_time' => $datetime,
            'table_id' => $request->table_id,
            'number_of_guests' => $request->guests,
        ]);

        // 4. Redirect kembali dengan Flash Session (Bukan JSON)
        return redirect()->back()->with('success', 'Reservasi berhasil dibuat.');
        // Alternatif jika ingin dialihkan ke halaman lain:
        // return redirect()->route('reservation.index')->with('success', '...');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
