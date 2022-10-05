<?php $this->load->view('header') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="page-header pb-2 d-block">
                        <div class="mb-5">
                            <?php if ($post->category_id): ?>
                                <a href="<?php echo base_url("forum/category/{$post->category_id}") ?>" class="btn btn-sm btn-secondary"><i class="fe fe-chevron-left"></i>Back to threads</a>
                            <?php elseif ($post->course_id): ?>
                                <a href="<?php echo base_url("forum/course/{$post->course_id}") ?>" class="btn btn-sm btn-secondary"><i class="fe fe-chevron-left"></i>Back to threads</a>
                            <?php else: ?>
                                <a href="<?php echo base_url("forum") ?>" class="btn btn-sm btn-secondary"><i class="fe fe-chevron-left"></i>Back to threads</a>
                            <?php endif ?>
                        </div>
                        <h1 class="page-title d-inline"><?php echo $post->title ?></h1><span class="p-2 text-muted">&middot;</span><span class="text-muted"><?php echo $post->category ?></span>
                    </div>

                    <!-- Parent Post -->
                    <div class="card post mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="forum-avatar <?php echo $post->avatar_color ?? 'bg-cyan' ?>">
                                        <i class="fas fa-<?php echo $post->avatar_icon ?? 'smile' ?>"></i>
                                    </div>
                                    <div class="font-weight-semibold pt-2"><?php echo forum_username($post->username) ?></div>
                                    <div class="small"><?php echo $post->post_count ?> <?php echo $post->post_count == 1 ? 'post' : 'posts' ?></div>

                                    <?php if (is_instructor()): ?>
                                        <div class="card mt-3">
                                            <div class="card-body p-2 small">
                                                <b class="d-block badge badge-secondary mb-2">MODERATOR TOOLS</b>
                                                Name: <?php echo display_name($post) ?><br>
                                                <?php if (is_moderator()): ?>
                                                    <div class="pt-1">
                                                        <a href="<?php echo base_url("admin/forum/delete/{$post->id}") ?>" onclick="return confirm('Are you sure you want to remove this post?')" class="btn btn-sm btn-secondary" title="Delete Post"><i class="fe fe-trash"></i> Delete</a>
                                                        <a href="<?php echo base_url("admin/forum/ban/{$post->created_by}") ?>" onclick="return confirm('Are you sure you want to ban this user?')" class="btn btn-sm btn-secondary" title="Ban User"><i class="fe fe-user-x"></i> Ban</a>
                                                    </div>
                                                <?php endif ?>
                                            </div>
                                        </div>
                                    <?php endif ?>

                                </div>
                                <div class="col-lg-9">
                                    <h5><?php echo $post->title ?></h5>
                                    <?php echo nl2br($post->message) ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0 border-0">
                            <div class="row">
                                <div class="col-lg-9 offset-3 d-flex">
                                    <div class="small text-muted">Posted <?php echo date('d/m/Y H:ia', strtotime($post->created)) ?></div>
                                    <?php if ($post->created_by == $this->member->id): ?>
                                    <div class="small ml-auto text-muted">
                                        <a href="<?php echo base_url("forum/edit/{$post->id}") ?>" class="text-muted small"><i class="fe fe-edit-2"></i> Edit</a>
                                    </div>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Replies -->
                    <?php foreach($post->replies as $reply): ?>
                        <a name="<?php echo $reply->id ?>"></a>
                        <div class="card post mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="forum-avatar <?php echo $reply->avatar_color ?? 'bg-cyan' ?>">
                                            <i class="fas fa-<?php echo $reply->avatar_icon ?? 'smile' ?>"></i>
                                        </div>
                                        <div class="font-weight-semibold pt-2"><?php echo forum_username($reply->username) ?></div>
                                        <div class="small"><?php echo $reply->post_count ?> <?php echo $reply->post_count == 1 ? 'post' : 'posts' ?></div>

                                        
                                        <?php if (is_instructor()): ?>
                                            <div class="card mt-3">
                                                <div class="card-body p-2 small">
                                                    <b class="d-block badge badge-secondary mb-2">MODERATOR TOOLS</b>
                                                    Name: <?php echo display_name($reply) ?><br>
                                                    <?php if (is_moderator()): ?>
                                                        <div class="pt-1">
                                                            <a href="<?php echo base_url("admin/forum/delete/{$reply->id}") ?>" onclick="return confirm('Are you sure you want to remove this reply?')" class="btn btn-sm btn-secondary" title="Delete reply"><i class="fe fe-trash"></i> Delete</a>
                                                            <a href="<?php echo base_url("admin/forum/ban/{$reply->created_by}") ?>" onclick="return confirm('Are you sure you want to ban this user?')" class="btn btn-sm btn-secondary" title="Ban User"><i class="fe fe-user-x"></i> Ban</a>
                                                        </div>
                                                    <?php endif ?>
                                                </div>
                                            </div>
                                        <?php endif ?>
                                    </div>
                                    <div class="col-lg-9 pt-1">
                                        <?php if (!$reply->removed): ?>
                                            <?php echo nl2br($reply->message) ?>
                                        <?php else: ?>
                                            <i class="text-muted">Post has been removed</i>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0 border-0">
                                <div class="row">
                                    <div class="col-lg-9 offset-3 d-flex">
                                        <div class="small text-muted">Posted <?php echo date('d/m/Y H:ia', strtotime($reply->created)) ?></div>
                                        <?php if ($reply->created_by == $this->member->id): ?>
                                        <div class="small ml-auto text-muted">
                                            <a href="<?php echo base_url("forum/edit/{$reply->id}") ?>" class="text-muted small"><i class="fe fe-edit-2"></i> Edit</a>
                                        </div>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                    <hr>
                    <!-- Reply Card -->
                    <div class="card post">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-3">
                                </div>
                                <div class="col-lg-9">
                                    <?php if (empty($profile)): ?>
                                        <p>To get started with the forum, create a user profile.</p>
                                        <a href="<?php echo base_url('forum/profile') ?>" class="btn btn-sm btn-secondary mb-4">Create Profile</a>
                                    <?php else: ?>
                                        <form action="<?php echo base_url("forum/reply/{$post->id}") ?>" method="post">
                                            <div class="form-group">
                                                <label class="form-label">Post a Reply</label>
                                                <textarea type="text" rows="5" class="form-control" name="message"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <input type="submit" value="Post Reply" class="btn btn-primary">
                                            </div>
                                        </form>
                                    <?php endif ?>
                                </div>
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