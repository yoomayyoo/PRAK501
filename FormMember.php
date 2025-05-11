<?php
require_once 'Model.php';

// PROSES SUBMIT DATA
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'id_member' => $_POST['id_member'],
        'name_member' => $_POST['name_member'], 
        'nomor_member' => $_POST['nomor_member'], 
        'alamat' => $_POST['alamat']
    ];

    if (empty($data['id_member'])) {
        addMember($data);
    } else {
        updateMember($data['id_member'], $data);
    }
    
    header("Location: Member.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (strlen($_POST['nomor_member']) > 15) {
            throw new Exception("Nomor member maksimal 15 karakter");
        }
        if (empty($_POST['id_member'])) {
            $success = addMember($_POST);
        } else {
            $success = updateMember($_POST['id_member'], $_POST);
        }
        
        header("Location: Member.php?success=".($success ? '1' : '0'));
        exit();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// AMBIL DATA JIKA EDIT
$member = [
    'id_member' => '', 
    'name_member' => '', 
    'nomor_member' => '', 
    'alamat' => '',
    'tgl_mendaftar' => '',
    'tgl_terakhir_bayar' => ''
];
if (isset($_GET['id'])) {
    $member = getMemberById($_GET['id']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= empty($member['id_member']) ? 'Tambah' : 'Edit' ?> Member</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <h2><?= empty($member['id_member']) ? 'Tambah' : 'Edit' ?> Data Member</h2>
        
        <form method="post">
            <input type="hidden" name="id_member" value="<?= $member['id_member'] ?>">
            
            <div class="form-group">
                <label>Nama Member:</label>
                <input type="text" name="name_member" value="<?= htmlspecialchars($member['name_member']) ?>" required>
            </div>
            
            <div class="form-group">
                <label>Nomor Member:</label>
                <input type="text" name="nomor_member" value="<?= htmlspecialchars($member['nomor_member']) ?>" required>
            </div>
            
            <div class="form-group">
                <label>Alamat:</label>
                <input type="text" name="alamat" value="<?= htmlspecialchars($member['alamat']) ?>" required>
            </div>
            
            <?php if(!empty($member['id_member'])): ?>
            <div class="form-group">
                <div class="date-info">
                    <strong>Tanggal Mendaftar:</strong> 
                    <?= date('d/m/Y H:i', strtotime($member['tgl_mendaftar'])) ?>
                </div>
                <div class="date-info">
                    <strong>Tanggal Terakhir Bayar:</strong> 
                    <?= date('d/m/Y H:i', strtotime($member['tgl_terakhir_bayar'])) ?>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="form-actions">
                <a href="Member.php" class="back-btn">← Kembali</a>
                <button type="submit" class="submit-btn">Simpan</button>
            </div>
        </form>
    </div>
</body>
</html>