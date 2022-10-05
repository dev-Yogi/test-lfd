<?php $this->load->view('admin/header') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="page-header">
                        <h1 class="page-title">
                            <?php echo "Assignments" ?>
                        </h1>
                    </div>
                </div>
                <div class="col-lg-3">
                    <a href="<?php echo base_url("admin/lesson/view/{$lesson->id}") ?>" class="btn btn-secondary btn-block mb-3"><i class="fe fe-chevron-left mr-2"></i>Back to Lesson</a>
                </div>
                <div class="col-lg-9">
                    <?php echo alerts() ?>
                    <div class="card">
                        <div class="card-header">Assignments <a href="<?php echo base_url("admin/assignment/create/{$lesson->course_id}/{$lesson->id}")?>" class="btn btn-primary ml-auto"><i class="mr-2 fe fe-plus"></i>New Assignment</a></div>
                        <table class="table card-table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Posted</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($assignments as $assignment): ?>
                                    <tr>
                                        <td><?php echo $assignment->title ?></td>
                                        <td><?php echo $assignment->created ?></td>
                                        <td class="text-right">
                                            <a href="<?php echo base_url("admin/assignment/edit/{$assignment->course_id}/{$assignment->lesson_id}/{$assignment->id}/") ?>" class="btn btn-secondary btn-sm"><i class="fe fe-edit-2"></i> Edit</a>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                                <?php if (empty($assignments)): ?>
                                    <tr>
                                        <td colspan="5" class="text-muted">No Assignment - click "New Assignment" to add a assignment</td>
                                    </tr>
                                <?php endif ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('admin/footer') ?>