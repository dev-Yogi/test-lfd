<?php $this->load->view('admin/header') ?>
<?php $this->load->view('admin/menu') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="page-header">
                        <h1 class="page-title">
                            Assign <span class="text-muted small"><span class="p-2">&middot;</span> <?php echo $offering->title ?></span>
                        </h1>
                    </div>

                    <div class="row">
                        <div class="col">
                            <?php echo alerts() ?>
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title mr-auto">Assigned Members</h3>
                                    <!-- <div class="pr-0">
                                        <form class="input-icon">
                                            <input type="search" class="form-control header-search data-table-search" placeholder="Search…" tabindex="1">
                                            <div class="input-icon-addon">
                                                <i class="fe fe-search"></i>
                                            </div>
                                        </form>
                                    </div> -->
                                </div>
                                <div class="table-responsive">
                                    <table class="table card-table table-vcenter table-hover data-table data-table-offering-assigned">
                                        <thead>
                                            <tr>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($assigned as $member): ?>
                                                <tr>
                                                    <td><?php echo $member->first_name ?></td>
                                                    <td><?php echo $member->last_name ?></td>
                                                    <td class="text-right">
                                                        <a href="<?php echo base_url("admin/offering/unassign/{$offering->id}/{$member->id}") ?>" onclick="return confirm('Are you sure you want unassign this user?')" class="btn btn-sm btn-secondary float-right mr-3"><i class="fe fe-user-minus"></i> Unassign</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach ?>
                                            <?php if (empty($assigned)): ?>
                                                <tr>
                                                    <td colspan="4" class="text-muted">There are no assigned users to this offering</td>
                                                </tr>
                                            <?php endif ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                    <form method="post">
                        <div class="card p-5">
                            <div class="row">
                                <div class="col">
                                    <form method="post">
                                        <div class="form-group">
                                            <label class="form-label">Assign Member</label>
                                            <select id="member_id" class="selectize form-control <?php echo is_valid('member_id') ?>" name="member_id">
                                                <option value="">Start typing member name ...</option>
                                                <?php foreach ($members as $member) : ?>
                                                    <option 
                                                        value="<?php echo $member->id ?>" 
                                                        data-first_name="<?php echo $member->first_name ?>" 
                                                        data-last_name="<?php echo $member->last_name ?>" 
                                                        data-email="<?php echo $member->email ?>" 
                                                        <?php echo set_select('member_id') ?> >
                                                        <?php echo $member->last_name ?>, <?php echo $member->first_name ?> (<?php echo $member->email ?>)
                                                    </option>
                                                <?php endforeach ?>
                                            </select>
                                            <?php echo form_error('member_id') ?>
                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-primary">Assign Member</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </form>

            </div>
        </div>
    </div>
</div>
<?php $this->load->view('admin/footer') ?>