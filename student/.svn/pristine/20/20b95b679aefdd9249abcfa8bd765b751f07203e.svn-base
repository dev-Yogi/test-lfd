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
                    <?php foreach ($categories as $category): ?>
                        <div class="page-header mt-0 py-2">
                            <?php echo $category['category_name'] ?>
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
                                        <?php foreach($category['threads'] as $post): ?>
                                            <tr>
                                                <td>
                                                    <a href="<?php echo base_url("forum/view/{$post->id}") ?>" class="text-dark text-decoration-none">
                                                        <div class="thread">
                                                            <span class="text-inherit font-weight-semibold"><?php echo $post->title ?></span>
                                                            <span class="text-muted">&middot; <?php echo $post->replies ?? 0 ?> &middot;</span>
                                                            <span class="text-muted"><?php echo character_limiter($post->message, 80) ?></span>
                                                        </div>
                                                    </a>
                                                </td>
                                                <td><span class="posted"><?php echo date('m/d', strtotime($post->created)) ?></span></td>
                                            </tr>
                                        <?php endforeach ?>
                                        <?php if (empty($category['threads'])): ?>
                                            <tr>
                                                <td><span class="text-muted">No posts</span></td>
                                            </tr>
                                        <?php endif ?>
                                    </tbody>
                                </table>
                                <div class="card-footer text-right no-bg">
                                    <a href="<?php echo base_url("forum/category/{$category['category_id']}") ?>" class="btn btn-secondary btn-sm">View All <?php echo $category['category_name'] ?> »</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>

        </div>
    </div>
</div>
<?php $css = array('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css') ?>
<?php $this->load->view('footer', compact('css')) ?>