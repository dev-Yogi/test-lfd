<?php $this->load->view('admin/header') ?>
<div class="container lesson">
    <div class="page-header">
        <h1 class="page-title">
            Assignment
            <small class="ml-2 text-muted"><?php echo $lesson->title ?></small>
        </h1>
    </div>
    <div class="row">

        <div class="col-lg-3 mb-4 lesson-side">
            <a href="<?php echo base_url("admin/assignment/student/{$lesson->id}") ?>" class="btn btn-secondary btn-block mb-3"><i class="fe fe-chevron-left mr-2"></i>Back to Assignments</a>
            <div class="card card-complete">
                <div class="card-header">Complete Lesson</div>
                <div class="card-body text-center">
                    <?php if (($lesson->status ?? null) == 'complete'): ?>
                    <span class="text-muted">Complete</span><i class="ml-2 fe fe-check text-success font-weight-bold"></i>
                    <?php else: ?>
                        <p>To mark the lesson complete for this student, click the button below.</p>
                        <a href="<?php echo base_url("admin/assignment/complete/{$lesson->id}/{$student->id}") ?>" class="btn btn-primary btn-block"><i class="fe fe-check mr-2"></i>Complete Lesson</a>
                    <?php endif ?>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <?php echo alerts() ?>
            <div class="card">
                <div class="card-body p-lg-5 m-lg-5">
                    <h1><?php echo display_name($student) ?></h1>
                    <span class="text-muted">Submitted <?php echo date('j M, Y', strtotime($submissions[0]->created)) ?></span>
                    <hr>
                    <?php foreach ($submissions as $submission): ?>
                        <h5 class="m-0 mt-5 mb-2"><?php echo $submission->label ?></h5>
                        <div class="text-muted"><?php echo $submission->description ?></div>
                        <?php if (!empty($submission->submission->data)): ?>
                            <?php if ($submission->type == 'upload'): ?>
                                <a href="<?php echo base_url("lesson/download_assignment/{$submission->id}/{$student->id}") ?>" class="btn btn-secondary mt-3"><i class="fe fe-download mr-2"></i>Download</a>
                            <?php else: ?>
                                <?php echo nl2br($submission->submission->data ?? null) ?>
                            <?php endif ?>
                        <?php else: ?>
                            <i class="text-muted">No submission</i>
                        <?php endif ?>
                    <?php endforeach ?>
                </div>
            </div>
            <?php if ($message): ?>
                <?php foreach($message->replies as $reply): ?>
                    <a name="<?php echo $reply->id ?>"></a>
                    <div class="card post mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="font-weight-semibold pt-2"><?php echo $reply->first_name . " " . $reply->last_name ?></div>
                                </div>
                                <div class="col-lg-9 pt-1">
                                    <?php if (!$reply->removed): ?>
                                        <?php echo nl2br($reply->message) ?>
                                    <?php else: ?>
                                        <i class="text-muted">Post has been removed</i>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0 border-0">
                            <div class="row">
                                <div class="col-lg-9 offset-3 d-flex">
                                    <div class="small text-muted">Sent <?php echo date('d/m/Y H:ia', strtotime($reply->created)) ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            <?php endif ?>
            <div class="card post">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3">
                        </div>
                        <div class="col-lg-9">
                            <form method="post" action="<?php echo base_url("admin/assignment/submit_feedback/{$lesson->id}/{$student->id}") ?>">
                                <div class="form-group">
                                    <label class="form-label">Post feedback to student</label>
                                    <textarea type="text" rows="5" class="form-control" name="message"></textarea>
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="Post Reply" class="btn btn-primary">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('admin/footer') ?>