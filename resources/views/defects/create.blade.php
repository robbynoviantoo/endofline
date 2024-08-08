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
        <div class="form-group">
            <label for="qtyok">Qty OK:</label>
            <input type="number" id="qtyok" name="qtyok" class="form-control" required value="{{ old('qtyok') }}">
        </div>
        <div class="form-group">
            <label for="qtynok">Qty Not OK:</label>
            <input type="number" id="qtynok" name="qtynok" class="form-control" required value="{{ old('qtynok') }}">
        </div>
        <div class="row" id="defect-container">
            <div class="col-xl-6 defect-column" id="defects-left">
                <div class="form-group">
                    <label for="defect">Defect:</label>
                    <input type="text" name="defect[]" class="form-control" value="{{ old('defect.0') }}">
                </div>
            </div>
            <div class="col-xl-6 defect-column" id="defects-right">
                <div class="form-group">
                    <label for="defect"></label>
                    <input type="text" name="defect[]" class="form-control" value="{{ old('defect.1') }}">
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-primary" style="margin-top: 10px;" onclick="addDefectInput()">Tambah Defect</button>

        <!-- Input gambar -->
        <div class="form-group">
            <label for="images">Gambar:</label>
            <input type="file" id="images" name="images[]" class="form-control" multiple>
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Simpan</button>
    </form>
</div>

<script>
    function addDefectInput() {
        var defectsLeft = document.getElementById('defects-left');
        var defectsRight = document.getElementById('defects-right');
        var defectContainer = document.getElementById('defect-container');

        // Cek apakah salah satu kolom defect sudah terisi
        var leftInput = defectsLeft.querySelector('input[type="text"]');
        var rightInput = defectsRight.querySelector('input[type="text"]');

        if (leftInput.value.trim() === '' && rightInput.value.trim() === '') {
            alert('Silakan isi salah satu kolom defect sebelum menambah input baru.');
            return;
        }

        // Tambahkan input defect baru
        var newDefectInputLeft = document.createElement('div');
        newDefectInputLeft.className = 'form-group new-defect-input';
        newDefectInputLeft.innerHTML = '<input type="text" name="defect[]" class="form-control" placeholder="Defect">';

        var newDefectInputRight = document.createElement('div');
        newDefectInputRight.className = 'form-group new-defect-input';
        newDefectInputRight.innerHTML = '<input type="text" name="defect[]" class="form-control" placeholder="Defect">';

        // Tambahkan ke container
        defectContainer.appendChild(newDefectInputLeft);
        defectContainer.appendChild(newDefectInputRight);
    }
</script>

<style>
    @media (max-width: 768px) {
        #defect-container {
            display: block;
        }

        .defect-column {
            width: 100% !important;
        }

        .new-defect-input {
            margin-top: 20px;
        }
    }
</style>

@endsection
