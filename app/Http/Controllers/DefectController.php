<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Defect;
use Illuminate\Support\Facades\Storage;

class DefectController extends Controller
{
    // Menampilkan daftar defect
    public function index(Request $request)
    {
        $query = Defect::query();
        
        // Ambil daftar cell yang unik
        $cells = Defect::distinct()->pluck('cell');
        
        // Filter berdasarkan cell jika ada
        if ($request->has('cell') && $request->cell != '') {
            $query->where('cell', $request->cell);
        }

        // Urutkan berdasarkan tanggal descending
        $defects = $query->orderBy('tanggal', 'desc')->get();

        // Hitung total qtyok jika diperlukan
        foreach ($defects as $defect) {
            $defect->total_qtyok = array_sum(is_array($defect->qtyok) ? $defect->qtyok : json_decode($defect->qtyok, true));
        }
        
        return view('defects.index', compact('defects', 'cells'));
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
            'idpass.*' => 'nullable|string',
            'qtyok.*' => 'nullable|integer',
            'qtynok.*' => 'nullable|integer',
            'defect.*' => 'nullable|string',
            'images.*.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20000',
        ]);

        // Menyimpan data defect baru
        $defect = Defect::create([
            'tanggal' => $validated['tanggal'],
            'cell' => $validated['cell'],
            'idpass' => json_encode($request->input('idpass')), // Store as JSON
            'qtyok' => json_encode($request->input('qtyok')),   // Store as JSON
            'qtynok' => json_encode($request->input('qtynok')), // Store as JSON
            'defect' => isset($validated['defect']) ? json_encode($validated['defect']) : null,
        ]);

        // Menyimpan gambar jika ada
        $imagePaths = [];
        foreach ($request->file('images', []) as $index => $images) {
            foreach ($images as $image) {
                $path = $image->store('images', 'public');
                $imagePaths[$index][] = $path;
            }
        }
        $defect->images = json_encode($imagePaths);
        $defect->save();

        return redirect()->route('defects.index')->with('success', 'Defect added successfully!');
    }

    // Menampilkan detail defect
    public function show($id)
    {
        $defect = Defect::findOrFail($id);

        // Decode JSON fields if necessary
        $defect->images = json_decode($defect->images, true);
        $defect->qtyok = is_string($defect->qtyok) ? json_decode($defect->qtyok, true) : $defect->qtyok;
        $defect->qtynok = is_string($defect->qtynok) ? json_decode($defect->qtynok, true) : $defect->qtynok;
        $defect->defect = is_string($defect->defect) ? json_decode($defect->defect, true) : $defect->defect;

        // Calculate totals
        $qtyokTotal = is_array($defect->qtyok) ? array_sum($defect->qtyok) : (is_numeric($defect->qtyok) ? (int)$defect->qtyok : 0);
        $qtynokTotal = is_array($defect->qtynok) ? array_sum($defect->qtynok) : (is_numeric($defect->qtynok) ? (int)$defect->qtynok : 0);

        // Calculate passrate
        $passrate = $qtyokTotal + $qtynokTotal > 0 ? ($qtyokTotal / ($qtyokTotal + $qtynokTotal)) * 100 : 0;
        $passrateClass = $passrate <= 90 ? 'low' : 'high';

        // Pass data to the view
        return view('defects.show', compact('defect', 'qtyokTotal', 'qtynokTotal', 'passrate', 'passrateClass'));
    }

    // Menampilkan formulir untuk mengedit defect
    public function edit(Defect $defect)
    {
        // Decode JSON fields if necessary
        $idpassArray = is_string($defect->idpass) ? json_decode($defect->idpass, true) : $defect->idpass;
        $qtyokArray = is_string($defect->qtyok) ? json_decode($defect->qtyok, true) : $defect->qtyok;
        $qtynokArray = is_string($defect->qtynok) ? json_decode($defect->qtynok, true) : $defect->qtynok;
        $images = is_string($defect->images) ? json_decode($defect->images, true) : [];

        return view('defects.edit', compact('defect', 'idpassArray', 'qtyokArray', 'qtynokArray', 'images'));
    }

    public function update(Request $request, Defect $defect)
    {
        // Validasi input
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'cell' => 'required|string',
            'idpass' => 'nullable|array',
            'qtyok' => 'nullable|array',
            'qtynok' => 'nullable|array',
            'defect' => 'nullable|array',
            'images.*.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20000',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'string',
        ]);

        // Memperbarui data di database
        $defect->update([
            'tanggal' => $validated['tanggal'],
            'cell' => $validated['cell'],
            'idpass' => json_encode($validated['idpass']),
            'qtyok' => json_encode($validated['qtyok']),
            'qtynok' => json_encode($validated['qtynok']),
            'defect' => isset($validated['defect']) ? json_encode($validated['defect']) : null,
        ]);

        // Menghapus gambar yang dipilih
        if ($request->has('remove_images')) {
            $imagesToRemove = $request->input('remove_images');
            $existingImages = is_string($defect->images) ? json_decode($defect->images, true) : [];

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
            $imagePaths = is_string($defect->images) ? json_decode($defect->images, true) : [];
            foreach ($request->file('images', []) as $index => $images) {
                foreach ($images as $image) {
                    $path = $image->store('images', 'public');
                    $imagePaths[$index][] = $path;
                }
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
        // Hapus gambar terkait jika ada
        $images = is_string($defect->images) ? json_decode($defect->images, true) : [];
        foreach ($images as $imagePaths) {
            foreach ($imagePaths as $imagePath) {
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }
        }

        $defect->delete();
    
        return redirect()->route('defects.index')->with('success', 'Defect deleted successfully!');
    }

    // Menampilkan dashboard
    public function dashboard(Request $request)
    {
        $cell = $request->query('cell'); // Ambil parameter cell dari query string
        $defectsQuery = Defect::query();
        
        if ($cell) {
            $defectsQuery->where('cell', $cell);
        }
    
        // Ambil data defect berdasarkan filter
        $defects = $defectsQuery->get(); 
        $cells = Defect::distinct()->pluck('cell'); // Ambil daftar cell yang tersedia
    
        // Inisialisasi variabel total
        $qtyokTotal = 0;
        $qtynokTotal = 0;
    
        // Loop untuk menghitung total qtyok dan qtynok
        foreach ($defects as $defect) {
            // Cek jika qtyok dan qtynok dalam format JSON atau string yang perlu didecode
            $qtyokArray = is_string($defect->qtyok) ? json_decode($defect->qtyok, true) : $defect->qtyok;
            $qtynokArray = is_string($defect->qtynok) ? json_decode($defect->qtynok, true) : $defect->qtynok;
    
            // Cek jika hasil json_decode menghasilkan array
            if (is_array($qtyokArray)) {
                $qtyokTotal += array_sum($qtyokArray);
            } else {
                $qtyokTotal += is_numeric($defect->qtyok) ? (int)$defect->qtyok : 0;
            }
    
            if (is_array($qtynokArray)) {
                $qtynokTotal += array_sum($qtynokArray);
            } else {
                $qtynokTotal += is_numeric($defect->qtynok) ? (int)$defect->qtynok : 0;
            }
        }
    
        // Hitung total dan passrate
        $total = $qtyokTotal + $qtynokTotal;
        $passrateTotal = $total > 0 ? ($qtyokTotal / $total) * 100 : 0;
        $passrateClass = $passrateTotal <= 90 ? 'low' : 'high';
    
        return view('dash', compact('defects', 'cells', 'qtyokTotal', 'qtynokTotal', 'passrateTotal', 'passrateClass'));
    }
}
