<?php // file ini untuk tampilan awal
$page = isset($_GET['page']) ? $_GET['page'] : '';

if ($page == 'member') {
    include 'Member.php';
} elseif ($page == 'buku') {
    include 'Buku.php';
} elseif ($page == 'peminjaman') {
    include 'Peminjaman.php';
} elseif ($page == 'formmember') {
    include 'FormMember.php';
} elseif ($page == 'formbuku') {
    include 'FormBuku.php';
} elseif ($page == 'formpeminjaman') {
    include 'FormPeminjaman.php';
} else {
?>
<!DOCTYPE html>
<html>
<head>
    <title>Perpus Online</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container center-page">
        <div class="menu-card menu-card-big">
            <h1 style="text-align:center;">Perpustakaan</h1>
            <div style="display: flex; flex-direction: column; gap: 20px;">
                <a href="index.php?page=member" class="add-btn" style="text-align:center;">Member</a>
                <a href="index.php?page=buku" class="add-btn" style="text-align:center;">Buku</a>
                <a href="index.php?page=peminjaman" class="add-btn" style="text-align:center;">Peminjaman</a>
            </div>
        </div>
    </div>
</body>
</html>
<?php } ?>
