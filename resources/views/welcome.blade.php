@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Input Defects</h1>
    <form action="{{ route('defects.store') }}" method="POST">
        @csrf
        <div id="defect-inputs">
            @for ($i = 0; $i < 10; $i++)
                <div class="form-group">
                    <label for="defect-{{ $i }}-tanggal">Tanggal:</label>
                    <input type="date" id="defect-{{ $i }}-tanggal" name="defects[{{ $i }}][tanggal]" class="form-control" required>
                    
                    <label for="defect-{{ $i }}-cell">Cell:</label>
                    <input type="text" id="defect-{{ $i }}-cell" name="defects[{{ $i }}][cell]" class="form-control" placeholder="Cell" required>
                    
                    <label for="defect-{{ $i }}-defect">Defect:</label>
                    <input type="text" id="defect-{{ $i }}-defect" name="defects[{{ $i }}][defect]" class="form-control" placeholder="Defect Name" required>
                    
                    <label for="defect-{{ $i }}-description">Defect Description:</label>
                    <textarea id="defect-{{ $i }}-description" name="defects[{{ $i }}][description]" class="form-control" placeholder="Defect Description"></textarea>
                </div>
            @endfor
        </div>
        <button type="submit" class="btn btn-primary">Submit Defects</button>
    </form>
</div>
@endsection