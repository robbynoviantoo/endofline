@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Defect</h1>
    <form action="{{ route('defects.update', $defect->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="tanggal">Tanggal:</label>
            <input type="date" id="tanggal" name="tanggal" class="form-control" value="{{ old('tanggal', $defect->tanggal) }}" required>
        </div>

        <div class="form-group">
            <label for="cell">Cell:</label>
            <input type="text" id="cell" name="cell" class="form-control" value="{{ old('cell', $defect->cell) }}" required>
        </div>

        <div class="form-group">
            <label for="qtyok">QTY OK:</label>
            <input type="number" id="qtyok" name="qtyok" class="form-control" value="{{ old('qtyok', $defect->qtyok) }}" required>
        </div>

        <div class="form-group">
            <label for="qtynok">QTY Not OK:</label>
            <input type="number" id="qtynok" name="qtynok" class="form-control" value="{{ old('qtynok', $defect->qtynok) }}" required>
        </div>

        <div class="form-group">
            <label for="defect">Defect:</label>
            <div class="row">
                <div class="col-xl-6">
                    @foreach(explode(';', $defect->defect) as $index => $defectItem)
                        <input type="text" name="defect[]" class="form-control mb-2" value="{{ old('defect.' . $index, $defectItem) }}">
                    @endforeach
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="images">Images:</label>
            <input type="file" id="images" name="images[]" class="form-control" multiple>
            @if($defect->images)
                @php
                    $images = json_decode($defect->images);
                @endphp
                <div class="mt-2">
                    @foreach($images as $index => $image)
                    <div class="d-inline-block position-relative">
                        <img src="{{ asset('storage/app/public/' . $image) }}" alt="Defect Image" style="max-width: 100px; max-height: 100px; margin-right: 10px;">
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeImage('{{ route('defects.removeImage', ['defect' => $defect->id, 'image' => basename($image)]) }}')">Hapus</button>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<script>
function removeImage(url) {
    if (confirm('Apakah Anda yakin ingin menghapus gambar ini?')) {
        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (response.ok) {
                return response.json(); // Mengembalikan respons JSON jika berhasil
            } else {
                throw new Error('Gagal menghapus gambar.');
            }
        })
        .then(data => {
            // Tangani respons dari server
            if (data.success) {
                location.reload(); // Refresh halaman setelah penghapusan berhasil
            } else {
                throw new Error('Gagal menghapus gambar.');
            }
        })
        .catch(error => {
            // Menangani kesalahan
            console.error('Terjadi kesalahan:', error);
            alert('Terjadi kesalahan saat menghapus gambar.');
        });
    }
}


</script>
@endsection
