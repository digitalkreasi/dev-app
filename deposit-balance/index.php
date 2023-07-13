<?php
include "../config.php";
$query = "SELECT * FROM tipe_pembayaran";
$execquery = mysqli_query($conn, $query);
?>
<html>

<head>

<body>
    <?php
    while ($r = mysqli_fetch_assoc($execquery)) {
    ?>
        <form action="deposit.php" method="POST">
            <input type="hidden" value="<?= $r['id'] ?>" name="namabank">
            <br>
            <input type="submit" name="pilihbank" value="<?= $r['nama'] ?>">
        </form>
    <?php
    }
    ?>

</body>
</head>

</html>