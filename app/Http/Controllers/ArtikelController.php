<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Http\Requests\UpdateArtikelRequest;

class ArtikelController extends Controller
{
    public function edit($id)
    {
        $artikel = Artikel::findOrFail($id);
        return view('edit', compact('artikel'));
    }

    public function update(UpdateArtikelRequest $request, $id)
    {
        $artikel = Artikel::findOrFail($id);
        $artikel->update($request->validated());

        return redirect()->route('artikels.index')
            ->with('success', 'Artikel updated successfully.');
    }
}
