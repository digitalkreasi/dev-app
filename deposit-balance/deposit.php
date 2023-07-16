<?php
session_start();
require("../config.php");
require '../lib/session_user.php';
if (isset($_POST['pilihbank'])) {
	$bank = $_POST['namabank'];
	$_SESSION['bank'] = $bank;
	$sessbank = $_SESSION['bank'];
}
if (isset($_POST['buat'])) {
	require '../lib/session_login.php';
	$post_bank = $conn->real_escape_string(filter($_POST['tipe']));
	$post_jumlah = $conn->real_escape_string(filter($_POST['jumlah']));
	$post_pin = $conn->real_escape_string(filter($_POST['pin']));

	$cek_depo = $conn->query("SELECT * FROM deposit WHERE username = '$sess_username' AND status = 'Pending'");
	$data_depo = $cek_depo->fetch_assoc();
	$count_depo = mysqli_num_rows($cek_depo);

	$tipe_saldo = "saldo_top_up";
	$kode = acak_nomor(3) . acak_nomor(3);

	$error = array();


	if (empty($post_jumlah)) {
		$error['jumlah'] = '*Tidak Boleh Kosong.';
	}
	if (empty($post_pin)) {
		$error['pin'] = '*Tidak Boleh Kosong.';
	} else if ($post_pin <> $data_user['pin']) {
		$error['pin'] = '*PIN Yang Kamu Masukkan Salah.';
	} else {

		if ($count_depo >= 1) {
			$_SESSION['hasil'] = array('alert' => 'danger', 'pesan' => 'Ups, Kamu Masih Memiliki Isi Saldo Yang Berstatus Pending.<script>swal("Ups Gagal!", "Kamu Masih Memiliki Isi Saldo Berstatus Pending.", "error");</script>');
		} else {
		}
	}
}
require("../lib/header.php");

?>

<!-- Start Sub Header -->
<div class="kt-subheader kt-grid__item" id="kt_subheader">
	<div class="kt-container">
		<div class="kt-subheader__main">
			<h3 class="kt-subheader__title">Isi Saldo</h3>
			<div class="kt-subheader__breadcrumbs">
				<a href="<?php echo $config['web']['url'] ?>deposit-balance/" class="kt-subheader__breadcrumbs-home"><i class="flaticon-coins"></i></a>
				<span class="kt-subheader__breadcrumbs-separator"></span>
				<a href="<?php echo $config['web']['url'] ?>" class="kt-subheader__breadcrumbs-link">Halaman Utama</a>
				<span class="kt-subheader__breadcrumbs-separator"></span>
				<a href="<?php echo $config['web']['url'] ?>deposit-balance/" class="kt-subheader__breadcrumbs-link">Isi Saldo</a>
			</div>
		</div>
	</div>
</div>
<!-- End Sub Header -->

