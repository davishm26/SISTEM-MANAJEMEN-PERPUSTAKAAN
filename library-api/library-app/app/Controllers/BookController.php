<?php

namespace App\Controllers;

use App\Services\LibraryService;

class BookController extends BaseController
{
    protected LibraryService $library;

    public function __construct()
    {
        // Inisialisasi Service yang tadi kita buat di folder Services
        $this->library = new LibraryService();
    }

    // Menampilkan daftar semua buku
    public function index(): string
    {
        // Mengambil parameter pencarian jika ada
        $search = $this->request->getGet('search');
        $params = $search ? ['search' => $search] : [];

        // Memanggil fungsi getBooks dari LibraryService
        $result = $this->library->getBooks($params);

        // Mengirimkan data ke view 'books/index'
        return view('books/index', [
            'title' => 'Daftar Buku Perpustakaan',
            'books' => $result['data'] ?? [], // Data buku dari Laravel API
            'error' => $result['error'] ?? null, // Pesan error jika API gagal
        ]);
    }

    // Menampilkan detail satu buku
    public function detail(int $id): string
    {
        // Memanggil fungsi getBook berdasarkan ID
        $result = $this->library->getBook($id);

        return view('books/detail', [
            'title' => 'Detail Buku',
            'book'  => $result['data'] ?? null,
            'error' => $result['error'] ?? null,
        ]);
    }
}
