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
        .passrate {
            font-weight: bold;
        }
        .passrate.high {
            color: green;
        }
        .passrate.low {
            color: red;
        }
    </style>
    <script>
        // Auto refresh setiap 5 detik
        setTimeout(function(){
            location.reload();
        }, 5000);
    </script>
</head>
<body>
    @if($defects->isNotEmpty())
        @php
            $defect = $defects->last(); // Mengambil inputan terakhir
            $images = json_decode($defect->images);
            $qtyok = $defect->qtyok;
            $qtynok = $defect->qtynok;
            $passrate = ($qtyok + $qtynok) > 0 ? $qtyok / ($qtyok + $qtynok) * 100 : 0; // Menghitung passrate dalam persen
            $passrateClass = $passrate <= 90 ? 'low' : 'high'; // Menentukan kelas berdasarkan nilai passrate
        @endphp
        <div class="judul">
            <div class="subjudul">
                <h1>Daily End Of Line Validation</h1>
            </div>
        </div>
        <div class="bagi2" style="height: calc(100vh - 100px); overflow-y: auto;">
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
                            <td>: {{ $qtyok }}</td> <!-- Menjaga sejajar -->
                        </tr>
                        <tr>
                            <td style="color: red">Not OK</td>
                            <td>: {{ $qtynok }}</td> <!-- Menjaga sejajar -->
                        </tr>
                        <tr>
                            <td class="passrate {{ $passrateClass}}">Passrate</td>
                            <td class="passrate {{ $passrateClass }}">
                                : {{ number_format($passrate, 2) }}%
                            </td> <!-- Menampilkan passrate dalam persen dengan 2 desimal -->
                        </tr>
                    </table>
                </div>
            </div>
            <div class="kanan">
                <h1 class="judul" style="margin-left: 70px;">Detail Defect</h1>
                @if($defect->images)
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
    @else
        <div>Tidak ada defect yang tersedia.</div>
    @endif
</body>
</html>