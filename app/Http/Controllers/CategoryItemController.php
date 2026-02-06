<?php

namespace App\Http\Controllers;

use App\Models\CategoryItem;
use Illuminate\Http\Request;

class CategoryItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = CategoryItem::orderBy('id', 'desc')->paginate(10);
        return view('category_items.index.index', compact('categories'));
    }

    public function search(Request $request)
    {
        $query = CategoryItem::query();
    
        if ($request->filled('kode')) {
            $query->where('kode', $request->kode);
        }
    
        if ($request->filled('nama')) {
            $query->where('nama', 'LIKE', '%' . $request->nama . '%');
        }
    
        $categories = $query
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString(); // keep search params on pagination
    
        return view('category_items.index.index', compact('categories'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('category_items.form.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        // Auto-generate kode
        $count = CategoryItem::count();
        $kode = $request->kode;

        CategoryItem::create([
            'nama' => $request->nama,
            'kode' => $kode,
        ]);

        return redirect()->route('category-items.index')
                        ->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = CategoryItem::findOrFail($id);
        return view('category_items.single.index', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $category = CategoryItem::findOrFail($id);
        return view('category_items.form.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:255',
        ]);

        $category = CategoryItem::findOrFail($id);
        $category->update([
            'nama' => $request->nama,
            'kode' => $request->kode,
        ]);

        return redirect()->route('category-items.index')
                        ->with('success', 'Kategori berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = CategoryItem::findOrFail($id);
        $category->delete();

        return redirect()->route('category-items.index')
                        ->with('success', 'Kategori berhasil dihapus!');
    }
}