<?php

namespace App\Services;

class ChatBotService
{
    /**
     * Daftar keyword => jawaban otomatis.
     * Bisa ditambah/diubah sesuka hati.
     */
    protected static array $rules = [
        ['keywords' => ['jam buka', 'jam operasional', 'buka jam', 'operasional'],
         'answer'   => 'Toko kami online 24 jam! Kamu bisa belanja kapan saja. Untuk respon admin, kami biasanya online pukul 08.00–22.00 WIB.'],

        ['keywords' => ['ongkir', 'ongkos kirim', 'pengiriman', 'kirim berapa hari', 'estimasi kirim'],
         'answer'   => 'Pengiriman gratis untuk semua pesanan! Estimasi sampai 1-3 hari kerja tergantung lokasi kamu.'],

        ['keywords' => ['bayar', 'pembayaran', 'metode bayar', 'cara bayar', 'cod'],
         'answer'   => 'Kami menerima pembayaran via Transfer BCA, Transfer Mandiri, GoPay, dan COD (Bayar di Tempat).'],

        ['keywords' => ['batal', 'cancel', 'membatalkan'],
         'answer'   => 'Untuk membatalkan pesanan, silakan hubungi admin kami melalui chat ini sebelum status pesanan "Dikirim".'],

        ['keywords' => ['retur', 'tukar', 'kembali barang', 'refund'],
         'answer'   => 'Kami menerima retur untuk buku yang rusak/cacat produksi dalam 7 hari setelah pesanan diterima. Hubungi admin untuk proses lebih lanjut.'],

        ['keywords' => ['stok', 'tersedia', 'ada gak', 'ready'],
         'answer'   => 'Kamu bisa cek ketersediaan stok langsung di halaman produk masing-masing buku ya! Kalau stok habis, biasanya akan di-restock dalam beberapa hari.'],

        ['keywords' => ['bundle', 'paket hemat', 'diskon paket'],
         'answer'   => 'Kami punya paket Bundle dengan harga lebih hemat dibanding beli satuan! Cek halaman "Bundle" di menu untuk lihat semua paket tersedia.'],

        ['keywords' => ['kupon', 'promo', 'diskon', 'voucher'],
         'answer'   => 'Kupon promo bisa dimasukkan saat checkout di kolom "Kode Kupon". Pantau halaman beranda untuk promo terbaru!'],

        ['keywords' => ['halo', 'hai', 'hi', 'pagi', 'siang', 'malam'],
         'answer'   => 'Halo! 👋 Selamat datang di Pustaka Nusantara. Ada yang bisa saya bantu? Kamu bisa tanya soal pengiriman, pembayaran, atau hal lainnya.'],

        ['keywords' => ['terima kasih', 'makasih', 'thanks'],
         'answer'   => 'Sama-sama! Senang bisa membantu. Selamat membaca! 📚'],
    ];

    /**
     * Coba cari jawaban otomatis berdasarkan pesan user.
     * Return null kalau tidak ada yang cocok (akan dianggap perlu eskalasi ke admin).
     */
    public static function reply(string $message): ?string
    {
        $text = mb_strtolower($message);

        foreach (self::$rules as $rule) {
            foreach ($rule['keywords'] as $kw) {
                if (str_contains($text, $kw)) {
                    return $rule['answer'];
                }
            }
        }

        return null;
    }

    /**
     * Pesan default ketika bot tidak mengerti — sekaligus menandakan
     * percakapan butuh eskalasi ke admin.
     */
    public static function fallbackMessage(): string
    {
        return 'Maaf, saya belum bisa menjawab pertanyaan itu. Saya akan menghubungkan kamu dengan admin kami ya, mohon tunggu sebentar 🙏';
    }
}