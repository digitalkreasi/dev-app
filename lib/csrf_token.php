<?php

/**
 * Penulis Kode - SMM Panel script
 * Domain: http://penuliskode.com/
 * Documentation: http://penuliskode.com/smm/script/version-n1/documentation.html
 */

if (empty($_SESSION['token'])) {
	if (function_exists('mcrypt_create_iv')) {
		$_SESSION['token'] = bin2hex(random_bytes(32));
	} else {
		$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
	}
}
$config['csrf_token'] = $_SESSION['token'];

if ($_POST and !isset($_POST['csrf_token'])) {
	exit("Permintaan tidak diterima.");
} elseif ($_POST and isset($_POST['csrf_token'])) {
	if (hash_equals($_SESSION['token'], $_POST['csrf_token']) == false) {
		exit("Permintaan tidak diterima.");
	}
}
