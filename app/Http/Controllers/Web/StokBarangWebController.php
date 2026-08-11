<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\StokBarang;
use Illuminate\Http\Request;

class StokBarangWebController extends Controller
{
    public function index()
    {
        $stokBarangs = StokBarang::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.stok_barangs.index', compact('stokBarangs'));
    }

    public function create()
    {
        return view('admin.stok_barangs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang'  => 'required|string|max:100',
            'satuan'       => 'required|string|max:20',
            'stok'         => 'required|integer|min:0',
            'minimum_stok' => 'required|integer|min:0',
        ]);

        StokBarang::create($validated);

        return redirect()->route('admin.stok_barangs.index')->with('success', 'Stok barang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $stokBarang = StokBarang::findOrFail($id);
        return view('admin.stok_barangs.edit', compact('stokBarang'));
    }

    public function update(Request $request, $id)
    {
        $stokBarang = StokBarang::findOrFail($id);

        $validated = $request->validate([
            'nama_barang'  => 'required|string|max:100',
            'satuan'       => 'required|string|max:20',
            'stok'         => 'required|integer|min:0',
            'minimum_stok' => 'required|integer|min:0',
        ]);

        $stokBarang->update($validated);

        return redirect()->route('admin.stok_barangs.index')->with('success', 'Stok barang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $stokBarang = StokBarang::findOrFail($id);
        $stokBarang->delete();

        return redirect()->route('admin.stok_barangs.index')->with('success', 'Stok barang berhasil dihapus.');
    }
}
