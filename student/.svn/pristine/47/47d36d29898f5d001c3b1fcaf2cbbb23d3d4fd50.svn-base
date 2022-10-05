<?php $this->load->view('header') ?>

<div class="container lesson">
    <div class="page-header">
        <h1 class="page-title">
            Lesson: <?php echo $lesson->title ?>
            <small class="ml-2 text-muted"><?php echo $course->name ?></small>
        </h1>
    </div>
    <div class="row">
        <div class="col-lg-8">


            <div class="mb-3">
                <a href="<?php echo base_url("lesson/view/{$lesson->id}") ?>" class="btn btn-secondary"><i class="fe fe-chevron-left mr-2"></i>Back to Lesson</a>
            </div>

            <!-- LESSON ASSIGNMENTS -->
            <?php if ($assignments): ?>
                <div class="card">
                    <div class="card-header"><i class="fe fe-edit mr-2 text-muted"></i>Assignment</div>
                    
                    <?php if ($has_submitted): ?>
                        <div class="card-alert alert alert-info mb-0 show">
                            Re-submitting will overwrite your previous submission.
                        </div>
                    <?php endif ?>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-8">
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
                                    <a href="<?php echo base_url("lesson/view/{$lesson->id}") ?>" class="btn btn-secondary ml-2">Cancel</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif ?>
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
