<?php
require_once 'Model.php';

// HANDLE DELETE ACTION
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (deleteBook($_POST['id_buku'])) {
        header("Location: Buku.php?success=deleted");
    } else {
        header("Location: Buku.php?error=delete_failed");
    }
    exit();
}

// GET ALL BOOKS WITH ERROR HANDLING
$books = getAllBooks();
if (!$books) {
    die("Error mengambil data: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Buku</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <a href="index.php" class="back-btn">← Kembali ke Menu</a>
        <h1>Daftar Buku</h1>
        <a href="FormBuku.php" class="add-btn">+ Tambah Buku Baru</a>
    </div>

    <?php if(isset($_GET['success'])): ?>
        <div class="success-message">
            <?= ($_GET['success'] === 'deleted') ? 'Data buku berhasil dihapus!' : '' ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_GET['error'])): ?>
        <div class="error-message">
            <?= ($_GET['error'] === 'delete_failed') ? 'Gagal menghapus data buku!' : '' ?>
        </div>
    <?php endif; ?>

    <table>
    <thead>
        <tr>
            <th>No</th>
            <th>ID Buku</th>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Penerbit</th>
            <th>Tahun</th>
            <th>Opsi</th>
        </tr>
    </thead>
    <tbody>
        <?php if(mysqli_num_rows($books) > 0): ?>
            <?php 
            $counter = 1; // Initialize counter
            while($row = mysqli_fetch_assoc($books)): ?>
            <tr>
                <td><?= $counter++ ?></td> <!-- Sequential number -->
                <td class="id-cell"><?= str_pad($row['id_buku'], 3, '0', STR_PAD_LEFT) ?></td>
                <td><?= htmlspecialchars($row['judul_buku']) ?></td>
                <td><?= htmlspecialchars($row['penulis']) ?></td>
                <td><?= htmlspecialchars($row['penerbit']) ?></td>
                <td><?= htmlspecialchars($row['tahun_terbit']) ?></td>
                <td class="action-cell">
                    <!-- Edit Button -->
                    <a href="FormBuku.php?id=<?= $row['id_buku'] ?>" class="edit-btn">Edit</a>
                    
                    <!-- Delete Button -->
                    <form method="post" action="Buku.php">
                        <input type="hidden" name="id_buku" value="<?= $row['id_buku'] ?>">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" class="delete-btn" 
                                onclick="return confirm('Hapus buku <?= htmlspecialchars($row['judul_buku']) ?>?')">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="no-data">Tidak ada data buku</td>
            </tr>
        <?php endif; ?>
    </tbody>
    </table>
</body>
</html>