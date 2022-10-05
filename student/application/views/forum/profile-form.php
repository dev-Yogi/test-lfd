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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Username</label>
                                        <input type="text" class="form-control-plaintext" value="<?php echo $profile->username ?? "" ?>">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="forum-avatar <?php echo $profile->avatar_color ?? 'bg-cyan' ?>">
                                            <i class="fas fa-<?php echo $profile->avatar_icon ?? 'smile' ?>"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Avatar Icon</label>
                                        <select class="selectize form-control <?php echo is_valid('avatar_icon') ?>" name="avatar_icon">
                                            <?php foreach ($icons as $icon): ?>
                                                <option value="<?php echo $icon ?>" <?php echo set_select('avatar_icon', $icon, ($profile->avatar_icon ?? null) == $icon) ?> ><?php echo ucwords(str_replace('-', ' ', $icon)) ?></option>
                                            <?php endforeach ?>
                                        </select>
                                        <?php echo form_error('avatar_icon') ?>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Avatar Background Color</label>
                                        <select class="selectize form-control <?php echo is_valid('avatar_color') ?>" name="avatar_color">
                                            <?php foreach ($colors as $color): ?>
                                                <option value="<?php echo $color ?>" <?php echo set_select('avatar_color', $color, ($profile->avatar_color ?? null) == $color) ?> ><?php echo ucwords(str_replace('bg-', '', $color)) ?></option>
                                            <?php endforeach ?>
                                        </select>
                                        <?php echo form_error('avatar_color') ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mt-5">
                                    <button class="btn btn-primary">Save</button>
                                    <a href="<?php echo base_url("forum") ?>" class="btn btn-secondary ml-2">Cancel</a>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $css = array('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css') ?>
<?php $this->load->view('footer', compact('css')) ?>