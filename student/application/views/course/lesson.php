<?php $this->load->view('header') ?>

<div class="container lesson">
    <div class="page-header">
        <h1 class="page-title">
            Lesson: <?php echo $lesson->title ?>
            <small class="ml-2 text-muted"><?php echo $course->name ?></small>
        </h1>
    </div>
    <div class="row">
        <div class="col-lg-3">
            <a href="<?php echo base_url("course/lessons/{$lesson->course_id}") ?>" class="btn btn-secondary btn-block mb-3"><i class="fe fe-chevron-left"></i> Back to Lessons</a>
            <div class="card course-details text-dark d-none d-md-block">
                <div class="card-header bg-cyan-light" style="background-image: url(<?php echo course_image_url($course->image ?? '') ?>);"></div>
                <div class="card-body d-flex flex-column">
                    <p>Course:<br><b><?php echo $course->name ?></b></p>
                    <div class="progress progress-sm">
                        <div class="progress-bar" role="progressbar" style="width: <?php echo get_lesson_position($course->lessons, $lesson->order) ?>%" aria-valuenow="42" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <small class="mb-3 text-muted">Lesson <?php echo $lesson->order ?> of <?php echo count($course->lessons) ?></small>
                    <p><?php echo $course->description ?></p>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <?php echo alerts() ?>
            <!-- LESSON CONTENT -->
            <div class="card">
                <div class="lesson-body card-body">
                    <h1><?php echo $lesson->title ?></h1>
                    <?php echo $lesson->content ?>
                </div>
            </div>

            <!-- LESSON VIDEOS -->
            <?php if ($videos): ?>
                <div class="card">
                    <div class="card-header"><i class="fe fe-film mr-2 text-muted"></i>Video</div>
                    <table class="table card-table table-vcenter">
                        <?php foreach ($videos as $video): ?>
                            <?php if ($video->start_time): ?>
                                <?php if (substr($video->url, 0, 4) != 'http') $video->url = 'https://' . $video->url ?>
                                <tr>
                                    <td colspan="2">
                                        <div class="text-uppercase font-weight-semibold text-muted my-2">Live Video Conference</div>
                                        <h6><?php echo $video->label ?></h6>
                                        <div><?php echo $video->description ?></div>
                                        <h4 class="font-weight-normal py-3"><i class="fe fe-clock mr-2 text-muted"></i><b><?php echo date('j F, Y g:iA', strtotime($video->start_time)) ?> - <?php echo date('g:iA', strtotime($video->start_time)) ?> CST</b> <span class="text-muted">(<?php echo timespan(strtotime('now'), strtotime($video->start_time), 1) ?>)</span></h4>
                                        <?php if (strtotime('-4 hours') < strtotime($video->start_time)): ?>
                                        <a href="<?php echo $video->url ?>" class="btn btn-primary" target="_blank"><i class="fe fe-log-in mr-2"></i>Enter Video Lecture</a>
                                        <?php else: ?>
                                            <a href="<?php echo $video->url ?>" class="btn btn-secondary disabled" target="_blank"><i class="fe fe-log-in mr-2"></i>Conference Ended</a>
                                        <?php endif ?>
                                    </td>
                                </tr>
                                <?php else: ?>
                                    <tr>
                                        <td width="100">
                                            <a href="<?php echo $video->url ?>" target="_blank" class="btn btn-primary btn-lg"><i class="fe fe-play d-block"></i>Play</a>
                                        </td>
                                        <td>
                                            <h6><?php echo $video->label ?></h6>
                                            <div class="text-muted"><?php echo $video->description ?></div>
                                        </td>
                                    </tr>
                                <?php endif ?>
                            <?php endforeach ?>
                        </table>   
                    </div>
                <?php endif ?>

                <!-- LESSON ASSIGNMENTS -->
                <?php if ($assignments): ?>
                    <div class="card">
                        <div class="card-header"><i class="fe fe-edit mr-2 text-muted"></i>Assignment</div>
                        <div class="card-body">
                            <?php if (empty($assignment_submission)): ?>
                                <form method="post" id="assignment" action="<?php echo base_url("lesson/submit_assignment/{$lesson->id}") ?>" enctype="multipart/form-data">
                                    <?php foreach ($assignments as $assignment): ?>
                                        <div class="form-group">
                                            <div class="form-label"><?php echo $assignment->label ?></div>
                                            <?php if ($assignment->type == 'text'): ?>
                                                <input type="text" name="<?php echo $assignment->id ?>" class="form-control <?php echo is_valid($assignment->id) ?>" value="<?php echo set_value($assignment->id) ?>">
                                                <?php echo form_error($assignment->id) ?>
                                            <?php elseif ($assignment->type == 'textarea'): ?>
                                                <textarea class="form-control <?php echo is_valid($assignment->id) ?>" rows="6" name="<?php echo $assignment->id ?>"><?php echo set_value($assignment->id) ?></textarea>
                                                <?php echo form_error($assignment->id) ?>
                                            <?php elseif ($assignment->type == 'upload'): ?>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input <?php echo is_valid($assignment->id) ?>" name="<?php echo $assignment->id ?>">
                                                    <label class="custom-file-label">Choose file</label>
                                                    <?php echo form_error($assignment->id) ?>
                                                </div>
                                                <small class="form-text text-muted">Document and Image files only. Maximum size 50MB.</small>
                                            <?php endif ?>
                                            <small class="form-text text-muted"><?php echo $assignment->description ?></small>
                                        </div>
                                    <?php endforeach ?>

                                    <div class="progress mb-3 d-none">
                                        <div class="progress-bar" style="width: 38%" id="upload_progress" role="progressbar" aria-valuenow="38" aria-valuemin="0" aria-valuemax="100">
                                            <span class="visually-hidden">Uploading file ...</span>
                                        </div>
                                    </div>
                                    <input type="submit" class="btn btn-primary" value="Submit Assignment">
                                </form>
                            <?php else: ?>
                                <p class="text-muted">Below is your current submission. To submit again, click the "Re-submit Assignment" button.</p>
                                <?php foreach ($assignment_submission as $submission): ?>
                                    <h5 class="m-0 mt-5 mb-2"><?php echo $submission->label ?></h5>
                                    <div class="text-muted"><?php echo $submission->description ?></div>
                                    <?php if (!empty($submission->submission->data)): ?>
                                        <?php if ($submission->type == 'upload'): ?>
                                            <a href="<?php echo base_url("lesson/download_assignment/{$submission->id}/{$this->member->id}") ?>" class="btn btn-secondary mt-3"><i class="fe fe-download mr-2"></i>Download</a>
                                        <?php else: ?>
                                            <div class="my-2"><?php echo nl2br($submission->submission->data ?? null) ?></div>
                                        <?php endif ?>
                                    <?php else: ?>
                                        <i class="text-muted">No submission</i>
                                    <?php endif ?>
                                <?php endforeach ?>
                                <hr>
                                <a href="<?php echo base_url("lesson/submit_assignment/{$lesson->id}") ?>" class="btn btn-primary">Re-submit Assignment<i class="fe fe-arrow-right ml-2"></i></a>
                            <?php endif ?>
                        </div>
                    </div>
                <?php endif ?>
                <?php if (empty($assignments)): ?>
                    <!-- LESSON COMPLETION -->
                    <div class="card card-complete">
                        <div class="card-header bg-cyan text-light"><i class="fe fe-file mr-2"></i>Complete Lesson</div>
                            <div class="card-body text-center">
                                <?php if (($lesson->status ?? null) == 'complete'): ?>
                                    <span class="text-muted">Complete</span><i class="ml-2 fe fe-check text-success font-weight-bold"></i>
                                <?php else: ?>
                                    <p>If you have completed this lesson, click the button below to mark it as complete. <!-- <a href="">More information about lesson completion <i class="fe fe-external-link mr-2 text-muted"></i></a> --></p>
                                    <a href="<?php echo base_url("lesson/complete/{$lesson->id}") ?>" class="btn btn-primary btn-lg"><i class="fe fe-check mr-2"></i>I have completed this lesson</a>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- LESSON COMPLETION -->
                    <div class="card">
                        <div class="card-header"><i class="fe fe-file mr-2"></i>Complete Lesson</div>
                        <div class="card-body">
                            <?php if (($lesson->status ?? null) == 'complete'): ?>
                                <span class="text-muted">Complete</span><i class="ml-2 fe fe-check text-success font-weight-bold"></i>
                            <?php else: ?>
                                <p>The instructor will mark this lesson as complete after reviewing your assignment above.</p>
                            <?php endif ?>
                        </div>
                    </div>
                <?php endif ?>
                
                <div>
                    <a href="<?php echo base_url("course/lessons/{$lesson->course_id}") ?>" class="btn btn-secondary"><i class="fe fe-chevron-left mr-2"></i>Back to Lessons</a>
                </div>
                </div>
            </div>


<?php $script = <<<'EOT'
$('.custom-file-input').bind('change', function() {
if (this.files[0].size / 1000 > 200000) {
alert("The file you have selected is too large. Please upload a file less under 200MB.");
this.value = '';
}
});
EOT;
?>
<?php $this->load->view('footer', compact('script')) ?>
