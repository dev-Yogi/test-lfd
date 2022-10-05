<?php $this->load->view('admin/header') ?>
<?php $this->load->view('admin/menu') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">
                    Members
                </h1>
            </div>
            <div class="row">
                <div class="col">
                    <?php echo alerts() ?>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title mr-auto">Members</h3>
                            <div class="pr-0">
                                <form class="input-icon">
                                    <input type="search" class="form-control header-search data-table-search" placeholder="Search…" tabindex="1">
                                    <div class="input-icon-addon">
                                        <i class="fe fe-search"></i>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table card-table table-hover data-table data-table-members">
                                <thead>
                                    <tr>
                                        <th class="pl-5">ID</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Created</th>
                                        <th>Last Login</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($members as $member) : ?>
                                        <tr>
                                            <td class="pl-5 text-muted"><?php echo $member->id ?></td>
                                            <td><?php echo $member->first_name ?></td>
                                            <td><?php echo $member->last_name ?></td>
                                            <td><?php echo $member->email ?></td>
                                            <td class="text-right">
                                                <?php echo $member->created ?>
                                            </td>
                                            <td class="text-right">
                                                <?php echo $member->last_login ?>
                                            </td>
                                            <td class="text-right">
                                                <a href="<?php echo base_url("admin/member/edit/{$member->id}") ?>" class="btn btn-secondary btn-sm"><i class="fe fe-edit-2"></i> Edit</a>
                                            </td>
                                            </a>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="data-table-pagination float-left"></div>
                    <div class="data-table-records-info d-none"></div>
                    <div class="data-table-show-per-page float-right"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('admin/footer') ?>