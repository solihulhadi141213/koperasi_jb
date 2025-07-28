// Fungsi Untuk Menampilkan Data Barang
function CountOfBarang() {
    $.ajax({
        type: 'POST',
        url: '_Page/Dashboard/CountOfBarang.php',
        dataType: "json",
        success: function(response) {
            if (response.status == "Success") {
                $('#put_count_rp_barang').hide().html(response.rp_barang).fadeIn(500);
                $('#put_count_item_barang').hide().html(response.item_barang).fadeIn(500);
                CountOfPenjualan();
            } else {
                $('#notifikasi_proses').hide().html('<div class="alert alert-danger"><small>' + response.message + '</small></div>').fadeIn(500);
            }
        },
        error: function() {
            $('#notifikasi_proses').hide().html('<div class="alert alert-danger"><small>Terjadi Kesalahan Pada Sistem Saat Menghitung Barang!</small></div>').fadeIn(500);
        },
    });
}


// Fungsi Untuk Menampilkan Data Penjualan
function CountOfPenjualan() {
    $.ajax({
        type: 'POST',
        url: '_Page/Dashboard/CountOfPenjualan.php',
        dataType: "json",
        success: function(response) {
            if (response.status == "Success") {
                $('#put_nominal_penjualan').hide().html('<i class="bi bi-coin"></i> '+response.put_nominal_penjualan+'').fadeIn(500);
                $('#put_record_penjualan').hide().html('<i class="bi bi-table"></i> ('+response.put_record_penjualan+')').fadeIn(500);
                CountOfPembelian();
            } else {
                $('#notifikasi_proses').hide().html('<div class="alert alert-danger"><small>' + response.message + '</small></div>').fadeIn(500);
            }
        },
        error: function() {
            $('#notifikasi_proses').hide().html('<div class="alert alert-danger"><small>Terjadi Kesalahan Pada Sistem Saat Menghitung Penjualan!</small></div>').fadeIn(500);
        },
    });
}
// Fungsi Untuk Menampilkan Data Pembelan
function CountOfPembelian() {
    $.ajax({
        type: 'POST',
        url: '_Page/Dashboard/CountOfPembelian.php',
        dataType: "json",
        success: function(response) {
            if (response.status == "Success") {
                $('#put_nominal_pembelian').hide().html('<i class="bi bi-coin"></i> '+response.put_nominal_pembelian+'').fadeIn(500);
                $('#put_record_pembelian').hide().html('<i class="bi bi-table"></i> ('+response.put_record_pembelian+')').fadeIn(500);
                CountOfTransaksiOperasional();
            } else {
                $('#notifikasi_proses').hide().html('<div class="alert alert-danger"><small>' + response.message + '</small></div>').fadeIn(500);
            }
        },
        error: function() {
            $('#notifikasi_proses').hide().html('<div class="alert alert-danger"><small>Terjadi Kesalahan Pada Sistem Saat Menghitung Penjualan!</small></div>').fadeIn(500);
        },
    });
}

// Fungsi Untuk Menampilkan Data Transaksi Operasional
function CountOfTransaksiOperasional() {
    $.ajax({
        type: 'POST',
        url: '_Page/Dashboard/CountOfTransaksiOperasional.php',
        dataType: "json",
        success: function(response) {
            if (response.status == "Success") {
                $('#put_nominal_transaksi').hide().html('<i class="bi bi-coin"></i> '+response.put_nominal_transaksi+'').fadeIn(500);
                $('#put_record_transaksi').hide().html('<i class="bi bi-table"></i> ('+response.put_record_transaksi+')').fadeIn(500);
                ShowPemberitahuanSistem();
            } else {
                $('#notifikasi_proses').hide().html('<div class="alert alert-danger"><small>' + response.message + '</small></div>').fadeIn(500);
            }
        },
        error: function() {
            $('#notifikasi_proses').hide().html('<div class="alert alert-danger"><small>Terjadi Kesalahan Pada Sistem Saat Menghitung Penjualan!</small></div>').fadeIn(500);
        },
    });
}
// Fungsi Untuk Menampilkan Pemberitahuan Sistem
function ShowPemberitahuanSistem() {
    $.ajax({
        type: 'POST',
        url: '_Page/Dashboard/ShowPemberitahuanSistem.php',
        success: function(response) {
            $('#ShowPemberitahuanSistem').hide().html(response).fadeIn(500);
            ShowGrafikSiimpanPinjam();
        }
    });
}

// Fungsi Untuk Menampilkan Grafik
function ShowGrafikSiimpanPinjam() {
    // Fungsi untuk mengambil data dari file JSON
    $.getJSON("_Page/Dashboard/GrafikTransaksi.json", function (data) {
        // Mengolah data untuk ApexCharts
        const categories = data.map(item => item.x);
        const simpananSeries = data.map(item => parseFloat(item.ySimpanan));
        const pinjamanSeries = data.map(item => parseFloat(item.yPinjaman));

        // Konfigurasi grafik
        var options = {
            chart: {
                type: 'bar',
                height: 400
            },
            series: [
                {
                    name: 'Simpanan',
                    data: simpananSeries
                },
                {
                    name: 'Pinjaman',
                    data: pinjamanSeries
                }
            ],
            xaxis: {
                categories: categories
            },
            yaxis: {
                labels: {
                    formatter: function (value) {
                        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value);
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function (value) {
                        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value);
                    }
                }
            },
            dataLabels: {
                enabled: false // Menonaktifkan label nilai pada bar
            }
        };

        // Inisialisasi grafik
        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    });
}

// Fungsi untuk menampilkan jam digital
function tampilkanJam() {
    const waktu = new Date();
    let jam = waktu.getHours().toString().padStart(2, '0');
    let menit = waktu.getMinutes().toString().padStart(2, '0');
    let detik = waktu.getSeconds().toString().padStart(2, '0');

    $('#jam_menarik').text(`${jam}:${menit}:${detik}`);
}

// Fungsi untuk menampilkan tanggal
function tampilkanTanggal() {
    const waktu = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const tanggal = waktu.toLocaleDateString('id-ID', options);
    
    $('#tanggal_menarik').text(tanggal);
}

$(document).ready(function () {
    //Menampilkan Data Pertama Kali
    CountOfBarang();
    //Jam Menarik
    tampilkanTanggal(); // Tampilkan tanggal saat halaman dimuat
    tampilkanJam();     // Tampilkan jam pertama kali
    setInterval(tampilkanJam, 1000); // Perbarui jam setiap detik
});