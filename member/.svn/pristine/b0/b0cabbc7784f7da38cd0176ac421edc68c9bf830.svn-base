<?php $this->load->view('admin/header') ?>
<?php $this->load->view('admin/menu') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="page-header">
                        <h1 class="page-title">
                            Members <span class="text-muted small"><span class="p-2">&middot;</span> <?php echo empty($member) ? "New" : "Edit" ?></span>
                        </h1>
                    </div>
                    <form method="post">
                        <?php echo alerts() ?>
                        <div class="card p-5">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">First Name</label>
                                        <input type="text" class="form-control <?php echo is_valid('first_name') ?>" name="first_name" value="<?php echo set_value('first_name', $member->first_name ?? "") ?>">
                                        <?php echo form_error('first_name') ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Last Name</label>
                                        <input type="text" class="form-control <?php echo is_valid('last_name') ?>" name="last_name" value="<?php echo set_value('last_name', $member->last_name ?? "") ?>">
                                        <?php echo form_error('last_name') ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Email</label>
                                        <input type="text" class="form-control <?php echo is_valid('email') ?>" name="email" value="<?php echo set_value('email', $member->email ?? "") ?>">
                                        <?php echo form_error('email') ?>
                                    </div>
                                </div>

                                <?php if (!empty($member)): ?>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Password</label>
                                            <a href="<?php echo base_url("admin/member/reset_password/{$member->id}") ?>" class="btn btn-secondary"><i class="fe fe-lock mr-2"></i>Reset Password</a>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="password">Password</label>
                                            <div class="form-control-plaintext text-muted">Password will be sent via email</div>
                                        </div>
                                    </div>
                                <?php endif ?>
                                <div class="col-md-12">
                                    <button class="btn btn-primary btn-block mt-5">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <?php if (!empty($member)): ?>
                        <form method="post" action="<?php echo base_url("admin/member/tag_add/{$member->id}") ?>">
                            <div class="card">
                                <div class="card-header">
                                    Tags
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="form-label">Member Tags</label>
                                        <div class="tags">
                                            <?php foreach ($member->tags as $tag): ?>
                                                <span class="tag tag-blue">
                                                    <?php echo ucwords($tag->label) ?> 
                                                    <?php if ($tag->assignable): ?>
                                                        <a href="<?php echo base_url("admin/member/tag_remove/{$member->id}/{$tag->id}") ?>" class="tag-addon"><i class="fe fe-x"></i></a>
                                                    <?php endif ?>
                                                </span>
                                            <?php endforeach ?>
                                        </div>

                                        <?php if (empty($member->tags)): ?>
                                            <small class="text-muted">Member does not have any tags</small>
                                        <?php endif ?>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Add Tag</label>
                                        <div class="input-group">
                                            <select name="tag_id" class="form-control custom-select">
                                                <?php foreach ($tags as $tag): ?>
                                                    <option value="<?php echo $tag->id ?>" <?php echo $tag->assignable ? null : "disabled" ?>><?php echo ucwords($tag->label) ?><?php echo $tag->assignable ? null : " (not assignable)" ?></option>
                                                <?php endforeach ?>
                                            </select>
                                            <span class="input-group-append">
                                                <button class="btn btn-primary" type="submit"><i class="fe fe-plus"></i> Add</button>
                                            </span>
                                        </div>
                                        <small class="form-text text-muted">Unassignable tags may be assigned on their specific platforms.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('admin/footer') ?>