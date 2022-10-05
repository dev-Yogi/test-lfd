<?php $this->load->view('header') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">Forum</h1>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <div class="page-header mt-0">
                        <a href="<?php echo base_url('forum/post') ?>" class="btn btn-primary btn-block"><i class="fe fe-plus mr-2"></i>New Post</a>
                    </div>
                    <div class="card card-profile">
                        <div class="card-body text-center">
                            <div class="forum-avatar <?php echo $profile->avatar_color ?? 'bg-cyan' ?> mx-auto my-4">
                                <i class="fas fa-<?php echo $profile->avatar_icon ?? 'smile' ?>"></i>
                            </div>
                            <h3><?php echo forum_username($profile->username) ?></h3>
                            <a href="<?php echo base_url('forum/profile') ?>" class="btn btn-sm btn-secondary mb-4">Edit Profile</a>
                            <div class="list-group list-group-transparent mb-0 text-left">
                                <a href="" class="list-group-item list-group-item-action"><span class="icon mr-3"><i class="fe fe-file-text"></i></span>My Started Threads</a>
                                <a href="" class="list-group-item list-group-item-action"><span class="icon mr-3"><i class="fe fe-corner-down-right"></i></span>My Replies</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <?php echo alerts() ?>
                    <div class="page-header mt-0 py-2">
                        Announcements
                    </div>
                    <div class="card threads">
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter table-hover">
                                <thead>
                                    <tr>
                                        <th>Topic</th>
                                        <th width="120">Posted</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php foreach($posts as $post): ?>
                                        <tr>
                                            <td>
                                                <a href="<?php echo base_url("forum/view/{$post->id}") ?>" class="text-dark text-decoration-none">
                                                    <div class="thread">
                                                        <span class="text-inherit font-weight-semibold"><?php echo $post->title ?></span>
                                                        <span class="text-muted">&middot; <?php echo $post->replies ?? 0 ?> &middot;</span>
                                                        <span class="text-muted"><?php echo character_limiter($post->message, 100) ?></span>
                                                    </div>
                                                </a>
                                            </td>
                                            <td><span class="posted"><?php echo date('m/d', strtotime($post->created)) ?></span></td>
                                        </tr>
                                    <?php endforeach ?>

                                </tbody>
                            </table>
                            <div class="card-footer text-right no-bg">
                                <a href="#" class="btn btn-secondary btn-sm">View All Announcements »</a>
                            </div>
                        </div>
                    </div>
                    <div class="page-header">
                        Course Discussion
                    </div>
                    <div class="card-deck">
                        <div class="card">
                            <div class="table-responsive">
                                <table class="table card-table table-vcenter table-hover">
                                    <thead>
                                        <tr>
                                            <th>Topic</th>
                                            <th width="120">Posted</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div><a href="" class="text-inherit font-weight-semibold">Some topic title <span class="text-muted font-weight-normal">&middot; 2 &middot; Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                                tempor incididunt ut ...</span></a></div>
                                            </td>
                                            <td>2 days ago</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div><a href="" class="text-inherit font-weight-semibold">Some topic title <span class="text-muted font-weight-normal">&middot; 2 &middot; Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                                tempor incididunt ut ...</span></a></div>
                                            </td>
                                            <td>2 days ago</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div><a href="" class="text-inherit font-weight-semibold">Some topic title <span class="text-muted font-weight-normal">&middot; 2 &middot; Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                                tempor incididunt ut ...</span></a></div>
                                            </td>
                                            <td>2 days ago</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div><a href="" class="text-inherit font-weight-semibold">Some topic title <span class="text-muted font-weight-normal">&middot; 2 &middot; Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                                tempor incididunt ut ...</span></a></div>
                                            </td>
                                            <td>2 days ago</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer text-right no-bg">
                                <a href="#" class="btn btn-secondary btn-sm">View All Announcements »</a>
                            </div>
                        </div>
                    </div>
                    <div class="page-header">
                        General Discussion
                    </div>
                    <div class="card-deck">
                        <div class="card">
                            <div class="table-responsive">
                                <table class="table card-table table-vcenter table-hover">
                                    <thead>
                                        <tr>
                                            <th>Topic</th>
                                            <th width="120">Posted</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div><a href="" class="text-inherit font-weight-semibold">Some topic title <span class="text-muted font-weight-normal">&middot; 2 &middot; Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                                tempor incididunt ut ...</span></a></div>
                                            </td>
                                            <td>2 days ago</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div><a href="" class="text-inherit font-weight-semibold">Some topic title <span class="text-muted font-weight-normal">&middot; 2 &middot; Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                                tempor incididunt ut ...</span></a></div>
                                            </td>
                                            <td>2 days ago</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div><a href="" class="text-inherit font-weight-semibold">Some topic title <span class="text-muted font-weight-normal">&middot; 2 &middot; Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                                tempor incididunt ut ...</span></a></div>
                                            </td>
                                            <td>2 days ago</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div><a href="" class="text-inherit font-weight-semibold">Some topic title <span class="text-muted font-weight-normal">&middot; 2 &middot; Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                                tempor incididunt ut ...</span></a></div>
                                            </td>
                                            <td>2 days ago</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer text-right no-bg">
                                <a href="#" class="btn btn-secondary btn-sm">View All Announcements »</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?php $css = array('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css') ?>
<?php $this->load->view('footer', compact('css')) ?>