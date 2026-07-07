<?php

include "config/koneksi.php";

$id = intval($_GET['id']);

mysqli_begin_transaction($conn);

try{

// =======================
// Update Status Resep
// =======================

mysqli_query($conn,"
UPDATE resep
SET status='Sudah Diambil'
WHERE id='$id'
");

// =======================
// Ambil id pemeriksaan
// =======================

$q = mysqli_query($conn,"
SELECT id_pemeriksaan
FROM resep
WHERE id='$id'
");

$data = mysqli_fetch_assoc($q);

$id_pemeriksaan = $data['id_pemeriksaan'];

// =======================
// Update Pemeriksaan
// =======================

mysqli_query($conn,"
UPDATE pemeriksaan
SET status='Selesai'
WHERE id='$id_pemeriksaan'
");

// =======================
// Ambil id pendaftaran
// =======================

$q2 = mysqli_query($conn,"
SELECT id_pendaftaran
FROM pemeriksaan
WHERE id='$id_pemeriksaan'
");

$data2 = mysqli_fetch_assoc($q2);

$id_pendaftaran = $data2['id_pendaftaran'];

// =======================
// Update Pendaftaran
// =======================

mysqli_query($conn,"
UPDATE pendaftaran
SET status='Selesai'
WHERE id='$id_pendaftaran'
");

mysqli_commit($conn);

header("Location: dashboard_apotek.php?status=Sudah Diambil");

}catch(Exception $e){

mysqli_rollback($conn);

echo $e->getMessage();

}