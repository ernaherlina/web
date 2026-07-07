<?php
session_start();
require_once "config/koneksi.php";

date_default_timezone_set("Asia/Jakarta");

if(!isset($_SESSION['id_dokter'])){
    header("Location: login_dokter.php");
    exit;
}

mysqli_begin_transaction($conn);

try{

/* =====================================
   AMBIL DATA FORM
===================================== */

$id_pendaftaran = isset($_POST['id_pendaftaran'])
    ? intval($_POST['id_pendaftaran'])
    : 0;

$id_dokter = intval($_SESSION['id_dokter']);

$tekanan_darah = mysqli_real_escape_string($conn,$_POST['tekanan_darah'] ?? '');
$suhu          = mysqli_real_escape_string($conn,$_POST['suhu'] ?? '');
$nadi          = mysqli_real_escape_string($conn,$_POST['nadi'] ?? '');
$respirasi     = mysqli_real_escape_string($conn,$_POST['respirasi'] ?? '');
$spo2          = mysqli_real_escape_string($conn,$_POST['spo2'] ?? '');

$berat_badan   = mysqli_real_escape_string($conn,$_POST['berat_badan'] ?? '');
$tinggi_badan  = mysqli_real_escape_string($conn,$_POST['tinggi_badan'] ?? '');

$diagnosa      = mysqli_real_escape_string($conn,$_POST['diagnosa'] ?? '');
$tindakan      = mysqli_real_escape_string($conn,$_POST['tindakan'] ?? '');
$instruksi     = mysqli_real_escape_string($conn,$_POST['instruksi'] ?? '');
$catatan       = mysqli_real_escape_string($conn,$_POST['catatan'] ?? '');

$obat   = $_POST['obat'] ?? [];
$jumlah = $_POST['jumlah'] ?? [];
$aturan = $_POST['aturan'] ?? [];


/* =====================================
   VALIDASI
===================================== */

if($id_pendaftaran<=0){

    throw new Exception("ID Pendaftaran tidak ditemukan.");

}

if(trim($diagnosa)==""){

    throw new Exception("Diagnosa wajib diisi.");

}

/* =====================================
   AMBIL DATA PENDAFTARAN
===================================== */

$q = mysqli_query($conn,"
SELECT *
FROM pendaftaran
WHERE id='$id_pendaftaran'
LIMIT 1
");

if(!$q){

    throw new Exception(mysqli_error($conn));

}

if(mysqli_num_rows($q)==0){

    throw new Exception("Data pendaftaran tidak ditemukan.");

}

$data = mysqli_fetch_assoc($q);

/* =====================================
   GABUNGKAN NAMA OBAT
===================================== */

$resep="";

if(!empty($obat)){
$list=[];
foreach($obat as $idObat){
$idObat=intval($idObat);
if($idObat<=0){
continue;
}

        $qObat=mysqli_query($conn,"
        SELECT nama_obat
        FROM obat
        WHERE id='$idObat'
        ");

        if(!$qObat){
        throw new Exception(mysqli_error($conn));
        }

        if(mysqli_num_rows($qObat)>0){
            $d=mysqli_fetch_assoc($qObat);
             $list[]=$d['nama_obat'];
             }

}

$resep=implode(", ",$list);

}

/* =====================================
   SIMPAN PEMERIKSAAN
===================================== */

$simpanPemeriksaan = mysqli_query($conn,"
INSERT INTO pemeriksaan
(
    id_pendaftaran,
    id_dokter,
    tekanan_darah,
    suhu,
    nadi,
    respirasi,
    spo2,
    berat_badan,
    tinggi_badan,
    diagnosa,
    tindakan,
    instruksi,
    resep,
    status,
    catatan
)
VALUES
(
    '$id_pendaftaran',
    '$id_dokter',
    '$tekanan_darah',
    '$suhu',
    '$nadi',
    '$respirasi',
    '$spo2',
    '$berat_badan',
    '$tinggi_badan',
    '$diagnosa',
    '$tindakan',
    '$instruksi',
    '$resep',
    'Selesai',
    '$catatan'
)
");

if(!$simpanPemeriksaan){

    throw new Exception("Gagal menyimpan pemeriksaan : ".mysqli_error($conn));

}

/* =====================================
   AMBIL ID PEMERIKSAAN
===================================== */

$id_pemeriksaan = mysqli_insert_id($conn);

if($id_pemeriksaan <= 0){

    throw new Exception("ID Pemeriksaan gagal dibuat.");

}

/* =====================================
   SIMPAN DATA RESEP
===================================== */

$simpanResep = mysqli_query($conn,"
INSERT INTO resep
(
    id_pemeriksaan,
    status
)
VALUES
(
    '$id_pemeriksaan',
    'Belum Diambil'
)
");

if(!$simpanResep){

    throw new Exception("Gagal membuat resep : ".mysqli_error($conn));

}

/* =====================================
   AMBIL ID RESEP
===================================== */

$id_resep = mysqli_insert_id($conn);

if($id_resep <= 0){

    throw new Exception("ID Resep gagal dibuat.");

}

/* =====================================
   SIMPAN DETAIL RESEP OBAT
===================================== */

if(!empty($obat)){

    foreach($obat as $i => $id_obat){

        $id_obat = intval($id_obat);

        if($id_obat <= 0){
            continue;
        }

        $qty = isset($jumlah[$i]) ? intval($jumlah[$i]) : 1;

        if($qty <= 0){
            $qty = 1;
        }

        $aturan_pakai = mysqli_real_escape_string(
            $conn,
            $aturan[$i] ?? ''
        );

        /* ============================
           CEK DATA OBAT
        ============================ */

        $cekObat = mysqli_query($conn,"
        SELECT stok
        FROM obat
        WHERE id='$id_obat'
        LIMIT 1
        ");

        if(!$cekObat){

            throw new Exception("Gagal mengambil data obat : ".mysqli_error($conn));

        }

        if(mysqli_num_rows($cekObat)==0){

            throw new Exception("Obat tidak ditemukan.");

        }

        $dataObat = mysqli_fetch_assoc($cekObat);

        $stok = (int)$dataObat['stok'];

        if($stok < $qty){

            throw new Exception("Stok obat tidak mencukupi.");

        }

        /* ============================
           SIMPAN RESEP OBAT
        ============================ */

        $insertResepObat = mysqli_query($conn,"
        INSERT INTO resep_obat
        (
            id_resep,
            id_obat,
            jumlah,
            aturan_pakai
        )
        VALUES
        (
            '$id_resep',
            '$id_obat',
            '$qty',
            '$aturan_pakai'
        )
        ");

            if(!$insertResepObat){

            throw new Exception("Gagal menyimpan resep obat : ".mysqli_error($conn));

        }

        /* ============================
           UPDATE STOK OBAT
        ============================ */

        $updateStok = mysqli_query($conn,"
        UPDATE obat
        SET stok = stok - $qty
        WHERE id='$id_obat'
        ");

        if(!$updateStok){

    throw new Exception(
        "Gagal mengurangi stok obat : ".mysqli_error($conn)
    );
    
    }

    } 
    
}

/* =====================================
   UPDATE STATUS PENDAFTARAN
===================================== */

$updatePendaftaran = mysqli_query($conn,"
UPDATE pendaftaran
SET
    status='Selesai'
WHERE id='$id_pendaftaran'
");

if(!$updatePendaftaran){

    throw new Exception(
        "Gagal mengubah status pendaftaran : ".
        mysqli_error($conn)
    );

}

/* =====================================
   COMMIT TRANSAKSI
===================================== */

if(!mysqli_commit($conn)){

    throw new Exception(
        "Commit transaksi gagal."
    );

}

/* =====================================
   BERHASIL
===================================== */

$_SESSION['success'] = "Pemeriksaan berhasil disimpan.";

header("Location: detail_pemeriksaan.php?id=".$id_pemeriksaan);

exit;

}catch(Exception $e){

    mysqli_rollback($conn);

    $_SESSION['error'] = $e->getMessage();

    header("Location: pemeriksaan.php?id=".$id_pendaftaran);

    exit;

}