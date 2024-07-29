@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Defects List</h1>
    <a href="{{ route('defects.create') }}" class="btn btn-primary">Add Defect</a>
    <a href="{{ route('dash') }}" class="btn btn-secondary mb-3">Lihat Dashboard</a> <!-- Tombol untuk melihat dashboard -->
    @if(session('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif
    <table class="table mt-2">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tanggal</th>
                <th>Cell</th>
                <th>Defect</th>
                <th>Images</th> <!-- Tambahkan kolom untuk gambar -->
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($defects as $defect)
                <tr>
                    <td>{{ $defect->id }}</td>
                    <td>{{ $defect->tanggal }}</td>
                    <td>{{ $defect->cell }}</td>
                    <td>
                        <ul>
                            @foreach(explode(';', $defect->defect) as $defectItem)
                                <li>{{ $defectItem }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        @if($defect->images)
                            @php
                                $images = json_decode($defect->images);
                            @endphp
                            @foreach($images as $image)
                                <img src="{{ asset('storage/app/public/'. $image) }}" alt="Defect Image" style="max-width: 100px; max-height: 100px; margin-right: 10px;">
                            @endforeach
                        @else
                            No Images
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('defects.show', $defect) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('defects.edit', $defect) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('defects.destroy', $defect) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
