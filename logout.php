<?php
session_start();
require("config.php");

// Hapus cookie dengan mengeset waktu kedaluwarsa ke masa lalu
if (isset($_COOKIE['cookie_token'])) {
    setcookie('cookie_token', '', time() - 3600, '/');
}
$insert_user = $conn->query("INSERT INTO aktifitas VALUES ('', '".$_SESSION['user']['username']."', 'Keluar', '".get_client_ip()."', '$date', '$time')");
// Simpan username pengguna sebelum session dihapus untuk digunakan dalam log aktivitas
$username = isset($_SESSION['user']['username']) ? $_SESSION['user']['username'] : '';

// Hapus data sesi
session_unset();

// Hapus data pengguna dari variabel $_SESSION
unset($_SESSION['user']);
unset($_SESSION['cookie']);

// Hapus data session secara fisik
session_destroy();
error_log('Pengguna '.$username.' telah logout', 0);

// Masukkan log aktivitas ke database
if (!empty($username)) {
    $insert_user = $conn->query("INSERT INTO aktifitas VALUES ('', '$username', 'Keluar', '".get_client_ip()."', '$date', '$time')");
}

// Arahkan kembali ke halaman login
header("Location: ".$config['web']['url']."auth/login");
exit;
?>


				