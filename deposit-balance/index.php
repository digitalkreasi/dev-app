<?php
session_start();
require "../config.php";
require "../lib/session_user.php";
$query = "SELECT * FROM tipe_pembayaran";
$execquery = mysqli_query($conn, $query);
?>
<html>

<head>

<body>
    <?php
    while ($r = mysqli_fetch_assoc($execquery)) {
        if ($r['status = "ON"']) {
    ?>
            <form action="deposit" method="POST">
                <input type="hidden" value="<?= $r['id'] ?>" name="namabank">
                <br>
                <input type="submit" name="pilihbank" value="<?= $r['nama'] ?>">
            </form>
        <?php
        } else {
        ?>
            <form action="deposit" method="POST">
                <input type="hidden" value="<?= $r['id'] ?>" name="namabank">
                <br>
                <input type="submit" name="pilihbank" value="<?= $r['nama'] ?>" disabled>
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