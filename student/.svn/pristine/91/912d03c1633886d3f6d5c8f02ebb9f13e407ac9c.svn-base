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
                                <?php if ($this->router->fetch_method() != 'reply' && empty($post->parent_id)): ?>
                                    <?php if (!empty($categories)): ?>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Thread Category</label>
                                                <select class="form-control <?php echo is_valid('category_id') ?>" name="category_id">
                                                    <?php foreach ($categories as $category): ?>
                                                        <?php if (!$category->staff_only || is_moderator() && !$category->course_id): ?>
                                                            <option value="<?php echo $category->id ?>" <?php echo set_select('category_id', $category->id, ($post->category_id ?? null) == $category->id) ?> ><?php echo $category->name ?></option>
                                                        <?php endif ?>
                                                    <?php endforeach ?>
                                                </select>
                                                <?php echo form_error('category_id') ?>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Thread Title</label>
                                            <input type="text" class="form-control <?php echo is_valid('title') ?>" name="title" value="<?php echo set_value('title', $post->title ?? "" ) ?>">
                                            <?php echo form_error('title') ?>
                                        </div>
                                    </div>
                                <?php endif ?>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Message</label>
                                        <textarea type="text" rows="5" class="form-control <?php echo is_valid('message') ?>" name="message"><?php echo set_value('message', $post->message ?? "" ) ?></textarea>
                                        <?php echo form_error('message') ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col mt-5 d-flex">
                                    <button class="btn btn-primary"><?php echo empty($post) ? "Post" : "Update" ?></button>
                                    <a href="<?php echo base_url("forum") ?>" class="btn btn-secondary ml-2">Cancel</a>
                                    <?php if (!empty($post)): ?>
                                        <a href="<?php echo base_url("forum/delete/{$post->id}") ?>" class="btn btn-link text-muted ml-auto">Delete Post</a>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('footer') ?>