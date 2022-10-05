<?php $this->load->view('admin/header') ?>
<?php $this->load->view('admin/menu') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">
                    Offerings by <?php echo $this->input->get('organization') ?>
                </h1>
            </div>
            <div class="row">
                <div class="col">
                    <a href="<?php echo base_url('admin/offering/organization') ?>" class="btn btn-secondary mb-5"><i class="fe fe-chevron-left"></i> Back</a>
                    <?php alerts() ?>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title mr-auto">Offerings by <?php echo $this->input->get('organization') ?></h3>
                            
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
                                                <a href="<?php echo base_url("admin/offering/edit/{$offering->id}") ?>" class="btn btn-secondary btn-sm"><i class="fe fe-edit-2"></i> Edit</a>
                                            </td>
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