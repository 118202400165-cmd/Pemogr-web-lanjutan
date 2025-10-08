<?php
// 1. Mulai session di baris paling atas
session_start();

// --- Logika untuk mereset riwayat ---
if (isset($_POST['reset_history'])) {
    $_SESSION['conversion_history'] = []; // Kosongkan array riwayat
    header("Location: " . $_SERVER['PHP_SELF']); // Redirect untuk membersihkan POST data
    exit();
}

// 2. Inisialisasi array riwayat di session jika belum ada
if (!isset($_SESSION['conversion_history'])) {
    $_SESSION['conversion_history'] = [];
}

// --- Konfigurasi & Koneksi Database ---
$host = "localhost";
$username = "root";
$password = "";
$database = "tg_db"; 

$koneksi = mysqli_connect($host, $username, $password, $database);
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// --- Proses Form Jika Ada Data yang Dikirim (POST) ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['convert'])) {
    $jumlah = (float) $_POST['jumlah'];
    $kodeMataUang = $_POST['mata_uang'];

    if ($jumlah > 0 && !empty($kodeMataUang)) {
        $stmt = $koneksi->prepare("SELECT nilai_rupiah FROM tb_rates WHERE kode_mata_uang = ?");
        $stmt->bind_param("s", $kodeMataUang);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $rate = $row['nilai_rupiah'];
            $hasil = $jumlah * $rate;
            
            // --- PERUBAHAN 1: Sesuaikan format angka Rupiah ---
            // Format dengan 2 desimal (koma) dan pemisah ribuan (titik).
            $jumlahRupiah = "Rp " . number_format($hasil, 2, ',', '.');
            
            // --- PERUBAHAN 2: Sesuaikan string hasil konversi ---
            // Tambahkan kembali strtoupper() untuk membuat kode mata uang menjadi kapital.
            $hasilString = "$jumlah " . strtoupper($kodeMataUang) . " dikonversi menjadi $jumlahRupiah";
            array_unshift($_SESSION['conversion_history'], $hasilString);

        } else {
            $errorString = "Error: Rate untuk " . strtoupper($kodeMataUang) . " tidak ditemukan.";
            array_unshift($_SESSION['conversion_history'], $errorString);
        }
        $stmt->close();
    } else {
        $errorString = "Error: Mohon isi jumlah dan pilih mata uang dengan benar.";
        array_unshift($_SESSION['conversion_history'], $errorString);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Konverter Mata Uang</title>
    <style>
        /* CSS tidak ada perubahan, tetap sama */
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f4f9; display: flex; justify-content: center; align-items: flex-start; min-height: 100vh; margin: 0; padding: 40px 0; }
        .container { display: flex; gap: 40px; }
        .converter, .history { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .converter { width: 450px; }
        .history { width: 450px; }
        h2, h3 { text-align: center; color: #333; margin-top: 0; margin-bottom: 25px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; color: #555; }
        input, select { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; margin-top: 10px; }
        button:hover { background-color: #0056b3; }
        button.reset { background-color: #dc3545; }
        button.reset:hover { background-color: #c82333; }
        .history-item { margin-bottom: 15px; padding: 15px; border: 1px solid #ddd; border-radius: 4px; background-color: #f9f9f9; word-wrap: break-word; }
        .history-item.error { background-color: #f8d7da; border-color: #f5c6cb; color: #721c24; }
        #history-box { max-height: 400px; overflow-y: auto; padding-right: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="converter">
            <h2>Konverter Mata Uang ke Rupiah</h2>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="jumlah">Jumlah (Mata Uang Asing):</label>
                    <input type="number" id="jumlah" name="jumlah" step="any" placeholder="Contoh: 100000" required>
                </div>
                <div class="form-group">
                    <label for="mata-uang">Dari Mata Uang:</label>
                    <select id="mata-uang" name="mata_uang" required>
                        <option value="">-- Pilih Mata Uang --</option>
                        <?php
                        $query = "SELECT kode_mata_uang, nama_mata_uang FROM tb_rates ORDER BY nama_mata_uang";
                        $result = mysqli_query($koneksi, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . $row['kode_mata_uang'] . "'>" . strtoupper($row['kode_mata_uang']) . " - " . $row['nama_mata_uang'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" name="convert">Konversi</button>
            </form>
        </div>

        <div class="history">
            <h3>Riwayat Konversi</h3>
            <div id="history-box">
                <?php
                if (!empty($_SESSION['conversion_history'])) {
                    foreach ($_SESSION['conversion_history'] as $item) {
                        if (strpos($item, 'Error:') === 0) {
                            echo "<div class='history-item error'>$item</div>";
                        } else {
                            echo "<div class='history-item'>$item</div>";
                        }
                    }
                } else {
                    echo "<p style='text-align:center; color:#888;'>Belum ada riwayat konversi.</p>";
                }
                ?>
            </div>
             <?php if (!empty($_SESSION['conversion_history'])): ?>
                <form action="" method="POST">
                    <button type="submit" name="reset_history" class="reset">Reset Riwayat</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
<?php mysqli_close($koneksi); ?>