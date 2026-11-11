<?php
require_once 'database.php';

if (!isset($_SESSION['user_key'])) {
    header("Location: index.php");
    exit;
}

$kunciInput = $_SESSION['user_key'];
$query = mysqli_query($conn, "SELECT * FROM data_rahasia ORDER BY id DESC");

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Halaman Admin - Dekripsi Konten</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0f172a] text-slate-100 min-h-screen">
    <!-- Header -->
    <nav class="bg-[#0b1329] border-b border-slate-800 p-4 px-8 flex justify-between items-center">
        <div>
            <h1 class="font-bold text-base text-white">PORTAL ADMIN WARUNG NQ</h1>
            <p class="text-[11px] text-slate-400">Kunci yang Digunakan: <span class="font-mono text-emerald-400"><?= htmlspecialchars($kunciInput); ?></span></p>
        </div>
        <form action="" method="POST">
            <button type="submit" name="logout" class="bg-rose-900/40 hover:bg-rose-800/60 border border-rose-800 text-rose-300 text-xs px-4 py-2 rounded-xl">
                🚪 Keluar
            </button>
        </form>
    </nav>

    <div class="max-w-6xl mx-auto p-6 mt-6">
        <!-- Informasi Status Kunci -->
        <div class="bg-[#111c3a] border border-[#1e294b] p-4 rounded-xl mb-6 flex justify-between items-center text-xs">
            <span>🛡️ Mode Dekripsi Aktif (Algoritma: AES-256-CBC)</span>
            <span class="text-slate-400">Bila kunci salah, data di bawah akan tampil berantakan</span>
        </div>

        <!-- Tabel Konten -->
        <div class="bg-[#111c3a] border border-[#1e294b] rounded-2xl overflow-hidden shadow-xl">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#090f24] text-slate-400 border-b border-slate-800 uppercase tracking-wider">
                    <tr>
                        <th class="p-4">ID</th>
                        <th class="p-4">Ciphertext di Database (Teks Asli di MySQL)</th>
                        <th class="p-4">Nama Pelanggan (Hasil Dekripsi)</th>
                        <th class="p-4">Keterangan (Hasil Dekripsi)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    <?php while($row = mysqli_fetch_assoc($query)): ?>
                        <?php 
                            // Proses dekripsi dengan kunci yang dimasukkan saat login
                            $namaTerdekripsi = dekripsiData($row['nama_pelanggan'], $kunciInput);
                            $ketTerdekripsi = dekripsiData($row['keterangan'], $kunciInput);
                        ?>
                        <tr class="hover:bg-[#152347] transition">
                            <td class="p-4 text-slate-500 font-mono"><?= $row['id']; ?></td>
                            <td class="p-4 font-mono text-[11px] text-amber-300/80 max-w-xs truncate" title="<?= $row['nama_pelanggan']; ?>">
                                <?= substr($row['nama_pelanggan'], 0, 25); ?>...
                            </td>
                            <td class="p-4 font-bold text-white text-sm">
                                <?= htmlspecialchars($namaTerdekripsi); ?>
                            </td>
                            <td class="p-4 text-slate-300">
                                <?= htmlspecialchars($ketTerdekripsi); ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>