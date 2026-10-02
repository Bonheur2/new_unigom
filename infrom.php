<?php
$document_root = str_replace('\\', '/', rtrim($_SERVER['DOCUMENT_ROOT'], '/\\'));
$app_base_url = str_replace($document_root, '', str_replace('\\', '/', __DIR__));
?>
<!DOCTYPE html>
<html lang="fr">

<!-- layout-top-navigation.html  Tue, 07 Jan 2020 03:35:42 GMT -->
<head>
<meta charset="UTF-8">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
<title>STUMIS</title>
<link rel="icon" href="<?php echo $app_base_url; ?>/img/grad.png" type="image/icon type">
<!-- General CSS Files -->
<link rel="stylesheet" href="<?php echo $app_base_url; ?>/assets/modules/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo $app_base_url; ?>/assets/modules/fontawesome/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!-- CSS Libraries -->
<link rel="stylesheet" href="<?php echo $app_base_url; ?>/assets/modules/izitoast/css/iziToast.min.css">
<!-- Template CSS -->
<link rel="stylesheet" href="<?php echo $app_base_url; ?>/assets/css/style.min.css">
<link rel="stylesheet" href="<?php echo $app_base_url; ?>/assets/css/components.min.css">
<link rel="stylesheet" href="<?php echo $app_base_url; ?>/assets/modules/chocolat/dist/css/chocolat.css">
<!-- Portal fonts + landing/header/footer design -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Public+Sans:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@400;500&display=swap">
<link rel="stylesheet" href="<?php echo $app_base_url; ?>/assets/css/landing.css?v=<?php echo filemtime(__DIR__ . "/assets/css/landing.css"); ?>">
<!-- CSS Libraries -->
<link rel="stylesheet" href="<?php echo $app_base_url; ?>/assets/modules/bootstrap-daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="<?php echo $app_base_url; ?>/assets/modules/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
<link rel="stylesheet" href="<?php echo $app_base_url; ?>/assets/modules/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?php echo $app_base_url; ?>/assets/modules/jquery-selectric/selectric.css">
<link rel="stylesheet" href="<?php echo $app_base_url; ?>/assets/modules/bootstrap-timepicker/css/bootstrap-timepicker.min.css">
<link rel="stylesheet" href="<?php echo $app_base_url; ?>/assets/modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css">
<!-- Template CSS -->

<!--<script src="https://static.elfsight.com/platform/platform.js" data-use-service-core defer></script>-->
<div class="elfsight-app-60ad6e50-13d9-48e4-a46a-5bc911f2be6f"></div>
<style>
    /* mouse over link */
    /*a:hover {*/
    /*  color: #c42126;*/
    /*}*/
    .goog-te-banner-frame.skiptranslate {
        display: none !important;
    }
    .goog-tooltip {
    display: none !important;
    }
    .goog-tooltip:hover {
        display: none !important;
    }
    .goog-text-highlight {
        background-color: transparent !important;
        border: none !important;
        box-shadow: none !important;
    }

    body {
        top: 0px !important;
    }

    .mainmenu ul#nav>li {
        margin-right: 18px;
    }

    .mainmenu ul.sub-menu,
    .mainmenu ul.sub-menu ul.inside-menu {
        top: 60%;
    }

    #google_translate_element {
        display: none;
    }
    #dropdown-menu li {
        padding-top: 5px;
        padding-bottom: 5px;
    }
    .dropdown-menu span:nth-child(2) font font {
        font-size: 14px;
    }
    #langge:hover {
        background-color:#FFFFFF;
    }

</style>
</head>

<body class="layout-3">
<!-- Page Loader -->
<!--<div class="page-loader-wrapper">-->
<!--    <span class="loader"><span class="loader-inner"></span></span>-->
<!--</div>-->
