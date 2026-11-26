<?php
require_once 'database.php';

if (!isset($_SESSION['user_key'])) {
    header("Location: index.php");
    exit;
}

$kunciInput = $_SESSION['user_key'];
$kunciMaster = "KUNCI-WARUNG-2026";

function renderKonten($teksAsli, $kunciMaster, $kunciInput) {
    if ($kunciInput === $kunciMaster) {
        return $teksAsli;
    }
    $cipherSementara = enkripsiData($teksAsli, $kunciMaster);
    return dekripsiData($cipherSementara, $kunciInput);
}

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
    <title><?= htmlspecialchars(renderKonten("Halaman Admin - Dekripsi Konten", $kunciMaster, $kunciInput)); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0f172a] text-slate-100 min-h-screen">
    <!-- Header -->
    <nav class="bg-[#0b1329] border-b border-slate-800 p-4 px-8 flex justify-between items-center">
        <div>
            <h1 class="font-bold text-base text-white">
                <?= htmlspecialchars(renderKonten("PORTAL ADMIN WARUNG NQ", $kunciMaster, $kunciInput)); ?>
            </h1>
            <p class="text-[11px] text-slate-400">
                <?= htmlspecialchars(renderKonten("Kunci yang Digunakan:", $kunciMaster, $kunciInput)); ?> 
                <span class="font-mono text-emerald-400"><?= htmlspecialchars(renderKonten($kunciInput, $kunciMaster, $kunciInput)); ?></span>
            </p>
        </div>
        <form action="" method="POST">
            <button type="submit" name="logout" class="bg-rose-900/40 hover:bg-rose-800/60 border border-rose-800 text-rose-300 text-xs px-4 py-2 rounded-xl">
                <?= htmlspecialchars(renderKonten("Keluar", $kunciMaster, $kunciInput)); ?>
            </button>
        </form>
    </nav>

    <div class="max-w-6xl mx-auto p-6 mt-6">
        <!-- Informasi Status Kunci -->
        <div class="bg-[#111c3a] border border-[#1e294b] p-4 rounded-xl mb-6 flex justify-between items-center text-xs">
            <span><?= htmlspecialchars(renderKonten("Mode Dekripsi Aktif (Algoritma: AES-256-CBC)", $kunciMaster, $kunciInput)); ?></span>
            <span class="text-slate-400"><?= htmlspecialchars(renderKonten("Status: Kunci Valid Terverifikasi", $kunciMaster, $kunciInput)); ?></span>
        </div>

        <!-- Tabel Konten -->
        <div class="bg-[#111c3a] border border-[#1e294b] rounded-2xl overflow-hidden shadow-xl">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#090f24] text-slate-400 border-b border-slate-800 uppercase tracking-wider">
                    <tr>
                        <th class="p-4"><?= htmlspecialchars(renderKonten("ID", $kunciMaster, $kunciInput)); ?></th>
                        <th class="p-4"><?= htmlspecialchars(renderKonten("Nama Pelanggan (Hasil Dekripsi)", $kunciMaster, $kunciInput)); ?></th>
                        <th class="p-4"><?= htmlspecialchars(renderKonten("Keterangan (Hasil Dekripsi)", $kunciMaster, $kunciInput)); ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    <?php while($row = mysqli_fetch_assoc($query)): ?>
                        <?php 
                            $idTerdekripsi = renderKonten((string)$row['id'], $kunciMaster, $kunciInput);
                            $namaTerdekripsi = dekripsiData($row['nama_pelanggan'], $kunciInput);
                            $ketTerdekripsi = dekripsiData($row['keterangan'], $kunciInput);
                        ?>
                        <tr class="hover:bg-[#152347] transition">
                            <td class="p-4 text-slate-500 font-mono"><?= htmlspecialchars($idTerdekripsi); ?></td>
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