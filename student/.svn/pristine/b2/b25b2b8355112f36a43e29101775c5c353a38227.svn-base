<h5>Assignment: <?php echo $lesson->title ?></h5>
<span class="text-muted">Submitted by <?php echo display_name($student) ?> on <?php echo date('j M, Y', strtotime($submissions[0]->created)) ?></span>
<?php foreach ($submissions as $submission): ?>
    <h5 class="m-0 mt-5 mb-2"><?php echo $submission->label ?></h5>
    <div class="text-muted"><?php echo $submission->description ?></div>
    <?php if (!empty($submission->submission->data)): ?>
        <?php if ($submission->type == 'upload'): ?>
            <a href="<?php echo base_url("lesson/download_assignment/{$submission->id}/{$student->id}") ?>" class="btn btn-secondary mt-3"><i class="fe fe-download mr-2"></i>Download</a>
        <?php else: ?>
            <?php echo ucwords($submission->submission->data ?? null) ?>
        <?php endif ?>
    <?php else: ?>
        <i class="text-muted">No submission</i>
    <?php endif ?>
<?php endforeach ?>