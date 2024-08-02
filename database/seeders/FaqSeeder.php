<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Faq::insert([
            'question' => 'Apa itu Casiwa?',
            'answer' => 'Casiwa adalah sebuah platform digital berbasis website yang menyediakan fasilitas berbagi catatan mata kuliah untuk membantu mahasiswa dalam memahami materi perkuliahan dengan lebih mudah dan terjangkau. Casiwa hadir sebagai solusi untuk menyediakan berbagai catatan kuliah, tugas perkuliahan, kiat-kiat belajar, dan referensi lainnya yang dapat diakses oleh mahasiswa dari berbagai perguruan tinggi.'
        ]);
        Faq::insert([
            'question' => 'Apakah Casiwa menawarkan Uji Coba Gratis?',
            'answer' => 'Ya, Casiwa menawarkan uji coba gratis sebanyak 2 kali unduhan pertama untuk pengguna baru. Ini memungkinkan pengguna untuk mencoba layanan terlebih dahulu sebelum dokumen dikenakan biaya per item.'
        ]);
        Faq::insert([
            'question' => 'Apakah Casiwa memiliki aplikasi mobile?',
            'answer' => 'Casiwa saat ini berbasis website yang dapat diakses dengan berbagai web browser baik pada desktop maupun ponsel secara responsif sehingga nyaman digunakan oleh pengguna.'
        ]);
        Faq::insert([
            'question' => 'Bagaimana cara menemukan konten tertentu di Casiwa?',
            'answer' => 'Pengguna dapat menggunakan fitur pencarian di situs web untuk menemukan dokumen catatan kuliah tertentu berdasarkan judul atau penulis. Terdapat juga filter berdasarkan Universitas dan Program Studi untuk mendukung pencarian pengguna.'
        ]);
        Faq::insert([
            'question' => 'Bagaimana cara mengunggah dokumen ke Casiwa?',
            'answer' => 'Pengguna dapat mengunggah dokumen dengan masuk ke akun mereka, memilih opsi untuk mengunggah dari dashboard, dan mengikuti instruksi untuk mengunggah file dari perangkat mereka.'
        ]);
        Faq::insert([
            'question' => 'Bagaimana cara menghubungi dukungan pelanggan Casiwa?',
            'answer' => 'Pengguna dapat menghubungi dukungan pelanggan melalui situs web Casiwa dengan mengakses bagian "Layanan Pelanggan" dan mengisi formulir kontak, atau melalui email dukungan yang disediakan.'
        ]);
        Faq::insert([
            'question' => 'Format file apa yang didukung untuk pengunggahan di Casiwa? ',
            'answer' => 'Format file yang didukung untuk pengunggahan di Casiwa hanya dengan format PDF.'
        ]);
        Faq::insert([
            'question' => 'Apakah saya bisa mengedit dokumen setelah mengunggahnya ke Casiwa?',
            'answer' => 'Tidak, setelah dokumen diunggah, Casiwa tidak menyediakan opsi untuk mengedit konten dokumen langsung di platform. Pengguna harus mengedit file asli di perangkat mereka dan mengunggah ulang jika perlu.'
        ]);
        Faq::insert([
            'question' => 'Bagaimana cara menambahkan deskripsi pada dokumen yang saya unggah ke Casiwa?',
            'answer' => 'Saat mengunggah dokumen, pengguna akan diberikan opsi untuk menambahkan judul, deskripsi, dan file dokumen itu sendiri. Informasi ini dapat membantu dalam pengindeksan dan pencarian dokumen oleh pengguna lain.'
        ]);
        Faq::insert([
            'question' => 'Bagaimana cara saya menghasilkan insentif dari dokumen yang saya unggah ke Casiwa?',
            'answer' => 'Pengguna dapat menghasilkan insentif dari dokumen yang diunggah berdasarkan jumlah dokumen terdownload (secara berbayar) oleh pengguna lain. Dengan batas minimal insentif yang dapat diambil adalah lebih dari sama dengan Rp100.000,-'
        ]);
        Faq::insert([
            'question' => 'Bagaimana cara saya tahu akumulasi insentif yang telah saya kumpulkan?',
            'answer' => 'Pengguna dapat mengecek pada menu Profil yang menampilkan wallet atau seberapa banyak poin insentif yang telah terkumpul.'
        ]);
    }
}
