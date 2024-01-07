<?php
session_start();
require '../config.php';
$tipe = "Masuk";

if (isset($_COOKIE['cookie_token'])) {
	$data = $conn->query("SELECT * FROM users WHERE cookie_token='" . $_COOKIE['cookie_token'] . "'");
	if (mysqli_num_rows($data) > 0) {
		$hasil = mysqli_fetch_assoc($data);
		$_SESSION['user'] = $hasil;
	}
}

if (isset($_SESSION['user'])) {
	header("Location: " . $config['web']['url']);
} else {

	if (isset($_POST['masuk'])) {
		$username = $conn->real_escape_string(filter($_POST['username']));
		$password = $conn->real_escape_string(filter($_POST['password']));

		$cek_pengguna = $conn->query("SELECT * FROM users WHERE username = '$username'");
		$cek_pengguna_ulang = mysqli_num_rows($cek_pengguna);
		$data_pengguna = mysqli_fetch_assoc($cek_pengguna);

		$verif_password = password_verify($password, $data_pengguna['password']);

		$error = array();
		if (empty($username)) {
			$error['username'] = '*Tidak Boleh Kosong';
		} else if ($cek_pengguna_ulang == 0) {
			$error['username'] = '*Pengguna Tidak Terdaftar';
		}
		if (empty($password)) {
			$error['password'] = '*Tidak Boleh Kosong';
		} else if ($verif_password <> $data_pengguna['password']) {
			$error['password'] = '*Kata Sandi Anda Salah';
		} else {

			if ($data_pengguna['status_akun'] == "Belum Verifikasi") {
            $_SESSION['hasil'] = array(
            'alert' => 'danger', 
            'pesan' => 'Ups, Akun Kamu Belum Di Verifikasi. <script>
                function verifikasiAkun() {
                  swal({
                    title: "Gagal!",
                    text: "Akun Kamu Belum Di Verifikasi.",
                    icon: "error",
                    buttons: {
                      verifikasi: {
                        text: "Verifikasi Sekarang",
                        value: "verifikasi",
                      },
                      cancel: "Batal",
                    },
                  }).then((value) => {
                    if (value === "verifikasi") {
                      window.location.href = "' . $config['web']['url'] . 'auth/verification-account";
                    }
                  });
                }
                verifikasiAkun();
                </script>');
            }   else {

				if ($cek_pengguna_ulang == 1) {
                            if ($verif_password == true) {
                                    $cookie_token = md5($username);
                                    $conn->query("UPDATE users SET cookie_token='" . $cookie_token . "' WHERE username='" . $username . "'");
                                    $_SESSION['cookie'] = $cookie_token;
                                    setcookie('cookie_token', $cookie_token, time() + 60 * 60 * 24 * 365, '/'); 
                                    $_SESSION['username'] = $data_pengguna['username'];
                                    exit(header("Location: verifikasi_pin"));
                                } else {
                                    $_SESSION['hasil'] = array('pesan' => '<script>swal("Ups Gagal!", "Pengguna Tidak Terdaftar", "error");</script>');
                                }
                            }
                        }
				    }
			    }
		    }

require '../lib/header_home.php';

?>

<!-- Start Page Login -->
<div class="login-2" style="background-image: url('');">
	<div class="container">
		<div class="row">
			<div class="col-lg-6">
				<div class="form-section">
					<div style="margin-bottom:15px">
						<div class="">
							<h2 style="font-weight:600; font-size:20px; color:#000;">Selamat Datang Kembali</h2>
							<a href="<?php echo $config ['web']['url']?>auth/login">
								<img src="<?php echo $config ['web']['url']?>assets/media/logos/indofazz.png" width="180px" class="img" style="margin-top:10px;">
							</a>
						</div>
					</div>
					<br />
					<?php
					if (isset($_SESSION['hasil'])) {
					?>
						<div class="alert alert-<?php echo $_SESSION['hasil']['alert'] ?> alert-dismissible" role="alert">
							<?php echo $_SESSION['hasil']['pesan'] ?>
						</div>
					<?php
						unset($_SESSION['hasil']);
					}
					?>
					<div class="login-inner-form">
						<form class="form-horizontal" role="form" method="POST">
						<input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
							<div class="form-group form-box">
								<input type="text" name="username" class="input-text" placeholder="Username" value="<?php echo $username; ?>" required>
								<i class="flaticon-user"></i>
								<small class="text-danger font-13 pull-right"><?php echo ($error['username']) ? $error['username'] : ''; ?></small>
							</div>
							<div class="form-group form-box">
								<input type="password" name="password" class="input-text" placeholder="Kata Sandi" required>
								<i class="flaticon-password"></i>
								<small class="text-danger font-13 pull-right"><?php echo ($error['password']) ? $error['password'] : ''; ?></small>
							</div>
							<div class="form-group mb-0">
								<button type="submit" class="btn btn-primary btn-block" name="masuk">Masuk</button>
							</div>
							<br />
							<p>Lupa Password ?<a class="text-primary" href="<?php echo $config['web']['url'] ?>auth/forgot-password"> <strong>Reset Sekarang</strong></a><p>
								<br />
							<p>Belum Punya Akun ?<a class="text-primary" href="<?php echo $config['web']['url'] ?>auth/register"> <strong>Daftar</strong></a></p>
							<br />
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Page Login -->

<?php
require '../lib/footer_home.php';
?>