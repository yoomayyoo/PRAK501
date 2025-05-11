<?php
require_once 'Model.php';

// PROSES SUBMIT DATA
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'id_buku' => $_POST['id_buku'],
        'judul_buku' => $_POST['judul_buku'],
        'penulis' => $_POST['penulis'],
        'penerbit' => $_POST['penerbit'],
        'tahun_terbit' => $_POST['tahun_terbit']
    ];

    if (empty($data['id_buku'])) {
        addBook($data); // Create
    } else {
        updateBook($data['id_buku'], $data); // Update
    }
    
    header("Location: Buku.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method'])) {
    if ($_POST['_method'] === 'DELETE') {
        deleteBook($_POST['id_buku']);
        header("Location: Buku.php");
        exit();
    }
}

// AMBIL DATA JIKA EDIT
$book = ['id_buku' => '', 'judul_buku' => '', 'penulis' => '', 'penerbit' => '', 'tahun_terbit' => ''];
if (isset($_GET['id'])) {
    $book = getBookById($_GET['id']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= empty($book['id_buku']) ? 'Tambah' : 'Edit' ?> Buku</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <h2><?= empty($book['id_buku']) ? 'Tambah' : 'Edit' ?> Data Buku</h2>
        
        <form method="post">
            <input type="hidden" name="id_buku" value="<?= $book['id_buku'] ?>">
            
            <div class="form-group">
                <label>Judul Buku:</label>
                <input type="text" name="judul_buku" value="<?= $book['judul_buku'] ?>" required>
            </div>
            
            <div class="form-group">
                <label>Penulis:</label>
                <input type="text" name="penulis" value="<?= $book['penulis'] ?>" required>
            </div>
            
            <div class="form-group">
                <label>Penerbit:</label>
                <input type="text" name="penerbit" value="<?= $book['penerbit'] ?>" required>
            </div>
            
            <div class="form-group">
                <label>Tahun Terbit:</label>
                <input type="number" name="tahun_terbit" value="<?= $book['tahun_terbit'] ?>" required>
            </div>
            
            <a href="Buku.php" class="back-btn">← Kembali</a>
            <button type="submit" class="submit-btn">Simpan</button>
        </form>
    </div>
</body>
</html>