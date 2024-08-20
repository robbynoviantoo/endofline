@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Defects List</h1>
        <div class="mb-2">
            <a href="{{ route('defects.create') }}" class="btn btn-primary">Add Defect</a>
            <form action="{{ route('dash') }}" method="GET" class="mb-3 mt-2">
                <div class="form-group">
                    <label for="cell">Select Cell:</label>
                    <select name="cell" id="cell" class="form-control">
                        <option value="">All Cells</option>
                        @foreach ($cells as $cell)
                            <option value="{{ $cell }}" {{ request('cell') == $cell ? 'selected' : '' }}>
                                {{ $cell }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-secondary mt-4">Lihat Dashboard</button>
            </form>
            <form action="{{ route('filter.cell') }}" method="GET" class="d-inline">
                <div class="form-group d-inline">
                    <select name="cell" class="form-control form-control-sm d-inline" onchange="this.form.submit()">
                        <option value="">Pilih Cell</option>
                        @foreach ($cells as $cell)
                            <option value="{{ $cell }}" {{ request('cell') == $cell ? 'selected' : '' }}>
                                {{ $cell }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
        @if (session('success'))
            <div class="alert alert-success mt-2">{{ session('success') }}</div>
        @endif
        <div class="table-responsive mb-2">
            <table id="defectsTable" class="table table-striped mt-2">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Cell</th>
                        <th>Defect</th>
                        <th>Images</th> <!-- Tambahkan kolom untuk gambar -->
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($defects as $defect)
                        <tr>
                            <td>{{ $defect->tanggal }}</td>
                            <td>{{ $defect->cell }}</td>
                            <td>
                                <ul>
                                    @foreach (json_decode($defect->defect) as $defectItem)
                                        <li>{{ $defectItem }}</li>
                                    @endforeach
                                </ul>
                                
                            </td>
                            <td>
                                @if (!empty($defect->images))
                                    @php
                                        // Decode JSON jika perlu
                                        $images = is_string($defect->images) ? json_decode($defect->images, true) : $defect->images;
                                    @endphp
                                    @if (is_array($images))
                                        @foreach ($images as $imageGroup)
                                            @if (is_array($imageGroup))  <!-- Menangani array dalam array -->
                                                @foreach ($imageGroup as $image)
                                                    <img src="{{ asset('storage/app/public/' . $image) }}" alt="Defect Image"
                                                        style="max-width: 100px; max-height: 100px; margin-right: 10px;">
                                                @endforeach
                                            @else
                                                <!-- Jika tidak ada gambar -->
                                                No Images
                                            @endif
                                        @endforeach
                                    @else
                                        <!-- Jika tidak ada gambar -->
                                        No Images
                                    @endif
                                @else
                                    <!-- Jika tidak ada gambar -->
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
    </div>
@endsection
