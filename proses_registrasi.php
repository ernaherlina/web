<?php

include 'config/koneksi.php';

$nama = $_POST['nama'];
$nim_nip = $_POST['nim_nip'];
$no_hp = $_POST['no_hp'];
$jenis_kelamin = $_POST['jenis_kelamin'];
$status_user = $_POST['status_user'];
$password = $_POST['password'];
$konfirmasi_password = $_POST['konfirmasi_password'];

// Cek konfirmasi password
if ($password != $konfirmasi_password) {
    echo "
    <script>
        alert('Konfirmasi password tidak sesuai!');
        window.history.back();
    </script>
    ";
    exit();
}

// Enkripsi password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Query simpan data
$sql = "INSERT INTO users (
            nama,
            nim_nip,
            no_hp,
            jenis_kelamin,
            status_user,
            password
        ) VALUES (
            '$nama',
            '$nim_nip',
            '$no_hp',
            '$jenis_kelamin',
            '$status_user',
            '$password_hash'
        )";

$query = mysqli_query($conn, $sql);

// Jika berhasil, langsung ke halaman login
if ($query) {

    header("Location: login.php");
    exit();

} else {

    echo "
    <script>
        alert('Registrasi gagal!');
        window.history.back();
    </script>
    ";

}
?>