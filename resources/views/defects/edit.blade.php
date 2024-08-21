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

        <div class="form-group" id="idpass-container">
            <label for="idpass">ID Pass:</label>
            @foreach($idpassArray as $index => $idpassItem)
                <input type="text" name="idpass[]" class="form-control mb-2" value="{{ old('idpass.' . $index, $idpassItem) }}">
            @endforeach
            <button type="button" class="btn btn-success mt-2" onclick="addIdPassField()">Tambah ID Pass</button>
        </div>
        
        <div class="form-group" id="qtyok-container">
            <label for="qtyok">QTY OK:</label>
            @foreach($qtyokArray as $index => $qtyokItem)
                <input type="number" name="qtyok[]" class="form-control mb-2" value="{{ old('qtyok.' . $index, $qtyokItem) }}">
            @endforeach
            <button type="button" class="btn btn-success mt-2" onclick="addQtyOkField()">Tambah QTY OK</button>
        </div>
        
        <div class="form-group" id="qtynok-container">
            <label for="qtynok">QTY Not OK:</label>
            @foreach($qtynokArray as $index => $qtynokItem)
                <input type="number" name="qtynok[]" class="form-control mb-2" value="{{ old('qtynok.' . $index, $qtynokItem) }}">
            @endforeach
            <button type="button" class="btn btn-success mt-2" onclick="addQtyNokField()">Tambah QTY Not OK</button>
        </div>

        <div class="form-group">
            <label for="defect">Defect:</label>
            <div class="row">
                <div class="col-xl-6">
                    @foreach(json_decode($defect->defect, true) as $index => $defectItem)
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
                    // Decode images JSON string into an array
                    $images = is_string($defect->images) ? json_decode($defect->images, true) : [];
                @endphp
                <div class="mt-2">
                    @foreach($images as $index => $imageGroup)
                        @if(is_array($imageGroup))  <!-- Check if imageGroup is an array -->
                            @foreach($imageGroup as $image)
                                <div class="d-inline-block position-relative">
                                    <img src="{{ asset('storage/app/public/' . $image) }}" alt="Defect Image"
                                         style="max-width: 100px; max-height: 100px; margin-right: 10px;">
                                         <button type="button" class="btn btn-danger btn-sm mt-1"
                                         onclick="removeImage('{{ route('defects.removeImage', ['defect' => $defect->id, 'image' => basename($image)]) }}', this.parentElement)">Hapus</button>
                                    <input type="hidden" name="remove_images[]" value="{{ basename($image) }}">
                                </div>
                            @endforeach
                        @endif
                    @endforeach
                </div>
            @endif
        </div>

        <button type="submit" class="btn btn-primary mt-3">Update</button>
    </form>
</div>

<script>
function addIdPassField() {
    var container = document.getElementById('idpass-container');
    var input = document.createElement('input');
    input.type = 'text';
    input.name = 'idpass[]';
    input.className = 'form-control mb-2';
    container.appendChild(input);
}

function addQtyOkField() {
    var container = document.getElementById('qtyok-container');
    var input = document.createElement('input');
    input.type = 'number';
    input.name = 'qtyok[]';
    input.className = 'form-control mb-2';
    container.appendChild(input);
}

function addQtyNokField() {
    var container = document.getElementById('qtynok-container');
    var input = document.createElement('input');
    input.type = 'number';
    input.name = 'qtynok[]';
    input.className = 'form-control mb-2';
    container.appendChild(input);
}

function removeImage(url, imageElement) {
    if (confirm('Apakah Anda yakin ingin menghapus gambar ini?')) {
        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Jaringan tidak responsif');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Hapus elemen gambar dari DOM jika penghapusan berhasil
                imageElement.remove();
            } else {
                throw new Error('Gagal menghapus gambar.');
            }
        })
        .catch(error => {
            console.error('Terjadi kesalahan:', error);
            alert('Gagal menghapus gambar.');
        });
    }
}

</script>
@endsection
