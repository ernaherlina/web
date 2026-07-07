<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Klinik BTH</title>

  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="h-screen flex items-center justify-center bg-cover bg-center"
style="background-image: url('assets/img/gedung_direktorat.png');">

  <!-- overlay -->
  <div class="absolute inset-0 bg-black/40"></div>

  <!-- CONTAINER -->
  <div class="relative flex w-full max-w-2xl h-[430px] rounded-3xl overflow-hidden shadow-2xl z-10">

    <!-- KIRI -->
    <div class="w-1/2 relative bg-cover"
    style="
    background-image: url('assets/img/pendaftaran.png');
    background-size: cover 115%;
    background-position: center 20%;
    background-repeat: no-repeat;
    ">

      <!-- overlay -->
      <div class="absolute inset-0 bg-black/30 backdrop-blur-[1px]"></div>

      <!-- isi -->
      <div class="relative z-10 flex flex-col items-center justify-center h-full text-center px-6">
      </div>

    </div>

    <!-- KANAN -->
    <div class="w-1/2 bg-white flex items-center justify-center">

      <!-- POPUP LOGIN -->
      <div class="w-[240px]">

        <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">
          Log In
        </h2>

        <!-- FORM -->
        <form action="proses_login.php" method="POST" class="space-y-4">

          <!-- INPUT NIM -->
          <input
          type="text"
          name="nim_nip"
          placeholder="NIM / NIP"
          required
          class="w-full border border-gray-300 p-3 rounded-2xl
          focus:outline-none focus:ring-2 focus:ring-blue-500">

          <!-- INPUT PASSWORD -->
          <input
          type="password"
          name="password"
          placeholder="Password"
          required
          class="w-full border border-gray-300 p-3 rounded-2xl
          focus:outline-none focus:ring-2 focus:ring-blue-500">

          <!-- BUTTON -->
          <button
          type="submit"
          class="w-full bg-blue-600 hover:bg-blue-700
          text-white font-semibold py-3 rounded-2xl
          transition">

            Log In

          </button>

        </form>

        <!-- daftar -->
        <div class="text-center mt-5">
          <p class="text-sm text-gray-600">
            Belum punya akun?
            <a href="pendaftaran.php"
            class="text-blue-600 font-semibold hover:underline">
              Daftar di sini
            </a>
          </p>
        </div>

        <!-- lupa password -->
        <div class="text-center mt-3">
          <p class="text-sm text-gray-500">
            Lupa sandi?
            <a href="https://wa.me/6281234567890"
            target="_blank"
            class="text-blue-500 hover:underline">
              Hubungi IT
            </a>
          </p>
        </div>

        <!-- footer -->
        <p class="text-center text-gray-400 text-sm mt-4">
          Klinik BTH
        </p>

      </div>

    </div>

  </div>

</body>
</html>