<?php
session_start();
include "../config.php";
date_default_timezone_set('Asia/Jakarta');
$date = date('Y/m/d');
$time = date('H:i:s');
$dt = date('Y/m/d H:i:s');
$username = $_SESSION['username'];
$angkaotp = $_POST['pin'];
$qlogin = "SELECT * FROM users WHERE username = '$username'";
$execotp = mysqli_query($conn, $qlogin);
$dapatotp = mysqli_fetch_array($execotp);
if ($angkaotp == $dapatotp['pin']) {
    $_SESSION['user'] = $dapatotp;
    if (isset($_SESSION['cookie'])) {
        setcookie('cookie_token', $_SESSION['cookie'], time() + 60 * 60 * 24 * 365, '/');
    }
    header("Location: " . $config['web']['url']);
} else {
    echo "pin salah";
}
?>
<html>

<head>

<body>
    <form action="verifikasi_pin.php" method="POST">
        <input type="number" name="pin" placeholder="masukkan PIN" maxlength="6" pattern="[0-9]{6}" required>
        <br>
        <input type="submit" name="LOGIN" value="LOGIN">
    </form>
</body>
</head>

</html>