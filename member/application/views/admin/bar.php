
            <?php if (has_tag(Tag::INSTRUCTOR) || has_tag(Tag::STAFF)): ?>
                <div class="staff-bar bg-blue-dark py-2">
                    <div class="container">
                        <div class="d-flex">

                            <div class="dropdown d-md-none mobile-menu-site">
                                <a href="javascript:void(0)" class="nav-link text-light" data-toggle="dropdown">
                                    <i class="fe fe-menu"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-left">
                                    <?php $class = $this->router->fetch_class() ?>
                                    <a href="<?php echo base_url('admin/dashboard') ?>" class="dropdown-item <?php echo $class == 'dashboard' ? 'active' : '' ?>"><i class="fe fe-home"></i> Dashboard</a>
                                    <a href="<?php echo base_url("admin/member") ?>" class="dropdown-item <?php echo $class == 'member' ? 'active' : '' ?>"><i class="fe fe-users"></i> Members</a>
                                    <a href="<?php echo base_url("admin/product") ?>" class="dropdown-item <?php echo $class == 'product' ? 'active' : '' ?>"><i class="fe fe-box"></i> Products</a>
                                    <a href="<?php echo base_url('admin/invoice') ?>" class="dropdown-item <?php echo $class == 'invoice' ? 'active' : '' ?>"><i class="fe fe-dollar-sign"></i> Invoices</a>
                                </div>
                            </div>
                            <div>
                                <a href="<?php echo base_url('admin/dashboard') ?>" class="bg-blue btn-sm mt-1 d-inline-block"><b>AIM Platform Admin</b></a>
                            </div>
                            <div class="ml-auto d-flex order-lg-2 menus">
                                <div class="d-none d-lg-block">
                                </div>

                                <div class="current-program d-none d-md-inline">
                                </div>
                                <div class="dropdown mobile-menu-user d-md-flex">
                                    <a href="javascript:void(0)" class="nav-link text-light" data-toggle="dropdown">
                                        <span class="ml-2 d-none d-lg-block desktop-menu-user">
                                            <span class="text-light"><?php echo $this->member->first_name ?> <?php echo $this->member->last_name ?></span>
                                            <i class="fe fe-chevron-down text-light mt-1"></i>
                                        </span>
                                        <span class="d-lg-none"><i class="fe fe-user"></i></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">

                                        <a class="dropdown-item" href="/member/admin/dashboard">AIM Platform Admin</a>
                                        <div class="dropdown-divider"></div>
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