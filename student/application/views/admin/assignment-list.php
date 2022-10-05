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
            <a href="<?php echo base_url("admin/assignment/student/{$lesson->id}") ?>" class="btn btn-secondary btn-block mb-3"><i class="fe fe-user mr-2"></i>View By Student</a>

        </div>

        <div class="col-lg-9">
            <?php echo alerts() ?>
            <div class="card">
                <div class="table-responsive">
                    <table class="table card-table table-vcenter table-hover">
                        <thead>
                            <tr>
                                <th>Assignment</th>
                                <th>Type</th>
                                <th width="120">Created</th>
                                <th class="text-center">Submissions</th>
                                <th width="220"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($assignments as $assignment): ?>
                                <tr>
                                    <td>
                                        <?php echo $assignment->label ?><br>
                                        <small class="text-muted"><?php echo $assignment->description ?></small>
                                    </td>
                                    <td><?php echo ucwords($assignment->type) ?></td>
                                    <td><?php echo date('j M, Y', strtotime($assignment->created)) ?></td>
                                    <td class="text-center"><a href="<?php echo base_url("admin/assignment/submissions/{$assignment->id}") ?>"><?php echo $assignment->count ?? 0 ?></a></td>

                                    <td class="text-right">
                                        <a href="<?php echo base_url("admin/assignment/edit/{$assignment->id}") ?>" class="btn btn-secondary btn-sm"><i class="fe fe-edit-2"></i> Edit</a>
                                        <a href="<?php echo base_url("admin/assignment/submissions/{$assignment->id}") ?>" class="btn btn-secondary btn-sm"><i class="fe fe-file"></i> Submissions</a>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                            <?php if (empty($assignments)): ?>
                                <tr>
                                    <td class="text-muted">No assignments</td>
                                </tr>
                            <?php endif ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('admin/footer') ?>