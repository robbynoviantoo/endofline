<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard Defect</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('public/css/style.css') }}">
    <style>
        .passrate {
            font-weight: bold;
        }

        .passrate.high {
            color: green;
        }

        .passrate.low {
            color: red;
        }

        .defect-item {
            margin-bottom: 1rem;
        }

        .defect-image {
            max-width: 300px;
            max-height: 300px;
            margin-right: 40px;
        }

        .defect-description {
            display: inline-block;
            vertical-align: top;
            margin-top: 5px;
        }
    </style>
    <script>
        // Auto refresh setiap 5 detik
        setTimeout(function() {
            location.reload();
        }, 5000);
    </script>
</head>

<body>
    @if ($defect)
        <div class="judul">
            <div class="subjudul">
                <h1>Daily End Of Line Validation</h1>
            </div>
        </div>
        <div class="bagi2">
            <div class="kiri">
                <div class="content">
                    <h1>Time & Place</h1>
                    <table class="table">
                        <tr>
                            <td>Tanggal</td>
                            <td>: {{ $defect->tanggal }}</td>
                        </tr>
                        <tr>
                            <td>Cell</td>
                            <td>: {{ $defect->cell }}</td>
                        </tr>
                    </table>
                </div>
                <div class="content">
                    <h1>QTY CHECK</h1>
                    <table class="table">
                        <tr>
                            <td style="color: green">OK</td>
                            <td>: {{ $qtyokTotal }}</td>
                        </tr>
                        <tr>
                            <td style="color: red">Not OK</td>
                            <td>: {{ $qtynokTotal }}</td>
                        </tr>
                        <tr>
                            <td class="passrate {{ $passrateClass }}">Passrate</td>
                            <td class="passrate {{ $passrateClass }}">
                                : {{ number_format($passrate, 2) }}%
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="kanan">
                <h1 class="judul" style="margin-left: 70px;">Detail Defect</h1>
                @if (!empty($defect->images))
                    <div class="defect-container">
                        @php
                            $images = is_string($defect->images) ? json_decode($defect->images, true) : $defect->images;
                            $descriptions = is_string($defect->defect) ? json_decode($defect->defect, true) : $defect->defect;
                        @endphp
                        
                        @foreach ($images as $index => $imageGroup)
                            @if (is_array($imageGroup))
                                @foreach ($imageGroup as $imageIndex => $image)
                                    <div class="defect-item">
                                        <img src="{{ asset('storage/app/public/' . $image) }}" alt="Defect Image" class="defect-image">
                                        <div class="defect-description">
                                            {{ $defect->defect[$index] ?? 'Keterangan tidak tersedia' }}
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div>Tidak ada gambar.</div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div>Tidak ada gambar.</div>
                @endif
            </div>
            
        </div>
    @else
        <div>Tidak ada defect yang tersedia.</div>
    @endif
</body>

</html>