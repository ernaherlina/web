<?php
session_start();

if(!isset($_SESSION['nama'])){
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>

<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Daftar Berobat</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

body{
    font-family:'Poppins',sans-serif;
    background:linear-gradient(
        135deg,
        #eff6ff,
        #f8fafc,
        #ecfeff
    );
}

</style>

</head>

<body class="min-h-screen flex justify-center items-center p-4">

<div class="w-full max-w-xl bg-white rounded-3xl shadow-lg p-6">

<div class="text-center mb-6">

    <h1 class="text-2xl font-bold text-blue-600">
        Pendaftaran Berobat
    </h1>

    <p class="text-slate-500 text-sm mt-1">
        Silakan isi data pemeriksaan pasien
    </p>

</div>

<form action="simpan_antrian.php" method="POST" class="space-y-4">

    <!-- Nama -->
    <div>

        <label class="font-medium text-slate-700 text-sm">
            Nama Pasien
        </label>

        <input
        type="text"
        value="<?php echo $_SESSION['nama']; ?>"
        readonly

        class="w-full mt-2 border border-slate-200
        rounded-xl p-3 bg-slate-100 text-sm">

    </div>

    <!-- Keluhan -->
    <div>

        <label class="font-medium text-slate-700 text-sm">
            Keluhan
        </label>

        <textarea
        name="keluhan"
        required

        class="w-full mt-2 border border-slate-200
        rounded-xl p-3 h-20 text-sm
        focus:outline-none
        focus:ring-2 focus:ring-blue-500"

        placeholder="Contoh: demam, batuk, sakit kepala..."></textarea>

    </div>

    <!-- Tanggal -->
<div>

    <label class="font-medium text-slate-700 text-sm">
        Tanggal Berobat
    </label>

    <input
    type="date"
    name="tanggal_periksa"
    id="tanggal_periksa"
    min="<?= date('Y-m-d'); ?>"
    required

    class="w-full mt-2 border border-slate-200
    rounded-xl p-3 text-sm
    focus:outline-none
    focus:ring-2 focus:ring-blue-500">

    <p class="text-xs text-red-500 mt-1">
        * Klinik tutup setiap hari Minggu
    </p>

</div>

    <!-- Dokter -->
    <div>

        <label class="font-medium text-slate-700 text-sm">
            Pilih Dokter
        </label>

        <select
        name="dokter"
        id="dokter"
        required

        class="w-full mt-2 border border-slate-200
        rounded-xl p-3 text-sm
        focus:outline-none
        focus:ring-2 focus:ring-blue-500">

            <option value="">
                -- Pilih Dokter --
            </option>

            <option value="dr. Nayanika Putri Mareza">
                dr. Nayanika Putri Mareza
            </option>

            <option value="dr. Mahendra Mareza Putra">
                dr. Mahendra Mareza Putra
            </option>

        </select>

    </div>

    <!-- Jam -->
    <div>

        <label class="font-medium text-slate-700 text-sm">
            Pilih Jam Pemeriksaan
        </label>

        <select
        id="jam_periksa"
        name="jam_periksa"
        required

        class="w-full mt-2 border border-slate-200
        rounded-xl p-3 text-sm
        focus:outline-none
        focus:ring-2 focus:ring-blue-500">

            <option value="">
                -- Pilih Jam --
            </option>

        </select>

    </div>

    <!-- Info -->
    <div class="bg-blue-50 border border-blue-100 p-3 rounded-xl">

        <h3 class="font-semibold text-blue-700 text-sm mb-1">
            Informasi Jadwal
        </h3>

        <p class="text-xs text-blue-600">

            🕗 08:00 - 11:40 WIB

            <br>

            🕜 13:30 - 14:00 WIB

            <br><br>

            

        </p>

    </div>

    <!-- Tombol -->
    <button
    type="submit"

    class="w-full bg-gradient-to-r
    from-blue-500 to-blue-700
    text-white font-semibold
    py-3 rounded-xl
    hover:opacity-90 transition">

        Daftar Sekarang

    </button>

</form>

</div>

<script>

function loadJam(){
        



    let dokter =
    document.getElementById('dokter').value;

    let tanggal =
    document.getElementById('tanggal_periksa').value;

    if(dokter === '' || tanggal === ''){

        document.getElementById('jam_periksa').innerHTML =
        '<option value="">-- Pilih Jam --</option>';

        return;
    }

    fetch(
    'ambil_jam.php?dokter=' +
    encodeURIComponent(dokter) +
    '&tanggal=' +
    encodeURIComponent(tanggal)
)

.then(response => response.text())

.then(data => {

console.log(data);

document.getElementById('jam_periksa').innerHTML =
data;

});

}

document
.getElementById('dokter')
.addEventListener('change', loadJam);

document
.getElementById('tanggal_periksa')
.addEventListener('change', loadJam);

</script>

</body>
</html>

</body>
</html>
