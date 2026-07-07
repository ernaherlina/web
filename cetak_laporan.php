<?php
session_start();
include 'config/koneksi.php';
require('fpdf/fpdf.php');

date_default_timezone_set('Asia/Jakarta');

/*==================================
FILTER
==================================*/

$tgl_awal  = $_GET['tgl_awal']  ?? date('Y-m-01');
$tgl_akhir = $_GET['tgl_akhir'] ?? date('Y-m-d');
$status    = $_GET['status']    ?? '';

$where = "DATE(r.tanggal) BETWEEN '$tgl_awal' AND '$tgl_akhir'";

if($status!=""){
    $where .= " AND r.status='$status'";
}

/*==================================
STATISTIK
==================================*/

$totalResep = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM resep r
WHERE $where
"))['total'];

$totalSudah = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM resep r
WHERE $where
AND status='Sudah Diambil'
"))['total'];

$totalProses = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM resep r
WHERE $where
AND status='Sedang Disiapkan'
"))['total'];

$totalBelum = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM resep r
WHERE $where
AND status='Belum Diambil'
"))['total'];

$totalPendapatan = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(total_harga) total
FROM resep r
WHERE $where
"))['total'];

if($totalPendapatan==""){
    $totalPendapatan=0;
}

/*==================================
PDF
==================================*/

$pdf = new FPDF('L','mm','A4');

$pdf->SetAutoPageBreak(true,15);

$pdf->AddPage();

$pdf->SetTitle("Laporan Apotek Klinik BTH");



/*==================================
HEADER
==================================*/

$pdf->SetFillColor(33,115,255);

$pdf->Rect(0,0,297,28,'F');

$pdf->SetFont('Arial','B',18);

$pdf->SetTextColor(255,255,255);

$pdf->Cell(287,10,'KLINIK BTH TASIKMALAYA',0,1,'C');

$pdf->SetFont('Arial','',11);

$pdf->Cell(287,7,'Laporan Pelayanan Apotek',0,1,'C');



$pdf->Ln(6);



$pdf->SetTextColor(0,0,0);

$pdf->SetFont('Arial','',10);

$pdf->Cell(35,7,'Periode',0,0);

$pdf->Cell(5,7,':',0,0);

$pdf->Cell(70,7,
date('d-m-Y',strtotime($tgl_awal))
.' s/d '.
date('d-m-Y',strtotime($tgl_akhir))
,0,1);

$pdf->Cell(35,7,'Tanggal Cetak',0,0);

$pdf->Cell(5,7,':',0,0);

$pdf->Cell(70,7,date('d-m-Y H:i'),0,1);



$pdf->Ln(5);



/*==================================
RINGKASAN
==================================*/

$pdf->SetFont('Arial','B',12);

$pdf->Cell(287,8,'RINGKASAN LAPORAN',0,1);



$pdf->SetFillColor(240,245,255);

$pdf->SetDrawColor(220,220,220);

$pdf->SetFont('Arial','',10);

$pdf->Cell(60,10,'Total Resep',1,0,'L',true);
$pdf->Cell(25,10,$totalResep,1,0,'C');

$pdf->Cell(60,10,'Sudah Diambil',1,0,'L',true);
$pdf->Cell(25,10,$totalSudah,1,0,'C');

$pdf->Cell(60,10,'Sedang Disiapkan',1,0,'L',true);
$pdf->Cell(25,10,$totalProses,1,1,'C');



$pdf->Cell(60,10,'Belum Diambil',1,0,'L',true);
$pdf->Cell(25,10,$totalBelum,1,0,'C');

$pdf->Cell(60,10,'Pendapatan',1,0,'L',true);

$pdf->Cell(
110,
10,
'Rp '.number_format($totalPendapatan,0,",","."),
1,
1,
'L'
);

$pdf->Ln(8);

/*==================================
DETAIL LAPORAN
==================================*/

$pdf->SetFont('Arial','B',12);
$pdf->Cell(287,8,'DETAIL PELAYANAN APOTEK',0,1);

$pdf->SetFillColor(33,115,255);
$pdf->SetTextColor(255,255,255);
$pdf->SetFont('Arial','B',9);

/* Header Tabel */

$pdf->Cell(22,10,'Tanggal',1,0,'C',true);
$pdf->Cell(22,10,'No RM',1,0,'C',true);
$pdf->Cell(48,10,'Pasien',1,0,'C',true);
$pdf->Cell(58,10,'Dokter',1,0,'C',true);
$pdf->Cell(20,10,'Obat',1,0,'C',true);
$pdf->Cell(35,10,'Total',1,0,'C',true);
$pdf->Cell(35,10,'Status',1,0,'C',true);
$pdf->Cell(47,10,'Tanggal Ambil',1,1,'C',true);

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('Arial','',8);


/*==================================
QUERY DATA
==================================*/

