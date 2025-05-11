<?php
require_once 'Model.php';

// HANDLE ALL ACTIONS (DELETE/RENEW)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'delete') {
        if (deleteMember($_POST['id_member'])) {
            header("Location: Member.php?success=deleted");
        } else {
            header("Location: Member.php?error=delete_failed");
        }
    } 
    elseif ($_POST['action'] === 'renew') {
        if (renewMemberPayment($_POST['id_member'])) {
            header("Location: Member.php?success=renewed");
        } else {
            header("Location: Member.php?error=renew_failed");
        }
    }
    exit();
}

// GET ALL MEMBERS WITH ERROR HANDLING
$members = getAllMembers();
if (!$members) {
    die("Error mengambil data: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Member</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <a href="index.php" class="back-btn">← Kembali ke Menu</a>
        <h1>Daftar Member</h1>
        <a href="FormMember.php" class="add-btn">+ Tambah Member Baru</a>
    </div>

    <?php if(isset($_GET['success'])): ?>
    <div class="success-message">
        <?= ($_GET['success'] === 'deleted') ? 'Data berhasil dihapus!' : 'Masa aktif berhasil diperpanjang!' ?>
    </div>
<?php endif; ?>

<?php if(isset($_GET['error'])): ?>
    <div class="error-message">
        <?= ($_GET['error'] === 'delete_failed') ? 'Gagal menghapus data!' : 'Gagal memperpanjang masa aktif!' ?>
    </div>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>ID Member</th>
            <th>Nama Member</th>
            <th>Nomor Member</th>
            <th>Alamat</th>
            <th>Tanggal Mendaftar</th>
            <th>Terakhir Bayar</th>
            <th>Status</th>
            <th>Opsi</th>
        </tr>
    </thead>
    <tbody>
        <?php if(mysqli_num_rows($members) > 0): ?>
            <?php 
            $counter = 1; // Initialize counter
            while($row = mysqli_fetch_assoc($members)): 
                $isActive = strtotime($row['tgl_terakhir_bayar']) > time();
                $statusClass = $isActive ? 'status-active' : 'status-expired';
                $statusText = $isActive ? 'Aktif' : 'Kadaluarsa';
            ?>
            <tr>
                <td><?= $counter++ ?></td> <!-- Sequential number -->
                <td class="id-cell"><?= str_pad($row['id_member'], 3, '0', STR_PAD_LEFT) ?></td>
                <td><?= htmlspecialchars($row['name_member']) ?></td>
                <td><?= htmlspecialchars($row['nomor_member']) ?></td>
                <td><?= htmlspecialchars($row['alamat']) ?></td>
                <td class="date-cell">
                    <?= date('d/m/Y', strtotime($row['tgl_mendaftar'])) ?>
                </td>
                <td class="date-cell">
                    <?= date('d/m/Y', strtotime($row['tgl_terakhir_bayar'])) ?>
                </td>
                <td>
                    <span class="status-badge <?= $statusClass ?>">
                        <?= $statusText ?>
                    </span>
                </td>
                <td class="action-cell">
                    <!-- Edit Button -->
                    <a href="FormMember.php?id=<?= $row['id_member'] ?>" class="edit-btn">Edit</a>
                    
                    <!-- Renew Button -->
                    <form method="post" action="Member.php">
                        <input type="hidden" name="id_member" value="<?= $row['id_member'] ?>">
                        <input type="hidden" name="action" value="renew">
                        <button type="submit" class="renew-btn" 
                                onclick="return confirm('Perpanjang masa aktif member <?= htmlspecialchars($row['name_member']) ?>?')">
                            Perpanjang
                        </button>
                    </form>
                    
                    <!-- Delete Button -->
                    <form method="post" action="Member.php">
                        <input type="hidden" name="id_member" value="<?= $row['id_member'] ?>">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" class="delete-btn" 
                                onclick="return confirm('Hapus member <?= htmlspecialchars($row['name_member']) ?>?')">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="9" class="no-data">Tidak ada data member</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
</body>
</html>