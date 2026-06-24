<?php

namespace App\Services;

class ChatBotService
{
    /**
     * Daftar keyword => jawaban otomatis.
     * Bisa ditambah/diubah sesuka hati.
     */
    protected static array $rules = [

        // ── SAPAAN ──
        ['keywords' => ['halo', 'hai', 'hi', 'hey', 'pagi', 'siang', 'malam', 'sore'],
         'answer'   => 'Halo! 👋 Selamat datang di Pustaka Nusantara. Ada yang bisa saya bantu? Kamu bisa tanya soal pesanan, pengiriman, pembayaran, atau hal lainnya.'],

        ['keywords' => ['terima kasih', 'makasih', 'thanks', 'thank you', 'tengkyu'],
         'answer'   => 'Sama-sama! Senang bisa membantu. Selamat membaca! 📚'],

        ['keywords' => ['siapa kamu', 'kamu siapa', 'ini bot', 'robot atau manusia'],
         'answer'   => 'Saya adalah asisten virtual Pustaka Nusantara 🤖. Saya bisa bantu jawab pertanyaan umum. Kalau pertanyaanmu lebih spesifik, saya akan hubungkan ke admin kami ya!'],

        // ── PENGIRIMAN ──
        ['keywords' => ['ongkir', 'ongkos kirim', 'pengiriman', 'kirim berapa hari', 'estimasi kirim', 'lama sampai', 'kapan sampai'],
         'answer'   => 'Pengiriman gratis untuk semua pesanan! Estimasi sampai 1-3 hari kerja tergantung lokasi kamu.'],

        ['keywords' => ['lacak', 'resi', 'tracking', 'nomor resi', 'cek pesanan'],
         'answer'   => 'Kamu bisa cek status pesanan di menu "Riwayat Pesanan" pada profilmu. Di sana ada tracking timeline lengkap dari pesanan dibuat sampai diterima.'],

        ['keywords' => ['kurir', 'jne', 'jnt', 'sicepat', 'ekspedisi', 'pakai kurir apa'],
         'answer'   => 'Kami bekerja sama dengan beberapa ekspedisi terpercaya seperti JNE, J&T, dan SiCepat. Kurir yang digunakan bisa berbeda tergantung lokasi pengiriman.'],

        ['keywords' => ['daerah terpencil', 'luar jawa', 'kirim ke luar negeri', 'internasional'],
         'answer'   => 'Saat ini kami hanya melayani pengiriman dalam wilayah Indonesia. Untuk daerah terpencil, estimasi pengiriman bisa lebih lama dari biasanya.'],

        // ── PEMBAYARAN ──
        ['keywords' => ['bayar', 'pembayaran', 'metode bayar', 'cara bayar', 'cod'],
         'answer'   => 'Kami menerima pembayaran via Transfer BCA, Transfer Mandiri, GoPay, dan COD (Bayar di Tempat).'],

        ['keywords' => ['transfer ke mana', 'nomor rekening', 'rekening toko'],
         'answer'   => 'Nomor rekening untuk transfer akan muncul otomatis di halaman checkout setelah kamu memilih metode pembayaran transfer bank.'],

        ['keywords' => ['gagal bayar', 'pembayaran ditolak', 'sudah transfer belum masuk'],
         'answer'   => 'Jika pembayaran sudah dilakukan namun status belum berubah, mohon tunggu hingga 1 jam untuk konfirmasi otomatis. Jika lebih dari itu, admin kami akan segera membantu.'],

        ['keywords' => ['cicilan', 'kredit', 'paylater', 'angsuran'],
         'answer'   => 'Saat ini kami belum menyediakan opsi cicilan/paylater. Pembayaran hanya melalui transfer bank, GoPay, atau COD.'],

        // ── PESANAN & PEMBATALAN ──
        ['keywords' => ['batal', 'cancel', 'membatalkan'],
         'answer'   => 'Untuk membatalkan pesanan, silakan hubungi admin kami melalui chat ini sebelum status pesanan "Dikirim".'],

        ['keywords' => ['ubah alamat', 'ganti alamat', 'salah alamat', 'edit alamat'],
         'answer'   => 'Jika pesanan belum diproses/dikirim, kamu bisa minta admin untuk mengubah alamat pengiriman melalui chat ini.'],

        ['keywords' => ['ubah pesanan', 'tambah barang', 'ganti barang dipesanan'],
         'answer'   => 'Untuk perubahan jumlah atau jenis barang dalam pesanan yang sudah dibuat, mohon hubungi admin secepatnya sebelum pesanan diproses.'],

        ['keywords' => ['retur', 'tukar', 'kembali barang', 'refund', 'pengembalian dana'],
         'answer'   => 'Kami menerima retur untuk buku yang rusak/cacat produksi dalam 7 hari setelah pesanan diterima. Hubungi admin untuk proses lebih lanjut.'],

        ['keywords' => ['barang rusak', 'buku rusak', 'buku cacat', 'salah kirim', 'barang tidak sesuai'],
         'answer'   => 'Mohon maaf atas ketidaknyamanannya. Silakan kirim foto buku yang rusak/tidak sesuai ke admin kami melalui chat ini agar segera kami proses penggantiannya.'],

        // ── STOK & PRODUK ──
        ['keywords' => ['stok', 'tersedia', 'ada gak', 'ready', 'masih ada'],
         'answer'   => 'Kamu bisa cek ketersediaan stok langsung di halaman produk masing-masing buku ya! Kalau stok habis, biasanya akan di-restock dalam beberapa hari.'],

        ['keywords' => ['preorder', 'pre order', 'pesan dulu'],
         'answer'   => 'Saat ini kami belum menyediakan sistem pre-order. Buku hanya bisa dipesan jika stoknya tersedia di katalog.'],

        ['keywords' => ['buku original', 'asli atau palsu', 'bajakan', 'keaslian buku'],
         'answer'   => '100% original! Semua buku yang kami jual adalah cetakan resmi dari penerbit, bukan bajakan.'],

        ['keywords' => ['rekomendasi buku', 'buku bagus', 'saran buku', 'mau baca apa'],
         'answer'   => 'Kamu bisa cek halaman "Artikel" kami untuk rekomendasi buku terbaru, atau lihat kategori favorit di halaman Katalog!'],

        // ── BUNDLE & PROMO ──
        ['keywords' => ['bundle', 'paket hemat', 'diskon paket'],
         'answer'   => 'Kami punya paket Bundle dengan harga lebih hemat dibanding beli satuan! Cek halaman "Bundle" di menu untuk lihat semua paket tersedia.'],

        ['keywords' => ['kupon', 'promo', 'diskon', 'voucher'],
         'answer'   => 'Kupon promo bisa dimasukkan saat checkout di kolom "Kode Kupon". Pantau halaman beranda untuk promo terbaru!'],

        ['keywords' => ['kupon tidak berlaku', 'kupon invalid', 'kupon kadaluarsa', 'kupon error'],
         'answer'   => 'Kupon bisa tidak berlaku jika sudah melewati batas waktu, sudah dipakai, atau tidak memenuhi syarat minimal belanja. Cek kembali syarat & ketentuan kuponnya ya.'],

        ['keywords' => ['member', 'keanggotaan', 'poin', 'reward'],
         'answer'   => 'Saat ini kami belum memiliki program membership/poin reward, tapi pantau terus promo kami di halaman beranda!'],

        // ── AKUN & PROFIL ──
        ['keywords' => ['lupa password', 'reset password', 'tidak bisa login'],
         'answer'   => 'Untuk reset password, silakan hubungi admin melalui chat ini dengan menyertakan email akunmu agar segera kami bantu.'],

        ['keywords' => ['ubah email', 'ganti email', 'update profil'],
         'answer'   => 'Kamu bisa mengubah data profil termasuk email melalui menu "Profil Saya" setelah login.'],

        ['keywords' => ['hapus akun', 'tutup akun', 'nonaktifkan akun'],
         'answer'   => 'Untuk penghapusan akun, mohon hubungi admin kami melalui chat ini agar dapat diproses lebih lanjut.'],

        // ── WISHLIST & RIWAYAT BACA ──
        ['keywords' => ['wishlist', 'simpan buku', 'favorit'],
         'answer'   => 'Kamu bisa menyimpan buku favorit ke Wishlist dengan klik ikon hati ❤️ di halaman produk. Lihat semua wishlist-mu di menu "Wishlist Saya".'],

        ['keywords' => ['riwayat baca', 'daftar baca', 'sedang dibaca', 'sudah selesai dibaca'],
         'answer'   => 'Menu "Riwayat Baca" membantu kamu melacak buku yang ingin, sedang, dan sudah dibaca. Cek di navbar bagian atas!'],

        // ── REVIEW / RATING ──
        ['keywords' => ['kasih rating', 'cara review', 'ulasan', 'beri bintang'],
         'answer'   => 'Kamu bisa memberi rating & ulasan di halaman detail produk yang sudah kamu beli. Scroll ke bagian "Rating & Ulasan" ya!'],

        ['keywords' => ['hapus review', 'edit ulasan', 'ubah rating'],
         'answer'   => 'Ulasan yang sudah kamu buat bisa diedit atau dihapus langsung dari halaman detail produk terkait.'],

        // ── KONTAK & LOKASI ──
        ['keywords' => ['alamat toko', 'lokasi toko', 'dimana toko', 'kantor dimana'],
         'answer'   => 'Kantor kami berlokasi di Surabaya, Jawa Timur. Namun semua transaksi dilakukan secara online ya, jadi kamu tidak perlu datang langsung.'],

        ['keywords' => ['jam buka', 'jam operasional', 'buka jam', 'operasional'],
         'answer'   => 'Toko kami online 24 jam! Kamu bisa belanja kapan saja. Untuk respon admin, kami biasanya online pukul 08.00–22.00 WIB.'],

        ['keywords' => ['nomor wa', 'kontak admin', 'telepon', 'hubungi customer service'],
         'answer'   => 'Kamu sedang terhubung dengan kami sekarang melalui chat ini! Untuk kontak lain, cek halaman footer website untuk email dan nomor telepon kami.'],

        // ── KELUHAN UMUM ──
        ['keywords' => ['lambat', 'lama respon', 'kapan dibalas', 'belum dijawab'],
         'answer'   => 'Mohon maaf atas keterlambatannya 🙏 Admin kami akan segera membalas pesanmu. Mohon ditunggu sebentar ya.'],

        ['keywords' => ['komplain', 'kecewa', 'tidak puas', 'buruk sekali'],
         'answer'   => 'Mohon maaf atas pengalaman yang kurang menyenangkan ini. Boleh ceritakan lebih detail kendalanya? Admin kami akan segera membantu menyelesaikannya.'],
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