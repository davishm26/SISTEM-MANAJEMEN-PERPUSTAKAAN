<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query();
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('author', 'like', '%' . $request->search . '%');
        }
        return BookResource::collection($query->orderBy('title')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:200',
            'author'      => 'required|string|max:150',
            'isbn'        => 'required|string|max:20|unique:books,isbn',
            'category'    => 'nullable|string|max:100',
            'publisher'   => 'nullable|string|max:150',
            'year'        => 'nullable|integer|min:1900|max:2100',
            'stock'       => 'nullable|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $book = Book::create($validated);
        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil ditambahkan.',
            'data'    => new BookResource($book),
        ], 201);
    }

    public function show(string $id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json(['success' => false, 'message' => 'Buku tidak ditemukan.'], 404);
        }
        return response()->json(['success' => true, 'data' => new BookResource($book)]);
    }

    // TUGAS BAB 6: Implementasi Update
    public function update(Request $request, string $id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json(['success' => false, 'message' => 'Buku tidak ditemukan.'], 404);
        }

        $validated = $request->validate([
            'title'     => 'sometimes|required|string|max:200',
            'author'    => 'sometimes|required|string|max:150',
            'isbn'      => 'sometimes|required|string|max:20|unique:books,isbn,' . $id,
            'stock'     => 'sometimes|required|integer|min:0',
        ]);

        $book->update($validated);
        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil diperbarui.',
            'data'    => new BookResource($book)
        ], 200);
    }

    // TUGAS BAB 6: Implementasi Delete
    public function destroy(string $id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json(['success' => false, 'message' => 'Buku tidak ditemukan.'], 404);
        }

        // Cek stok sebelum hapus
        if ($book->stock > 0) {
            return response()->json([
                'success' => false,
                'message' => "Gagal menghapus! Buku '{$book->title}' masih memiliki stok {$book->stock}."
            ], 422);
        }

        $title = $book->title;
        $book->delete();

        return response()->json([
            'success' => true,
            'message' => "Buku '{$title}' berhasil dihapus."
        ], 200);
    }
}
