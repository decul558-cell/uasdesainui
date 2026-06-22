<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name'     => 'Admin Toko',
            'email'    => 'admin@tokobuku.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // User biasa
        $user = User::create([
            'name'     => 'Andy Mahasiswa',
            'email'    => 'andy@tokobuku.com',
            'password' => Hash::make('password'),
            'role'     => 'user',
        ]);

        // Categories
        $categories = [
            ['name' => 'Fiksi',             'slug' => 'fiksi',             'description' => 'Novel dan cerita fiksi'],
            ['name' => 'Non-Fiksi',         'slug' => 'non-fiksi',         'description' => 'Buku pengetahuan dan fakta'],
            ['name' => 'Teknologi',         'slug' => 'teknologi',         'description' => 'Buku pemrograman dan IT'],
            ['name' => 'Bisnis',            'slug' => 'bisnis',            'description' => 'Buku bisnis dan keuangan'],
            ['name' => 'Pengembangan Diri', 'slug' => 'pengembangan-diri', 'description' => 'Self improvement'],
            ['name' => 'Sejarah',           'slug' => 'sejarah',           'description' => 'Buku sejarah dan budaya'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // Products
        $products = [
            ['title' => 'Laskar Pelangi',          'author' => 'Andrea Hirata',        'category_id' => 1, 'price' => 89000,  'stock' => 50, 'publisher' => 'Bentang Pustaka',   'year' => 2005],
            ['title' => 'Bumi Manusia',             'author' => 'Pramoedya Ananta Toer','category_id' => 1, 'price' => 95000,  'stock' => 30, 'publisher' => 'Lentera Dipantara', 'year' => 1980],
            ['title' => 'Dilan 1990',               'author' => 'Pidi Baiq',            'category_id' => 1, 'price' => 72000,  'stock' => 45, 'publisher' => 'Pastel Books',      'year' => 2014],
            ['title' => 'Perahu Kertas',            'author' => 'Dee Lestari',          'category_id' => 1, 'price' => 78000,  'stock' => 40, 'publisher' => 'Bentang Pustaka',   'year' => 2009],
            ['title' => 'Sapiens',                  'author' => 'Yuval Noah Harari',    'category_id' => 2, 'price' => 120000, 'stock' => 25, 'publisher' => 'KPG',               'year' => 2015],
            ['title' => 'Homo Deus',                'author' => 'Yuval Noah Harari',    'category_id' => 2, 'price' => 115000, 'stock' => 20, 'publisher' => 'KPG',               'year' => 2017],
            ['title' => 'Clean Code',               'author' => 'Robert C. Martin',     'category_id' => 3, 'price' => 145000, 'stock' => 15, 'publisher' => 'Prentice Hall',     'year' => 2008],
            ['title' => 'The Pragmatic Programmer', 'author' => 'David Thomas',         'category_id' => 3, 'price' => 155000, 'stock' => 12, 'publisher' => 'Addison Wesley',    'year' => 2019],
            ['title' => 'Laravel: Up & Running',    'author' => 'Matt Stauffer',        'category_id' => 3, 'price' => 165000, 'stock' => 18, 'publisher' => "O'Reilly",          'year' => 2022],
            ['title' => 'Rich Dad Poor Dad',        'author' => 'Robert Kiyosaki',      'category_id' => 4, 'price' => 88000,  'stock' => 60, 'publisher' => 'Gramedia',          'year' => 2000],
            ['title' => 'Zero to One',              'author' => 'Peter Thiel',          'category_id' => 4, 'price' => 98000,  'stock' => 35, 'publisher' => 'Gramedia',          'year' => 2014],
            ['title' => 'Atomic Habits',            'author' => 'James Clear',          'category_id' => 5, 'price' => 108000, 'stock' => 55, 'publisher' => 'Gramedia',          'year' => 2019],
            ['title' => 'The 7 Habits',             'author' => 'Stephen Covey',        'category_id' => 5, 'price' => 95000,  'stock' => 40, 'publisher' => 'Binarupa Aksara',   'year' => 1989],
            ['title' => 'Ikigai',                   'author' => 'Hector Garcia',        'category_id' => 5, 'price' => 79000,  'stock' => 50, 'publisher' => 'Gramedia',          'year' => 2016],
            ['title' => 'Sejarah Indonesia Modern', 'author' => 'M.C. Ricklefs',        'category_id' => 6, 'price' => 135000, 'stock' => 20, 'publisher' => 'Serambi',           'year' => 2008],
            ['title' => 'Tetralogi Pulau Buru',     'author' => 'Pramoedya Ananta Toer','category_id' => 6, 'price' => 250000, 'stock' => 10, 'publisher' => 'Lentera Dipantara', 'year' => 1980],
        ];

        foreach ($products as $p) {
            Product::create([
                'title'       => $p['title'],
                'slug'        => Str::slug($p['title']) . '-' . Str::random(4),
                'author'      => $p['author'],
                'category_id' => $p['category_id'],
                'price'       => $p['price'],
                'stock'       => $p['stock'],
                'publisher'   => $p['publisher'],
                'year'        => $p['year'],
                'description' => 'Deskripsi buku ' . $p['title'] . '. Karya terbaik dari ' . $p['author'] . ' yang wajib dibaca.',
            ]);
        }

        // Articles
        $articles = [
            ['title' => '10 Buku Wajib Baca Sebelum Usia 30',         'excerpt' => 'Daftar buku yang akan mengubah cara pandangmu tentang kehidupan.',        'body' => '<p>Membaca buku adalah investasi terbaik. Berikut 10 buku wajib baca sebelum usia 30.</p><p>1. Atomic Habits - perubahan kecil menghasilkan hasil luar biasa.</p><p>2. Rich Dad Poor Dad - cara pandang baru tentang uang.</p>'],
            ['title' => 'Kenapa Membaca Fisik Lebih Baik dari E-Book?', 'excerpt' => 'Di era digital, buku fisik masih memiliki keunggulan tersendiri.',         'body' => '<p>Penelitian menunjukkan membaca buku fisik memiliki banyak keunggulan.</p><p>Otak lebih mudah menyerap informasi dari halaman fisik.</p>'],
            ['title' => 'Tips Membangun Kebiasaan Membaca 30 Menit',   'excerpt' => 'Strategi sederhana menjadikan membaca sebagai kebiasaan harian.',         'body' => '<p>Mulailah dengan 5 menit per hari, lalu tingkatkan secara bertahap.</p><p>Konsistensi lebih penting dari durasi.</p>'],
            ['title' => 'Rekomendasi Buku Fiksi Indonesia Terbaik',    'excerpt' => 'Karya penulis Indonesia yang mencuri perhatian nasional dan internasional.', 'body' => '<p>Sastra Indonesia terus berkembang dengan penulis berbakat baru.</p><p>Laskar Pelangi dan Bumi Manusia adalah dua karya terbaik.</p>'],
            ['title' => 'Cara Memilih Buku yang Tepat untuk Pemula',   'excerpt' => 'Panduan bagi kamu yang baru ingin memulai hobi membaca.',                  'body' => '<p>Memilih buku pertama menentukan apakah kamu akan jatuh cinta dengan membaca.</p>'],
        ];

        foreach ($articles as $a) {
            Article::create([
                'user_id'      => 1,
                'title'        => $a['title'],
                'slug'         => Str::slug($a['title']) . '-' . Str::random(4),
                'excerpt'      => $a['excerpt'],
                'body'         => $a['body'],
                'status'       => 'published',
                'published_at' => now(),
            ]);
        }

        // Sample Order
        $order = Order::create([
            'user_id'          => $user->id,
            'order_code'       => 'ORD-' . strtoupper(Str::random(8)),
            'total_price'      => 197000,
            'discount'         => 0,
            'status'           => 'paid',
            'payment_method'   => 'Transfer Bank',
            'shipping_address' => 'Jl. Raya Surabaya No. 123, Surabaya Timur',
            'paid_at'          => now(),
        ]);

        OrderItem::create(['order_id' => $order->id, 'product_id' => 1, 'quantity' => 1, 'price' => 89000]);
        OrderItem::create(['order_id' => $order->id, 'product_id' => 12, 'quantity' => 1, 'price' => 108000]);

        // Sample Coupons
        Coupon::create(['code' => 'DISKON10', 'type' => 'percent', 'value' => 10, 'min_order' => 50000,  'max_uses' => 100, 'is_active' => true, 'expired_at' => now()->addMonths(3)]);
        Coupon::create(['code' => 'HEMAT20',  'type' => 'percent', 'value' => 20, 'min_order' => 100000, 'max_uses' => 50,  'is_active' => true, 'expired_at' => now()->addMonths(1)]);
        Coupon::create(['code' => 'GRATIS15', 'type' => 'fixed',   'value' => 15000, 'min_order' => 75000, 'max_uses' => 200, 'is_active' => true, 'expired_at' => now()->addMonths(6)]);

        // Sample Notifications for user
        Notification::create(['user_id' => $user->id, 'title' => 'Selamat Datang!',          'message' => 'Selamat datang di TokoBuku! Temukan buku impianmu di sini.',                          'type' => 'info',    'is_read' => false]);
        Notification::create(['user_id' => $user->id, 'title' => 'Pesanan Dikonfirmasi',      'message' => 'Pesanan kamu telah dikonfirmasi dan sedang diproses.',                               'type' => 'success', 'is_read' => false]);
        Notification::create(['user_id' => $user->id, 'title' => 'Promo Spesial!',            'message' => 'Gunakan kode DISKON10 untuk mendapatkan diskon 10% untuk semua pembelian.',          'type' => 'warning', 'is_read' => false]);
        Notification::create(['user_id' => $user->id, 'title' => 'Pesanan Sedang Dikirim',    'message' => 'Pesanan kamu sedang dalam perjalanan. Estimasi tiba 2-3 hari kerja.',                'type' => 'info',    'is_read' => true]);
        Notification::create(['user_id' => $user->id, 'title' => 'Ulasan Buku',               'message' => 'Bagaimana pengalamanmu membaca Atomic Habits? Berikan ulasanmu sekarang!',           'type' => 'info',    'is_read' => true]);
    }
}