<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://aiminstitute.org/member/css/header-footer.css?v=42">


<div class="container">
    <header class="blog-header py-3">
        <div class="row flex-nowrap justify-content-between align-items-center">
            <div class="col-4 pt-1">
                <a href="#" class="mobile-menu"><img src="https://aiminstitute.org/wp-content/themes/aim/img/hamburger-menu-icon.png"></a>
                <a class="blog-header-logo text-dark" href="https://aiminstitute.org/"><img src="https://aiminstitute.org/wp-content/themes/aim/img/logo.png" alt=""></a>
            </div>
            <div class="col-4 text-center">
            </div>
            <div class="col-4 d-flex justify-content-end align-items-center">
                <a class="btn btn-sm btn-link text-secondary p-2 px-3 mr-3" href="https://aiminstitute.org/donate-info">Donate</a>
                <?php if (!empty($member_name)): ?>
                    <div class="dropdown mobile-menu-user d-md-flex">
                        <a href="javascript:void(0)" class="btn btn-sm btn-link text-secondary" data-toggle="dropdown" aria-expanded="true">
                            <span class="ml-2 d-none d-lg-block desktop-menu-user">
                                <span class=""><i class="fa fa-user-o font-weight-bold" aria-hidden="true"></i> <?php echo $member_name ?></span>
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
                    <a class="btn btn-sm btn-outline-secondary p-2 px-3" href="https://aiminstitute.org/member">Log In</a>
                <?php endif ?>
            </div>
        </div>
    </header>

    <ul class="primary-menu">
        <a href="#" id="jobtal">
            <li>About AIM</li>
        </a>
        <a href="#" id="prodev">
            <li>AIM Programs</li>
        </a>
        <a href="https://aiminstitute.org/setyouraim/jobs-in-tech/tech-concierge/" id="technav">
            <li>Tech Navigator</li>
        </a>
        <a href="https://stemplatform.aiminstitute.org/" id="techeco">
            <li>STEM Community Platform</li>
        </a>
        <a href="https://siliconprairienews.com/" id="spn">
            <li>SPN</li>
        </a>

        <div class="secondary-menu" id="menu-jobtal">
            <div class="arrow" style="left: 84px"></div>
            <h2>About AIM</h2>
            <ul>
                <div class="mobile-back"><i class="fa fa-caret-left"></i></div>
                <li><a href="https://aiminstitute.org/team">AIM Team</a></li>
                <li><a href="https://aiminstitute.org/sponsors">AIM Members</a></li>
                <li><a href="https://aiminstitute.org/aim-board-of-directors">AIM Board of Directors</a></li>
                <li><a href="https://aiminstitute.org/donor-partnership-program">Become a Member</a></li>
                <li><a href="http://culture.aimforbrilliance.org/">Join Our Team</a></li>
                <li><a href="https://aiminstitute.org/donate-info">Donate</a></li>
                <li><a href="https://aiminstitute.org/news">AIM Newsroom</a></li>
                <li><a href="https://aiminstitute.org/history">History</a></li>
            </ul>
        </div>
        <div class="secondary-menu" id="menu-prodev">
            <div class="arrow" style="left: 34px"></div>
            <h2>AIM Programs</h2>
            <ul>
                <div class="mobile-back"><i class="fa fa-caret-left"></i></div>
                <li><a href="#">Tech Access Programs</a>
                    <div class="subthing">
                        <a href="https://aiminstitute.org/youth-in-tech/">Youth In Tech Program</a><br>
                        <a href="https://aiminstitute.org/brainexchange/">Brain Exchange Programs & Outreach</a><br>
                    </div>
                </li>
                <li><a href="#">College Access Programs</a>
                    <div class="subthing">
                        <a href="https://aiminstitute.org/graduatetwice/talent-search-monroe-benson/">AIM Talent Search - North Omaha</a><br>
                        <a href="https://aiminstitute.org/graduatetwice/talent-search-council-bluffs/">AIM Talent Search - Council Bluffs</a><br>
                        <a href="https://aiminstitute.org/graduatetwice/educational-opportunity-center/">Education Opportunity Center - Greater NE</a><br>
                    </div>
                </li>
                <li><a href="#">Youth Academies</a>
                    <div class="subthing">
                        <a href="https://aiminstitute.org/graduatetwice/upward-bound-omaha-bryan">Upward Bound: Omaha Bryan</a><br>
                        <a href="https://aiminstitute.org/graduatetwice/upward-bound-papillion-la-vista">Upward Bound: Papillion La Vista</a><br>
                        <a href="https://aiminstitute.org/graduatetwice/upward-bound-abraham-lincoln-2">Upward Bound: Abraham Lincoln</a><br>
                        <a href="https://aiminstitute.org/graduatetwice/upward-bound-thomas-jefferson">Upward Bound: Thomas Jefferson</a>
                    </div>
                </li>
            </ul>
            <ul>
                <li><a href="#">Technical Training</a>
                    <div class="subthing">
                        <a href="https://interfaceschool.com/">AIM Code School</a><br>
                        <a href="https://aiminstitute.org/training/">Custom Technical Training for Employers</a>
                    </div>
                </li>
                <li><a href="#">IT Leadership Academies</a>
                    <div class="subthing">
                        <a href="https://aiminstitute.org/leadership-academy/">Advanced Leaders Academy</a><br>
                        <a href="https://aiminstitute.org/emerging-leaders-program/">Emerging Leaders Academy</a>
                    </div>
                </li>
                <li><a href="https://aiminstitute.org/educational-and-networking-events">Conferences and Events</a>
                    <div class="subthing">
                        <a href="https://aiminstitute.org/infotec">Infotec</a><br>
                        <a href="https://aiminstitute.org/hdc">HDC</a><br>
                        <a href="https://aiminstitute.org/techcelebration">Tech Celebration</a>
                    </div>
                </li>
            </ul>
        </div>
    </ul>
</div>
