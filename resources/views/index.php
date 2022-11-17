<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $hostUrl; ?></title>
</head>

<body>
    <p>
        <?php echo $start . ' record(s) updated.'; ?>
    </p>

    <p>
        <?php echo $affectedRows . ' record(s) updated at the moment.'; ?>
    </p>

    <?php
    if ($affectedRows === $takeItems) {
        $url = $hostUrl . '/wp-plugins/?start=' . $start;
    ?>
        <script type="text/javascript">
            setTimeout(() => {
                window.location.href = '<?php echo $url; ?>';
            }, 1);
        </script>
    <?php } ?>

</body>

</html>