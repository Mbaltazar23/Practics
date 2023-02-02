<!DOCTYPE html>
<html>
    <head>
        <!-- Basic Page Info -->
        <meta charset="utf-8">
        <?php
        $navDashboard = navDashboard();
        ?>
        <title><?= $data["page_tag"] ?></title>
        <!-- Site favicon -->
        <link rel="apple-touch-icon" sizes="180x180" href="<?= media(); ?>/images/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="<?= media(); ?>/images/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="<?= media(); ?>/images/favicon-16x16.png">
        <!-- Mobile Specific Metas -->
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <!-- Google Font -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <!-- CSS -->
        <link rel="stylesheet" type="text/css" href="<?= media(); ?>/css/core.css">
        <link rel="stylesheet" type="text/css" href="<?= media(); ?>/css/icon-font.min.css">
        <link rel="stylesheet" type="text/css" href="<?= media(); ?>/js/plugins/datatables/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" type="text/css" href="<?= media(); ?>/js/plugins/datatables/css/responsive.bootstrap4.min.css">
        <link rel="stylesheet" type="text/css" href="<?= media(); ?>/css/style.css">
        <link rel="stylesheet" type="text/css" href="<?= media(); ?>/css/main.css"/>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-119386393-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());

            gtag('config', 'UA-119386393-1');
        </script>
    </head>
    <body>
        <?php require_once("nav_admin.php"); ?> 