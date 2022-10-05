<?php $this->load->view('admin/header') ?>
<?php $this->load->view('admin/menu') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">
                    Organizations
                </h1>
            </div>
            <div class="row">
                <div class="col">
                    <a href="<?php echo base_url('admin/offering') ?>" class="btn btn-secondary mb-5"><i class="fe fe-chevron-left"></i> Back</a>
                    <?php alerts() ?>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title mr-auto">Organization</h3>
                            
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
                            <table class="table card-table table-hover data-table">
                                <thead>
                                    <tr>
                                        <th class="pl-5">Organization</th>
                                        <th>Offerings</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($organizations as $organization) : ?>
                                        <tr>
                                            <td><?php echo $organization->organization ?></td>
                                            <td><?php echo $organization->count ?></td>
                                            <td class="text-right">
                                                <a href="<?php echo base_url("admin/offering/organization/?organization={$organization->organization}") ?>" class="btn btn-secondary btn-sm"><i class="fe fe-eye"></i> View</a>
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