<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Language" content="en" />
    <meta name="msapplication-TileColor" content="#2d89ef">
    <meta name="theme-color" content="#4188c9">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <title><?php echo get_title() ? get_title() . ' | ' : '' ?>AIM Member Portal</title>

    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Source+Sans+Pro:300,300i,400,400i,500,500i,600,600i,700,700i&amp;subset=latin-ext">
    <link href="<?php echo base_url('css/tabler.css') ?>?v=4" rel="stylesheet">
    <link href="<?php echo base_url('css/tabler-override.css') ?>?v=4" rel="stylesheet">
    <link href="<?php echo base_url('css/member.css') ?>?v=4" rel="stylesheet">
    <link href="<?php echo base_url('css/member-tablet.css') ?>?v=4" rel="stylesheet">
    <link href="<?php echo base_url('css/member-mobile.css') ?>?v=4" rel="stylesheet">

    <style>
        @font-face {
            font-family: "feather";
            src: url("<?php echo base_url() ?>fonts/feather/feather-webfont.eot?t=1501841394106");
            src: url("<?php echo base_url() ?>fonts/feather/feather-webfont.eot?t=1501841394106#iefix") format("embedded-opentype"), url("<?php echo base_url() ?>fonts/feather/feather-webfont.woff?t=1501841394106") format("woff"), url("<?php echo base_url() ?>fonts/feather/feather-webfont.ttf?t=1501841394106") format("truetype"), url("<?php echo base_url() ?>fonts/feather/feather-webfont.svg?t=1501841394106#feather") format("svg");
        }
    </style>
</head>

<body>
    <?php if ($this->member && $this->member_model->has_tag($this->member->id, Tag::STAFF)): ?>
        <?php $this->load->view('admin/bar') ?>
    <?php endif ?>
    <div class="page">
        <div class="page-main">
            <div class="header">
                <div class="container">
                    <div class="d-block d-md-flex">
                        <a class="navbar-brand" href="<?php echo base_url() ?>">
                            <img src="<?php echo base_url('img/logo.png') ?>" class="navbar-brand-img" alt="AIM Careerlink">
                        </a>
                        <?php if ($this->member && !$this->member_model->has_tag($this->member->id, Tag::STAFF)): ?>
                            <div class="ml-auto d-flex order-lg-2 menus">
                                <div class="d-none d-lg-block">
                                </div>
                                <div class="dropdown mobile-menu-user">
                                    <a href="javascript:void(0)" class="nav-link" data-toggle="dropdown">
                                        <span class="ml-2 d-none d-lg-block desktop-menu-user mt-3">
                                            <i class="fe fe-user mr-1"></i>
                                            <span class="text-default"><?php echo $this->member->first_name  . " " . $this->member->last_name ?></span>
                                            <i class="fe fe-chevron-down"></i>
                                        </span>
                                        <span class="d-lg-none"><i class="fe fe-user"></i></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" href="<?php echo base_url('me//password') ?>">
                                            <span>Change Password</span>
                                        </a>
                                        <a class="dropdown-item" href="<?php echo base_url('contact') ?>">
                                            <span>Support</span>
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="<?php echo base_url('user/logout') ?>">Sign out</a>
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            </div>
            <div class="header-nav d-flex">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col">
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a href="<?php echo base_url() ?>" class="nav-link"><i class="fe fe-home"></i> Home</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo base_url("me") ?>" class="nav-link"><i class="fe fe-settings"></i> Account</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo base_url("contact") ?>" class="nav-link"><i class="fe fe-help-circle"></i> Help</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="imply-scroll"></div>
            </div>