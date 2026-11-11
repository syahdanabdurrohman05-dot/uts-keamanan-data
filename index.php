<?php
require_once 'database.php';

$pesan = "";
// Simpan data jika ada input baru
if (isset($_POST['simpan_data'])) {
    $kunciDefault = "KUNCI-WARUNG-2026"; // Kunci sah untuk enkripsi saat simpan
    $nama = $_POST['nama_pelanggan'];
    $keterangan = $_POST['keterangan'];

    // Data dienkripsi sebelum masuk database
    $namaEnkripsi = enkripsiData($nama, $kunciDefault);
    $ketEnkripsi = enkripsiData($keterangan, $kunciDefault);

    $stmt = $conn->prepare("INSERT INTO data_rahasia (nama_pelanggan, keterangan) VALUES (?, ?)");
    $stmt->bind_param("ss", $namaEnkripsi, $ketEnkripsi);
    $stmt->execute();
    $pesan = "Data '$nama' berhasil dienkripsi dan masuk database!";
}

// Proses login kunci
if (isset($_POST['login_kunci'])) {
    $_SESSION['user_key'] = $_POST['kunci'];
    header("Location: admin.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Warung Madura NQ - Landing Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0f172a] text-slate-100 min-h-screen">
    <!-- Navbar -->
    <nav class="bg-[#0b1329] border-b border-slate-800 p-4 px-8 flex justify-between items-center">
        <h1 class="font-bold text-lg text-white">🏪 WARUNG MADURA NQ</h1>
        <span class="text-xs text-slate-400">Sistem Informasi Keamanan Data</span>
    </nav>

    <div class="max-w-4xl mx-auto p-6 space-y-8 mt-6">
        <!-- Banner Sambutan -->
        <div class="bg-gradient-to-r from-blue-950 to-[#0b1329] border border-blue-900/40 p-6 rounded-2xl">
            <h2 class="text-xl font-bold">Selamat Datang di Sistem Informasi Warung</h2>
            <p class="text-xs text-slate-400 mt-1">Layanan kasbon dan catatan transaksi dengan enkripsi kriptografi AES-256.</p>
        </div>

        <?php if($pesan): ?>
            <div class="bg-emerald-500/20 border border-emerald-500/40 text-emerald-400 p-3 rounded-xl text-xs">
                <?= $pesan; ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Form Input Kasbon (Di sini input "prabowo") -->
            <div class="bg-[#111c3a] border border-[#1e294b] p-6 rounded-2xl">
                <h3 class="font-bold text-sm text-slate-200 mb-4">✍️ INPUT KASBON (OTOMATIS TERENKRIPSI)</h3>
                <form action="" method="POST" class="space-y-4">
                    <div>
                        <label class="block text-xs text-slate-400 mb-1">Nama Pelanggan (Coba isi "prabowo")</label>
                        <input type="text" name="nama_pelanggan" class="w-full bg-[#090f24] border border-slate-800 rounded-xl p-3 text-xs text-white" placeholder="prabowo" required>
                    </div>
                    <div>
                        <label class="block text-xs text-slate-400 mb-1">Keterangan Barang</label>
                        <input type="text" name="keterangan" class="w-full bg-[#090f24] border border-slate-800 rounded-xl p-3 text-xs text-white" placeholder="Beras 5kg, Kopi 1 renceng" required>
                    </div>
                    <button type="submit" name="simpan_data" class="w-full bg-blue-600 hover:bg-blue-500 py-3 rounded-xl text-xs font-semibold">
                        🔒 Enkripsi & Simpan ke DB
                    </button>
                </form>
            </div>

            <!-- Form Akses Admin via Kunci -->
            <div class="bg-[#111c3a] border border-[#1e294b] p-6 rounded-2xl flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-sm text-slate-200 mb-2">🔑 MASUK HALAMAN ADMIN</h3>
                    <p class="text-xs text-slate-400 mb-4">Masukkan kunci rahasia untuk membuka dekripsi data.</p>
                    
                    <form action="" method="POST" class="space-y-4">
                        <div>
                            <label class="block text-xs text-slate-400 mb-1">Input Kunci / Private Key</label>
                            <input type="text" name="kunci" class="w-full bg-[#090f24] border border-slate-800 rounded-xl p-3 text-xs text-emerald-400 font-mono" placeholder="Ketik kunci di sini..." required>
                            <p class="text-[10px] text-slate-500 mt-1">Kunci Sah: <code class="text-slate-300">KUNCI-WARUNG-2026</code> (Jika isi selain ini, isi data hancur/rusak).</p>
                        </div>
                        <button type="submit" name="login_kunci" class="w-full bg-emerald-600 hover:bg-emerald-500 py-3 rounded-xl text-xs font-semibold">
                            Buka Halaman Admin ➔
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>