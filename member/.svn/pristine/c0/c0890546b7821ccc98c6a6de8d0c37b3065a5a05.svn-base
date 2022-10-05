<?php $this->load->view('admin/header') ?>
<?php $this->load->view('admin/menu') ?>
<?php 
$badges = array(
    'approved' => 'success',
    'pending' => 'info',
    'rejected' => 'danger'
);

?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">
                    Program Approval Queue
                </h1>
            </div>
            <div class="row">
                <div class="col">
                    <a href="<?php echo base_url('admin/program') ?>" class="btn btn-secondary mb-5"><i class="fe fe-chevron-left"></i>  Back to Programs</a>
                    <?php alerts() ?>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title mr-auto">Programs</h3>
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
                            <table class="table card-table table-hover data-table data-table-program-queue">
                                <thead>
                                    <tr>
                                        <th class="pl-5">Name</th>
                                        <th>Category</th>
                                        <th>Submitted</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($submissions as $submission) : ?>
                                        <tr>
                                            <td class="pl-5">
                                                <?php echo $submission->program_name ?>
                                            </td>
                                            <td><?php echo ucwords($submission->program_category) ?></td>
                                            <td>
                                                <?php echo $submission->created ?>
                                            </td>
                                            <td><div class="badge badge-<?php echo $badges[$submission->status] ?? null ?>"><?php echo ucwords($submission->status) ?></div></td>
                                            <td class="text-right">
                                                <?php if ($submission->status == 'approved'): ?>
                                                    <a href="<?php echo base_url("admin/program/edit/{$submission->approved_program_id}") ?>" class="btn btn-secondary btn-sm"><i class="fe fe-edit-2"></i> View Program</a>
                                                <?php else: ?>
                                                    <a href="<?php echo base_url("admin/program/review/{$submission->id}") ?>" class="btn btn-secondary btn-sm"><i class="fe fe-eye"></i> Review</a>
                                                <?php endif ?>
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