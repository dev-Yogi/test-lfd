<?php $this->load->view('admin/header') ?>
<?php $this->load->view('admin/menu') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">
                    Staff Users
                </h1>
            </div>
            
            <?php alerts() ?>
            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            Staff
                        </div>
                        <a href="<?php echo base_url('employer/settings/user') ?>" class="btn btn-secondary m-5">Manage Staff Users <i class="fe fe-arrow-right float-right mt-1"></i></a>


                        <table class="table table-card m-0">
                            <tr>
                                <th class="pl-5">Name</th>
                            </tr>
                            <?php foreach ($staff as $user): ?>
                            <tr>
                                <td class="pl-5"><?php echo get_user_name($user->id) ?></td>
                            </tr>
                            <?php endforeach ?>

                            <?php foreach ($managers as $user): ?>
                            <tr>
                                <td class="pl-5"><?php echo get_user_name($user->id) ?></td>
                            </tr>
                            <?php endforeach ?>

                            <?php foreach ($admins as $user): ?>
                            <tr>
                                <td class="pl-5"><?php echo get_user_name($user->id) ?></td>
                            </tr>
                            <?php endforeach ?>
                        </table>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            Managers
                        </div>
                        <form method="post" class="card-body">
                            <div class="form-group">
                                <div class="input-group">
                                    <select class="form-control <?php echo is_valid('member_id') ?>" name="member_id">
                                        <option>Select user</option>
                                        <?php foreach ($users as $user): ?>
                                            <option value="<?php echo $user->id ?>"><?php echo get_user_name($user->id) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                    <input type="hidden" name="role_id" value="4">
                                    <span class="input-group-append">
                                        <button class="btn btn-primary <?php echo disable_below_role(Role::MANAGER) ?>" type="submit">Add</button>
                                    </span>
                                </div>
                            </div>
                        </form>


                        <table class="table table-card m-0">
                            <tr>
                                <th class="pl-5">Name</th>
                                <th></th>
                            </tr>
                            <?php foreach ($managers as $user): ?>
                            <tr>
                                <td class="pl-5"><?php echo get_user_name($user->id) ?></td>
                                <td class="text-right pr-5"><a href="<?php echo base_url("admin/dashboard/staff_users_demote/{$user->id}") ?>" onclick="return confirm('Are you sure you want to remove this product?')" class="btn btn-secondary btn-sm <?php echo disable_below_role(Role::MANAGER) ?>"><i class="fe fe-arrow-down"></i> Demote</a></td>
                            </tr>
                            <?php endforeach ?>
                        </table>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            Admins
                        </div>
                        <form method="post" class="card-body">
                            <div class="form-group">
                                <div class="input-group">
                                    <select class="form-control <?php echo is_valid('member_id') ?>" name="member_id">
                                        <option>Select user</option>
                                        <?php foreach ($users as $user): ?>
                                            <option value="<?php echo $user->id ?>"><?php echo get_user_name($user->id) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                    <input type="hidden" name="role_id" value="5">
                                    <span class="input-group-append">
                                        <button class="btn btn-primary <?php echo disable_below_role(Role::MANAGER) ?>" type="submit">Add</button>
                                    </span>
                                </div>
                            </div>
                        </form>


                        <table class="table table-card m-0">
                            <tr>
                                <th class="pl-5">Name</th>
                                <th></th>
                            </tr>
                            <?php foreach ($admins as $user): ?>
                            <tr>
                                <td class="pl-5"><?php echo get_user_name($user->id) ?></td>
                                <td class="text-right pr-5"><a href="<?php echo base_url("admin/dashboard/staff_users_demote/{$user->id}") ?>" onclick="return confirm('Are you sure you want to remove this product?')" class="btn btn-secondary btn-sm"><i class="fe fe-arrow-down"></i> Demote</a></td>
                            </tr>
                            <?php endforeach ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php $this->load->view('admin/footer') ?>