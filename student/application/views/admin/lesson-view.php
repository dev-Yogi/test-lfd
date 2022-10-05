<?php $this->load->view('admin/header') ?>
<div class="container lesson">
    <div class="page-header">
        <h1 class="page-title">
            <?php echo $lesson->title ?>
            <small class="ml-2 text-muted">Lesson</small>
        </h1>
    </div>
    <div class="row">
        <div class="col-lg-3 mb-4 lesson-side">       
            <a href="<?php echo base_url("admin/course/view/{$lesson->course_id}") ?>" class="btn btn-secondary btn-block mb-3"><i class="fe fe-chevron-left mr-2"></i>Back to Lessons</a>
            <a href="<?php echo base_url("admin/lesson/edit/{$lesson->id}") ?>" class="btn btn-primary btn-block mb-3"><i class="fe fe-edit-2 mr-2"></i>Edit</a>
            <div class="card">
                <table class="table card-table table-vcenter table-sm">
                    <tbody>
                        <tr>
                            <th>Length</th>
                            <td><?php echo time_length_label($lesson->length) ?></td>
                        </tr>
                        <tr>
                            <th>Started</th>
                            <td><a href="<?php echo base_url("admin/lesson/students/{$lesson->id}") ?>"><?php echo $count_students_started ?> students</a></td>
                        </tr>
                        <tr>
                            <th>Completed</th>
                            <td><a href="<?php echo base_url("admin/lesson/students/{$lesson->id}") ?>"><?php echo $count_students_completed ?> students</a></td>
                        </tr>
                        <tr>
                            <th>Created</th>
                            <td><?php echo date('j M, Y', strtotime($lesson->created)) ?></td>
                        </tr>
                        <tr>
                            <th>Modified</th>
                            <?php if ($lesson->modified): ?>
                                <td><?php echo date('j M, Y H:iA', strtotime($lesson->modified)) ?></td>
                            <?php else: ?>
                                <td>-</td>
                            <?php endif ?>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card">
                <div class="card-header d-flex">
                    <div>Assignments</div>
                    <a href="<?php echo base_url("admin/assignment/student/{$lesson->id}") ?>" class="btn btn-secondary btn-sm ml-auto">Manage <i class="fe fe-chevron-right"></i></a>
                </div>
                <?php if (!empty($assignments)): ?>
                    <div class="card-body">
                        <?php foreach ($assignments as $key => $assignment): ?>
                            <h6><?php echo $assignment->label ?></h6>
                            <small class="text-muted"><?php echo $assignment->count ?? 0 ?> submissions</small>
                            <?php if (count($assignments) > 1 && $key != count($assignments) - 1): ?>
                                <hr class="my-3">
                            <?php endif ?>
                        <?php endforeach ?>
                    </div>
                <?php endif ?>
            </div>
            
            <div class="card">
                <div class="card-header d-flex">
                    <div>Videos</div>
                    <a href="<?php echo base_url("admin/video/list/{$lesson->id}") ?>" class="btn btn-secondary btn-sm ml-auto">Manage <i class="fe fe-chevron-right"></i></a>
                </div>
                <?php if (!empty($videos)): ?>
                    <div class="card-body">
                        <?php foreach ($videos as $key => $video): ?>
                            <h6><?php echo $video->label ?></h6>
                            <?php if ($video->start_time): ?>
                                <small class="text-muted">Starts <?php echo date('j M, Y H:iA', strtotime($video->start_time)) ?></small>
                            <?php endif ?>
                            <?php if (count($videos) > 1 && $key != count($videos) - 1): ?>
                                <hr class="my-3">
                            <?php endif ?>
                        <?php endforeach ?>
                    </div>
                <?php endif ?>
            </div>

        </div>

        <div class="col-lg-9">
            <?php echo alerts() ?>
            <div class="card">
                <div class="lesson-body card-body">
                    <h1><?php echo $lesson->title ?></h1>
                    <?php echo $lesson->content ?>
                </div>
            </div>

            <!-- <div class="card">
                <div class="card-header"><i class="fe fe-film mr-2 text-muted"></i>Video</div>
                <div class="card-body">
                    <p class="text-muted">No Videos</p>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><i class="fe fe-edit mr-2 text-muted"></i>Assignment</div>
                <div class="card-body">
                    <p class="text-muted">No Assignments</p>
                </div>
            </div> -->
        </div>
    </div>
</div>
<?php $this->load->view('admin/footer') ?>