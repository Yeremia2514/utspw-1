<?php

require_once 'config/database.php';

$results = $pdo->query("SELECT * FROM bahan");

$semua_bahan = [];
while ($row = $results->fetch(PDO::FETCH_ASSOC)) {
    $semua_bahan[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jamuku - Yeremia Nicolas</title>
</head>
<body>

    <h1>Jamu Tradisionalku</h1>
    <p>Racik jamu kamu sendiri sesuai khasiat yang diinginkan!</p>
    <hr>

    <form action="keranjang.php" method="POST">
        
        <h3>Pilih Komposisi Bahan Jamu:</h3>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>Pilih</th>
                    <th>Nama Bahan</th>
                    <th>Jenis</th>
                    <th>Deskripsi</th>
                    <th>Harga</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($semua_bahan as $bahan): ?>
                <tr>
                    <td>
                        <input type="checkbox" name="bahan[]" value="<?= $bahan['id']; ?>">
                    </td>
                    <td><strong><?= $bahan['nama']; ?></strong></td>
                    <td><?= $bahan['jenis']; ?></td>
                    <td><?= $bahan['deskripsi']; ?></td>
                    <td>Rp <?= number_format($bahan['harga'], 0, ',', '.'); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <br>
        <label for="porsi"><strong>Jumlah Porsi Racikan:</strong></label>
        <input type="number" id="porsi" name="porsi" value="1" min="1" required style="width: 50px;"> Porsi
        
        <br><br>
        <button type="submit">Masukkan ke Keranjang Belanja</button>
    </form>

</body>
</html>