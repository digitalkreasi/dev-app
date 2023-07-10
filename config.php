<?php
date_default_timezone_set('Asia/Jakarta');
error_reporting(0);
$maintenance = 0; // Maintenance? 1 = ya 0 = tidak
if ($maintenance == 1) {
	die("site Maintance");
}
// database
$config['db'] = array(
	'host' => 'localhost',
	'name' => 'indt5495_app',
	'username' => 'indt5495_app',
	'password' => 'Rama1234.'
);

mysqli_real_escape_string($conn = mysqli_connect($config['db']['host'], $config['db']['username'], $config['db']['password'], $config['db']['name']));
if (!$conn) {
	die("Koneksi Gagal : " . mysqli_connect_error());
}
$config['web'] = array(
	'url' => 'https://digitalkreasigroup.com/indofazz/' // contoh: htdtp://sdmin.com/
);
// date & time
$date = date("Y-m-d");
$time = date("H:i:s");
require("lib/function.php");
require("lib/setting.php");
