<?php $this->load->view('admin/header') ?>
<div class="container lesson">
    <div class="page-header">
        <h1 class="page-title">
            Students
            <small class="ml-2 text-muted"><?php echo $lesson->title ?></small>
        </h1>
    </div>
    <div class="row">

        <div class="col-lg-3 mb-4 lesson-side">
            <a href="<?php echo base_url("admin/lesson/view/{$lesson->id}") ?>" class="btn btn-secondary btn-block mb-3"><i class="fe fe-chevron-left mr-2"></i>Back to Lesson</a>

        </div>

        <div class="col-lg-9">
            <?php echo alerts() ?>
            <div class="card">
                <div class="card-header">
                    Started Lesson
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter table-hover">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th width="140">Status</th>
                                <th width="140">Started</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students_started as $student): ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo base_url("admin/student/view/{$student->student_id}") ?>"><?php echo display_name($student) ?></a>
                                    </td>
                                    <td><?php echo ucwords($student->status) ?></td>
                                    <td><?php echo date('j M, Y', strtotime($student->created)) ?></td>
                                </tr>
                            <?php endforeach ?>
                            <?php if (empty($students_started)): ?>
                                <tr>
                                    <td class="text-muted">No students</td>
                                </tr>
                            <?php endif ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    Finished Lesson
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter table-hover">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th width="140">Status</th>
                                <th width="140">Finished</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students_completed as $student): ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo base_url("admin/student/view/{$student->student_id}") ?>"><?php echo display_name($student) ?></a>
                                    </td>
                                    <td><?php echo ucwords($student->status) ?></td>
                                    <td><?php echo date('j M, Y', strtotime($student->created)) ?></td>
                                </tr>
                            <?php endforeach ?>
                            <?php if (empty($students_completed)): ?>
                                <tr>
                                    <td class="text-muted">No students</td>
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