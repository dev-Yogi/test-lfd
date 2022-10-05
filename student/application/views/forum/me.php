<?php $this->load->view('header') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">Forum</h1>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <?php $this->load->view('forum/sidebar') ?>
                </div>
                <div class="col">
                    <?php echo alerts() ?>
                        <div class="page-header mt-0 py-2">
                            <?php if ($this->uri->segment(3) == 'threads'): ?>
                                My Started Threads
                            <?php else: ?>
                                My Replies
                            <?php endif ?>
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
                                                    <?php if ($this->uri->segment(3) == 'threads'): ?>
                                                        <a href="<?php echo base_url("forum/view/{$post->id}") ?>" class="text-dark text-decoration-none">
                                                            <div class="thread">
                                                                <span class="text-inherit font-weight-semibold"><?php echo $post->title ?></span>
                                                                <span class="text-muted">&middot; <?php echo $post->replies ?? 0 ?> &middot;</span>
                                                                <span class="text-muted"><?php echo character_limiter($post->message, 100) ?></span>
                                                            </div>
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="<?php echo base_url("forum/view/{$post->thread_id}#{$post->id}") ?>" class="text-dark text-decoration-none">
                                                            <div class="thread">
                                                                <div class="text-inherit"><?php echo character_limiter($post->message, 100) ?></div>
                                                                <div class="text-muted"><?php echo $post->thread_title ?></div>
                                                            </div>
                                                        </a>
                                                    <?php endif ?>
                                                </td>
                                                <td><span class="posted"><?php echo date('m/d', strtotime($post->created)) ?></span></td>
                                            </tr>
                                        <?php endforeach ?>
                                        <?php if (empty($posts)): ?>
                                            <tr>
                                                <td><span class="text-muted">No posts</span></td>
                                            </tr>
                                        <?php endif ?>
                                    </tbody>
                                </table>
                                <div class="card-footer no-bg">
                                    <a href="<?php echo base_url("forum/home") ?>" class="btn btn-secondary btn-sm">&laquo; Back to Forums</a>
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