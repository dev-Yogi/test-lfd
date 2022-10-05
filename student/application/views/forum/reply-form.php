<?php $this->load->view('header') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="page-header">
                        <h1 class="page-title">
                            Forum <span class="text-muted small"><span class="p-2">&middot;</span> <?php echo empty($post) ? "New" : "Edit" ?></span>
                        </h1>
                    </div>
                    <form method="post" enctype="multipart/form-data">
                        <?php echo alerts() ?>
                        <div class="card p-5 mb-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Message</label>
                                        <textarea type="text" rows="5" class="form-control <?php echo is_valid('message') ?>" name="message"><?php echo set_value('message', $post->message ?? "" ) ?></textarea>
                                        <?php echo form_error('message') ?>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col mt-5">
                                    <button class="btn btn-primary"><?php echo empty($post) ? "Post" : "Update" ?></button>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($post)): ?>
                            <a href="<?php echo base_url("post/remove/{$post->id}") ?>" onclick="return confirm('Are you sure you want to remove this post?')" class="btn btn-link float-right mr-3"><i class="fe fe-trash"></i> Remove Category</a>
                        <?php endif ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('footer') ?>