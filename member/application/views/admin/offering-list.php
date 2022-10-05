<?php $this->load->view('admin/header') ?>
<?php $this->load->view('admin/menu') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">
                    Offerings
                </h1>
            </div>
            <div class="row">
                <div class="col">
                    <a href="<?php echo base_url('offering/submit') ?>" class="btn btn-primary mb-5 mr-3" target="_blank"><i class="fe fe-plus"></i> Create offering</a>
                    <a href="<?php echo base_url('admin/offering/queue') ?>" class="btn btn-secondary mb-5 mr-3"><i class="fe fe-inbox"></i> Approval Queue</a>
                    <a href="<?php echo base_url('admin/offering/settings') ?>" class="btn btn-secondary mb-5 mr-3"><i class="fe fe-settings"></i> Catalog Settings</a>
                    <a href="<?php echo base_url('admin/offering/all_columns') ?>" class="btn btn-secondary mb-5 mr-3"><i class="fe fe-database"></i> View Full Dataset</a>
                    <a href="<?php echo base_url('admin/offering/organization') ?>" class="btn btn-secondary mb-5 mr-3"><i class="fe fe-users"></i> View By Organization</a>
                    <a href="<?php echo base_url('offering') ?>" class="btn btn-secondary mb-5 mr-3" target="_blank">View Catalog <i class="fe fe-external-link"></i></a>
                    <?php alerts() ?>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title mr-auto">Offerings</h3>
                            <?php if (empty($this->input->get('past'))): ?>
                                <a href="<?php echo base_url("admin/offering/?past=true") ?>" class="btn btn-secondary btn-sm mr-3"><span><i class="fe fe-eye"></i> Include Expired</span></a>
                            <?php else: ?>
                                <a href="<?php echo base_url("admin/offering") ?>" class="btn btn-secondary btn-sm mr-3"><span><i class="fe fe-eye-off"></i> Exclude Expired</span></a>
                            <?php endif ?>
                            
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
                            <table class="table card-table table-hover data-table data-table-offerings">
                                <thead>
                                    <tr>
                                        <th class="pl-5">ID</th>
                                        <th>Title</th>
                                        <th>Organization</th>
                                        <th>Start Date</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($offerings as $offering) : ?>
                                        <tr>
                                            <td class="pl-5 text-muted"><?php echo $offering->id ?></td>
                                            <td><?php echo $offering->title ?></td>
                                            <td><?php echo $offering->organization ?></td>
                                            <td><?php echo $offering->start_date ?></td>
                                            <td class="text-right">
                                                <?php if ($offering->ose_submitted_offering_id): ?>
                                                    <a data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Omaha STEM Ecosystem Best Practices Assessment" href="<?php echo base_url("admin/offering/ose_assessment/{$offering->id}") ?>" class="btn btn-secondary btn-sm"><i class="fe fe-clipboard"></i> BPA</a>
                                                <?php else: ?>
                                                    <span data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Omaha STEM Ecosystem Best Practices Assessment (Not taken)">
                                                        <a href="" class="btn btn-secondary btn-sm disabled"><i class="fe fe-clipboard"></i> BPA</a>
                                                    </span>
                                                <?php endif ?>
                                                <a href="<?php echo base_url("admin/offering/edit/{$offering->id}") ?>" class="btn btn-secondary btn-sm"><i class="fe fe-edit-2"></i> Edit</a>
                                                <a href="<?php echo base_url("admin/offering/assign/{$offering->id}") ?>" class="btn btn-secondary btn-sm"><i class="fe fe-users"></i> Assign</a>
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