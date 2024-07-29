@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Defect Details</h1>
    <table class="table">
        <tr>
            <th>ID</th>
            <td>{{ $defect->id }}</td>
        </tr>
        <tr>
            <th>Tanggal</th>
            <td>{{ $defect->tanggal }}</td>
        </tr>
        <tr>
            <th>Cell</th>
            <td>{{ $defect->cell }}</td>
        </tr>
        <tr>
            <th>Defect</th>
            <td>{{ $defect->defect }}</td>
        </tr>
        <tr>
            <th>Description</th>
            <td>{{ $defect->description }}</td>
        </tr>
    </table>
    <a href="{{ route('defects.index') }}" class="btn btn-primary">Back to List</a>
</div>
@endsection