$data = mysqli_query($conn,"

SELECT

r.id,
r.tanggal,
r.status,
r.total_harga,

u.no_rm,
u.nama,

d.nama_dokter,

COUNT(ro.id) jumlah_obat

FROM resep r

JOIN pemeriksaan pm
ON pm.id=r.id_pemeriksaan

JOIN pendaftaran p
ON p.id=pm.id_pendaftaran

JOIN users u
ON u.id=p.id_user

LEFT JOIN dokter d
ON d.id=pm.id_dokter

LEFT JOIN resep_obat ro
ON ro.id_resep=r.id

WHERE $where

GROUP BY r.id

ORDER BY r.tanggal DESC

");



$totalKeseluruhan=0;



while($row=mysqli_fetch_assoc($data)){

$totalKeseluruhan += $row['total_harga'];


/* Warna selang-seling */

$pdf->SetFillColor(250,250,250);

$pdf->Cell(
22,
8,
date('d-m-Y',strtotime($row['tanggal'])),
1,
0,
'C',
true
);

$pdf->Cell(
22,
8,
$row['no_rm'],
1,
0,
'C',
true
);


/* PASIEN */

$pdf->Cell(
48,
8,
substr($row['nama'],0,28),
1,
0,
'L',
true
);


/* DOKTER */

$pdf->Cell(
58,
8,
substr($row['nama_dokter'],0,32),
1,
0,
'L',
true
);


/* JUMLAH OBAT */

$pdf->Cell(
20,
8,
$row['jumlah_obat'],
1,
0,
'C',
true
);


/* TOTAL */

$pdf->Cell(
35,
8,
'Rp '.number_format($row['total_harga'],0,",","."),
1,
0,
'R',
true
);


/* STATUS */

$statusCetak=$row['status'];

if($statusCetak=="Sudah Diambil"){

$statusCetak="Sudah";

}

elseif($statusCetak=="Sedang Disiapkan"){

$statusCetak="Proses";

}

elseif($statusCetak=="Belum Diambil"){

$statusCetak="Belum";

}

$pdf->Cell(
35,
8,
$statusCetak,
1,
0,
'C',
true
);


/* Tanggal Ambil */

$pdf->Cell(
47,
8,
date('d-m-Y',strtotime($row['tanggal'])),
1,
1,
'C',
true
);

}


/*==================================
TOTAL
==================================*/

$pdf->SetFont('Arial','B',10);

$pdf->SetFillColor(230,240,255);

$pdf->Cell(
170,
10,
'TOTAL PENDAPATAN',
1,
0,
'R',
true
);

$pdf->Cell(
117,
10,
'Rp '.number_format($totalKeseluruhan,0,",","."),
1,
1,
'R',
true
);

$pdf->Ln(8);

/*==================================
CATATAN LAPORAN
==================================*/

$pdf->SetFont('Arial','I',9);
$pdf->SetTextColor(90,90,90);

$pdf->MultiCell(
287,
6,
"Catatan :
Laporan ini dibuat secara otomatis oleh Sistem Informasi Klinik BTH Tasikmalaya. Seluruh data bersumber dari transaksi pelayanan apotek yang telah tersimpan di dalam sistem.",
0,
'L'
);

$pdf->Ln(6);


/*==================================
INFORMASI CETAK
==================================*/

$pdf->SetFont('Arial','',9);

$pdf->Cell(
45,
6,
"Dicetak Oleh",
0,
0
);

$pdf->Cell(
5,
6,
":",
0,
0
);

$pdf->Cell(
120,
6,
$_SESSION['nama'] ?? "Petugas Apotek",
0,
1
);

$pdf->Cell(
45,
6,
"Tanggal Cetak",
0,
0
);

$pdf->Cell(
5,
6,
":",
0,
0
);

$pdf->Cell(
120,
6,
date('d-m-Y H:i:s'),
0,
1
);

$pdf->Ln(15);


/*==================================
TANDA TANGAN
==================================*/

$pdf->SetFont('Arial','',10);

$pdf->Cell(140,6,"Mengetahui,",0,0,'C');
$pdf->Cell(147,6,"Petugas Apotek,",0,1,'C');

$pdf->Ln(22);

$pdf->SetFont('Arial','B',10);

$pdf->Cell(
140,
6,
"( ________________________ )",
0,
0,
'C'
);

$pdf->Cell(
147,
6,
"( ".$_SESSION['nama']." )",
0,
1,
'C'
);

$pdf->SetFont('Arial','',9);

$pdf->Cell(
140,
5,
"Kepala Klinik",
0,
0,
'C'
);

$pdf->Cell(
147,
5,
"Petugas Apotek",
0,
1,
'C'
);


/*==================================
FOOTER
==================================*/

$pdf->SetY(-12);

$pdf->SetFont('Arial','I',8);

$pdf->SetTextColor(120,120,120);

$pdf->Cell(
0,
6,
"Klinik BTH Tasikmalaya | Halaman ".$pdf->PageNo(),
0,
0,
'C'
);


/*==================================
OUTPUT
==================================*/

ob_clean();

$pdf->Output(
"I",
"Laporan_Apotek_".date('Ymd').".pdf"
);

exit;