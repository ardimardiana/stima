<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title><?= isset($title) ? $title : 'Dashboard Admin'; ?> - CMS</title>
        <link href="<?= base_url('assets/css/styles.css'); ?>" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.9.1/summernote-bs5.min.css" rel="stylesheet">
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.9.1/summernote-bs5.min.js"></script>
    </head>
    <body class="sb-nav-fixed">
        <?php $this->load->view('admin/_partials/_topbar'); ?>
        <div id="layoutSidenav">
            <?php $this->load->view('admin/_partials/_sidebar'); ?>
            <div id="layoutSidenav_content">
                <main>