<!-- Start Content -->
<div class="kt-container kt-grid__item kt-grid__item--fluid">

	<!-- Start Page Top Up Balance -->
	<div class="row">
		<div class="col-lg-7">
			<div class="kt-portlet">
				<div class="kt-portlet__head">
					<div class="kt-portlet__head-label">
						<h3 class="kt-portlet__head-title">
							<i class="flaticon-coins text-primary"></i>
							Isi Saldo
						</h3>
					</div>
				</div>
				<div class="kt-portlet__body">
					<?php
					$cek_depo = $conn->query("SELECT * FROM deposit WHERE username = '$sess_username' AND status = 'Pending' ORDER BY id DESC");
					while ($data_depo = $cek_depo->fetch_assoc()) {
					?>
						<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
						<script>
							var url = "<?php echo $config['web']['url'] ?>deposit-balance/invoice?kode_deposit=<?php echo $data_depo['kode_deposit']; ?>"; // URL Tujuan
							var count = 1; // dalam detik
							function countDown() {
								if (count > 0) {
									count--;
									var waktu = count + 1;
									setTimeout("countDown()", 1000);
								} else {
									<?php
									if ($data_depo['provider'] == 'Payment Gateway') {
									?>
										window.location.href = "<?= $data_depo['checkout_url'] ?>";
									<?php
									} else {
									?>
										window.location.href = url;
									<?php
									}
									?>

								}
							}
							countDown();
						</script>
					<?php
					}
					?>
					<?php
					if (isset($_SESSION['hasil'])) {
					?>
						<div class="alert alert-<?php echo $_SESSION['hasil']['alert'] ?> alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<?php echo $_SESSION['hasil']['pesan'] ?><div id="respon"></div>
						</div>
					<?php
						unset($_SESSION['hasil']);
					}
					?>
					<form class="form-horizontal" role="form" method="POST">
						<div class="form-group row">
							<label class="col-xl-3 col-lg-3 col-form-label">Tipe</label>
							<div class="col-lg-9 col-xl-6">
								<?php
								$querybank = "SELECT * FROM tipe_pembayaran where nama = '$sessbank'";
								$execbank = mysqli_query($conn, $querybank);
								$databank = mysqli_fetch_assoc($execbank);
								?>
								<select class="form-control" name="tipe" id="tipe">
									<option value="<?= $sessbank ?>" selected><?= $databank['kode']; ?></option>
								</select>
								<span class="form-text text-muted"><?php echo ($error['tipe']) ? $error['tipe'] : ''; ?></span>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-xl-3 col-lg-3 col-form-label">Jumlah</label>
							<div class="col-lg-9 col-xl-6">
								<div class="input-group">
									<div class="input-group-prepend"><span class="input-group-text text-primary">Rp</span></div>
									<input type="number" class="form-control" name="jumlah" placeholder="Masukkan Jumlah Isi Saldo" id="jumlah">
								</div>
								<span class="form-text text-muted"><?php echo ($error['jumlah']) ? $error['jumlah'] : ''; ?></span>
							</div>
						</div>
						<input type="hidden" id="rate">
						<div class="form-group row">
							<label class="col-xl-3 col-lg-3 col-form-label">Saldo Yang Didapatkan</label>
							<div class="col-lg-9 col-xl-6">
								<div class="input-group">
									<div class="input-group-prepend"><span class="input-group-text text-primary">Rp</span></div>
									<input type="number" class="form-control" name="saldo" value="0" id="total" readonly>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-xl-3 col-lg-3 col-form-label">PIN</label>
							<div class="col-lg-9 col-xl-6">
								<div class="input-group">
									<div class="input-group-prepend"><span class="input-group-text"><i class="la la-lock text-primary"></i></span></div>
									<input type="number" name="pin" class="form-control" placeholder="Masukkan PIN Kamu">
								</div>
								<span class="form-text text-muted"><?php echo ($error['pin']) ? $error['pin'] : ''; ?></span>
							</div>
						</div>
						<div class="kt-portlet__foot">
							<div class="kt-form__actions">
								<div class="row">
									<div class="col-lg-3 col-xl-3">
									</div>
									<div class="col-lg-9 col-xl-9">
										<button type="submit" name="buat" class="btn btn-primary btn-elevate btn-pill btn-elevate-air">Submit</button>
										<button type="reset" class="btn btn-danger btn-elevate btn-pill btn-elevate-air">Ulangi</button>




									</div>
								</div>
							</div>
						</div>
				</div>
				</form>
			</div>
		</div>

		<div class="col-lg-5">
			<div class="kt-portlet">
				<div class="kt-portlet__head">
					<div class="kt-portlet__head-label">
						<h3 class="kt-portlet__head-title">
							<i class="flaticon-alert text-primary"></i>
							Informasi
						</h3>
					</div>
				</div>
				<!--<div class="kt-portlet__body">
						<p>Isi Saldo Pada <b><?php echo $data['short_title']; ?></b> Menggunakan Sistem Verifikasi Otomatis, Saldo Akan Bertambah Ketika Kamu Klik Tombol <b>KONFIRMASI</b> Pada Invoice.</p>
						<p>Jadwal Bank <b>OFFLINE</b> Bisa Kamu Cek Di Bawah Ini Dengan Mengeklik Salah Satu Tipe Pembayaran.</p>
						<div class="mb-3" id="accordion">
							<div class="card mb-1">
								<div class="card-header" id="headingOne">
									<h4 class="m-0">
										<a class="text-dark collapsed" data-toggle="collapse" href="#collapseOne" aria-expanded="true">
										<i class="flaticon2-information mr-1 text-primary"></i>
										BANK BRI
										</a>
									</h4>
								</div>
								<div id="collapseOne" class="card-collapse collapse in" role="tabcard" aria-labelledby="headingOne">
									<div class="kt-portlet__body">
										<ul>
											<li>Senin - Minggu : 22:00 WIB - 06:00 WIB</li>
											<li>Bank BRI Sering Gangguan Dadakan, Harap Jika Sudah Transfer Masuk Dan Bank BRI Mengalami Gangguan Dadakan Langsung Hubungi (CS) Bantuan <b><?php echo $data['short_title']; ?></b> Untuk Di Tindak Lanjuti.</li>
										</ul>
									</div>
								</div>
							</div>
							<div class="card mb-1">
							    <div class="card-header" id="headingTwo">
									<h4 class="m-0">
										<a class="text-dark collapsed" data-toggle="collapse" href="#collapseTwo" aria-expanded="true">
										<i class="flaticon2-information mr-1 text-primary"></i>
										BANK BCA
										</a>
									</h4>
								</div>
								<div id="collapseTwo" class="card-collapse collapse" role="tabcard" aria-labelledby="headingTwo">
									<div class="kt-portlet__body">
										<ul>
											<li>Senin - Jumat : 21:00 WIB - 01:00 WIB</li>
											<li>Sabtu : 18:00 WIB - 20:00 WIB, 21:00 WIB - 01:00 WIB</li>
											<li>Minggu : 00:00 WIB - 05:00 WIB</li>
										</ul>
									</div>
								</div>
							</div>
							<div class="card mb-1">
							    <div class="card-header" id="headingTiga">
									<h4 class="m-0">
										<a class="text-dark collapsed" data-toggle="collapse" href="#collapseTiga" aria-expanded="true">
										<i class="flaticon2-information mr-1 text-primary"></i>
										MANDIRI
										</a>
									</h4>
								</div>
								<div id="collapseTiga" class="card-collapse collapse" role="tabcard" aria-labelledby="headingTiga">
									<div class="card-body">
										<ul>
											<li>Senin - Jumat : 23:00 WIB - 03:30 WIB</li>
											<li>Sabtu - Minggu : 22:00 WIB - 04:00 WIB</b></li>
										</ul>
									</div>
								</div>
							</div>
							<div class="card mb-1">
								<div class="card-header" id="headingEmpat">
									<h4 class="m-0">
										<a class="text-dark collapsed" data-toggle="collapse" href="#collapseEmpat" aria-expanded="false">
										<i class="flaticon2-information mr-1 text-primary"></i>
										BANK BTPN JENIUS
										</a>
									</h4>
								</div>
								<div id="collapseEmpat" class="card-collapse collapse" role="tabcard" aria-labelledby="headingEmpat" style="">
									<div class="card-body">
										<ul>
											<li>Senin - Minggu : 22:00 WIB - 06:00 WIB</li>
										</ul>
									</div>
								</div>
							</div>
							<div class="card mb-1">
							    <div class="card-header" id="headingLima">
									<h4 class="m-0">
										<a class="text-dark collapsed" data-toggle="collapse" href="#collapseLima" aria-expanded="true">
										<i class="flaticon2-information mr-1 text-primary"></i>
										BANK BNI
										</a>
									</h4>
								</div>
								<div id="collapseLima" class="card-collapse collapse" role="tabcard" aria-labelledby="headingLima">
									<div class="card-body">
										<ul>
											<li>24 Jam</li>
											<li>Jarang Sekali Terjadi <b>OFFLINE</b> Kecuali Kalau Ada Gangguan Dadakan.</li>
										</ul>
									</div>
								</div>
							</div>
                        </div>
                    </div>-->
			</div>
		</div>
	</div>
	<!-- End Page Top Up Balance -->

