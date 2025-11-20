<?php
    $productRecords = html_escape($all);
    $userRecords = html_escape($users);

    $dashboardPayload = [
        'baseUrl' => base_url(),
        'siteUrl' => site_url(),
        'products' => $productRecords,
        'users' => $userRecords
    ];

    $dashboardJson = htmlspecialchars(json_encode($dashboardPayload), ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechStore Admin - POS System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url();?>public/css/home.css">
</head>
<body class="light-mode">
    <div id="toast-container"></div>
    <audio id="notifSound" src="<?= base_url();?>public/resources/notif.mp3" preload="auto"></audio>

    <div
        id="react-dashboard-root"
        data-dashboard='<?= $dashboardJson; ?>'
    ></div>
    <?php
      include APP_DIR.'views/modals/_account.php';
      include APP_DIR.'views/modals/_logout.php';
      include APP_DIR.'views/modals/applicant_Delete.php';
      include APP_DIR.'views/modals/applicant_Verify.php';
      include APP_DIR.'views/modals/inventory_Export.php';
      include APP_DIR.'views/modals/inventory_Import.php';
      include APP_DIR.'views/modals/inventory_Update.php';
      include APP_DIR.'views/modals/product_Add.php';
      include APP_DIR.'views/modals/product_Delete.php';
      include APP_DIR.'views/modals/product_Update.php';
      include APP_DIR.'views/modals/staff_Barcode.php';
      include APP_DIR.'views/modals/staff_Delete.php';
    ?>

    <script crossorigin src="https://unpkg.com/react@18/umd/react.development.js"></script>
    <script crossorigin src="https://unpkg.com/react-dom@18/umd/react-dom.development.js"></script>
    <script src="<?= base_url();?>public/js/react-dashboard.js"></script>
</body>
</html>