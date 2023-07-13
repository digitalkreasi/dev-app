<?php
session_start();
require "../config.php";
require "../lib/session_user.php";
$ch = curl_init();
$secret_key = "JDJ5JDEzJENKbUVuVk1ULkJSVS5ZQ2l1a0NUVGVYbVdkMk9tTENkNkp3UXNNODhKQi9xNXdBSGFxcVdD";

curl_setopt($ch, CURLOPT_URL, "https://bigflip.id/api/v2/general/banks");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/x-www-form-urlencoded"
));

curl_setopt($ch, CURLOPT_USERPWD, $secret_key . ":");

$response = curl_exec($ch);
curl_close($ch);

$r = json_decode($response, true);

$querybank = "SELECT * FROM tipe_pembayaran";
$execbank = mysqli_query($conn, $querybank);
$bank = [];
while ($databank = mysqli_fetch_assoc($execbank)) {
    $bank[] = $databank;
}

$matchedData = array();

foreach ($r as $apiItem) {
    foreach ($bank as $dbItem) {
        if ($apiItem['bank_code'] === $dbItem['nama']) {
            $matchedData[] = array(
                'api_data' => $apiItem,
                'db_data' => $dbItem
            );
        }
    }
}
foreach ($matchedData as $match) {
    $apiData = $match['api_data'];
    $dbData = $match['db_data'];

    if ($apiItem['status'] == "OPERATIONAL") {
        $status = "ON";
    } else {
        $status = "OFF";
    }

    $kodebank = $dbData['nama'];
    $queryUpdate = "UPDATE tipe_pembayaran SET status = '$status'
  WHERE nama = '$kodebank'";
    $execUpdate = mysqli_query($conn, $queryUpdate);
}
?>
<html>

<head>

<body>
    <?php
    $query = "SELECT * FROM tipe_pembayaran";
    $execquery = mysqli_query($conn, $querybank);
    while ($row = mysqli_fetch_assoc($execquery)) {
        if ($row['status'] == 'ON') {
    ?>
            <form action="deposit" method="POST">
                <input type="hidden" value="<?= $row['id'] ?>" name="namabank">
                <br>
                <input type="submit" name="pilihbank" value="<?= $row['kode'] ?>">
            </form>
        <?php
        } else {
        ?>
            <form action="deposit" method="POST">
                <input type="hidden" value="<?= $row['id'] ?>" name="namabank">
                <br>
                <input type="submit" name="pilihbank" value="<?= $row['kode'] ?>" disabled>
                <br>
                <label for="namabank">Bank sedang tidak aktif</label>
            </form>
        <?php
        }
        ?>

    <?php
    }
    ?>

</body>
</head>

</html>