<?php
include 'config/koneksi.php';

$id = $_GET['id'];

mysqli_query($conn,"
UPDATE pendaftaran
SET status='Selesai'
WHERE id='$id'
");

header("Location: dashboard_dokter.php");
exit;
?>