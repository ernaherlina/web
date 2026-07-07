<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Daftar Akun - Klinik BTH</title>

<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<link rel="preconnect" href="https://fonts.googleapis.com">

<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

body{
    font-family:'Inter',sans-serif;
}

</style>

</head>

<body class="bg-slate-100 min-h-screen flex items-center justify-center p-3 md:p-4">

<!-- CONTAINER -->
<div class="bg-white rounded-3xl shadow-xl max-w-4xl w-full flex flex-col md:flex-row overflow-hidden min-h-[580px]">

    <!-- LEFT -->
    <div class="w-full md:w-[40%] bg-gradient-to-b from-[#1e61d4] via-[#2563eb] to-[#f59e0b] p-6 md:p-8 flex flex-col justify-between relative text-white">

        <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>

        <!-- LOGO -->
        <div class="flex justify-center">

            <a href="index.php">

                <img
                src="assets/img/logo_klinik.png"
                alt="Logo Klinik BTH"
                class="w-24 md:w-30">

            </a>

        </div>

        <!-- TEXT -->
        <div class="my-8 md:my-0 z-10 text-center md:text-left">

            <h1 class="text-2xl md:text-3xl font-bold tracking-wide mb-3">

                Buat Akun
                <br>
                Baru

            </h1>

            <p class="text-white/90 text-xs leading-relaxed max-w-sm mx-auto md:mx-0">

                Sistem pendaftaran berobat online untuk mahasiswa dan karyawan agar pelayanan kesehatan menjadi lebih cepat, aman, dan nyaman.

            </p>

        </div>

        <!-- CARD -->
        <div class="bg-white/95 backdrop-blur-sm rounded-xl p-3.5 text-slate-800 flex items-start gap-3 shadow-md z-10 max-w-sm mx-auto md:mx-0">

            <div class="bg-blue-100 p-2 rounded-lg text-blue-600 flex items-center justify-center shrink-0">

                <i class="fa-solid fa-clipboard-list text-lg"></i>

            </div>

            <div>

                <h4 class="font-bold text-xs text-blue-900 mb-0.5">

                    Pendaftaran Online

                </h4>

                <p class="text-[11px] text-slate-600 leading-normal">

                    Daftar berobat lebih cepat dan mudah tanpa antre panjang.

                </p>

            </div>

        </div>

    </div>

    <!-- RIGHT -->
    <div class="w-full md:w-[60%] p-5 md:p-8 flex flex-col justify-center bg-white relative">

        <!-- TITLE -->
        <div class="mb-5">

            <h2 class="text-xl md:text-2xl font-bold text-blue-950 mb-1">

                Daftar Akun

            </h2>

            <p class="text-xs text-slate-500">

                Silakan isi data diri Anda dengan benar.

            </p>

        </div>

        <!-- FORM -->
        <form action="proses_registrasi.php" method="POST" class="space-y-3">

            <!-- NAMA -->
            <div class="flex flex-col gap-1">

                <label class="text-[11px] font-semibold text-blue-950">

                    Nama Lengkap

                </label>

                <div class="relative flex items-center">

                    <i class="fa-regular fa-user absolute left-3.5 text-slate-400 text-xs"></i>

                    <input
                    type="text"
                    name="nama"
                    required
                    placeholder="Masukkan nama lengkap"
                    class="w-full pl-10 pr-4 py-2.5 text-xs bg-white border border-slate-200 rounded-xl outline-none focus:border-blue-500 text-slate-700 placeholder-slate-400 transition-colors">

                </div>

            </div>

            <!-- GRID -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                <!-- NIM -->
                <div class="flex flex-col gap-1">

                    <label class="text-[11px] font-semibold text-blue-950">

                        NIM / NIP

                    </label>

                    <div class="relative flex items-center">

                        <i class="fa-regular fa-id-card absolute left-3.5 text-slate-400 text-xs"></i>

                        <input
                        type="text"
                        name="nim_nip"
                        required
                        placeholder="Masukkan NIM/NIP"
                        class="w-full pl-10 pr-4 py-2.5 text-xs bg-white border border-slate-200 rounded-xl outline-none focus:border-blue-500 text-slate-700 placeholder-slate-400 transition-colors">

                    </div>

                </div>

                <!-- STATUS -->
                <div class="flex flex-col gap-1">

                    <label class="text-[11px] font-semibold text-blue-950">

                        Status

                    </label>

                    <div class="relative flex items-center">

                        <select
                        name="status_user"
                        required
                        class="w-full px-3.5 py-2.5 text-xs bg-white border border-slate-200 rounded-xl outline-none focus:border-blue-500 text-slate-700 appearance-none transition-colors">

                            <option value="" disabled selected hidden>

                                Pilih Status

                            </option>

                            <option value="Mahasiswa">

                                Mahasiswa

                            </option>

                            <option value="Karyawan">

                                Karyawan

                            </option>

                        </select>

                        <i class="fa-solid fa-chevron-down absolute right-3.5 text-slate-400 text-[10px] pointer-events-none"></i>

                    </div>

                </div>

            </div>

            <!-- GRID -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                <!-- JK -->
                <div class="flex flex-col gap-1">

                    <label class="text-[11px] font-semibold text-blue-950">

                        Jenis Kelamin

                    </label>

                    <div class="relative flex items-center">

                        <select
                        name="jenis_kelamin"
                        required
                        class="w-full px-3.5 py-2.5 text-xs bg-white border border-slate-200 rounded-xl outline-none focus:border-blue-500 text-slate-700 appearance-none transition-colors">

                            <option value="" disabled selected hidden>

                                Pilih Jenis Kelamin

                            </option>

                            <option value="Laki-laki">

                                Laki-laki

                            </option>

                            <option value="Perempuan">

                                Perempuan

                            </option>

                        </select>

                        <i class="fa-solid fa-chevron-down absolute right-3.5 text-slate-400 text-[10px] pointer-events-none"></i>

                    </div>

                </div>

                <!-- HP -->
                <div class="flex flex-col gap-1">

                    <label class="text-[11px] font-semibold text-blue-950">

                        No HP

                    </label>

                    <div class="relative flex items-center">

                        <i class="fa-solid fa-phone absolute left-3.5 text-slate-400 text-xs"></i>

                        <input
                        type="tel"
                        name="no_hp"
                        required
                        placeholder="08xxxxxxxxxx"
                        class="w-full pl-10 pr-4 py-2.5 text-xs bg-white border border-slate-200 rounded-xl outline-none focus:border-blue-500 text-slate-700 placeholder-slate-400 transition-colors">

                    </div>

                </div>

            </div>

            <!-- PASSWORD -->
            <div class="flex flex-col gap-1">

                <label class="text-[11px] font-semibold text-blue-950">

                    Password

                </label>

                <div class="relative flex items-center">

                    <i class="fa-solid fa-lock absolute left-3.5 text-slate-400 text-xs"></i>

                    <input
                    type="password"
                    name="password"
                    required
                    placeholder="Minimal 8 karakter"
                    class="w-full pl-10 pr-10 py-2.5 text-xs bg-white border border-slate-200 rounded-xl outline-none focus:border-blue-500 text-slate-700 placeholder-slate-400 transition-colors">

                </div>

            </div>

            <!-- KONFIRMASI -->
            <div class="flex flex-col gap-1">

                <label class="text-[11px] font-semibold text-blue-950">

                    Konfirmasi Password

                </label>

                <div class="relative flex items-center">

                    <i class="fa-solid fa-lock absolute left-3.5 text-slate-400 text-xs"></i>

                    <input
                    type="password"
                    name="konfirmasi_password"
                    required
                    placeholder="Konfirmasi password"
                    class="w-full pl-10 pr-4 py-2.5 text-xs bg-white border border-slate-200 rounded-xl outline-none focus:border-blue-500 text-slate-700 placeholder-slate-400 transition-colors">

                </div>

            </div>

            <!-- BUTTON -->
            <div class="pt-2">

                <button
                type="submit"
                class="w-full bg-[#22c55e] hover:bg-[#16a34a] text-white text-xs font-medium py-3 rounded-xl shadow-md shadow-green-600/10 transition-all duration-200 active:scale-[0.99]">

                    Daftar Sekarang

                </button>

            </div>

        </form>

        <!-- LOGIN -->
        <div class="text-center mt-4">

            <p class="text-xs text-slate-500">

                Sudah punya akun?

                <a href="login.php"
                class="text-blue-600 font-semibold hover:underline">

                    Login di sini

                </a>

            </p>

        </div>

    </div>

</div>

</body>
</html>