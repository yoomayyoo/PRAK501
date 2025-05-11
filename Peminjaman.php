<?php
require_once 'Model.php';

// HANDLE ACTIONS
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'delete') {
        if (deleteLoan($_POST['id_peminjaman'])) {
            header("Location: Peminjaman.php?success=deleted");
        } else {
            header("Location: Peminjaman.php?error=delete_failed");
        }
    }
    exit();
}

// GET ALL LOANS
$loans = getAllLoans();
if (!$loans) {
    die("Error mengambil data: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Peminjaman</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <a href="index.php" class="back-btn">← Kembali</a>
        <h1>Daftar Peminjaman</h1>
        <a href="FormPeminjaman.php" class="add-btn">+ Tambah Peminjaman</a>
    </div>

    <?php if(isset($_GET['success'])): ?>
        <div class="success-message">Data berhasil dihapus!</div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Peminjaman</th>
                <th>ID Member</th>
                <th>ID Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Opsi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $counter = 1;
            while($row = mysqli_fetch_assoc($loans)): ?>
            <tr>
                <td><?= $counter++ ?></td>
                <td class="id-cell"><?= str_pad($row['id_peminjaman'], 3, '0', STR_PAD_LEFT) ?></td>
                <td class="id-cell"><?= str_pad($row['id_member'], 3, '0', STR_PAD_LEFT) ?></td>
                <td class="id-cell"><?= str_pad($row['id_buku'], 3, '0', STR_PAD_LEFT) ?></td>
                <td class="date-cell"><?= date('d/m/Y', strtotime($row['tgl_pinjam'])) ?></td>
                <td class="date-cell"><?= date('d/m/Y', strtotime($row['tgl_kembali'])) ?></td>
                <td class="action-cell">
                    <a href="FormPeminjaman.php?id=<?= $row['id_peminjaman'] ?>" class="edit-btn">Edit</a>
                    <form method="post" action="Peminjaman.php" style="display:inline;">
                        <input type="hidden" name="id_peminjaman" value="<?= $row['id_peminjaman'] ?>">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" class="delete-btn" 
                            onclick="return confirm('Hapus data peminjaman ini?')">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>