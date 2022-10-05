<?php $this->load->view('admin/header') ?>
<?php $this->load->view('admin/menu') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">
                    EOC Submissions
                </h1>
            </div>
            <div class="row">
                <div class="col">
                    <?php alerts() ?>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title mr-auto">EOC Forms</h3>
                            
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
                            <table class="table card-table table-hover data-table data-table-eoc">
                                <thead>
                                    <tr>
                                        <th class="pl-5">ID</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Submitted</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($submissions as $submission) : ?>
                                        <tr>
                                            <td class="pl-5 text-muted"><?php echo $submission->id ?></td>
                                            <td><?php echo $submission->first_name ?></td>
                                            <td><?php echo $submission->last_name ?></td>
                                            <td><?php echo $submission->email ?></td>
                                            <td><?php echo date('j M, Y', strtotime($submission->created)) ?></td>
                                            <td class="text-right">
                                                <a href="<?php echo base_url("admin/eoc/view/{$submission->id}") ?>" class="btn btn-secondary btn-sm"><i class="fe fe-eye"></i> View</a>
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