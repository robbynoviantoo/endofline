<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard Defect</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="public/css/style.css">
    <style>
        .defect-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center; /* Menyelaraskan konten ke tengah */
        }
        .defect-item {
            width: 200px;
            margin: 10px;
            text-align: center;
        }
        .defect-image {
            max-width: 100%;
            height: auto;
        }
        .defect-description {
            margin-top: 5px;
            font-size: 0.9em;
            color: #555;
            text-align: center; /* Menyelaraskan teks ke tengah */
        }
    </style>
</head>
<body style="height: 100vh; overflow-y: auto;">
    @foreach($defects as $defect)
    <div class="judul">
        <div class="subjudul">
            <h1>Daily End Of Line Validation</h1>
        </div>
    </div>
    <div class="bagi2" style="height: calc(100vh - 100px); overflow-y: auto;">
        <div class="kiri">
            <div class="content">
                <h1>Time & Place</h1>
                <div>Tanggal: {{ $defect->tanggal }}</div>
                <div>Cell: {{ $defect->cell }}</div>
            </div>
            <div class="content">
                <h1>QTY Status</h1>
                <div>QTY OK: {{ $defect->qtyok }}</div>
                <div>QTY Not OK: {{ $defect->qtynok }}</div>
            </div>
        </div>
        <div class="kanan">
            @if($defect->images)
                @php
                    $images = json_decode($defect->images);
                @endphp
                <div class="defect-container">
                    @foreach($images as $index => $image)
                    <div class="defect-item">
                        <img src="{{ asset('storage/app/public/' . $image) }}" alt="Defect Image" class="defect-image">
                        <div class="defect-description">
                            {{ explode(';', $defect->defect)[$index] ?? 'Keterangan tidak tersedia' }}
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div>Tidak ada gambar.</div>
            @endif
        </div>
    </div>
    @endforeach
</body>
</html>