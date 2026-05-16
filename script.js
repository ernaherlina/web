document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("formDaftar");

    form.addEventListener("submit", function (e) {

        const nama = document.querySelector('input[name="nama"]').value.trim();
        const nim = document.querySelector('input[name="nim"]').value.trim();
        const hp = document.querySelector('input[name="hp"]').value.trim();
        const password = document.querySelector('input[name="password"]').value.trim();

        if (nama === "" || nim === "" || hp === "" || password === "") {
            e.preventDefault();
            alert("Semua field wajib diisi!");
            return;
        }

        if (hp.length < 10) {
            e.preventDefault();
            alert("Nomor HP tidak valid!");
            return;
        }

        alert("Pendaftaran berhasil!");
    });

});