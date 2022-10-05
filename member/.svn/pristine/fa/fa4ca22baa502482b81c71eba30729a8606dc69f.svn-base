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
    <title>STEM Portal<?php echo get_title() ? ' &raquo; ' . get_title() : '' ?></title>
    <?php // Stylesheets are inside body ?>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://aiminstitute.org/member/css/header-footer.css?v=4">
    <link href="<?php echo base_url('css/aim-2020.css') ?>?v=4" rel="stylesheet">
    <link href="<?php echo base_url('css/sya-override.css') ?>?v=4" rel="stylesheet">
    <link href="<?php echo base_url('css/stemplatform.css') ?>?v=4" rel="stylesheet">
</head>
<body>
    <header id="header">
        <div class="container flex">
            <a href="https://stemplatform.aiminstitute.org" id="logo">
                <img src="https://stemplatform.aiminstitute.org/wp-content/themes/setyouraim/_ui/skin/img/aim-logo-blue.svg" alt="STEM Portal">
            </a>
            <nav class="menu-main-menu-container">
                <ul id="menu-main-menu" class="menu">
                    <li id="menu-item-3274" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-3274"><a href="https://stemplatform.aiminstitute.org/about/">About</a></li>
                    <li id="menu-item-3275" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-3275"><a href="https://stemplatform.aiminstitute.org/founding-partners/">Sponsors</a></li>
                    <li id="menu-item-3276" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-3276"><a rel="noopener" href="https://aiminstitute.org/member/offering/">Find STEM Offerings</a></li>
                    <li id="menu-item-3277" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-3277"><a href="https://stemplatform.aiminstitute.org/omaha-stem-ecosystem/">Learn About STEM Careers</a></li>
                    <li id="menu-item-3278" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-3278"><a rel="noopener" href="https://aiminstitute.org/donate-info">Donate</a></li>

                    <?php if ($this->member): ?>
                        <div class="dropdown mobile-menu-user d-md-flex ml-3">
                            <a href="javascript:void(0)" class="btn btn-link" data-toggle="dropdown" aria-expanded="true">
                                <span class="ml-2 d-none d-lg-block desktop-menu-user">
                                    <span class=""><i class="fa fa-user-o font-weight-bold" aria-hidden="true"></i> <?php echo $this->member->first_name ?></span>
                                    <i class="fa fa-chevron-down mt-1"></i>
                                </span>
                                <span class="d-lg-none"><i class="fa fa-user-o font-weight-bold"></i></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow" x-placement="bottom-end" style="position: absolute; transform: translate3d(-64px, 32px, 0px); top: 0px; left: 0px; will-change: transform;">
                                <a class="dropdown-item" href="/member">Dashboard</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" target="_blank" href="/member/me/password">
                                    <span>Change Password</span>
                                </a>
                                <a class="dropdown-item" href="/member/help">
                                    <span>Support</span>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/member/user/logout">Sign out</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <li id="menu-item-3279" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-3279"><a rel="noopener" href="<?php echo base_url() ?>">Log In</a></li>

                    <?php endif ?>
                </ul>
            </nav>
            <a href="#" id="menu-button"><span class="bar"></span></a>
        </div>
        <div id="header__mobile-menu">
            <nav class="menu-main-menu-container">
                <ul id="menu-main-menu-1" class="menu">
                    <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-3274"><a href="https://stemplatform.aiminstitute.org/about/">About</a></li>
                    <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-3275"><a href="https://stemplatform.aiminstitute.org/founding-partners/">Sponsors</a></li>
                    <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-3276"><a rel="noopener" href="https://aiminstitute.org/member/offering/">Find STEM Offerings</a></li>
                    <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-3277"><a href="https://stemplatform.aiminstitute.org/omaha-stem-ecosystem/">Learn About STEM Careers</a></li>
                    <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-3278"><a rel="noopener" href="https://aiminstitute.org/donate-info">Donate</a></li>
                    <?php if (empty($this->member)): ?>
                        <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-3279"><a rel="noopener" href="<?php echo base_url() ?>">Log In</a></li>
                    <?php endif ?>
                </ul>
            </nav>
        </div>
    </header>