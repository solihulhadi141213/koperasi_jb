<?php

    //Jumlah Shu
    $SumShu = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(shu) AS jumlah_shu FROM shu_rincian"));
    $JumlahShu = $SumShu['jumlah_shu'];
    $JumlahShu = "" . number_format($JumlahShu,0,',','.');

    //Jumlah Transaksi
    $SumTransaksi = mysqli_fetch_array(mysqli_query($Conn, "SELECT SUM(jumlah) AS jumlah FROM transaksi"));
    $JumlahTransaksi = $SumTransaksi['jumlah'];
    $JumlahTransaksi = "Rp " . number_format($JumlahTransaksi,0,',','.');

    //Untuk Menampilkan grafik maka dibuat dulu file json
    include "_Page/Dashboard/ProsesHitungSimpanPinjam.php";
?>
<div class="pagetitle">
    <h1>
        <a href="">
            <i class="bi bi-grid"></i> Dashboard
        </a>
    </h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </nav>
</div>
<section class="section dashboard">
    <div class="row">
        <div class="col-md-12" id="notifikasi_proses">
            <!-- Kejadian Kegagalan Menampilkan Data Akan Ditampilkan Disini -->
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-12">
                        <div class="card" id="card_jam_menarik">
                            <div class="card-body">
                                <div id="tanggal_menarik">Hari, 01 Januari 1900</div>
                                <div id="jam_menarik">00:00:00</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-xxl-3 col-md-6 col-6">
                            <div class="card info-card sales-card">
                                <div class="card-body">
                                    <h5 class="card-title">Barang</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-box"></i>
                                        </div>
                                        <div class="ps-3">
                                            <span class="text-muted small pt-1 fw-bold" id="put_count_rp_barang"></span><br>
                                            <span class="text-muted small pt-2 ps-1" id="put_count_item_barang"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-md-6 col-6">
                            <div class="card info-card purple-card">
                                <div class="card-body">
                                    <h5 class="card-title">Penjualan</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-cart-dash"></i>
                                        </div>
                                        <div class="ps-3">
                                            <span class="text-muted small pt-1 ps-1" 
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="top" 
                                                data-bs-custom-class="custom-tooltip" 
                                                data-bs-title="Jumlah Total Nominal Penjualan" 
                                                id="put_nominal_penjualan">
                                                <i class="bi bi-coin"></i> 0.000.000
                                            </span><br>
                                            <span class="text-muted small pt-2 ps-1" 
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="top" 
                                                data-bs-custom-class="custom-tooltip" 
                                                data-bs-title="Jumlah Total Record Penjualan" 
                                                id="put_record_penjualan">
                                                <i class="bi bi-table"></i> (0.000.000)
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-md-6 col-6">
                            <div class="card info-card customers-card">
                                <div class="card-body">
                                    <h5 class="card-title">Pembelian</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-cart-plus"></i>
                                        </div>
                                        <div class="ps-3">
                                            <span class="text-muted small pt-2 ps-1"
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="top" 
                                                data-bs-custom-class="custom-tooltip" 
                                                data-bs-title="Jumlah Total Nominal Pembelian" 
                                                id="put_nominal_pembelian">
                                                <i class="bi bi-coin"></i> 0.00.000
                                            </span><br>
                                            <span class="text-muted small pt-2 ps-1"
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="top" 
                                                data-bs-custom-class="custom-tooltip" 
                                                data-bs-title="Jumlah Total Record Pembelian" 
                                                id="put_record_pembelian">
                                                <i class="bi bi-table"></i> (0.00.000)
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-md-6 col-6">
                            <div class="card info-card transsaction-card">
                                <div class="card-body">
                                    <h5 class="card-title">Transaksi Operasional</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-arrow-left-right"></i>
                                        </div>
                                        <div class="ps-3">
                                            <span class="text-muted small pt-2 ps-1"
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="top" 
                                                data-bs-custom-class="custom-tooltip" 
                                                data-bs-title="Jumlah Total Nominal Transaksi Operasional" 
                                                id="put_nominal_transaksi">
                                                <i class="bi bi-coin"></i> 0.00.000
                                            </span><br>
                                            <span class="text-muted small pt-2 ps-1"
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="top" 
                                                data-bs-custom-class="custom-tooltip" 
                                                data-bs-title="Jumlah Total Record Transaksi Operasional" 
                                                id="put_record_transaksi">
                                                <i class="bi bi-table"></i> (0.00.000)
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <!-- Reports -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <b class="card-title">
                                Simpanan & Pinjaman Anggota Thn <?php echo date ('Y'); ?>
                            </b>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title" id="NamaTitleData"></h5>
                            <div id="chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <b class="card-title"># Pemberitahuan Sistem</b> 
                        </div>
                        <div class="card-body" id="ShowPemberitahuanSistem">
                            <!-- Menampilkan Pemberitahuan Sistem -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
