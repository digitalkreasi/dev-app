<?php
include "config.php";
date_default_timezone_set('Asia/Jakarta');
$date = date('Y/m/d');
$time = date('H:i:s');
$dt = date('Y/m/d H:i:s');
if (isset($_POST['loginawal'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $query = "INSERT INTO aktifitas VALUES ('','$username','Masuk','ip','$date','$time');";
    $exec = mysqli_query($conn, $query);
}

if (isset($_POST['OTP'])) {
    $username = $_POST['username'];
    $qotp = "DELETE FROM verifikasi_login WHERE username = '$username';
    INSERT INTO verifikasi_login VALUES ('','$username','$dt', LPAD(FLOOR(RAND() * 999999.99), 6, '0'))";
    $eotp = mysqli_multi_query($conn, $qotp);
}
 if(isset($_POST['LOGIN'])){
    $username = $_POST['username'];
    $angkaotp = $_POST['angkaotp'];
    $qlogin = "SELECT * FROM verifikasi_login WHERE username = '$username' ORDER BY tanggal_waktu DESC LIMIT 1";
    $execotp = mysqli_query($conn, $qlogin);
    $dapatotp = mysqli_fetch_array($execotp);
    if($angkaotp == $dapatotp['kode_otp']){
        header("Location: http://localhost/tes_login/halamanutama.php");
        $qdeleteotp = "DELETE FROM verifikasi_login WHERE username = '$username'";
        $execdotp = mysqli_query($conn, $qdeleteotp);
    }else{
        echo "verifikasi salah";
    }
 }
?>
<html>

<head>

<body>
    <form action="verifikasi.php" method="POST">
        <input type="text" name="username" value="<?= $username ?>">
        <br>
        <input type="number" name="angkaotp" placeholder="masukkan OTP">
        <br>
        <input type="submit" name="OTP" value="SEND OTP">
        <input type="submit" name="LOGIN" value="LOGIN">
    </form>
</body>
</head>

</html>