@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Defect</h1>
    <form action="{{ route('defects.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="tanggal">Tanggal:</label>
            <input type="date" id="tanggal" name="tanggal" class="form-control" required value="{{ old('tanggal', date('Y-m-d')) }}">
        </div>
        <div class="form-group">
            <label for="cell">Cell:</label>
            <select id="cell" name="cell" class="form-control @error('cell') is-invalid @enderror" required>
                <option value="">Pilih Cell</option>
                @for ($i = 1; $i <= 12; $i++)
                    @php
                        $cellValue = sprintf('B-%02d', $i);
                    @endphp
                    <option value="{{ $cellValue }}" {{ old('cell') == $cellValue ? 'selected' : '' }}>
                        {{ $cellValue }}
                    </option>
                @endfor
            </select>
            @error('cell')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- ID Pass, Qty OK, Qty Not OK -->
        <div id="idpass-entries" class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="idpass">ID Pass:</label>
                    <input type="text" name="idpass[]" class="form-control" placeholder="ID Pass" value="{{ old('idpass.0') }}" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="qtyok">Qty OK:</label>
                    <input type="number" name="qtyok[]" class="form-control" placeholder="Qty OK" value="{{ old('qtyok.0') }}" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="qtynok">Qty Not OK:</label>
                    <input type="number" name="qtynok[]" class="form-control" placeholder="Qty Not OK" value="{{ old('qtynok.0') }}" required>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-primary" style="margin-bottom: 10px" onclick="addIdPassEntry()">Tambah ID Pass Entry</button>

        <!-- Defect -->
        <div id="defect-entries">
            @for ($i = 0; $i < 3; $i++)
                <div class="defect-entry">
                    <div class="form-group">
                        <label for="defect">Defect:</label>
                        <input type="text" name="defect[]" class="form-control" placeholder="Defect" value="{{ old('defect.' . $i) }}">
                    </div>
                </div>
            @endfor
        </div>
        <button type="button" class="btn btn-primary" style="margin-top: 10px;" onclick="addDefectEntry()">Tambah Defect</button>

        <!-- Input gambar -->
        <div class="form-group">
            <label for="images">Gambar:</label>
            <input type="file" id="images" name="images[]" class="form-control" multiple>
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Simpan</button>
    </form>
</div>

<script>
function addIdPassEntry() {
    var container = document.getElementById('idpass-entries');

    // Buat div untuk ID Pass
    var idPassDiv = document.createElement('div');
    idPassDiv.className = 'col-md-4';
    idPassDiv.innerHTML = `
        <div class="form-group mb-0">
            <label for="idpass">ID Pass:</label>
            <input type="text" name="idpass[]" class="form-control" placeholder="ID Pass" required>
        </div>
    `;
    
    // Buat div untuk Qty OK
    var qtyOkDiv = document.createElement('div');
    qtyOkDiv.className = 'col-md-4';
    qtyOkDiv.innerHTML = `
        <div class="form-group mb-0">
            <label for="qtyok">Qty OK:</label>
            <input type="number" name="qtyok[]" class="form-control" placeholder="Qty OK" required>
        </div>
    `;
    
    // Buat div untuk Qty Not OK
    var qtyNokDiv = document.createElement('div');
    qtyNokDiv.className = 'col-md-4';
    qtyNokDiv.innerHTML = `
        <div class="form-group mb-0">
            <label for="qtynok">Qty Not OK:</label>
            <input type="number" name="qtynok[]" class="form-control" placeholder="Qty Not OK" required>
        </div>
    `;

    // Tambahkan kolom ke dalam container
    container.appendChild(idPassDiv);
    container.appendChild(qtyOkDiv);
    container.appendChild(qtyNokDiv);

    // Tambahkan baris baru jika container tidak kosong
    if (container.children.length % 3 === 0) {
        var rowBreak = document.createElement('div');
        rowBreak.className = 'w-100'; // Force break to new line in flexbox
        container.appendChild(rowBreak);
    }
}

function addDefectEntry() {
    var container = document.getElementById('defect-entries');

    var newDefectEntry = document.createElement('div');
    newDefectEntry.className = 'defect-entry';
    newDefectEntry.innerHTML = `
        <div class="form-group">
            <label for="defect">Defect:</label>
            <input type="text" name="defect[]" class="form-control" placeholder="Defect">
        </div>
    `;
    container.appendChild(newDefectEntry);
}
</script>

<style>
    .form-group {
        margin-bottom: 1rem;
    }

    .row {
        display: flex;
        align-items: center;
    }

    .col-md-4 {
        margin-bottom: 1rem;
    }

    @media (max-width: 768px) {
        .row {
            display: block;
        }
    }
</style>

@endsection
