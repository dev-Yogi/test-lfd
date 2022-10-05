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
	<link href="<?php echo base_url('css/tabler.css') ?>?v=9" rel="stylesheet">
	<link href="<?php echo base_url('css/tabler-override.css') ?>?v=9" rel="stylesheet">
	<link href="<?php echo base_url('css/member.css') ?>?v=9" rel="stylesheet">
	<link href="<?php echo base_url('css/member-tablet.css') ?>?v=9" rel="stylesheet">
	<link href="<?php echo base_url('css/member-mobile.css') ?>?v=9" rel="stylesheet">
	<link href="<?php echo base_url('css/student.css') ?>?v=9" rel="stylesheet">

    <style>
        @font-face {
            font-family: "feather";
            src: url("<?php echo base_url('fonts/feather/feather-webfont.eot?t=1501841394106') ?>");
            src: url("<?php echo base_url('fonts/feather/feather-webfont.eot?t=1501841394106#iefix') ?>") format("embedded-opentype"), url("<?php echo base_url('fonts/feather/feather-webfont.woff?t=1501841394106') ?>") format("woff"), url("<?php echo base_url('fonts/feather/feather-webfont.ttf?t=1501841394106') ?>") format("truetype"), url("<?php echo base_url('fonts/feather/feather-webfont.svg?t=1501841394106#feather') ?>") format("svg");
        }
    </style>
</head>

<body>