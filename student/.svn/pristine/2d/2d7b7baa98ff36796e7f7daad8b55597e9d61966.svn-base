<?php $this->load->view('admin/header') ?>
<div class="container lesson">
    <div class="page-header">
        <h1 class="page-title">
            Videos
            <small class="ml-2 text-muted"><?php echo $lesson->title ?></small>
        </h1>
    </div>
    <div class="row">

        <div class="col-lg-3 mb-4 lesson-side">
            <a href="<?php echo base_url("admin/lesson/view/{$lesson->id}") ?>" class="btn btn-secondary btn-block mb-3"><i class="fe fe-chevron-left mr-2"></i>Back to Lesson</a>
            <a href="<?php echo base_url("admin/video/create/{$lesson->id}") ?>" class="btn btn-primary btn-block mb-3"><i class="fe fe-plus mr-2"></i>New Video</a>
        </div>

        <div class="col-lg-9">
            <?php echo alerts() ?>
            <div class="card">
                <div class="table-responsive">
                    <table class="table card-table table-vcenter table-hover">
                        <thead>
                            <tr>
                                <th>Video</th>
                                <th>Link</th>
                                <th width="150">Start Time</th>
                                <th width="120">Created</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($videos as $video): ?>
                                <tr>
                                    <td>
                                        <?php echo $video->label ?><br>
                                        <small class="text-muted"><?php echo $video->description ?></small>
                                    </td>
                                    <td><a href="<?php echo $video->url ?>" target="_blank" class="btn btn-secondary btn-sm">Open Video<i class="fe fe-external-link ml-2"></i></a></td>
                                    <?php if ($video->start_time): ?>
                                        <td><?php echo date('j M, Y H:iA', strtotime($video->start_time)) ?></td>
                                    <?php else: ?>
                                        <td class="text-muted">No Start time</td>
                                    <?php endif ?>
                                    <td><?php echo date('j M, Y', strtotime($video->created)) ?></td>
                                    <td class="text-right">
                                        <a href="<?php echo base_url("admin/video/edit/{$video->id}") ?>" class="btn btn-secondary btn-sm"><i class="fe fe-edit-2"></i> Edit</a>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                            <?php if (empty($videos)): ?>
                                <tr>
                                    <td class="text-muted">No videos</td>
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