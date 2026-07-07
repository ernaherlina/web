<?php

session_start();
include 'config/koneksi.php';

if(!isset($_SESSION['id'])){
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['id'];

$keluhan = mysqli_real_escape_string($conn,$_POST['keluhan']);
$dokter = mysqli_real_escape_string($conn,$_POST['dokter']);
$tanggal_periksa = mysqli_real_escape_string($conn,$_POST['tanggal_periksa']);
$jam_periksa = mysqli_real_escape_string($conn,$_POST['jam_periksa']);

$tanggal = date('Y-m-d');
$jam = date('H:i:s');


/* ===========================
   CEK JAM SUDAH DIBOOKING
=========================== */

$cek = mysqli_query($conn,"
SELECT id
FROM pendaftaran
WHERE dokter='$dokter'
AND tanggal_periksa='$tanggal_periksa'
AND jam_periksa='$jam_periksa'
");

if(mysqli_num_rows($cek)>0){

    echo "
    <script>
    alert('Jam tersebut sudah dibooking pasien lain!');
    history.back();
    </script>
    ";
    exit;
}


/* ===========================
   NOMOR ANTRIAN
=========================== */

$q = mysqli_query($conn,"
SELECT nomor_antrian
FROM pendaftaran
WHERE dokter='$dokter'
AND tanggal_periksa='$tanggal_periksa'
ORDER BY id DESC
LIMIT 1
");

if(mysqli_num_rows($q)>0){

    $d = mysqli_fetch_assoc($q);

    $angka = intval(substr($d['nomor_antrian'],1));

    $angka++;

}else{

    $angka = 1;

}

$nomor_antrian = "A".str_pad($angka,3,"0",STR_PAD_LEFT);


/* ===========================
   SIMPAN
=========================== */

$simpan = mysqli_query($conn,"
INSERT INTO pendaftaran
(
id_user,
tanggal,
keluhan,
nomor_antrian,
status,
jam,
dokter,
tanggal_periksa,
jam_periksa
)

VALUES
(
'$id_user',
'$tanggal',
'$keluhan',
'$nomor_antrian',
'Menunggu Verifikasi',
'$jam',
'$dokter',
'$tanggal_periksa',
'$jam_periksa'
)
");

if($simpan){

    echo "
    <script>
    alert('Pendaftaran berhasil!');
    window.location='dashboard.php';
    </script>
    ";

}else{

    echo "
    <script>
    alert('Pendaftaran gagal!');
    history.back();
    </script>
    ";

}

?>