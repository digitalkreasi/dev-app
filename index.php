<?php
session_start();
require("config.php");
print_r ($_SESSION);
if (isset($_COOKIE['cookie_token'])) {
  $data = $conn->query("SELECT * FROM users WHERE cookie_token='" . $_COOKIE['cookie_token'] . "'");
  if (mysqli_num_rows($data) > 0) {
    $hasil = mysqli_fetch_assoc($data);
    $_SESSION['user'] = $hasil;

    // Check if PIN verification is complete
    $pin_verified = $hasil['pin_verified'];

    // Redirect to dashboard page if PIN verification is complete
    if ($pin_verified) {
      header("Location: " . $config['web']['url'] . "dashboard");
      exit;
    }
  }
}

if (isset($_SESSION['user'])) {
  $sess_username = $_SESSION['user']['username'];
  $check_user = $conn->query("SELECT * FROM users WHERE username = '$sess_username'");
  $data_user = $check_user->fetch_assoc();
  $check_username = $check_user->num_rows;
  if ($check_username == 0) {
    header("Location: " . $config['web']['url'] . "logout.php");
  }
} else {
  $_SESSION['user'] = $data_user;
  header("Location: " . $config['web']['url'] . "auth/login");
}

include("lib/header_1.php");
if (isset($_SESSION['user'])) {

?>

    <!-- Start Card Box Order -->
    <div class="kt-container">

        <div class="row">
            <div class="product-catagory-wrap col-lg-6">
                <div class="kt-widget kt-widget--user-profile-1">
                        <div class="kt-widget__head">
                            <div class="kt-widget__media" style="margin-right: 10px; margin left: 5px; margin-top:10px;">
                                <img src="<?php echo $config['web']['url'] ?>assets/media/icon/fav-icon.png" alt="image">
                            </div>
                            <a style="margin-top: 20px; color: #fff;">
                                Selamat Datang<strong style="font-size: 15px"><br><?php echo $data_user['nama_depan'] .' '.$data_user['nama_belakang']; ?></strong>
                                <i class="flaticon2-correct kt-font-warning"></i>
                            </a>
                        </div>
                    </div>
                <!--
                <div role="alert" class="alert alert-info alert-dismissible fade show mt-2">
                <a class="text-white" href="">[INFORMASI] Maintenance Sistem: Hari, Tanggal 00:00-06:00 WIB. Klik untuk lebih detail.</a>
                </ol><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></div>-->
                
                    <div class="card shadow mt-2">
                        <div class="kt-portlet__head" style="padding: 5px 25px; border-bottom: 1px solid #ebedf2;">
                            <div class="kt-portlet__head-label">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div style="display: flex:1; align-items: center;">
                                        <strong style="font-size: 13px">Fazz Saldo</strong> <br>
                                        <strong style="font-size: 15px">Rp. <?php echo number_format($data_user['saldo_top_up'], 0, ',', '.'); ?></strong>
                                    </div>
                                    <div style="border-left: 1px solid #ebedf2; height: 45px; margin-right:50px;"></div>
                                    <div style="text-align: right;">
                                        <strong style="font-size: 13px; text-align: left;">Fazz Poin:</strong><br>
                                        <strong style="font-size: 15px; text-align: left;"><?php echo $data_user['koin']; ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                             <div class="row mt-2">
                        <div class="col-3">
                            <div class="mb-3 catagory-card">
                                <a href="<?php echo $config['web']['url'] ?>deposit-balance/"><img src="assets/media/icon/deposit.png" alt="" width="33" height="33" style="margin-bottom: 13px; margin-top:5px;">
                                    <span>Deposit</span></a>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="mb-3 catagory-card">
                                <a href="<?php echo $config['web']['url'] ?>order/paket-data-internet"><img src="assets/media/icon/poin.png" alt="" width="33" height="33" style="margin-bottom: 13px; margin-top:5px;">
                                    <span>Tukar<br>Poin</span></a>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="mb-3 catagory-card">
                                <a href="#" data-toggle="modal" data-target="#alltransfer" onclick="showBelowModal()">
                                    <img src="assets/media/icon/transfer.png" alt="" width="33" height="33" style="margin-bottom: 13px; margin-top: 5px;">
                                    <span>Transfer</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="mb-3 catagory-card">
                                <a href="<?php echo $config['web']['url'] ?>order/saldo-emoney"><img src="assets/media/icon/cs.png" alt="" width="33" height="33" style="margin-bottom: 13px; margin-top:5px;">
                                    <br>
                                    <span>CS</span></a>
                            </div>
                        </div>
                    </div>
                    </div>
                    <br>
                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner" style="border-radius: 7px;">
                            
                            <div class="carousel-item active">
                                <a href="<?php echo $config['web']['url'] ?>deposit-balance"><img class="d-block w-100" src="assets/media/slide/banner-2.png" alt="Slide Pertama"></a>
                            </div>
                            <div class="carousel-item">
                                <a href="<?php echo $config['web']['url'] ?>price-list/social-media"><img class="d-block w-100" src="assets/media/slide/banner-3.png" alt="Slide Pertama"></a>
                            </div>
                        </div>
                        <!--<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Sebelumnya</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Selanjutnya</span>
                        </a>-->
                <br>
                <div class="card shadow mt-2">
                    <div class="row mt-2">
                        <div class="col-3">
                            <div class="mb-3 catagory-card">
                                <a href="<?php echo $config['web']['url'] ?>order/pulsa-reguler"><img src="assets/media/icon-pay/pulsa.png" alt="">
                                    <span>Pulsa</span></a>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="mb-3 catagory-card">
                                <a href="<?php echo $config['web']['url'] ?>order/paket-data-internet"><img src="assets/media/icon-pay/internet.png" alt="">
                                    <span>Paket Data</span></a>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class=" mb-3 catagory-card">
                                <a href="<?php echo $config['web']['url'] ?>order/paket-sms-telepon"><img src="assets/media/icon-pay/phone-sms.png" alt="">
                                    <span>Telpon & SMS</span></a>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="mb-3 catagory-card">
                                <a href="<?php echo $config['web']['url'] ?>order/saldo-emoney"><img src="assets/media/icon-pay/e-money.png" alt="">
                                    <span>E-Money</span></a>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="mb-3 catagory-card">
                                <a href="<?php echo $config['web']['url'] ?>order/voucher-game"><img src="assets/media/icon-pay/voucher-game.png" alt="">
                                    <span>Topup Game</span></a>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="mb-3 catagory-card">
                                <a href="<?php echo $config['web']['url'] ?>order/token-pln"><img src="assets/media/icon-pay/token-listrik1.png" alt="">
                                    <span>Token Listrik</span></a>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="mb-3 catagory-card">
                                <a href="" data-toggle="modal" data-target='#allkategori'><img src="assets/media/icon-pay/kategori.png" alt="">
                                    <span>Semua Kategori</span></a>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                <!-- Modal -->
<div class="modal fade" id="alltransfer" tabindex="-1" role="dialog" aria-labelledby="alltransfer">
    <div class="modal-dialog" role="document" style="position: absolute; bottom: 0;">
        <div class="modal-content">
            <div class="kt-container mt-2">
                <p style="font-weight: 600; font-size: 15px; text-align: center; margin-top:5px; color:#000;">Metode Transfer</p>
                <div class="row">
                    <!-- Baris Pertama -->
                    <div class="col-lg-6" style="border: 3px solid #ebedf2; border-radius: 5px; margin-bottom: 10px;">
                        <div class="row mt-2">
                            <div class="col-6" style="display: flex; align-items: center; padding: 10px; box-sizing:;">
                                <a href="<?php echo $config['web']['url'] ?>order/pulsa-reguler"></a>
                                <div class="mb-3 catagory-card" style="display: flex; align-items: center; flex-wrap: nowrap; text-align: left;">
                                    <img src="assets/media/icon/user-fazz.png" alt="" style="width: 45px; height: 50px; margin-right: 10px;">
                                    <a href="#" style="text-decoration: none; color: #000; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        <strong style="font-size:13px;">Akun indofazz</strong><br>
                                        <div style="border-bottom: 3px solid #ebedf2; margin-top:5px; margin-bottom:5px; width:230px;"></div>
                                        Kirim Saldo ke Sesama Pengguna<br> indofazz
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div><br />
                    <!-- Baris Kedua -->
                    <div class="col-lg-6" style="border: 3px solid #ebedf2; border-radius: 5px; margin-bottom: 10px;">
                        <div class="row mt-2">
                            <div class="col-6" style="display: flex; align-items: center; padding: 10px; box-sizing:;">
                                <a href="<?php echo $config['web']['url'] ?>order/pulsa-reguler"></a>
                                <div class="mb-3 catagory-card" style="display: flex; align-items: center; flex-wrap: nowrap; text-align: left;">
                                    <img src="assets/media/icon/bank.png" alt="" style="width: 45px; height: 50px; margin-right: 10px;">
                                    <a href="#" style="text-decoration: none; color: #000; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        <strong style="font-size:13px;">Rekening Bank</strong><br>
                                        <div style="border-bottom: 3px solid #ebedf2; margin-top:5px; margin-bottom:5px; width:230px;"></div>
                                        Kirim Saldo ke Rekening Bank
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Fungsi untuk menampilkan modal di bawah saat tombol diklik
    function showBelowModal() {
    $('#alltransfer').modal('show');
    $('.modal').css('top', 'auto');
    $('.modal').css('bottom', '0');
}
</script>


                <!-- End Modal Transfer -->
                <!-- Start Modal Kategori -->
                <div class="modal fade" id="allkategori" tabindex="-1" role="dialog" aria-labelledby="allkategoriLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="kt-container mt-2">
                                <div class="row">
                                    <div class="product-catagory-wrap col-lg-12">
                                        <div class="row mt-2">
                                            <div class="col-3">
                                                <div class="mb-3 catagory-card">
                                                    <a href="<?php echo $config['web']['url'] ?>order/pulsa-reguler"><img src="assets/media/icon-pay/pulsa.png" alt="">
                                                        <span>Pulsa</span></a>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="mb-3 catagory-card">
                                                    <a href="<?php echo $config['web']['url'] ?>order/paket-data-internet"><img src="assets/media/icon-pay/internet.png" alt="">
                                                        <span>Paket Data</span></a>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class=" mb-3 catagory-card">
                                                    <a href="<?php echo $config['web']['url'] ?>order/paket-sms-telepon"><img src="assets/media/icon-pay/phone-sms.png" alt="">
                                                        <span>Telpon & SMS</span></a>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="mb-3 catagory-card">
                                                    <a href="<?php echo $config['web']['url'] ?>order/saldo-emoney"><img src="assets/media/icon-pay/e-money.png" alt="">
                                                        <span>E-Money</span></a>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="mb-3 catagory-card">
                                                    <a href="<?php echo $config['web']['url'] ?>order/voucher-game"><img src="assets/media/icon-pay/voucher-game.png" alt="">
                                                        <span>Topup Game</span></a>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="mb-3 catagory-card">
                                                    <a href="<?php echo $config['web']['url'] ?>order/token-pln"><img src="assets/media/icon-pay/token-listrik1.png" alt="">
                                                        <span>Token Listrik</span></a>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="mb-3 catagory-card">
                                                    <a href="<?php echo $config['web']['url'] ?>order/pulsa-internasional"><img src="assets/media/icon-pay/pulsa-internasional.png" alt="">
                                                        <span>Pulsa Internasional</span></a>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="mb-3 catagory-card">
                                                    <a href="<?php echo $config['web']['url'] ?>order/wifi-id"><img src="assets/media/icon-pay/wifi-id.png" alt="">
                                                        <span>Wifi ID</span></a>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="mb-3 catagory-card">
                                                    <a href="<?php echo $config['web']['url'] ?>order/voucher"><img src="assets/media/icon-pay/voucher.png" alt="">
                                                        <span>Voucher</span></a>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="mb-3 catagory-card">
                                                    <a href="<?php echo $config['web']['url'] ?>order/streaming"><img src="assets/media/icon-pay/streaming.png" alt="">
                                                        <span>Streaming</span></a>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="mb-3 catagory-card">
                                                    <a href="<?php echo $config['web']['url'] ?>order/pln-pascabayar"><img src="assets/media/icon-pay/listrik-pasca.png" alt="">
                                                        <span>Listrik</span></a>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="mb-3 catagory-card">
                                                    <a href="<?php echo $config['web']['url'] ?>order/hp-pascabayar"><img src="assets/media/icon-pay/pascabayar.png" alt="">
                                                        <span>HP Pascabayar</span></a>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="mb-3 catagory-card">
                                                    <a href="<?php echo $config['web']['url'] ?>order/bpjs-kesehatan"><img src="assets/media/icon-pay/bpjs.png" alt="">
                                                        <span>BPJS Kesehatan</span></a>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="mb-3 catagory-card">
                                                    <a href="<?php echo $config['web']['url'] ?>order/internet-pascabayar"><img src="assets/media/icon-pay/inet-pasca.png" alt="">
                                                        <span>Internet Pascabayar</span></a>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="mb-3 catagory-card">
                                                    <a href="<?php echo $config['web']['url'] ?>order/pbb"><img src="assets/media/icon-pay/pbb.png" alt="">
                                                        <span>PBB</span></a>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="mb-3 catagory-card">
                                                    <a href="<?php echo $config['web']['url'] ?>order/pdam"><img src="assets/media/icon-pay/pdam.png" alt="">
                                                        <span>PDAM</span></a>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="mb-3 catagory-card">
                                                    <a href="<?php echo $config['web']['url'] ?>order/gas-negara"><img src="assets/media/icon-pay/gas.png" alt="">
                                                        <span>Gas Negara</span></a>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="mb-3 catagory-card">
                                                    <a href="<?php echo $config['web']['url'] ?>order/multifinance"><img src="assets/media/icon-pay/multifinance.png" alt="">
                                                        <span>Multifinance</span></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Modal Modal Kategori -->

                <br>
                <strong style="font-size:17px">Bayar Tagihan</strong>
                <div class="card shadow">
                    <div class="row mt-2">
                        <div class="col-3">
                            <div class="mb-3 catagory-card">
                                <a href="<?php echo $config['web']['url'] ?>order/pln-pascabayar"><img src="assets/media/icon-pay/listrik-pasca.png" alt="">
                                    <span>Listrik</span></a>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="mb-3 catagory-card">
                                <a href="<?php echo $config['web']['url'] ?>order/hp-pascabayar"><img src="assets/media/icon-pay/pascabayar.png" alt="">
                                    <span>HP Pascabayar</span></a>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="mb-3 catagory-card">
                                <a href="<?php echo $config['web']['url'] ?>order/bpjs-kesehatan"><img src="assets/media/icon-pay/bpjs.png" alt="">
                                    <span>BPJS Kesehatan</span></a>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="mb-3 catagory-card">
                                <a href="<?php echo $config['web']['url'] ?>order/internet-pascabayar"><img src="assets/media/icon-pay/inet-pasca.png" alt="">
                                    <span>Internet Pascabayar</span></a>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="mb-3 catagory-card">
                                <a href="<?php echo $config['web']['url'] ?>order/pbb"><img src="assets/media/icon-pay/pbb.png" alt="">
                                    <span>PBB</span></a>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="mb-3 catagory-card">
                                <a href="<?php echo $config['web']['url'] ?>order/pdam"><img src="assets/media/icon-pay/pdam.png" alt="">
                                    <span>PDAM</span></a>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="mb-3 catagory-card">
                                <a href="<?php echo $config['web']['url'] ?>order/gas-negara"><img src="assets/media/icon-pay/gas.png" alt="">
                                    <span>Gas Negara</span></a>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="mb-3 catagory-card">
                                <a href="<?php echo $config['web']['url'] ?>order/multifinance"><img src="assets/media/icon-pay/multifinance.png" alt="">
                                    <span>Multifinance</span></a>
                            </div>
                        </div>
                    </div>
                </div><br>
                <!--<div class="card shadow">
                <div class="kt-portlet__head" style="padding: 5px 25px;border-bottom: 1px solid #ebedf2;">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Kategori Lain
                        </h3>
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-3">
                        <div class="mb-3 catagory-card">
                            <a target="blank" href="https://sellyjha.my.id/"><img src="assets/media/kategori/hapus-akun.png" alt="">
                            <span>Jasa Hapus akun</span></a>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="mb-3 catagory-card">
                            <a target="blank" href="http://biolink.solusimedia.com/"><img src="assets/media/kategori/biolink.png" alt="">
                            <span>BioLink</span></a>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="mb-3 catagory-card">
                            <a target="blank" href="https://flazzpedia.com"><img src="assets/media/kategori/shop.png" alt="">
                            <span>Shop</span></a>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="mb-3 catagory-card">
                            <a target="blank" href="https://yourmail.my.id"><img src="assets/media/kategori/email.png" alt="">
                            <span>FakeMail</span></a>
                        </div>
                    </div>
                </div>
                </div><br>-->
            </div>

            <!-- Start News -->
            <div class="col-lg-6">
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                <i class="flaticon2-bell text-primary"></i>
                                Berita & Informasi
                            </h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="kt-notes">
                            <div class="kt-notes__items">
                                <?php
                                $cek_berita = $conn->query("SELECT * FROM berita ORDER BY id DESC LIMIT 5");
                                while ($data_berita = $cek_berita->fetch_assoc()) {
                                    $beritastr = "-" . strlen($data_berita['konten']);
                                    $beritasensor = substr($data_berita['konten'], $slider_beritastr, +100);
                                    if ($data_berita['tipe'] == "INFO") {
                                        $label = "info";
                                    } else if ($data_berita['tipe'] == "PERINGATAN") {
                                        $label = "warning";
                                    } else if ($data_berita['tipe'] == "PENTING") {
                                        $label = "danger";
                                    }

                                    if ($data_berita['icon'] == "PESANAN") {
                                        $label_icon = "flaticon2-shopping-cart";
                                    } else if ($data_berita['icon'] == "LAYANAN") {
                                        $label_icon = "flaticon-signs-1";
                                    } else if ($data_berita['icon'] == "DEPOSIT") {
                                        $label_icon = "flaticon-coins";
                                    } else if ($data_berita['icon'] == "PENGGUNA") {
                                        $label_icon = "flaticon2-user";
                                    } else if ($data_berita['icon'] == "PROMO") {
                                        $label_icon = "flaticon2-percentage";
                                    }
                                ?>
                                    <div class="kt-notes__item">
                                        <div class="kt-notes__media">
                                            <span class="kt-notes__icon">
                                                <i class="<?php echo $label_icon; ?> text-primary"></i>
                                            </span>
                                        </div>
                                        <div class="kt-notes__content">
                                            <div class="kt-notes__section">
                                                <div class="kt-notes__info">
                                                    <a href="<?php echo $config['web']['url'] ?>page/news-details?id=<?php echo $data_berita['id']; ?>" class="kt-notes__title">
                                                        <?php echo $data_berita['title']; ?>
                                                    </a>
                                                    <span class="kt-notes__desc">
                                                        (<?php echo tanggal_indo($data_berita['date']); ?>)
                                                    </span>
                                                    <span class="kt-badge kt-badge--<?php echo $label; ?> kt-badge--inline"><?php echo $data_berita['tipe']; ?></span>
                                                </div>
                                                <div class="kt-subheader__wrapper" data-toggle="kt-tooltip" title="" data-original-title="Mau Lihat?">
                                                    <a href="<?php echo $config['web']['url'] ?>page/news-details?id=<?php echo $data_berita['id']; ?>" class="btn btn-sm btn-icon-md btn-icon">
                                                        <i class="flaticon-eye"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <span class="kt-notes__body">
                                                <?php echo nl2br($beritasensor . "....."); ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                        <a href="<?php echo $config['web']['url'] ?>page/news" class="btn btn-sm btn-primary text-center"><i class="flaticon-visible"></i> Lihat Semua...</a><br /><br />
                    </div>
                </div>
            </div>
            <!-- End News -->

            <!-- Start Modal Content -->
            <?php
            if ($data_user['read_news'] == 0) {
            ?>
                <div class="modal fade show" id="news" tabindex="-1" role="dialog" aria-labelledby="NewsInfo" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title mt-0" id="NewsInfo"><b><i class="flaticon2-bell text-primary"></i> Berita & Informasi</b></h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" style="max-height: 400px; overflow: auto;">
                                <?php
                                $cek_berita = $conn->query("SELECT * FROM berita ORDER BY id DESC LIMIT 5");
                                while ($data_berita = $cek_berita->fetch_assoc()) {
                                    if ($data_berita['tipe'] == "INFO") {
                                        $label = "info";
                                    } else if ($data_berita['tipe'] == "PERINGATAN") {
                                        $label = "warning";
                                    } else if ($data_berita['tipe'] == "PENTING") {
                                        $label = "danger";
                                    }
                                ?>
                                    <div class="alert alert-warning">
                                        <div class="alert-text">
                                            <p><span class="float-right text-muted"><?php echo tanggal_indo($data_berita['date']); ?>, <?php echo $data_berita['time']; ?></span></p>
                                            <h5 class="inbox-item-author mt-0 mb-1"><?php echo $data_berita['title']; ?></h5>
                                            <h5><span class="badge badge-<?php echo $label; ?>"><?php echo $data_berita['tipe']; ?></span></h5>
                                            <?php echo nl2br($data_berita['konten']); ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="read_news()"><i class="flaticon2-check-mark"></i> Saya Sudah Membaca</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <!-- End Modal Content-->
        </div>
        <!-- Start Scrolltop -->
        <div id="kt_scrolltop" class="kt-scrolltop">
            <i class="fa fa-arrow-up"></i>
        </div>
        <!-- End Scrolltop -->
    <?php
}
require 'lib/footer.php';
    ?>