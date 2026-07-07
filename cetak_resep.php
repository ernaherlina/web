<?php
session_start();
require_once "config/koneksi.php";

if(!isset($_GET['id'])){
    die("ID Pemeriksaan tidak ditemukan.");
}

$id = intval($_GET['id']);

$query = mysqli_query($conn,"
SELECT

p.id,
p.diagnosa,
p.tindakan,
p.instruksi,
p.catatan,
p.tanggal_pemeriksaan,

u.nama,
u.no_rm,
u.jenis_kelamin,
u.no_hp,

d.nama_dokter,

pd.nomor_antrian,
pd.tanggal_periksa,
pd.jam_periksa

FROM pemeriksaan p

INNER JOIN pendaftaran pd
ON p.id_pendaftaran=pd.id

INNER JOIN users u
ON pd.id_user=u.id

INNER JOIN dokter d
ON p.id_dokter=d.id

WHERE p.id='$id'

LIMIT 1
");

if(mysqli_num_rows($query)==0){

die("Data resep tidak ditemukan.");

}

$data=mysqli_fetch_assoc($query);


/* ===========================
   AMBIL DAFTAR OBAT
=========================== */

$obat = mysqli_query($conn,"

SELECT

o.nama_obat,
ro.jumlah,
ro.aturan_pakai

FROM resep r

INNER JOIN resep_obat ro
ON r.id=ro.id_resep

INNER JOIN obat o
ON ro.id_obat=o.id

WHERE r.id_pemeriksaan='$id'

ORDER BY o.nama_obat ASC

");
?>

<!DOCTYPE html>

<html lang="id">

<head>

<meta charset="UTF-8">

<title>Cetak Resep</title>

<style>

body{

font-family:Arial,Helvetica,sans-serif;

background:#f5f7fb;

margin:0;

padding:40px;

}

.container{

width:850px;

margin:auto;

background:white;

padding:40px;

border-radius:15px;

box-shadow:0 5px 20px rgba(0,0,0,.1);

}

.header{

display:flex;

justify-content:space-between;

align-items:center;

border-bottom:3px solid #0d6efd;

padding-bottom:20px;

margin-bottom:30px;

}

.logo{

font-size:30px;

font-weight:bold;

color:#0d6efd;

}

.judul{

font-size:28px;

font-weight:bold;

color:#198754;

}

table{

width:100%;

border-collapse:collapse;

margin-top:20px;

}

td{

padding:8px;

vertical-align:top;

}

.label{

width:180px;

font-weight:bold;

}


.info{

display:grid;

grid-template-columns:1fr 1fr;

gap:30px;

margin-bottom:30px;

}

.box{

border:1px solid #ddd;

border-radius:10px;

padding:15px;

}

.box h3{

margin:0 0 15px;

color:#0d6efd;

font-size:18px;

}

.obat{

margin-top:30px;

}

.obat table{

width:100%;

border-collapse:collapse;

}

.obat th{

background:#0d6efd;

color:white;

padding:10px;

border:1px solid #ddd;

}

.obat td{

padding:10px;

border:1px solid #ddd;

}

.footer{

margin-top:70px;

display:flex;

justify-content:flex-end;

}

.ttd{

text-align:center;

width:250px;

}

@media print{

body{

background:white;

padding:0;

}

.container{

box-shadow:none;

width:100%;

border-radius:0;

}

.no-print{

display:none;

}

}

</style>

</head>

<body>

<div class="container">

<div class="header">

<div class="logo">

KLINIK BTH

</div>

<div class="judul">

RESEP DOKTER

</div>

</div>

<div class="info">

<div class="box">

<h3>Data Pasien</h3>

<table>

<tr>

<td class="label">Nama</td>

<td><?= htmlspecialchars($data['nama']) ?></td>

</tr>

<tr>

<td class="label">No. RM</td>

<td><?= htmlspecialchars($data['no_rm']) ?></td>

</tr>

<tr>

<td class="label">Jenis Kelamin</td>

<td><?= htmlspecialchars($data['jenis_kelamin']) ?></td>

</tr>

<tr>

<td class="label">No. HP</td>

<td><?= htmlspecialchars($data['no_hp']) ?></td>

</tr>

<tr>

<td class="label">Nomor Antrian</td>

<td><?= htmlspecialchars($data['nomor_antrian']) ?></td>

</tr>

</table>

</div>

<div class="box">

<h3>Data Pemeriksaan</h3>

<table>

<tr>

<td class="label">Dokter</td>

<td><?= htmlspecialchars($data['nama_dokter']) ?></td>

</tr>

<tr>

<td class="label">Tanggal</td>

<td><?= date('d-m-Y',strtotime($data['tanggal_periksa'])) ?></td>

</tr>

<tr>

<td class="label">Jam</td>

<td><?= htmlspecialchars($data['jam_periksa']) ?></td>

</tr>

<tr>

<td class="label">Diagnosa</td>

<td><?= nl2br(htmlspecialchars($data['diagnosa'])) ?></td>

</tr>

<tr>

<td class="label">Instruksi</td>

<td><?= nl2br(htmlspecialchars($data['instruksi'])) ?></td>

</tr>

</table>

</div>

</div>

<div class="obat">

<h3>Daftar Obat</h3>

<table>

<tr>

<th width="60">No</th>

<th>Nama Obat</th>

<th width="120">Jumlah</th>

<th>Aturan Pakai</th>

</tr>

<?php

$no=1;

while($r=mysqli_fetch_assoc($obat)){

?>

<tr>

<td align="center"><?= $no++ ?></td>

<td><?= htmlspecialchars($r['nama_obat']) ?></td>

<td align="center"><?= htmlspecialchars($r['jumlah']) ?></td>

<td><?= htmlspecialchars($r['aturan_pakai']) ?></td>

</tr>

<?php

}

?>

</table>

</div>

<div style="margin-top:25px">

<b>Tindakan :</b><br>

<?= nl2br(htmlspecialchars($data['tindakan'])) ?>

</div>

<div style="margin-top:20px">

<b>Catatan Dokter :</b><br>

<?= nl2br(htmlspecialchars($data['catatan'])) ?>

</div>

<div class="footer">

<div class="ttd">

Tasikmalaya,

<?= date('d F Y') ?>

<br><br><br><br><br>

<b>

<?= htmlspecialchars($data['nama_dokter']) ?>

</b>

</div>

</div>

<div class="no-print" style="text-align:center;margin-top:40px;">

<button

onclick="window.print();"

style="

background:#0d6efd;

color:white;

border:none;

padding:12px 30px;

font-size:16px;

border-radius:10px;

cursor:pointer;

margin-right:10px;

">

🖨 Cetak Resep

</button>

<button

onclick="window.close();"

style="

background:#dc3545;

color:white;

border:none;

padding:12px 30px;

font-size:16px;

border-radius:10px;

cursor:pointer;

">

✖ Tutup

</button>

</div>

</div>

<script>

window.onload=function(){

setTimeout(function(){

window.print();

},500);

}

</script>

</body>

</html>