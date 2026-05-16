<?php
include 'koneksi.php';

$nama = $_POST['nama'];
$nim_nip = $_POST['nim_nip'];
$no_hp = $_POST['no_hp'];

$password = password_hash(
    $_POST['password'],
    PASSWORD_DEFAULT
);

$sql = "INSERT INTO users
(nama, nim_nip, no_hp, password)

VALUES
('$nama','$nim_nip','$no_hp','$password')";

$query = mysqli_query($conn, $sql);

if($query){

    header("Location: login.php");
    exit;

}else{
    echo "Pendaftaran gagal";
}
?>