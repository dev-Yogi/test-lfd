<?php $this->load->view('admin/header') ?>
<?php $this->load->view('admin/menu') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="page-header">
                        <h1 class="page-title">
                            Categories <span class="text-muted small"><span class="p-2">&middot;</span> <?php echo empty($category) ? "New" : "Edit" ?></span>
                        </h1>
                    </div>
                    <form method="post" enctype="multipart/form-data">
                        <?php echo alerts() ?>
                        <div class="card p-5 mb-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Category Name</label>
                                        <input type="text" class="form-control <?php echo is_valid('name') ?>" name="name" value="<?php echo set_value('name', $category->name ?? "" ) ?>">
                                        <?php echo form_error('name') ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Category Description</label>
                                        <textarea type="text" rows="5" class="form-control <?php echo is_valid('description') ?>" name="description"><?php echo set_value('description', $category->description ?? "" ) ?></textarea>
                                        <?php echo form_error('description') ?>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col mt-5">
                                    <button class="btn btn-primary"><?php echo empty($category) ? "Create" : "Update" ?></button>
                                    <a href="<?php echo base_url("admin/program_category/") ?>" class="btn btn-secondary ml-2">Cancel</a>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($category)): ?>
                            <a href="<?php echo base_url("admin/program_category/remove/{$category->id}") ?>" onclick="return confirm('Are you sure you want to remove this category?')" class="btn btn-link float-right mr-3"><i class="fe fe-trash"></i> Remove Category</a>
                        <?php endif ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('admin/footer') ?>