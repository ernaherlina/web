<?php
session_start();
include "config/koneksi.php";

if(!isset($_SESSION['role']) || $_SESSION['role']!="superadmin"){
    header("Location:login.php");
    exit;
}

date_default_timezone_set("Asia/Jakarta");

/* ================= FILTER ================= */

$tgl_awal  = $_GET['tgl_awal'] ?? "";
$tgl_akhir = $_GET['tgl_akhir'] ?? "";
$dokter    = $_GET['dokter'] ?? "";
$poli      = $_GET['poli'] ?? "";

$where="1=1";

if($tgl_awal!=""){
    $where.=" AND DATE(p.tanggal_periksa)>='$tgl_awal'";
}

if($tgl_akhir!=""){
    $where.=" AND DATE(p.tanggal_periksa)<='$tgl_akhir'";
}

if($dokter!=""){
    $dokter=mysqli_real_escape_string($conn,$dokter);
    $where.=" AND d.nama_dokter='$dokter'";
}

if($poli!=""){
    $poli=mysqli_real_escape_string($conn,$poli);
    $where.=" AND d.poli='$poli'";
}

/* ================= QUERY ================= */

$query=mysqli_query($conn,"
SELECT

p.nomor_antrian,

u.nama,

u.no_rm,

d.nama_dokter,

d.poli,

p.keluhan,

pm.diagnosa,

pm.status,

pm.tanggal_pemeriksaan,

IFNULL(

(
SELECT SUM(ro.jumlah*o.harga)

FROM resep r

JOIN resep_obat ro
ON r.id=ro.id_resep

JOIN obat o
ON ro.id_obat=o.id

WHERE r.id_pemeriksaan=pm.id

)

,0) total_biaya

FROM pemeriksaan pm

JOIN pendaftaran p
ON pm.id_pendaftaran=p.id

JOIN users u
ON p.id_user=u.id

JOIN dokter d
ON pm.id_dokter=d.id

WHERE $where

ORDER BY pm.tanggal_pemeriksaan DESC
");

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Super_Admin.xls");

echo "<table border='1'>";

echo "
<tr style='background:#2563eb;color:#fff;font-weight:bold;'>

<th>No</th>

<th>No Antrian</th>

<th>Nama Pasien</th>

<th>No RM</th>

<th>Dokter</th>

<th>Poli</th>

<th>Keluhan</th>

<th>Diagnosa</th>

<th>Status</th>

<th>Tanggal Pemeriksaan</th>

<th>Total Biaya</th>

</tr>
";

$no=1;
$total=0;

while($d=mysqli_fetch_assoc($query)){

$total += $d['total_biaya'];

echo "<tr>";

echo "<td>".$no++."</td>";

echo "<td>".$d['nomor_antrian']."</td>";

echo "<td>".$d['nama']."</td>";

echo "<td>".$d['no_rm']."</td>";

echo "<td>".$d['nama_dokter']."</td>";

echo "<td>".$d['poli']."</td>";

echo "<td>".$d['keluhan']."</td>";

echo "<td>".$d['diagnosa']."</td>";

echo "<td>".$d['status']."</td>";

echo "<td>".date('d-m-Y H:i',strtotime($d['tanggal_pemeriksaan']))."</td>";

echo "<td>".$d['total_biaya']."</td>";

echo "</tr>";

}

echo "

<tr style='background:#f1f5f9;font-weight:bold;'>

<td colspan='10' align='right'>

TOTAL PENDAPATAN

</td>

<td>

Rp ".number_format($total,0,",",".")."

</td>

</tr>

";

echo "</table>";

exit;