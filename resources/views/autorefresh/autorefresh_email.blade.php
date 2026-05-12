<?php

$page = $_SERVER['PHP_SELF'];
$sec = "60";

date_default_timezone_set('Asia/Jakarta');

?>

<!DOCTYPE html>
<html>

<head>

    <title>Auto Refresh Email</title>

    <meta http-equiv="refresh"
          content="<?php echo $sec ?>;
          URL='<?php echo $page ?>'">

</head>

<body>

    <h2>
        Pengecekan Pengiriman Email Invoice
    </h2>

    <?php

        echo "Halaman akan refresh otomatis setiap 60 detik.<br>";

        echo "Tanggal dan waktu sekarang: "
            . date("Y-m-d h:i:sa")
            . "<br>";

    ?>

</body>

</html>