<?php
require_once 'Model.php';

// PROSES SUBMIT DATA
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'id_peminjaman' => $_POST['id_peminjaman'] ?? '',
        'id_member' => (int)$_POST['id_member'],
        'id_buku' => (int)$_POST['id_buku'],
        'tgl_pinjam' => date('Y-m-d')
    ];

    try {
        if (empty($data['id_peminjaman'])) {
            addLoan($data);
            $_SESSION['success'] = "Peminjaman berhasil ditambahkan";
        } else {
            updateLoan($data['id_peminjaman'], $data);
            $_SESSION['success'] = "Peminjaman berhasil diperbarui";
        }
        header("Location: Peminjaman.php");
        exit();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// AMBIL DATA JIKA EDIT
$loan = [
    'id_peminjaman' => '',
    'id_member' => '',
    'id_buku' => '',
    'tgl_pinjam' => date('Y-m-d')
];

if (isset($_GET['id'])) {
    $loan = getLoanById($_GET['id']);
}

// AMBIL DATA DROPDOWN
$members = getAllMembersForDropdown();
$books = getAllBooksForDropdown();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= empty($loan['id_peminjaman']) ? 'Tambah' : 'Edit' ?> Peminjaman</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <h2><?= empty($loan['id_peminjaman']) ? 'Tambah' : 'Edit' ?> Peminjaman</h2>
        
        <?php if(isset($error)): ?>
            <div class="error-message"><?= $error ?></div>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="id_peminjaman" value="<?= $loan['id_peminjaman'] ?>">
            
            <div class="form-group">
                <label for="id_member">Member:</label>
                <select id="id_member" name="id_member" required>
                    <option value="">Pilih Member</option>
                    <?php while($member = mysqli_fetch_assoc($members)): ?>
                        <option value="<?= $member['id_member'] ?>" 
                            <?= ($member['id_member'] == $loan['id_member']) ? 'selected' : '' ?>>
                            <span class="id-option"><?= str_pad($member['id_member'], 3, '0', STR_PAD_LEFT) ?></span> - 
                            <?= htmlspecialchars($member['name_member']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="id_buku">Buku:</label>
                <select id="id_buku" name="id_buku" required>
                    <option value="">Pilih Buku</option>
                    <?php while($book = mysqli_fetch_assoc($books)): ?>
                        <option value="<?= $book['id_buku'] ?>" 
                            <?= ($book['id_buku'] == $loan['id_buku']) ? 'selected' : '' ?>>
                            <span class="id-option"><?= str_pad($book['id_buku'], 3, '0', STR_PAD_LEFT) ?></span> - 
                            <?= htmlspecialchars($book['judul_buku']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="tgl_pinjam">Tanggal Pinjam:</label>
                <input type="date" id="tgl_pinjam" name="tgl_pinjam" 
                       value="<?= $loan['tgl_pinjam'] ?>" required>
            </div>
            
            <div class="form-actions">
                <a href="Peminjaman.php" class="back-btn">← Kembali</a>
                <button type="submit" class="submit-btn">Simpan</button>
            </div>
        </form>
    </div>
</body>
</html>