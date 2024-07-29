<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Defect;
use Illuminate\Support\Facades\Storage;

class DefectController extends Controller
{
    // Menampilkan daftar defect
    public function index()
    {
        $defects = Defect::all();
        return view('defects.index', compact('defects'));
    }

    // Menampilkan formulir untuk menambahkan defect baru
    public function create()
    {
        return view('defects.create');
    }

    // Menyimpan defect baru ke database
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'cell' => 'required|string',
            'qtyok' => 'required|integer',
            'qtynok' => 'required|integer',
            'defect' => 'required|array',
            'defect.*' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Menyimpan gambar jika ada
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('images', 'public');
                $imagePaths[] = $path;
            }
        }

        // Menambahkan gambar ke data yang akan disimpan
        $validated['images'] = json_encode($imagePaths); // Konversi array ke JSON string

        // Pastikan untuk tidak menyimpan array, tetapi string JSON
        Defect::create([
            'tanggal' => $validated['tanggal'],
            'cell' => $validated['cell'],
            'qtyok' => $validated['qtyok'],
            'qtynok' => $validated['qtynok'],
            'defect' => implode(';', $validated['defect']), // Mengubah array defect menjadi string
            'images' => $validated['images'], // Menyimpan string JSON gambar
        ]);

        return redirect()->route('defects.index')->with('success', 'Defect added successfully!');
    }

    // Menampilkan detail defect
    public function show(Defect $defect)
    {
        return view('defects.show', compact('defect'));
    }

    // Menampilkan formulir untuk mengedit defect
    public function edit(Defect $defect)
    {
        return view('defects.edit', compact('defect'));
    }

    // Memperbarui defect di database
    public function update(Request $request, Defect $defect)
    {
        // Validasi input
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'cell' => 'required|string',
            'qtyok' => 'required|integer',
            'qtynok' => 'required|integer',
            'defect' => 'required|array',
            'defect.*' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'string',
        ]);

        // Memperbarui data di database
        $defect->update([
            'tanggal' => $validated['tanggal'],
            'cell' => $validated['cell'],
            'qtyok' => $validated['qtyok'],
            'qtynok' => $validated['qtynok'],
            'defect' => implode(';', $validated['defect']),
        ]);

        // Menghapus gambar yang dipilih
        if ($request->has('remove_images')) {
            $imagesToRemove = $request->input('remove_images');
            $existingImages = json_decode($defect->images, true);

            foreach ($imagesToRemove as $imageToRemove) {
                $imageToRemovePath = 'images/' . $imageToRemove;
                if (($key = array_search($imageToRemovePath, $existingImages)) !== false) {
                    unset($existingImages[$key]);
                    // Hapus gambar dari penyimpanan publik
                    Storage::disk('public')->delete($imageToRemovePath);
                }
            }

            // Update daftar gambar di database
            $defect->images = json_encode(array_values($existingImages));
            $defect->save();
        }

        // Menyimpan gambar baru jika ada
        if ($request->hasFile('images')) {
            $imagePaths = json_decode($defect->images, true) ?? [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('images', 'public');
                $imagePaths[] = $path;
            }
            $defect->images = json_encode($imagePaths);
            $defect->save();
        }

        return redirect()->route('defects.index')->with('success', 'Defect updated successfully!');
    }

    // Menghapus gambar dari defect
    public function removeImage(Request $request, $defectId, $image)
{
    $defect = Defect::findOrFail($defectId);
    $imageToRemove = 'images/' . $image;
    $images = json_decode($defect->images, true);

    if (($key = array_search($imageToRemove, $images)) !== false) {
        unset($images[$key]);

        if (Storage::disk('public')->exists($imageToRemove)) {
            Storage::disk('public')->delete($imageToRemove);
        }
    }

    $defect->images = json_encode(array_values($images));
    $defect->save();

    return response()->json(['success' => true]); // Kembalikan respons JSON
}



    // Menghapus defect dari database
    public function destroy(Defect $defect)
    {
        $defect->delete();
    
        return redirect()->route('defects.index')->with('success', 'Defect deleted successfully!');
    }

    public function dashboard()
{
    $defects = Defect::all(); // Ambil semua data defect
    return view('dash', compact('defects'));
}
}
