@extends('layouts.app')

@section('content')

<div class="container">
    <h1>Tambah Defect</h1>
    <form action="{{ route('defects.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="tanggal">Tanggal:</label>
            <input type="date" id="tanggal" name="tanggal" class="form-control" required value="{{ old('tanggal') }}">
        </div>
        <div class="form-group">
            <label for="cell">Cell:</label>
            <input type="text" id="cell" name="cell" class="form-control" required value="{{ old('cell') }}">
        </div>
        <div class="form-group">
            <label for="qtyok">QTY OK:</label>
            <input type="number" id="qtyok" name="qtyok" class="form-control" required value="{{ old('qtyok') }}">
        </div>
        <div class="form-group">
            <label for="qtynok">QTY Not OK:</label>
            <input type="number" id="qtynok" name="qtynok" class="form-control" required value="{{ old('qtynok') }}">
        </div>
        <div class="row">
            <div class="col-xl-6">
                <div class="form-group" id="defects-left">
                    <label for="defect">Defect:</label>
                    <input type="text" name="defect[]" class="form-control" value="{{ old('defect.0') }}">
                </div>
            </div>
            <div class="col-xl-6">
                <div class="form-group" id="defects-right">
                    <label for="defect"></label>
                    <input type="text" name="defect[]" class="form-control" value="{{ old('defect.1') }}">
                </div>
            </div>
        </div>
        <button type="button" onclick="addDefectInput()">Tambah Defect</button>
        <script>
            function addDefectInput() {
                var newDefectInputLeft = document.createElement('div');
                newDefectInputLeft.style.marginTop = "10px"; // Menambahkan sedikit margin
                newDefectInputLeft.innerHTML = '<input type="text" name="defect[]" class="form-control" required>';
                document.getElementById('defects-left').appendChild(newDefectInputLeft);

                var newDefectInputRight = document.createElement('div');
                newDefectInputRight.style.marginTop = "10px"; // Menambahkan sedikit margin
                newDefectInputRight.innerHTML = '<input type="text" name="defect[]" class="form-control" required>';
                document.getElementById('defects-right').appendChild(newDefectInputRight);
            }
        </script>
        
        <!-- Input gambar -->
        <div class="form-group">
            <label for="images">Gambar:</label>
            <input type="file" id="images" name="images[]" class="form-control" multiple>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>

@endsection
