<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            // 1. Starter
            ['name' => 'INSALATA DI CAESAR', 'price' => 55000, 'category_id' => 1, 'description' => 'Selada romain segar yang dicampur dengan bawang putih & teri, diberi telur rebus, potongan bacon, dada ayam panggang, crouton panggang, dan taburan keju parmesan parut.'],
            ['name' => 'GADO-GADO', 'price' => 35000, 'category_id' => 1, 'description' => 'Campuran sayuran Indonesia harian (wortel, kol, tauge, kacang panjang, bayam) disajikan dengan lontong, telur rebus, kentang rebus, kerupuk emping, dan saus kacang.'],
            ['name' => 'RUJAK', 'price' => 28000, 'category_id' => 1, 'description' => 'Salad buah campur Indonesia dengan asam jawa, gula aren, dan saus kacang pedas.'],
            ['name' => 'THAI CHICKEN SALAD', 'price' => 25000, 'category_id' => 1, 'description' => 'Ayam panggang dengan selada campur disajikan dengan Dressing rempah Thailand.'],

            // 2. Soup
            ['name' => 'CREMA DI FUNGI', 'price' => 32000, 'category_id' => 2, 'description' => 'Sup krim jamur hutan dengan aroma tomat kering dan bawang, disajikan dengan stik puff pastry.'],
            ['name' => 'CREAMY PUMKIN', 'price' => 32000, 'category_id' => 2, 'description' => 'Sup krim labu panggang dengan aroma tomat kering, disajikan dengan crouton roti.'],
            ['name' => 'ZUPPA DI PATATE E PORRI', 'price' => 32000, 'category_id' => 2, 'description' => 'Sup klasik kentang dan daun bawang disajikan dengan potongan bacon dan crouton roti.'],
            ['name' => 'TOM YAM TALAY', 'price' => 48000, 'category_id' => 2, 'description' => 'Sup Thailand dengan udang, ikan, dan cumi, jamur, tomat, dan pasta tom yum disajikan dengan bawang merah goreng dan daun ketumbar.'],
            ['name' => 'TOM YAM GAI', 'price' => 35000, 'category_id' => 2, 'description' => 'Sup Thailand dengan ayam, wortel, jagung muda, jamur, tomat, dan pasta tom yum disajikan dengan bawang merah goreng dan daun ketumbar.'],
            ['name' => 'ANDALAS/ SUP KAMBING', 'price' => 65000, 'category_id' => 2, 'description' => 'Bahu domba yang dimasak lambat dalam krim kuning kelapa dengan kentang, wortel, irisan kol putih, tomat, bawang merah goreng, seledri, dan daun bawang.'],
            ['name' => 'CHICKEN CLEAR SOUP', 'price' => 36000, 'category_id' => 2, 'description' => 'Sup bening dengan ayam, kentang, wortel, jamur, tomat, disajikan dengan nasi kukus dan sambal cabai hijau di samping.'],
            ['name' => 'OXTAIL SOUP', 'price' => 122000, 'category_id' => 2, 'description' => 'Sup buntut otentik Indonesia disajikan dengan kentang, wortel, tomat, bawang merah goreng, seledri, daun bawang, disajikan dengan nasi kukus, sambal, dan jeruk nipis.'],

            // 3. Noodle Soup
            ['name' => 'SOTO AYAM', 'price' => 27000, 'category_id' => 3, 'description' => 'Sup tradisional Indonesia dengan mi nasi, ayam, tomat, daun bawang di atasnya, disajikan dengan sambal dan jeruk nipis.'],
            ['name' => 'SINGAPORE LAKSA', 'price' => 38000, 'category_id' => 3, 'description' => 'Mi laksa, udang, fishcake, tahu, telur rebus dalam kuah laksa.'],

            // 4. Snack
            ['name' => 'Beef Spring Roll', 'price' => 56000, 'category_id' => 4, 'description' => 'Daging sapi cincang yang digoreng dalam kulit lumpia bertepung, disajikan dengan saus Keju dan saus BBQ.'],
            ['name' => 'CHICKEN CHEESSE BALL', 'price' => 55000, 'category_id' => 4, 'description' => 'Bola keju goreng dalam ayam renyah disajikan dengan saus pepper relish dan keju leleh di atasnya.'],
            ['name' => 'CHICKEN FIRE WING', 'price' => 43000, 'category_id' => 4, 'description' => 'Sayap ayam renyah goreng dicampur dengan saus bbq, biji wijen di atasnya, dan saus keju oranye di samping.'],
            ['name' => 'FISH AND CHIP', 'price' => 42000, 'category_id' => 4, 'description' => 'Ikan goreng dengan adonan bir, French fries disajikan dengan saus tomat dan saus tartar.'],
            ['name' => 'FISH FINGER', 'price' => 42000, 'category_id' => 4, 'description' => 'Ikan bertepung goreng dengan salad campur disajikan dengan saus tartar dan mayo madu pedas.'],
            ['name' => 'VEGETABLE SPRING ROLL', 'price' => 28000, 'category_id' => 4, 'description' => 'Sayuran goreng dalam kulit lumpia disajikan dengan saus cabai manis.'],
            ['name' => 'GARLIC FRIES', 'price' => 35000, 'category_id' => 4, 'description' => 'Kentang goreng lurus yang dicampur dengan mentega bawang putih dan keju parmesan parut disajikan dengan saus keju Cheddar.'],
            ['name' => 'POTATO WEDGES', 'price' => 37000, 'category_id' => 4, 'description' => 'Kentang wedges goreng disajikan dengan mayones, cabai Thailand, dan saus keju oranye.'],
            ['name' => 'HASHBRON', 'price' => 55000, 'category_id' => 4, 'description' => 'Hashbrown goreng disajikan dengan salad campur, saus keju oranye, dan saus chili mayo.'],
            ['name' => 'CASSAVA', 'price' => 32000, 'category_id' => 4, 'description' => 'Singkong goreng disajikan dengan chilli con carne dan saus keju oranye.'],
            ['name' => 'CHICKEN BURITOS', 'price' => 58000, 'category_id' => 4, 'description' => 'Ayam juicy dengan paprika campur, keju leleh, saus krim dibungkus dalam kulit tortilla disajikan dengan saus pepper relish.'],
            ['name' => 'CHICKEN NUGGET', 'price' => 42000, 'category_id' => 4, 'description' => 'Ayam renyah goreng disajikan dengan selada campur dan saus keju.'],
            ['name' => 'GOHYONG', 'price' => 52000, 'category_id' => 4, 'description' => 'Ayam cincang yang dimarinasi dengan udang dan digulung dengan kulit tahu dan disajikan dengan saus cabai manis.'],
            ['name' => 'CIRENG', 'price' => 35000, 'category_id' => 4, 'description' => 'Makanan ringan tradisional Indonesia dengan lapisan tepung terigu dan tepung beras disajikan dengan saus manis pedas.'],
            ['name' => 'OTAK OTAK CRISPY', 'price' => 38000, 'category_id' => 4, 'description' => 'Makanan ringan tradisional Indonesia dengan ikan cincang, tepung terigu, dan digulung dengan rice pepper disajikan dengan saus manis pedas.'],
            ['name' => 'EMPEK - EMPEK', 'price' => 65000, 'category_id' => 4, 'description' => 'Makanan ringan tradisional Indonesia dengan lapisan tepung terigu dan ikan cincang segar disajikan dengan saus manis pedas.'],
            ['name' => 'EMPEK - EMPEK D\'MARIO', 'price' => 65000, 'category_id' => 4, 'description' => 'Varian empek-empek khas D\'Mario.'],
            ['name' => 'EMPEK - EMPEK KULIT', 'price' => 65000, 'category_id' => 4, 'description' => 'Empek-empek yang dibuat dari kulit ikan pilihan.'],
            ['name' => 'EMPEK - EMPEK LENJER', 'price' => 65000, 'category_id' => 4, 'description' => 'Empek-empek berbentuk lonjong (lenjer).'],

            // 5. Burger
            ['name' => 'BEEF BURGER', 'price' => 58000, 'category_id' => 5, 'description' => 'Patty daging sapi panggang dengan keju cheddar leleh, beef bacon, telur goreng, selada, irisan tomat dalam bun wijen panggang disajikan dengan garlic parmesan fries dan gravy.'],
            ['name' => 'CHICKEN BURGER', 'price' => 52000, 'category_id' => 5, 'description' => 'Patty ayam renyah goreng dengan keju cheddar leleh, selada, irisan tomat dalam bun wijen panggang disajikan dengan garlic parmesan fries.'],

            // 6. Share Menu
            ['name' => 'GRILL PLATTER', 'price' => 185000, 'category_id' => 6, 'description' => 'Platter campur dengan kaki ayam panggang, sosis Bruwtash panggang, lamb chop shoulder panggang disajikan dengan saus BBQ, saus lada hitam, atau saus jamur.'],
            ['name' => 'CHICKEN LOLLIPOPS', 'price' => 235000, 'category_id' => 6, 'description' => 'Paha ayam empuk, dibungkus dalam bacon asap dengan glasir madu bbq, disajikan dengan hashbrown dan potato wedges.'],
            ['name' => 'MIX PLATER', 'price' => 135000, 'category_id' => 6, 'description' => 'Platter campur dengan fish finger, cumi calamari, sayap ayam, chicken nugget, hashbrown, potato wedges disajikan dengan salad campur, saus tartar, saus keju, dan saus BBQ.'],

            // 7. Pizzette
            ['name' => 'D\'MARIO', 'price' => 125000, 'category_id' => 7, 'description' => 'Saus tomat, keju mozzarella, keju cheddar, tomat segar, bawang bombay, paprika campur panggang, dan udang cabai merah beraroma dengan percikan minyak zaitun.'],
            ['name' => 'PEPERONI ARROSITITI', 'price' => 115000, 'category_id' => 7, 'description' => 'Saus tomat, keju mozzarella, keju cheddar, beef peperoni, bawang bombay, dan paprika campur panggang.'],
            ['name' => 'THREEMUSKETER', 'price' => 125000, 'category_id' => 7, 'description' => 'Beef pepperoni, chicken ham, paprika, berbasis tomat, dan keju mozzarella.'],
            ['name' => 'BIANCA', 'price' => 115000, 'category_id' => 7, 'description' => 'Ayam panggang dalam cabai manis, bawang bombay, paprika, berbasis tomat, dan keju mozzarella.'],
            ['name' => 'AL FUNGI', 'price' => 110000, 'category_id' => 7, 'description' => 'Berbasis tomat, keju mozzarella, jamur, dan bawang bombay.'],
            ['name' => 'HAWAIIAN', 'price' => 120000, 'category_id' => 7, 'description' => 'Ayam panggang, chicken ham, paprika, nanas, berbasis tomat, dan keju mozzarella.'],
            ['name' => 'CHICKEN FLORENTINE', 'price' => 125000, 'category_id' => 7, 'description' => 'Berbasis tomat, keju mozarela dengan ayam krim, paprika campur, bawang bombay, dan jagung.'],
            ['name' => 'MARGARITA', 'price' => 85000, 'category_id' => 7, 'description' => 'Berbasis tomat, keju mozarela, tomat segar, disajikan dengan oregano.'],
            ['name' => 'BOLOGNAISE', 'price' => 125000, 'category_id' => 7, 'description' => 'Saus tomat, keju mozzarella, keju cheddar, bawang bombay, daging sapi cincang yang dimarinasi, dan paprika campur panggang.'],

            // 8. Pasta
            ['name' => 'AGLIO OLIO DE PEPPERONCINO', 'price' => 45000, 'category_id' => 8, 'description' => 'Pilihan pasta: SPAGHETTI, PENNE, DAN LINGUINE. Ditumis dengan minyak zaitun, bawang putih, cabai kering, peterseli, dan udang panggang di atasnya.'],
            ['name' => 'BEEF LASAGNA', 'price' => 75000, 'category_id' => 8, 'description' => 'Ragout daging sapi cincang Italia dengan lapisan pasta yang dimasak dalam saus tomat, keju, dan saus bechamel.'],
            ['name' => 'ALFREDO', 'price' => 60000, 'category_id' => 8, 'description' => 'Saus krim kontemporer yang diresapi keju dengan chicken ham dan kacang polong, diberi irisan dada ayam panggang.'],
            ['name' => 'BOLOGNESE', 'price' => 55000, 'category_id' => 8, 'description' => 'Ragout daging sapi cincang Italia dengan anggur merah dan saus tomat, bawang bombay karamel, dan oregano.'],

            // 9. Fried Rice and noodler
            ['name' => 'LAMB FRIED RICE', 'price' => 85000, 'category_id' => 9, 'description' => 'Nasi goreng Indonesia dengan domba, pasta cabai, telur goreng, lamb chop shoulder panggang, kerupuk, dan acar.'],
            ['name' => 'SEAFOOD FRIED RICE', 'price' => 47000, 'category_id' => 9, 'description' => 'Nasi goreng dengan makanan laut, sayuran, dicampur dengan pasta XO disajikan dengan telur goreng, udang, dan kerupuk.'],
            ['name' => 'KAMPONG BUGIS FRIED RICE', 'price' => 45000, 'category_id' => 9, 'description' => 'Nasi goreng gaya kampung dengan pasta cabai, ikan teri disajikan dengan ayam goreng, telur goreng, kerupuk udang, dan acar.'],
            ['name' => 'CHICKEN FRIED RICE', 'price' => 38000, 'category_id' => 9, 'description' => 'Nasi goreng Indonesia dengan ayam, pasta cabai, sate ayam, telur goreng, ayam goreng, kerupuk, dan acar.'],
            ['name' => 'JAVA FRIED NOODLE', 'price' => 38000, 'category_id' => 9, 'description' => 'Mi goreng Indonesia dengan ayam, sayuran campur, dan pasta cabai disajikan dengan telur goreng, kerupuk, dan acar.'],
            ['name' => 'SEAFOOD FRIED NOODLE', 'price' => 48000, 'category_id' => 9, 'description' => 'Mi goreng dengan udang, cumi, bakso ikan, sayuran campur, dan pasta XO disajikan dengan telur goreng, prawn beer butter, dan kerupuk.'],

            // 10. Asian Delight
            ['name' => 'AYAM PANGGANG TALIWANG', 'price' => 65000, 'category_id' => 10, 'description' => 'Setengah ayam panggang dengan marinasi khas Bali, disajikan dengan sayuran, nasi kukus, acar, dan kerupuk.'],
            ['name' => 'BUNTUT BAKAR', 'price' => 128000, 'category_id' => 10, 'description' => 'Buntut sapi panggang yang dimarinasi pedas dan manis dengan bumbu dan rempeyek krekers disajikan dengan nasi kukus.'],
            ['name' => 'AYAM BAKAR KECAP', 'price' => 55000, 'category_id' => 10, 'description' => 'Paha ayam panggang dengan marinasi pasta kuning Indonesia disajikan dengan tahu.'],
        ];

        foreach ($items as &$item) {
            $item['is_available'] = true;
            $item['created_at'] = now();
            $item['updated_at'] = now();
        }

        DB::table('menu_items')->insert($items);
    }
}
