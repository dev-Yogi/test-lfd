<?php $this->load->view('admin/header') ?>
<div class="container lesson">
    <div class="page-header">
        <h1 class="page-title">
            <?php echo $assignment->label ?>
            <small class="ml-2 text-muted">Assignments</small>
        </h1>
    </div>
    <div class="row">

        <div class="col-lg-3 mb-4 lesson-side">
            <a href="<?php echo base_url("admin/assignment/list/{$lesson->id}") ?>" class="btn btn-secondary btn-block mb-3"><i class="fe fe-chevron-left mr-2"></i>Back to Assignments</a>
        </div>

        <div class="col-lg-9">
            <?php echo alerts() ?>
            <div class="card">
                <div class="table-responsive">
                    <table class="table card-table table-hover">
                        <thead>
                            <tr>
                                <th width="180">Name</th>
                                <th>Submission</th>
                                <th width="120">Submitted</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($assignments as $submission): ?>
                                <tr>
                                    <td>
                                        <?php echo display_name($submission) ?><br>
                                    </td>
                                    <?php if ($assignment->type == 'upload'): ?>
                                        <td><a href="<?php echo base_url("lesson/download_assignment/{$assignment->id}/{$submission->student_id}") ?>" class="btn btn-secondary btn-sm"><i class="fe fe-download mr-2"></i>Download</a></td>
                                    <?php else: ?>
                                        <td><?php echo ucwords($submission->data) ?></td>
                                    <?php endif ?>
                                    <td><?php echo date('j M, Y', strtotime($submission->created)) ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('admin/footer') ?>