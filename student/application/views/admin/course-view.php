<?php $this->load->view('admin/header') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="page-header">
                        <h1 class="page-title">
                            <?php echo $course->name ?> <span class="text-muted small"><span class="p-2">&middot;</span> Course</span>

                        </h1>
                        <a href="<?php echo base_url("course/view/{$course->id}") ?>" class="btn btn-secondary ml-auto <?php echo $course->status == 'published' ? '' : 'disabled' ?>" target="_blank">View Course Page<i class="fe fe-external-link ml-2"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 d-none d-lg-block">
                    <a href="<?php echo base_url("admin/course/") ?>" class="btn btn-secondary btn-block mb-3"><i class="fe fe-chevron-left mr-2"></i>Back to Courses</a>
                    <a href="<?php echo base_url("admin/course/edit/{$course->id}") ?>" class="btn btn-primary btn-block mb-3"><i class="fe fe-edit-2 mr-2"></i>Edit</a>
                    <a href="<?php echo base_url("admin/course/assign/{$course->id}") ?>" class="btn btn-secondary btn-block mb-3"><i class="fe fe-user-plus mr-2"></i>Assign to Students</a>
                    <div class="card course-details text-dark">
                        <div class="card-header bg-cyan-light" style="background-image: url(<?php echo course_image_url($course->image ?? '') ?>);"></div>
                        <div class="card-body d-flex flex-column">
                            <b><?php echo $course->name ?></b>
                            <?php echo $course->description ?>

                            <p class="pt-5">Instructor:</p>
                            <?php foreach ($course->instructors as $instructor): ?>
                                <div class="d-flex align-items-center mt-auto mb-3">
                                    <div class="avatar avatar-md mr-3 bg-cyan" style="background-image: url(<?php echo base_url("img/user-avatar.jpg") ?>)"></div>
                                    <div>
                                        <?php echo "{$instructor->instructor_first_name} {$instructor->instructor_last_name}" ?>
                                        <small class="d-block text-muted"><?php echo $instructor->email ?></small>
                                    </div>
                                </div>
                            <?php endforeach ?>
                            <p class="mt-5">Last update <?php echo $course->modified ? date('M d, Y', strtotime($course->created)) : date('M d, Y', strtotime($course->created)) ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <?php if ($course->status == 'draft'): ?>
                        <div class="alert alert-info alert-dismissible fade show" role="alert"><b>Draft Course </b>The course is in draft status, and is not visible to students. To publish the course, go to <i>Edit</i> and click <i>Publish</i>.</div>
                    <?php endif ?>
                    <?php echo alerts() ?>
                    <div class="card">
                        <div class="card-header d-flex">Lessons <a href="<?php echo base_url("admin/lesson/create/{$course->id}") ?>" class="btn btn-primary ml-auto"><i class="mr-2 fe fe-plus"></i>New Lesson</a></div>

                        <div class="table-responsive">
                            <table class="table card-table">
                                <thead>
                                    <tr>
                                        <th>Order</th>
                                        <th>Title</th>
                                        <th>Length</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($course->lessons as $lesson): ?>
                                        <tr>
                                            <td width="1"><?php echo $lesson->order ?></td>
                                            <td><?php echo $lesson->title ?></td>
                                            <td><span class="text-muted"><?php echo time_length_label($lesson->length) ?></span></td>
                                            <td class="text-right">
                                                <a href="<?php echo base_url("lesson/view/{$lesson->id}") ?>" class="btn btn-secondary btn-sm <?php echo $course->status == 'published' ? '' : 'disabled' ?>" target="_blank"><i class="fe fe-external-link"></i> View</a>
                                                <a href="<?php echo base_url("admin/lesson/create/{$course->id}/{$lesson->id}") ?>" class="btn btn-secondary btn-sm"><i class="fe fe-copy"></i> Copy</a>
                                                <a href="<?php echo base_url("admin/lesson/view/{$lesson->id}") ?>" class="btn btn-secondary btn-sm"><i class="fe fe-edit-2"></i> Edit</a>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                    <?php if (empty($course->lessons)): ?>
                                        <tr>
                                            <td colspan="5" class="text-muted">No lessons - click "New Lesson" to add a lesson</td>
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
</div>
<?php $this->load->view('admin/footer') ?>