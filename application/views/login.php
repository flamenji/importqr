
<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> 
<html class="no-js" lang=""> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>DAMAN SDA</title>
    <meta name="description" content="Sufee Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="<?php echo site_url(); ?>apple-icon.png">
    <!-- <link rel="shortcut icon" href="<?php echo site_url(); ?>favicon.ico"> -->
    <link rel="shortcut icon" type="image/png" href="<?php echo site_url(); ?>images/patrick.png"/>


    <link rel="stylesheet" href="<?php echo site_url(); ?>assets/css/normalize.css">
    <link rel="stylesheet" href="<?php echo site_url(); ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo site_url(); ?>assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo site_url(); ?>assets/css/themify-icons.css">
    <link rel="stylesheet" href="<?php echo site_url(); ?>assets/css/flag-icon.min.css">
    <link rel="stylesheet" href="<?php echo site_url(); ?>assets/css/cs-skin-elastic.css">
    <!-- <link rel="stylesheet" href="assets/css/bootstrap-select.less"> -->
    <link rel="stylesheet" href="<?php echo site_url(); ?>assets/scss/style.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

    <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.min.js"></script> -->

</head>
<style>
body {
    background: url(<?php echo site_url(); ?>images/wallpaper_3.png);
}
</style>
<body class="animated zoomInDown">
    <!-- <body style="background-image: url('<?php echo site_url(); ?>images/wallpaper.jpg');"> -->
    <div class="sufee-login d-flex align-content-center flex-wrap">
        <div class="container">
            <div class="login-content">
                <div class="login-form">
                    <div class="login-logo">
                        <a href="index.html">
                            <img style="display:inline-block;height:150px" class="align-content" src="<?php echo site_url(); ?>images/patrick.png" alt="">
                        </a>
                        <a href="index.html">
                            <h3>DAMAN SIDOARJO</h3>
                        </a>
                    </div>
                    <form action="<?php echo site_url(); ?>main/login_func" method="POST">
                        <div class="form-group">
                            <label>NIK</label>
                            <input id="nik" name="nik" type="text" class="form-control" placeholder="NIK">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input id="password" name="password" type="password" class="form-control" placeholder="Password">
                        </div>
                        <button type="submit" class="btn btn-success btn-flat m-b-30 m-t-30">Sign in</button>
                    </form>
                </div>
                
            </div>
        </div>
    </div>


    <script src="<?php echo site_url(); ?>assets/js/vendor/jquery-2.1.4.min.js"></script>
    <script src="<?php echo site_url(); ?>assets/js/popper.min.js"></script>
    <script src="<?php echo site_url(); ?>assets/js/plugins.js"></script>
    <script src="<?php echo site_url(); ?>assets/js/main.js"></script>


</body>
</html>
