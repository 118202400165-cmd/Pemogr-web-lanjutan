<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Analisis Suhu Udara</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container { 
            background-color: #fff;
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            width: 500px;
        }
        h2 { 
            text-align: center; 
            color: #333; 
            margin-top: 0;
        }
        p { 
            font-size: 1.1em; 
            line-height: 1.7; 
            color: #555;
        }
        strong {
            color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Analisis Data Suhu Udara 🌡️</h2>

    <?php
    // Array suhu yang disediakan
    $suhuUdara = [29, 35, 38, 31, 34, 36, 39, 33, 34, 40, 35, 32, 37, 34, 31, 36, 33, 39, 30, 33, 41];

    // a. Menghitung Rata-rata Suhu
    $rataRata = array_sum($suhuUdara) / count($suhuUdara);
    echo "<p>Rata-rata suhu adalah <strong>" . number_format($rataRata, 1) . "</strong></p>";

    // Menghapus nilai duplikat dari array
    $suhuUnik = array_unique($suhuUdara);
    // Mengurutkan suhu dari yang terendah ke tertinggi
    sort($suhuUnik);

    // b. Menampilkan 5 Suhu Paling Rendah
    $suhuTerendah = array_slice($suhuUnik, 0, 5);
    echo "<p>5 suhu paling rendah adalah <strong>" . implode(", ", $suhuTerendah) . "</strong></p>";

    // c. Menampilkan 5 Suhu Paling Tinggi
    $suhuTertinggi = array_slice($suhuUnik, -5);
    echo "<p>5 suhu paling tinggi adalah <strong>" . implode(", ", $suhuTertinggi) . "</strong></p>";
    ?>
</div>

</body>
</html>