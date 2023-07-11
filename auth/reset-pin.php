<?php
session_start();
require("../config.php");
require("../lib/class.phpmailer.php");
$tipe = "Reset PIN";

        if (isset($_POST['reset-pin'])) {
            $email = $conn->real_escape_string(filter(trim($_POST['email'])));

            $cek_pengguna = $conn->query("SELECT * FROM users WHERE email = '$email'");
            $cek_pengguna_ulang = mysqli_num_rows($cek_pengguna);
            $data_pengguna = mysqli_fetch_assoc($cek_pengguna);

            $error = array();
            if (empty($email)) {
    		    $error ['email'] = '*Tidak Boleh Kosong';
            } else if ($cek_pengguna_ulang == 0) {
    		    $error ['email'] = '*Email Tidak Ditemukan';
            } else {

            $pin = acak_nomor(3).acak_nomor(3);
            $pengguna = $data_pengguna['username'];

            $mail = new PHPMailer;
            $mail->IsSMTP();
            $mail->SMTPSecure = 'ssl'; 
            $mail->Host = "mail.indofazz.com"; //host masing2 provider email
            $mail->SMTPDebug = 2;
            $mail->Port = 465;
            $mail->SMTPAuth = true;
            $mail->Username = "noreply@indofazz.com"; //user email
            $mail->Password = "D3laszband1234."; //password email 
            $mail->SetFrom("noreply@indofazz.com",""); //set email pengirim
            $mail->Subject = "Lupa PIN Akun"; //subyek email
            $mail->AddAddress("$email","");  //tujuan email
            $mail->MsgHTML("Reset PIN Akun<br><br><b>Email : $email<br><br>PIN Baru : $pin<b><br><br>Silahkan Masuk Dengan Menggunakan PIN Anda dan Ubah PIN Di pengaturan Akun. Terima Kasih!");
            if ($mail->Send());
                if ($conn->query("UPDATE users SET pin = '$pin' WHERE username = '".$data_pengguna['username']."'") == true) {
                    $_SESSION['hasil'] = array('alert' => 'success', 'pesan' => 'Sip, PIN Baru Telah Dikirim Ke Email Kamu.<script>swal("Berhasil!", "PIN Baru Telah Dikirim Ke Email Kamu.", "success");</script>');
                } else {
                    $_SESSION['hasil'] = array('alert' => 'danger', 'pesan' => 'Ups, Gagal! Sistem Kami Sedang Mengalami Gangguan.<script>swal("Ups Gagal!", "Sistem Kami Sedang Mengalami Gangguan.", "error");</script>');    
                }
            }
        }
?>
<html>

<head>

<body>
    <form action="" method="POST">
        <input type="email" name="email" value="<?php echo $email; ?>">
        <br>
        <button type="submit" name="reset-pin">SUBMIT</button>
    </form>
</body>
</head>

</html>