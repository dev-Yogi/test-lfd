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
    <title><?php echo get_title() ? get_title() . ' | ' : '' ?>AIM Participant Portal</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,300i,400,400i,500,500i,600,600i,700,700i&amp;subset=latin-ext">
    <link href="<?php echo base_url('css/tabler.css') ?>?v=10" rel="stylesheet">
    <link href="<?php echo base_url('css/tabler-override.css') ?>?v=10" rel="stylesheet">
    <link href="<?php echo base_url('css/member.css') ?>?v=10" rel="stylesheet">
    <link href="<?php echo base_url('css/member-tablet.css') ?>?v=10" rel="stylesheet">
    <link href="<?php echo base_url('css/member-mobile.css') ?>?v=10" rel="stylesheet">
    <link href="<?php echo base_url('css/datatable.css') ?>?v=10" rel="stylesheet">
    <link href="<?php echo base_url('css/staff.css') ?>?v=10" rel="stylesheet">

    <style>
        @font-face {
            font-family: "feather";
            src: url("<?php echo base_url('fonts/feather/feather-webfont.eot?t=1501841394106') ?>");
            src: url("<?php echo base_url('fonts/feather/feather-webfont.eot?t=1501841394106#iefix') ?>") format("embedded-opentype"), url("<?php echo base_url('fonts/feather/feather-webfont.woff?t=1501841394106') ?>") format("woff"), url("<?php echo base_url('fonts/feather/feather-webfont.ttf?t=1501841394106') ?>") format("truetype"), url("<?php echo base_url('fonts/feather/feather-webfont.svg?t=1501841394106#feather') ?>") format("svg");
        }
    </style>
</head>

<body>

    <div class="staff-bar bg-cyan-dark py-lg-2">
        <div class="container">

            <div class="d-block d-md-flex">
                <div class="d-none d-md-block">

                    <a href="<?php echo base_url("admin/dashboard") ?>" class="bg-cyan btn-sm mt-1 d-inline-block"><b>AIM Participant Portal Admin</b></a>
                </div>
                <div class="ml-auto d-flex order-lg-2 menus">
                    <div class="d-none d-lg-block">
                    </div>

                    <div class="dropdown d-md-none mobile-menu-site">
                        <a href="javascript:void(0)" class="nav-link text-light" data-toggle="dropdown">
                            <i class="fe fe-menu"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-left">
                            <?php $class = $this->router->fetch_class() ?>
                            <a href="<?php echo base_url('admin/dashboard') ?>" class="dropdown-item <?php echo $class == 'dashboard' ? 'active' : '' ?>"><i class="fe fe-home"></i> Dashboard</a>
                            <a href="<?php echo base_url("admin/course/all") ?>" class="dropdown-item <?php echo ($class == 'course' || $class == 'lesson' || $class == 'category')  ? 'active' : '' ?>"><i class="fe fe-book-open"></i> Courses</a>
                            <a href="<?php echo base_url("admin/student/all") ?>" class="dropdown-item <?php echo $class == 'student' ? 'active' : '' ?>"><i class="fe fe-users"></i> Students</a>
                            <?php if (is_staff()): ?>
                                <a href="<?php echo base_url('admin/report') ?>" class="dropdown-item <?php echo $class == 'report' ? 'active' : '' ?>"><i class="fe fe-bar-chart-2"></i> Reports</a>
                            <?php endif ?>
                        </div>
                    </div>

                    <div class="current-program d-none d-lg-inline">
                        <b>Program:</b>
                        <a href="<?php echo base_url("admin/program") ?>" class="text-light">
                            <?php echo $this->program->name ?> <small>#<?php echo $this->program->id ?></small>
                        </a>
                    </div>
                    <?php $this->load->view('header-notification') ?>
                    <div class="dropdown mobile-menu-user d-md-flex">
                        <a href="javascript:void(0)" class="nav-link text-light" data-toggle="dropdown">
                            <span class="ml-2 d-none d-lg-block desktop-menu-user">
                                <span class="text-light"><?php echo $this->member->first_name ?> <?php echo $this->member->last_name ?></span>
                                <i class="fe fe-chevron-down text-light mt-1"></i>
                            </span>
                            <span class="d-lg-none"><i class="fe fe-user"></i></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                            <a class="dropdown-item" href="/member/">
                                <span>AIM Platform</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="/member/me/password">
                                <span>Change Password</span>
                            </a>
                            <a class="dropdown-item" href="<?php echo base_url('help') ?>">
                                <span>Support</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="/member/user/logout">Sign out</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php $class = $this->router->fetch_class() ?>
    <div class="staff-menu-bar bg-dark d-none d-md-block">
        <div class="container">
            <ul class="nav nav-tabs border-0 flex-column flex-lg-row text-light">
                <li class="nav-item">
                    <a href="<?php echo base_url('admin/dashboard') ?>" class="nav-link <?php echo $class == 'dashboard' ? 'active' : '' ?>"><i class="fe fe-home"></i> Dashboard</a>
                </li>
                <li class="nav-item dropdown">
                    <a href="<?php echo base_url("admin/course/all") ?>" class="nav-link <?php echo ($class == 'course' || $class == 'lesson' || $class == 'category')  ? 'active' : '' ?>"><i class="fe fe-book-open"></i> Courses</a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url("admin/student/all") ?>" class="nav-link <?php echo $class == 'student' ? 'active' : '' ?>"><i class="fe fe-users"></i> Students</a>
                </li>
                <?php if (is_staff()): ?>
                    <li class="nav-item">
                        <a href="<?php echo base_url('admin/report') ?>" class="nav-link <?php echo $class == 'report' ? 'active' : '' ?>"><i class="fe fe-bar-chart-2"></i> Reports</a>
                    </li>
                <?php endif ?>
            </ul>

        </div>
    </div>