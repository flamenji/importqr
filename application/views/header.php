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
    <meta name="description" content="MSO Admin - Map">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- <link rel="apple-touch-icon" href="apple-icon.png"> -->
    <link rel="shortcut icon" type="image/png" href="<?php echo site_url(); ?>images/patrick.png"/>

    <!-- <link rel="shortcut icon" href="favicon.ico"> -->
    <link rel="stylesheet" href="<?php echo site_url(); ?>assets/jquery-ui-1.12.1/jquery-ui.min.css">

    <link rel="stylesheet" href="<?php echo site_url(); ?>assets/css/normalize.css">
    <link rel="stylesheet" href="<?php echo site_url(); ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo site_url(); ?>assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo site_url(); ?>assets/css/themify-icons.css">
    <link rel="stylesheet" href="<?php echo site_url(); ?>assets/css/flag-icon.min.css">
    <link rel="stylesheet" href="<?php echo site_url(); ?>assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="<?php echo site_url(); ?>assets/css/buttons.css">
    <link rel="stylesheet" href="<?php echo site_url(); ?>assets/css/leaflet.contextmenu.css">
    <link rel="stylesheet" href="<?php echo site_url(); ?>assets/scss/style.css">
    <!-- <link href="<?php echo site_url(); ?>assets/css/lib/vector-map/jqvmap.min.css" rel="stylesheet"> -->

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width. initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo site_url(); ?>assets/css/leaflet.css" >
    <script src="<?php echo site_url(); ?>assets/js/leaflet.js"></script>
    <script src="<?php echo site_url(); ?>assets/js/jquery-3.3.1.js"></script>
    
    <script src="<?php echo site_url(); ?>assets/js/leaflet.contextmenu.js"></script>
    <script src="<?php echo site_url(); ?>assets/js/popper.min.js"></script>
    <script src="<?php echo site_url(); ?>assets/js/plugins.js"></script>
    <!-- <script src="<?php echo site_url(); ?>assets/js/main.js"></script> -->    
    <script src="<?php echo site_url(); ?>assets/js/jquery-ui.js"></script>
</head>
<body>

    <div id="right-panel" class="right-panel">
        <!-- Header-->
        <header style="padding-right: 0px;" id="header" class="header">
            <div class="header-menu">
                <div class="col-sm-12">
                        <div style="padding-left: 0%" class="user-area dropdown float-right">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <table>
                                    <tr>
                                        <td style="padding-right: 5px;"><h6>Account Settings</h6></td>
                                    </tr>
                                </table>
                            </a>
                            <div class="user-menu dropdown-menu">
                                <a class="nav-link" href="<?php echo site_url(); ?>home/logout"><i class="fa fa-power -off"></i>Logout</a>
                            </div>
                        </div>
                    
                </div>
            </div>
        </header><!-- /header -->
        <!-- Header-->

        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1><?php echo $breadcrumbs; ?></h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">
        <style>
            html. body {
                height: 100%;
                margin: 0;
            }
            #map {
                width: 600px;
                height: 400px;
            }
            .table_popup {
                border: 1px solid #ccc;
                border-collapse: collapse;
                margin: 0;
                padding: 3px;
                width: 100%;
                table-layout: fixed;
            }

            .table_popup td {
                padding: 3px;
            }

            .leaflet-container.crosshair-cursor-enabled {
                cursor:crosshair;
            }

            .leaflet-control-layers-toggle {
                background-image: url(<?php echo site_url(); ?>images/layers.png);
                width: 36px;
                height: 36px;
            }

            .leaflet-retina .leaflet-control-layers-toggle {
                background-image: url(<?php echo site_url(); ?>images/layers-2x.png);
                background-size: 26px 26px;
            }

            .leaflet-default-icon-path {
                background-image: url(<?php echo site_url(); ?>images/marker-icon.png);
            }

        </style>
        <script>
            // $.noConflict();
            jQuery(document).ready(function($) {

                "use strict";

                [].slice.call( document.querySelectorAll( 'select.cs-select' ) ).forEach( function(el) {
                    new SelectFx(el);
                } );

                jQuery('.selectpicker').selectpicker;


                $('#menuToggle').on('click', function(event) {
                    $('body').toggleClass('open');
                });

                $('.search-trigger').on('click', function(event) {
                    event.preventDefault();
                    event.stopPropagation();
                    $('.search-trigger').parent('.header-left').addClass('open');
                });

                $('.search-close').on('click', function(event) {
                    event.preventDefault();
                    event.stopPropagation();
                    $('.search-trigger').parent('.header-left').removeClass('open');
                });
            });

        </script>