</div>
<!-- End Content -->

<!-- Start Scrolltop -->
<div id="kt_scrolltop" class="kt-scrolltop">
	<i class="fa fa-arrow-up"></i>
</div>
<!-- End Scrolltop -->

<script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#tipe").change(function() {
			var tipe = $("#tipe").val();
			$.ajax({
				url: '<?php echo $config['web']['url']; ?>ajax/provider-top-up-balance.php',
				data: 'tipe=' + tipe,
				type: 'POST',
				dataType: 'html',
				success: function(msg) {
					$("#provider").html(msg);
				},
				error(e) {
					console.log(e)
				}
			});
		});
		$("#provider").change(function() {
			var provider = $("#provider").val();
			console.log(provider)
			$.ajax({
				url: '<?php echo $config['web']['url']; ?>ajax/pembayaran-top-up-balance.php',
				data: 'provider=' + provider,
				type: 'POST',
				dataType: 'html',
				success: function(msg) {
					console.log(msg)
					$("#pembayaran").html(msg);
				},
				error(e) {
					console.log(e)
				}
			});
		});
		$("#pembayaran").change(function() {
			var method = $("#pembayaran").val();
			$.ajax({
				url: '<?php echo $config['web']['url']; ?>ajax/rate-top-up-balance.php',
				type: 'POST',
				dataType: 'html',
				data: 'method=' + method,
				success: function(result) {
					$("#rate").val(result);
				}
			});
		});
	});
	document.getElementById("transfer_pulsa").style.display = "none";
	$("#tipe").change(function() {
		var selectedCountry = $("#tipe option:selected").text();
		if (selectedCountry.indexOf('Transfer Bank') !== -1) {
			document.getElementById("transfer_pulsa").style.display = "none";
		} else {
			document.getElementById("transfer_pulsa").style.display = "block";
		}
	});

	function get_total(jumlah) {
		var rate = $("#rate").val();
		var result = eval(jumlah) * rate;
		$('#total').val(result);
	}
	$(document).on('keyup', '#jumlah', function() {
		console.log("hei")
		var jumlah = $(this).val()
		var rate = $("#rate").val();
		if (rate == '') {
			rate = 1;
		}
		var result = eval(jumlah) * rate;
		$('#total').val(result);
	})
</script>

<?php
require("../lib/footer.php");
?>