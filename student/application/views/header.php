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
	<link href="<?php echo base_url('css/tabler.css') ?>?v=12" rel="stylesheet">
	<link href="<?php echo base_url('css/tabler-override.css') ?>?v=12" rel="stylesheet">
	<link href="<?php echo base_url('css/member.css') ?>?v=12" rel="stylesheet">
	<link href="<?php echo base_url('css/member-tablet.css') ?>?v=12" rel="stylesheet">
	<link href="<?php echo base_url('css/member-mobile.css') ?>?v=12" rel="stylesheet">
	<link href="<?php echo base_url('css/student.css') ?>?v=12" rel="stylesheet">

    <style>
        @font-face {
            font-family: "feather";
            src: url("<?php echo base_url('fonts/feather/feather-webfont.eot?t=1501841394106') ?>");
            src: url("<?php echo base_url('fonts/feather/feather-webfont.eot?t=1501841394106#iefix') ?>") format("embedded-opentype"), url("<?php echo base_url('fonts/feather/feather-webfont.woff?t=1501841394106') ?>") format("woff"), url("<?php echo base_url('fonts/feather/feather-webfont.ttf?t=1501841394106') ?>") format("truetype"), url("<?php echo base_url('fonts/feather/feather-webfont.svg?t=1501841394106#feather') ?>") format("svg");
        }
    </style>
</head>

<body>

	<div class="page">
		<div class="flex-fill">
			<?php if (has_tag(Tag::INSTRUCTOR) || has_tag(Tag::STAFF)): ?>
				<div class="staff-bar bg-cyan-dark py-2">
					<div class="container">
						<div class="d-flex">
							<div>
								<a href="<?php echo base_url('admin/dashboard') ?>" class="bg-cyan btn-sm mt-1 d-inline-block"><b>AIM Participant Portal Admin</b></a>
							</div>
							<div class="ml-auto d-flex order-lg-2 menus">
								<div class="d-none d-lg-block">
								</div>

		                    	<div class="current-program d-none d-md-inline">
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
			                            <a class="dropdown-item" target="_blank" href="/member/me/password">
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
			<?php endif ?>
			<div class="header py-lg-5">
				<div class="container">
					<div class="d-block d-md-flex">
						<a class="navbar-brand" href="<?php echo base_url() ?>">
							<img src="<?php echo base_url('img/aim-logo.png') ?>" class="navbar-brand-img" alt="AIM Participant Portal">
							Participant Portal
						</a>

						<?php if (!has_tag(Tag::INSTRUCTOR) && !has_tag(Tag::STAFF)): ?>
							<div class="ml-auto d-flex order-lg-2 menus">
								<?php if (count($this->student->programs) > 1): ?>
									<div class="d-none d-lg-block">
										<div class="dropdown border">
											<a href="javascript:void(0)" class="nav-link py-2 px-3" data-toggle="dropdown">
												<?php echo $this->program->name ?> <i class="fe fe-chevron-down ml-3"></i>
											</a>
											<div class="dropdown-menu" x-placement="bottom-start">
												<?php foreach ($this->student->programs as $program): ?>
													<a class="dropdown-item" href="<?php echo base_url("home/switch_program/{$program->id}") ?>">
														<span><?php echo $program->name ?> <small class="text-muted"><?php echo $program->id == $this->program->id ? '(current)' : null ?></small></span>
													</a>
												<?php endforeach ?>
											</div>
										</div>
									</div>
								<?php endif ?>
								<?php $this->load->view('header-notification') ?>
								<div class="dropdown mobile-menu-user d-md-flex">
									<a href="javascript:void(0)" class="nav-link" data-toggle="dropdown">
										<span class="ml-2 d-none d-lg-block desktop-menu-user">
											<span class="text-default"><?php echo $this->member->first_name ?></span>
											<i class="fe fe-chevron-down"></i>
										</span>
										<span class="d-lg-none mt-3"><i class="fe fe-user"></i></span>
									</a>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
										<a class="dropdown-item" target="_blank" href="/member/me/password">
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
									<a href="<?php echo base_url('course/me') ?>" class="nav-link"><i class="fe fe-book-open"></i> Courses</a>
								</li>
								<!-- <li class="nav-item">
									<a href="<?php echo base_url('video') ?>" class="nav-link"><i class="fe fe-video"></i> Videos <?php echo video_is_live() ?></a>
								</li>
								<li class="nav-item">
									<a href="<?php echo base_url('forum') ?>" class="nav-link"><i class="fe fe-message-square"></i> Forum</a>
								</li> -->
								<li class="nav-item">
									<a href="<?php echo base_url('help') ?>" class="nav-link"><i class="fe fe-help-circle"></i> Help</a>
								</li>
							</ul>
						</div>

						<div class="form-group m-0 d-none d-md-block">
							<form method="post" action="<?php echo base_url('search') ?>">
								<div class="input-group">
									<input type="text" class="form-control header-search" placeholder="Search" name="keywords">
									<span class="input-group-append">
										<button class="btn btn-secondary border-left-0" type="submit"><i class="fe fe-search"></i></button>
									</span>
								</div>
							</form>
						</div>


						<!-- <div class="col-lg-3 ml-auto d-none d-md-block">
							<form class="input-icon my-3 my-lg-0" method="post" action="<?php echo base_url('search') ?>">
								<input type="search" class="form-control header-search" placeholder="Search…" tabindex="1" name="keywords">
								<div class="input-icon-addon">
									<i class="fe fe-search"></i>
								</div>
							</form>
						</div> -->
					</div>
				</div>
				<div class="imply-scroll"></div>
			</div>