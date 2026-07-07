<?php
session_start();
require_once "config/koneksi.php";

/* ==========================================
   LOGIN DOKTER
========================================== */

if(!isset($_SESSION['id_dokter'])){
    header("Location: login_dokter.php");
    exit;
}

/* ==========================================
   ID PENDAFTARAN
========================================== */

if(!isset($_GET['id'])){
    die("Data pasien tidak ditemukan.");
}

$id = intval($_GET['id']);

/* ==========================================
   DATA PASIEN
========================================== */

$query = mysqli_query($conn,"
SELECT
p.*,
u.nama,
u.no_rm,
u.nim_nip,
u.no_hp,
u.jenis_kelamin
FROM pendaftaran p
JOIN users u
ON p.id_user=u.id
WHERE p.id='$id'
");

if(mysqli_num_rows($query)==0){
    die("Data pasien tidak ditemukan.");
}

$data=mysqli_fetch_assoc($query);


/* ==========================================
   DATA OBAT
========================================== */

$obat=mysqli_query($conn,"
SELECT *
FROM obat
ORDER BY nama_obat ASC
");
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width,initial-scale=1.0">

<title>Pemeriksaan Pasien</title>

<script src="https://cdn.tailwindcss.com"></script>

<link
href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
rel="stylesheet">

<style>

body{

font-family:'Poppins',sans-serif;

background:#F4F7FB;

}

.card{

background:white;

border-radius:20px;

box-shadow:0 8px 20px rgba(0,0,0,.08);

}

.judul{

font-size:22px;

font-weight:700;

color:#2563eb;

}

.label{

font-size:13px;

color:#64748b;

}

.value{

font-weight:600;

color:#1e293b;

}

input,
textarea,
select{

border:1px solid #d1d5db;

}

input:focus,
textarea:focus,
select:focus{

outline:none;

border-color:#2563eb;

box-shadow:0 0 0 3px rgba(37,99,235,.15);

}

</style>

</head>

<body>

<div class="max-w-7xl mx-auto p-4">

<!-- HEADER -->

<div class="flex justify-between items-center mb-6">

<div>

<h1 class="text-3xl font-bold text-blue-700">

🩺 Pemeriksaan Pasien

</h1>

<p class="text-gray-500 mt-2">

Lengkapi hasil pemeriksaan pasien.

</p>

</div>

<a
href="dashboard_dokter.php"
class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-xl">

← Kembali

</a>

</div>

<form action="proses_pemeriksaan.php" method="POST">

<input
type="hidden"
name="id_pendaftaran"
value="<?= $data['id']; ?>">


<!-- ==========================================
     INFORMASI PASIEN
========================================== -->

<div class="card p-4 mb-4">

<h2 class="judul mb-5">

👤 Informasi Pasien

</h2>

<div class="grid grid-cols-2 md:grid-cols-4 gap-5">

<div>

<div class="label">

Nama Pasien

</div>

<div class="value">

<?= htmlspecialchars($data['nama'] ?? '-'); ?>

</div>

</div>

<div>

<div class="label">

No. Rekam Medis

</div>

<div class="value">

<?= htmlspecialchars($data['no_rm'] ?? '-') ?>

</div>

</div>

<div>

<div class="label">

NIM / NIP

</div>

<div class="value">

<?= htmlspecialchars($data['nim_nip'] ?? '-'); ?>

</div>

</div>

<div>

<div class="label">

Jenis Kelamin

</div>

<div class="value">

<?= htmlspecialchars($data['jenis_kelamin'] ?? '-'); ?>

</div>

</div>

<div>

<div class="label">

Nomor HP

</div>

<div class="value">

<?= htmlspecialchars($data['no_hp'] ?? '-'); ?>

</div>

</div>

<div>

<div class="label">

Poli

</div>

<div class="value">

<?= htmlspecialchars($data['poli'] ?? '-'); ?>

</div>

</div>

<div>

<div class="label">

Dokter

</div>

<div class="value">

<?= htmlspecialchars($data['dokter'] ?? '-'); ?>

</div>

</div>

<div>

<div class="label">

Nomor Antrian

</div>

<div class="inline-block bg-blue-600 text-white px-4 py-2 rounded-xl font-bold">

<?= htmlspecialchars($data['nomor_antrian'] ?? '-'); ?>

</div>

</div>

</div>

<div class="mt-6">

<label class="block mb-2 font-semibold text-orange-600">

Keluhan Pasien

</label>

<textarea
readonly
rows="3"
class="w-full rounded-xl p-4 bg-orange-50"><?= htmlspecialchars($data['keluhan'] ?? '-'); ?></textarea>

</div>

</div>


<!-- ==========================================
     TANDA VITAL
========================================== -->

<div class="card p-4 mb-6">

<h2 class="judul mb-5">

❤️ Tanda Vital

</h2>

<div class="grid grid-cols-2 md:grid-cols-4 gap-5">

<div>

<label class="label">

Tekanan Darah

</label>

<input
type="text"
name="tekanan_darah"
placeholder="120/80"
class="w-full rounded-xl px-3 py-2 mt-2"
required>

</div>

<div>

<label class="label">

Suhu

</label>

<input
type="text"
name="suhu"
placeholder="36.5"
class="w-full rounded-xl p-3 mt-2"
required>

</div>

<div>

<label class="label">

Nadi

</label>

<input
type="text"
name="nadi"
placeholder="80"
class="w-full rounded-xl p-3 mt-2"
required>

</div>

<div>

<label class="label">

Respirasi

</label>

<input
type="text"
name="respirasi"
placeholder="20"
class="w-full rounded-xl p-3 mt-2"
required>

</div>

<div>

<label class="label">

SpO₂

</label>

<input
type="text"
name="spo2"
placeholder="98"
class="w-full rounded-xl p-3 mt-2"
required>

</div>

<div>

<label class="label">

Berat Badan

</label>

<input
type="text"
name="berat_badan"
placeholder="55"
class="w-full rounded-xl p-3 mt-2"
required>

</div>

<div>

<label class="label">

Tinggi Badan

</label>

<input
type="text"
name="tinggi_badan"
placeholder="165"
class="w-full rounded-xl p-3 mt-2"
required>

</div>

</div>

</div>


<!-- ==========================================
     DIAGNOSA & TINDAKAN
========================================== -->

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-4">

    <!-- Diagnosa -->

    <div class="card p-6">

        <h2 class="judul mb-4">

            🩺 Diagnosa

        </h2>

        <textarea
        name="diagnosa"
        rows="5"
        class="w-full rounded-xl p-4 resize-none"
        placeholder="Tuliskan hasil diagnosa pasien..."
        required></textarea>

    </div>

    <!-- Tindakan -->

    <div class="card p-6">

        <h2 class="judul mb-4 text-green-700">

            💉 Tindakan Medis

        </h2>

        <textarea
        name="tindakan"
        rows="5"
        class="w-full rounded-xl p-4 resize-none"
        placeholder="Tuliskan tindakan medis..."
        required></textarea>

    </div>

</div>


<!-- ==========================================
     INSTRUKSI & CATATAN
========================================== -->

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">

    <!-- Instruksi -->

    <div class="card p-6">

        <h2 class="judul mb-4 text-orange-600">

            📋 Instruksi Tambahan

        </h2>

        <textarea
        name="instruksi"
        rows="4"
        class="w-full rounded-xl p-4 resize-none"
        placeholder="Contoh :
- Minum obat sesudah makan
- Istirahat cukup
- Kontrol 3 hari lagi"></textarea>

    </div>

    <!-- Catatan -->

    <div class="card p-6">

        <h2 class="judul mb-4 text-purple-700">

            📝 Catatan Dokter

        </h2>

        <textarea
        name="catatan"
        rows="4"
        class="w-full rounded-xl p-4 resize-none"
        placeholder="Catatan tambahan dokter..."></textarea>

    </div>

</div>



<!-- ==========================================
     RESEP OBAT
========================================== -->

<div class="card p-6 mb-6">

    <h2 class="judul mb-5">
        💊 Resep Obat
    </h2>

    <div class="mb-5">
        <input
        type="text"
        id="keyword"
        placeholder="🔍 Cari nama obat..."
        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">

</div>

    <!-- HASIL PENCARIAN -->

    <div
    id="hasilCari"
    class="border rounded-xl">

        <div class="p-5 text-center text-gray-400">

            Ketik nama obat lalu klik Cari

        </div>

    </div>

    <hr class="my-6">

    <h3 class="text-xl font-bold text-green-700 mb-4">

        Obat Dipilih

    </h3>

    <div id="listResep">

        <p class="text-gray-400">

            Belum ada obat dipilih.

        </p>

    </div>

</div>


<script>


// ======================================
// REAL TIME SEARCH OBAT
// ======================================

let timerCari;

document.getElementById("keyword").addEventListener("keyup", function(){

    clearTimeout(timerCari);

    let keyword=this.value.trim();

    timerCari=setTimeout(function(){

        if(keyword.length<2){

            document.getElementById("hasilCari").innerHTML=`

            <div class="p-5 text-center text-gray-400">

                Ketik minimal 2 huruf...

            </div>

            `;

            return;

        }

        fetch("cari_obat.php?keyword="+encodeURIComponent(keyword))

        .then(res=>res.text())

        .then(html=>{

            document.getElementById("hasilCari").innerHTML=html;

            aktifkanTambah();

        });

    },300);

});

// ======================================
// TAMBAH OBAT
// ======================================

function aktifkanTambah(){

document.querySelectorAll(".tambahObat").forEach(function(btn){

btn.onclick=function(){

let id=this.dataset.id;

let nama=this.dataset.nama;

let dosis=this.dataset.dosis;


// jika sudah dipilih

if(document.getElementById("obat_"+id)){

alert("Obat sudah dipilih.");

return;

}


if(document.getElementById("listResep").innerHTML.includes("Belum ada")){

document.getElementById("listResep").innerHTML="";

}


document.getElementById("listResep").innerHTML+=`

<div id="obat_${id}" class="border rounded-xl p-3 mb-2 bg-green-50">

<input type="hidden" name="obat[]" value="${id}">

<div class="flex justify-between items-center">

<div>

<div class="font-bold text-lg">
${nama}
</div>

<div class="text-gray-500">
${dosis}
</div>

</div>

<button

type="button"

onclick="hapusObat(${id})"

class="text-red-600 font-bold">

Hapus

</button>

</div>


<div class="grid grid-cols-2 gap-4 mt-4">

<div>

<label>Jumlah</label>

<select
name="jumlah[]"
class="w-full border rounded-lg p-2">

<option value="5">5 Tablet</option>
<option value="10" selected>10 Tablet</option>
<option value="15">15 Tablet</option>
<option value="20">20 Tablet</option>
<option value="30">30 Tablet</option>

</select>

</div>


<div>

<label>Aturan Pakai</label>

<select
name="aturan[]"
class="w-full border rounded-lg p-2">

<option>1x1 Sesudah Makan</option>
<option>2x1 Sesudah Makan</option>
<option selected>3x1 Sesudah Makan</option>
<option>3x1 Sebelum Makan</option>
<option>Sesuai Anjuran Dokter</option>

</select>

</div>

</div>

</div>

`;

}

});

}


// ======================================
// HAPUS OBAT
// ======================================

function hapusObat(id){

document.getElementById("obat_"+id).remove();

if(document.getElementById("listResep").innerHTML.trim()==""){

document.getElementById("listResep").innerHTML=`
<p class="text-gray-400">
Belum ada obat dipilih.
</p>`;

}

}

</script>


<hr class="my-8">

<div class="flex justify-between items-center">

    <a
    href="dashboard_dokter.php"
    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-xl">

        ← Kembali

    </a>

    <button
    type="submit"
    class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-xl shadow-lg">

        💾 Simpan Pemeriksaan

    </button>

</div>

</form>

</div>

</body>

</html>