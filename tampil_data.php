<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Negara dan Ibukota Asia</title>
    <style>
        body { 
            font-family: sans-serif; 
            line-height: 1.6; 
            padding: 20px; 
        }
    </style>
</head>
<body>

    <h2>Daftar Negara dan Ibukota di Asia</h2>

    <?php
    // Array yang disediakan dalam soal
    $negaraAsia = [
        "Indonesia" => "Jakarta",
        "India" => "New Delhi",
        "Jepang" => "Tokyo",
        "Cina" => "Beijing",
        "Malaysia" => "Kuala Lumpur",
        "Filipina" => "Manila",
        "Korea Utara" => "Pyongyang",
        "Korea Selatan" => "Seoul",
        "Iran" => "Teheran",
        "Irak" => "Baghdad", // Koreksi dari "Bahgdad"
        "Vietnam" => "Hanoi",
        "Thailand" => "Bangkok",
    ];

    // Menggunakan tag <ol> (ordered list) agar penomoran dibuat otomatis
    echo "<ol>";

    // Melakukan perulangan (looping) pada setiap elemen di dalam array
    foreach ($negaraAsia as $negara => $ibukota) {
        // Mencetak setiap item list dengan format yang diinginkan
        echo "<li>$negara ibukotanya $ibukota</li>";
    }

    echo "</ol>";
    ?>

</body>
</html>