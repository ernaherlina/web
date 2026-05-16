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
    font-family: 'Poppins', sans-serif;
    background:
    linear-gradient(
    135deg,
    #eff6ff,
    #f8fafc,
    #ecfeff
    );
}

</style>

</head>

<body class="min-h-screen flex justify-center items-center p-6">

<div class="w-full max-w-2xl bg-white rounded-[35px] shadow-xl p-8">

    <!-- JUDUL -->
    <div class="text-center mb-8">

        <h1 class="text-4xl font-bold text-blue-600">
            Pendaftaran Berobat
        </h1>

        <p class="text-slate-500 mt-2">
            Silakan isi data pemeriksaan pasien
        </p>

    </div>

    <!-- FORM -->
    <form action="simpan_antrian.php" method="POST"
    class="space-y-5">

        <!-- NAMA -->
        <div>

            <label class="font-medium text-slate-700">
                Nama Pasien
            </label>

            <input
            type="text"
            name="nama"
            value="<?php echo $_SESSION['nama']; ?>"
            readonly

            class="w-full mt-2 border border-slate-200
            rounded-2xl p-4 bg-slate-100
            focus:outline-none">

        </div>

        <!-- KELUHAN -->
        <div>

            <label class="font-medium text-slate-700">
                Keluhan
            </label>

            <textarea
            name="keluhan"
            required

            class="w-full mt-2 border border-slate-200
            rounded-2xl p-4 h-32
            focus:outline-none
            focus:ring-2 focus:ring-blue-500"

            placeholder="Contoh: demam, batuk, sakit kepala..."></textarea>

        </div>

        <!-- PILIH DOKTER -->
        <div>

            <label class="font-medium text-slate-700">
                Pilih Dokter
            </label>

            <select
            name="dokter"
            required

            class="w-full mt-2 border border-slate-200
            rounded-2xl p-4
            focus:outline-none
            focus:ring-2 focus:ring-blue-500">

                <option value="">
                    -- Pilih Dokter --
                </option>

                <option>
                    dr. Nayanka Putri Mareza
                </option>

                <option>
                    dr. Mahendra Mareza Putra
                </option>

            </select>

        </div>

        <!-- JADWAL -->
        <div>

            <label class="font-medium text-slate-700">
                Jadwal Pemeriksaan
            </label>

            <input
            type="date"
            name="jadwal"
            required

            class="w-full mt-2 border border-slate-200
            rounded-2xl p-4
            focus:outline-none
            focus:ring-2 focus:ring-blue-500">

        </div>

        <!-- BUTTON -->
        <button
        type="submit"

        class="w-full bg-gradient-to-r
        from-blue-500 to-blue-700
        hover:scale-[1.01]
        transition
        text-white font-semibold
        py-4 rounded-2xl shadow-lg">

            Daftar Sekarang

        </button>

    </form>

</div>

</body>
</html>