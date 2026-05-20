<?php

session_start();

require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bahan'])) {
    $_SESSION['porsi'] = (int)$_POST['porsi'];
    $_SESSION['keranjang_bahan'] = $_POST['bahan'];
}

$porsi = isset($_SESSION['porsi']) ? $_SESSION['porsi'] : 1;
$id_bahan_dipilih = isset($_SESSION['keranjang_bahan']) ? $_SESSION['keranjang_bahan'] : [];

if (isset($_GET['aksi']) && $_GET['aksi'] === 'hapus' && isset($_GET['id'])) {
    $id_hapus = $_GET['id'];
    if (($key = array_search($id_hapus, $_SESSION['keranjang_bahan'])) !== false) {
        unset($_SESSION['keranjang_bahan'][$key]);
    }
    header("Location: keranjang.php");
    exit;
}
if (isset($_GET['aksi']) && $_GET['aksi'] === 'update_porsi' && isset($_POST['porsi_baru'])) {
    $_SESSION['porsi'] = (int)$_POST['porsi_baru'];
    header("Location: keranjang.php");
    exit;
}

$bahan_keranjang = [];
$total_harga_bahan = 0;

if (!empty($id_bahan_dipilih)) {
    
    $ids_string = implode(',', array_map('intval', $id_bahan_dipilih));
    
    $results = $pdo->query("SELECT * FROM bahan WHERE id IN ($ids_string)");
    
    while ($row = $results->fetch(PDO::FETCH_ASSOC)) {
        $bahan_keranjang[] = $row;
        $total_harga_bahan += $row['harga'];
    }
}

$total_pembayaran = $total_harga_bahan * $porsi;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja - Jamuku</title>
</head>
<body>

    <h1>Keranjang Racikan Jamu Kamu</h1>
    <a href="index.php">&larr; Kembali Meracik / Tambah Bahan</a>
    <hr>

    <?php if (empty($bahan_keranjang)): ?>
        <p>Keranjang Kamu kosong. Silakan pilih bahan terlebih dahulu di halaman utama.</p>
    <?php else: ?>

        <form action="keranjang.php?aksi=update_porsi" method="POST">
            <label for="porsi_baru"><strong>Jumlah Porsi Jamu saat ini:</strong></label>
            <input type="number" id="porsi_baru" name="porsi_baru" value="<?= $porsi; ?>" min="1" style="width: 50px;">
            <button type="submit">Update Porsi</button>
        </form>
        <br>

        <h3>Komposisi Racikan Kamu:</h3>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>Nama Bahan</th>
                    <th>Jenis</th>
                    <th>Harga Satuan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bahan_keranjang as $item): ?>
                <tr>
                    <td><strong><?= $item['nama']; ?></strong></td>
                    <td><?= $item['jenis']; ?></td>
                    <td>Rp <?= number_format($item['harga'], 0, ',', '.'); ?></td>
                    <td>
                        <a href="keranjang.php?aksi=hapus&id=<?= $item['id']; ?>" onclick="return confirm('Hapus bahan ini dari racikan?')">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <tr style="background-color: #f2f2f2;">
                    <td colspan="2"><strong>Total Harga Komposisi (1 Porsi)</strong></td>
                    <td colspan="2"><strong>Rp <?= number_format($total_harga_bahan, 0, ',', '.'); ?></strong></td>
                </tr>
            </tbody>
        </table>

        <h2>Total Yang Harus Dibayar (<?= $porsi; ?> Porsi):</h2>
        <h3 style="color: green;">Rp <?= number_format($total_pembayaran, 0, ',', '.'); ?></h3>

        <br>
        <button onclick="alert('Terima kasih! Pesanan jamu Kamu sedang diracik.')" style="font-size: 16px; padding: 10px 20px;">Bayar Sekarang</button>

    <?php endif; ?>

</body>
</html>