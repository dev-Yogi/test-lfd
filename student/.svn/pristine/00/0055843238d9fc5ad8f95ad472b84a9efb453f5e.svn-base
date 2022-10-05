<?php $this->load->view('admin/header') ?>
<div class="container lesson">
    <div class="page-header">
        <h1 class="page-title">
            Assignments
            <small class="ml-2 text-muted"><?php echo $lesson->title ?></small>
        </h1>
    </div>
    <div class="row">

        <div class="col-lg-3 mb-4 lesson-side">
            <a href="<?php echo base_url("admin/lesson/view/{$lesson->id}") ?>" class="btn btn-secondary btn-block mb-3"><i class="fe fe-chevron-left mr-2"></i>Back to Lesson</a>
            <a href="<?php echo base_url("admin/assignment/create/{$lesson->id}") ?>" class="btn btn-primary btn-block mb-3"><i class="fe fe-plus mr-2"></i>New Assignment</a>
        </div>

        <div class="col-lg-9">
            <?php echo alerts() ?>
            <div class="card">
                <div class="table-responsive">
                    <table class="table card-table table-hover">
                        <thead>
                            <tr>
                                <th width="180">Name</th>
                                <th>Submitted</th>
                                <th width="120"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($assignments as $submission): ?>
                                <tr>
                                    <td>
                                        <?php echo display_name($submission) ?><br>
                                    </td>
                                    <td><?php echo date('j M, Y', strtotime($submission->created)) ?></td>

                                    <td class="text-right">
                                        <a href="<?php echo base_url("admin/assignment/student/{$lesson->id}/{$submission->student_id}") ?>" class="btn btn-secondary btn-sm"><i class="fe fe-file"></i> View</a>
                                    </td>
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