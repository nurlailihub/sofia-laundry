<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use Illuminate\Http\Request;

class LayananWebController extends Controller
{
    public function index()
    {
        $layanans = Layanan::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.layanans.index', compact('layanans'));
    }

    public function create()
    {
        return view('admin.layanans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:100',
            'harga_per_kg' => 'required|numeric|min:0',
            'keterangan'   => 'nullable|string',
        ]);

        Layanan::create($request->only(['nama_layanan', 'harga_per_kg', 'keterangan']));

        return redirect()->route('admin.layanans.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $layanan = Layanan::findOrFail($id);
        return view('admin.layanans.edit', compact('layanan'));
    }

    public function update(Request $request, $id)
    {
        $layanan = Layanan::findOrFail($id);

        $request->validate([
            'nama_layanan' => 'required|string|max:100',
            'harga_per_kg' => 'required|numeric|min:0',
            'keterangan'   => 'nullable|string',
        ]);

        $layanan->update($request->only(['nama_layanan', 'harga_per_kg', 'keterangan']));

        return redirect()->route('admin.layanans.index')->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $layanan = Layanan::findOrFail($id);
        $layanan->delete();

        return redirect()->back()->with('success', 'Layanan berhasil dihapus.');
    }
}
