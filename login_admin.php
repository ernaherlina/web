<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Klinik BTH</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="h-screen flex items-center justify-center bg-cover bg-center"
      style="background-image: url('assets/img/gedung.jpg');">

  <!-- overlay -->
  <div class="absolute inset-0 bg-black/40"></div>

  <!-- container -->
  <div class="relative flex w-full max-w-4xl rounded-2xl overflow-hidden shadow-2xl">

    <!-- kiri -->
    <div class="w-1/2 flex items-center justify-center backdrop-blur-md bg-white/20">
      <div class="bg-black/50 p-6 rounded-xl text-center">
        <h1 class="text-white text-xl font-bold">KLINIK BTH</h1>
        <p class="text-white text-sm">Sehat Bersama, Hidup Lebih Baik</p>

        <div class="mt-3 bg-orange-500 text-white px-4 py-1 rounded-full inline-block">
          TASIKMALAYA
        </div>
      </div>
    </div>

    <!-- kanan -->
    <div class="w-1/2 bg-white p-8">

      <h2 class="text-xl font-bold text-center mb-4">Log In</h2>

      <!-- FORM -->
      <form action="proses_login.php" method="POST">

        <input type="text" name="nim_nip" required
          placeholder="NIM / NIP"
          class="w-full border p-2 rounded mb-3 focus:ring-2 focus:ring-blue-400 outline-none">

        <input type="password" name="password" required
          placeholder="Password"
          class="w-full border p-2 rounded mb-3 focus:ring-2 focus:ring-blue-400 outline-none">

        <button type="submit"
          class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
          Log In
        </button>

      </form>

      <!-- 🔥 SECTION ESTETIK -->
      <div class="mt-6 text-center">

        <div class="bg-gray-50 rounded-lg p-4 space-y-2 shadow-sm">

          <!-- daftar -->
          <p class="text-sm text-gray-600">
            Belum punya akun?
            <a href="pendaftaran.php" class="text-blue-600 font-semibold hover:underline">
              Daftar
            </a>
          </p>

          <!-- admin -->
          <p>
            <a href="login_admin.php"
               class="text-orange-500 font-medium hover:underline">
               Login sebagai Admin
            </a>
          </p>

          <!-- lupa sandi -->
          <p class="text-xs text-gray-400">
            Lupa sandi?
            <a href="https://wa.me/6281234567890"
               target="_blank"
               class="text-blue-500 hover:underline">
               Hubungi IT
            </a>
          </p>

        </div>

      </div>

      <!-- footer -->
      <p class="text-center text-gray-400 text-sm mt-5">
        Klinik BTH. Solusi Kesehatan Kita Semua.
      </p>

    </div>

  </div>

</body>
</html>