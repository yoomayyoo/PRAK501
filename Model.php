<?php
require_once 'Koneksi.php';

date_default_timezone_set('Asia/Jakarta');
mysqli_query($koneksi, "SET time_zone = '+07:00'");

// =============================================
// FUNGSI CRUD MEMBER
// =============================================
function getAllMembers() {
    global $koneksi;
    $query = "SELECT * FROM member";
    return mysqli_query($koneksi, $query);
}

function getMemberById($id) {
    global $koneksi;
    $query = "SELECT * FROM member WHERE id_member = $id";
    $result = mysqli_query($koneksi, $query);
    return mysqli_fetch_assoc($result);
}

function addMember($data) {
    global $koneksi;
    
    $stmt = $koneksi->prepare("INSERT INTO member 
        (name_member, nomor_member, alamat, tgl_mendaftar, tgl_terakhir_bayar) 
        VALUES (?, ?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 1 YEAR))");
    
    $stmt->bind_param("sss", 
        $data['name_member'],
        $data['nomor_member'],
        $data['alamat']
    );
    
    return $stmt->execute();
}

function updateMember($id, $data) {
    global $koneksi;
    $name = mysqli_real_escape_string($koneksi, $data['name_member']);
    $nomor = mysqli_real_escape_string($koneksi, $data['nomor_member']);
    $alamat = mysqli_real_escape_string($koneksi, $data['alamat']);

    $query = "UPDATE member SET 
              name_member = '$name',
              nomor_member = '$nomor',
              alamat = '$alamat'
              WHERE id_member = $id";
    return mysqli_query($koneksi, $query);
}

function deleteMember($id) {
    global $koneksi;
    $query = "DELETE FROM member WHERE id_member = $id";
    return mysqli_query($koneksi, $query);
}

function renewMemberPayment($id_member) {
    global $koneksi;
    $query = "UPDATE member SET 
              tgl_terakhir_bayar = DATE_ADD(NOW(), INTERVAL 1 YEAR)
              WHERE id_member = $id_member";
    return mysqli_query($koneksi, $query);
}

// =============================================
// FUNGSI CRUD BUKU
// =============================================
function getAllBooks() {
    global $koneksi;
    $query = "SELECT * FROM buku";
    return mysqli_query($koneksi, $query);
}

function getBookById($id) {
    global $koneksi;
    $query = "SELECT * FROM buku WHERE id_buku = $id";
    $result = mysqli_query($koneksi, $query);
    return mysqli_fetch_assoc($result);
}

function addBook($data) {
    global $koneksi;
    $judul = mysqli_real_escape_string($koneksi, $data['judul_buku']);
    $penulis = mysqli_real_escape_string($koneksi, $data['penulis']);
    $penerbit = mysqli_real_escape_string($koneksi, $data['penerbit']);
    $tahun = (int)$data['tahun_terbit'];

    $query = "INSERT INTO buku (judul_buku, penulis, penerbit, tahun_terbit) 
              VALUES ('$judul', '$penulis', '$penerbit', $tahun)";
    return mysqli_query($koneksi, $query);
}

function updateBook($id, $data) {
    global $koneksi;
    $judul = mysqli_real_escape_string($koneksi, $data['judul_buku']);
    $penulis = mysqli_real_escape_string($koneksi, $data['penulis']);
    $penerbit = mysqli_real_escape_string($koneksi, $data['penerbit']);
    $tahun = (int)$data['tahun_terbit'];

    $query = "UPDATE buku SET 
              judul_buku = '$judul',
              penulis = '$penulis',
              penerbit = '$penerbit',
              tahun_terbit = $tahun
              WHERE id_buku = $id";
    return mysqli_query($koneksi, $query);
}

function deleteBook($id) {
    global $koneksi;
    $query = "DELETE FROM buku WHERE id_buku = $id";
    return mysqli_query($koneksi, $query);
}

// =============================================
// FUNGSI CRUD PEMINJAMAN
// =============================================
function getAllLoans() {
    global $koneksi;
    $query = "SELECT p.*, m.name_member, b.judul_buku 
              FROM peminjaman p
              JOIN member m ON p.id_member = m.id_member
              JOIN buku b ON p.id_buku = b.id_buku";
    return mysqli_query($koneksi, $query);
}

function getLoanById($id) {
    global $koneksi;
    $query = "SELECT p.*, m.name_member, b.judul_buku 
              FROM peminjaman p
              JOIN member m ON p.id_member = m.id_member
              JOIN buku b ON p.id_buku = b.id_buku
              WHERE p.id_peminjaman = $id";
    $result = mysqli_query($koneksi, $query);
    return mysqli_fetch_assoc($result);
}

function addLoan($data) {
    global $koneksi;
    $id_member = (int)$data['id_member'];
    $id_buku = (int)$data['id_buku'];
    $tgl_pinjam = (string)$data['tgl_pinjam'];

    $query = "INSERT INTO peminjaman (id_member, id_buku, tgl_pinjam, tgl_kembali) 
              VALUES ($id_member, $id_buku, NOW(), '$tgl_pinjam')";
    return mysqli_query($koneksi, $query);
}

function updateLoan($id, $data) {
    global $koneksi;
    $id_member = (int)$data['id_member'];
    $id_buku = (int)$data['id_buku'];
    $tgl_kembali = mysqli_real_escape_string($koneksi, $data['tgl_kembali']);

    $query = "UPDATE peminjaman SET 
              id_member = $id_member,
              id_buku = $id_buku,
              tgl_kembali = '$tgl_kembali'
              WHERE id_peminjaman = $id";
    return mysqli_query($koneksi, $query);
}

function deleteLoan($id) {
    global $koneksi;
    $query = "DELETE FROM peminjaman WHERE id_peminjaman = $id";
    return mysqli_query($koneksi, $query);
}

// =============================================
// FUNGSI UTILITAS (Untuk Form Peminjaman)
// =============================================
function getAllMembersForDropdown() {
    global $koneksi;
    $query = "SELECT id_member, name_member FROM member";
    return mysqli_query($koneksi, $query);
}

function getAllBooksForDropdown() {
    global $koneksi;
    $query = "SELECT id_buku, judul_buku FROM buku";
    return mysqli_query($koneksi, $query);
}