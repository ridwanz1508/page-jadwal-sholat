<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
    <title>Jadwal Sholat</title>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <div id="tanggal-masehi"></div>
                        <div id="tanggal-hijriah"></div>
                    </li>
                    <li class="nav-items">
                        <div class="masjid-info">
                            <h1>MASJID NURUL ARKANIYAH</h1>
                            <h7>Kp. blabla, Desa Blabla blabla blabla, Kec. Blala, Kab. Blala, Blalala, 15555</h7>
                            <h7>Jalan Raya Blalalala No. 4</h7>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="waktu"></div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="content">
        <div class="content-atas">
            <div class="content-sholat">
                <div class="sholat">Shubuh</div>
                <div class="sholat">Zuhur</div>
                <div class="sholat">Ashar</div>
                <div class="sholat">Maghrib</div>
                <div class="sholat">Isya</div>
            </div>
            <div class="content-jadwal">
                <div class="jadwal" id="shubuh"></div>
                <div class="jadwal" id="zuhur"></div>
                <div class="jadwal" id="ashar"></div>
                <div class="jadwal" id="maghrib"></div>
                <div class="jadwal" id="isya"></div>
            </div>
        </div>
        <div class="content-bawah">
            <div class="content-bawah-blink-text">
                <p>"Dan dirikanlah sholat, dan keluarkanlah zakat, dan <br>tunduklah rukuk bersama orang-orang yang rukuk." <br> (QS. Al-Baqarah: 43)</p>
                <!-- <p>WAKTU SHOLAT, MOHON UNTUK TIDAK <br>BERBICARA/BERISIK UNTUK MENJAGA <br> KEKHUSYUAN SHOLAT</p> -->
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <marquee>JADWAL IMAM SHOLAT FARDHU HARI INI ADALAH USTAD, S.Si., M.Ag.</marquee>
    </div>

    <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="assets/js/moment.min.js"></script> 
	<script src="assets/js/moment-hijri.js"></script> 


    <script>
        function formatTanggal(tanggal) {
            const namaHari = ["Ahad", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
            const namaBulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni",
                                "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

            const hariIni = tanggal.getDay();
            const tanggalIni = tanggal.getDate();
            const bulanIni = tanggal.getMonth();
            const tahunIni = tanggal.getFullYear();

            const formatTanggal = `${namaHari[hariIni]}, ${tanggalIni} ${namaBulan[bulanIni]} ${tahunIni}`;

            return formatTanggal;
        }

        function formatWaktu(waktu) {
            const jam = waktu.getHours();
            const menit = waktu.getMinutes();
            const detik = waktu.getSeconds();

            const formatJam = jam < 10 ? `0${jam}` : jam;
            const formatMenit = menit < 10 ? `0${menit}` : menit;
            const formatDetik = detik < 10 ? `0${detik}` : detik;

            const formatWaktu = `${formatJam}:${formatMenit}:${formatDetik}`;

            return formatWaktu;
        }

        function updatePrayerTimes() {
            const city = 'Tangerang'; 
            const country = 'Indonesia'; 
            const method = 5; // Metode perhitungan waktu sholat (5: ISNA)

            fetch(`https://api.aladhan.com/v1/timingsByCity?city=${city}&country=${country}&method=${method}`)
                .then(response => response.json())
                .then(data => {
                    const jadwal = data.data.timings;
                    document.getElementById('shubuh').innerText = jadwal.Fajr;
                    document.getElementById('zuhur').innerText = jadwal.Dhuhr;
                    document.getElementById('ashar').innerText = jadwal.Asr;
                    document.getElementById('maghrib').innerText = jadwal.Maghrib;
                    document.getElementById('isya').innerText = jadwal.Isha;

                    // Pengecekan apakah waktu sholat sudah tiba
                    const currentWaktu = formatWaktu(new Date());
                    const shubuhWaktu = jadwal.Fajr.substr(0, 5); // Ambil hanya jam dan menit
                    const zuhurWaktu = jadwal.Dhuhr.substr(0, 5);
                    const asharWaktu = jadwal.Asr.substr(0, 5);
                    const maghribWaktu = jadwal.Maghrib.substr(0, 5);
                    const isyaWaktu = jadwal.Isha.substr(0, 5);

                    const jadwalSholat = [shubuhWaktu, zuhurWaktu, asharWaktu, maghribWaktu, isyaWaktu];

                    // Periksa apakah waktu sholat saat ini
                    for (let i = 0; i < jadwalSholat.length; i++) {
                        if (currentWaktu === jadwalSholat[i]) {
                            // Tambahkan kelas CSS 'blink' jika waktu saat ini sesuai dengan waktu sholat
                            document.getElementById('jadwal-' + (i + 1)).classList.add('blink');
                        } else {
                            // Hapus kelas CSS 'blink' jika waktu tidak sesuai
                            document.getElementById('jadwal-' + (i + 1)).classList.remove('blink');
                        }
                    }
                })
                .catch(error => console.error('Error fetching data:', error));
        } // sampai ini
        
        function updateClock() {
            const tanggalIni = new Date();
            const formatTanggalIni = formatTanggal(tanggalIni);
            document.getElementById('tanggal-masehi').innerText = formatTanggalIni;

            const tanggalHijriah = moment(tanggalIni).format('iD iMMMM iYYYY');
            document.getElementById('tanggal-hijriah').innerText = tanggalHijriah;

            const formatWaktuIni = formatWaktu(tanggalIni);
            document.querySelector('.waktu').innerText = formatWaktuIni;
        }
        updatePrayerTimes();
        updateClock();

        setInterval(updatePrayerTimes, 60000);
        setInterval(updateClock, 1000); 
    </script>

</body>
</html>
