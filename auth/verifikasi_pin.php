<?php
session_start();
include "../config.php";
date_default_timezone_set('Asia/Jakarta');
$date = date('Y/m/d');
$time = date('H:i:s');
$dt = date('Y/m/d H:i:s');
if (isset($_SESSION['user'])) {
    header("Location: " . $config['web']['url']);
    exit;
} else {
    if (isset($_POST['login'])) {
        $username = $_SESSION['username'];
        $angkaotp = $_POST['pin1'] . $_POST['pin2'] . $_POST['pin3'] . $_POST['pin4'] . $_POST['pin5'] . $_POST['pin6'];
        $qlogin = "SELECT * FROM users WHERE username = '$username'";
        $execotp = mysqli_query($conn, $qlogin);
        $dapatotp = mysqli_fetch_array($execotp);

        // Mengganti titik dengan angka pada PIN yang disimpan dalam database
        $pinDatabase = str_replace('.', '', $dapatotp['pin']);

        if ($angkaotp === $pinDatabase) {
            $_SESSION['user'] = $dapatotp;
            if (isset($_SESSION['cookie'])) {
                setcookie('cookie_token', $_SESSION['cookie'], time() + 60 * 60 * 24 * 365, '/');
            }
            header("Location: " . $config['web']['url']);
            exit;
        } else {
            echo '<script>alert("Verifikasi PIN gagal");</script>';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mobile-friendly Form</title>
  <style>
    * {
      box-sizing: border-box;
    }
    
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      background-color: #f2f2f2;
    }
    
    .container {
      max-width: 400px;
      width: 100%;
      height: 100vh; /* Menggunakan unit vh untuk tinggi container */
      margin: 0 auto;
      background-color: #fff;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      position: relative;
    }
    
    h1 {
      text-align: center;
    }
    
    p {
      text-align: center;
    }
    
    .logo {
      text-align: center;
      position: absolute;
      top: 0;
      left: 50%;
      transform: translateX(-50%);
      margin-top: 10px;
      margin-bottom: 20px;
    }
    
    .logo img {
      max-width: 100%;
      height: auto;
    }
    
    form {
      margin-top: 20px;
      text-align: center;
    }
    
    .pin-input-container {
      display: flex;
      justify-content: center;
      align-items: center;
    }
    
    .pin-input-container input {
      width: 30px;
      height: 40px;
      font-size: 24px;
      text-align: center;
      margin: 0 5px;
      padding: 5px;
      border: none;
      border-bottom: 2px solid #ccc;
      outline: none;
    }
    
    .pin-input-container input:focus {
      border-bottom-color: #4CAF50;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="logo">
      <img src="indofazz.png" alt="Logo">
    </div>
    <form action="" method="POST" id="verifikasiPin" name="verifikasiPin">
      <h1>Masukkan PIN</h1>
      <p>Masukkan 6 digit PIN kamu</p>
      <div class="pin-input-container">
        <input type="password" id="pin1" name="pin1" maxlength="1" oninput="handlePinInput(1)" onkeydown="handlePinKeyDown(event, 1)" required autofocus>
        <input type="password" id="pin2" name="pin2" maxlength="1" oninput="handlePinInput(2)" onkeydown="handlePinKeyDown(event, 2)" required>
        <input type="password" id="pin3" name="pin3" maxlength="1" oninput="handlePinInput(3)" onkeydown="handlePinKeyDown(event, 3)" required>
        <input type="password" id="pin4" name="pin4" maxlength="1" oninput="handlePinInput(4)" onkeydown="handlePinKeyDown(event, 4)" required>
        <input type="password" id="pin5" name="pin5" maxlength="1" oninput="handlePinInput(5)" onkeydown="handlePinKeyDown(event, 5)" required>
        <input type="password" id="pin6" name="pin6" maxlength="1" oninput="handlePinInput(6)" onkeydown="handlePinKeyDown(event, 6)" required>
      </div>
      <br>
      <input type="submit" value="Submit" id="login" name="login" style="display: none;">
    </form>
    <a href="reset-pin">Lupa PIN?</a>
  </div>
  <script>
    function handlePinInput(pinIndex) {
      const input = document.getElementById(`pin${pinIndex}`);
      const value = input.value;
      const sanitizedValue = value.replace(/[^0-9]/g, ''); // Menghapus karakter non-angka
      input.value = sanitizedValue;
      
      if (sanitizedValue.length === 1) {
          if (pinIndex === 6) {
              var submitBtn = document.getElementById("login");
              submitBtn.click();
      }else if(pinIndex < 6){
        focusNextPinInput(pinIndex);  
      }
    }
    }
    
    function handlePinKeyDown(event, currentPinIndex) {
      if (event.key === 'Backspace' && event.target.value === '') {
        focusPreviousPinInput(currentPinIndex);
      }
    }
    
    function focusNextPinInput(currentPinIndex) {
      const nextPinInput = document.getElementById(`pin${currentPinIndex + 1}`);
      if (nextPinInput) {
        nextPinInput.focus();
      }
    }
    
    function focusPreviousPinInput(previousPinIndex) {
      const previousPinInput = document.getElementById(`pin${previousPinIndex - 1}`);
      if (previousPinInput) {
        previousPinInput.focus();
      }
    }
  </script>
</body>
</html>
