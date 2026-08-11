<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\TarifAntarJemput;
use Illuminate\Http\Request;

class TarifAntarJemputController extends Controller
{
    public function index()
    {
        $tarifs = TarifAntarJemput::all();
        return view('admin.tarif.index', compact('tarifs'));
    }

    public function update(Request $request, $id)
    {
        $tarif = TarifAntarJemput::findOrFail($id);

        $request->validate([
            'harga'      => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $tarif->update([
            'harga'      => $request->harga,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('admin.tarif.index')
            ->with('success', 'Tarif ' . $tarif->label . ' berhasil diperbarui.');
    }
}
