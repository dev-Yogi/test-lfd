<?php $this->load->view('header') ?>

<div class="container lesson">
    <div class="page-header">
        <h1 class="page-title">
            <?php echo $course->name ?>
            <small class="ml-2 text-muted">Course</small>
        </h1>
    </div>
    <div class="row">
        <div class="col-lg-3">
            <a href="<?php echo base_url("course/me") ?>" class="btn btn-secondary btn-block mb-3"><i class="fe fe-chevron-left"></i> Back to My Courses</a>
            <div class="card course-details text-dark d-none d-md-block">
                <div class="card-header bg-cyan-light" style="background-image: url(<?php echo course_image_url($course->image ?? '') ?>);"></div>
                <div class="card-body d-flex flex-column">
                    <p>This Course Has:</p>
                    <div>
                        <i class="text-muted mr-2 fe fe-file"></i><?php echo count($course->lessons) == 1 ? count($course->lessons) . " lesson" : count($course->lessons) . " lessons" ?>
                    </div>
                    <p class="pt-5">Instructors:</p>

                    <?php foreach ($course->instructors as $instructor): ?>
                        <div class="d-flex align-items-center mt-auto mb-3">
                            <div class="avatar avatar-md mr-3 bg-cyan" style="background-image: url(<?php echo base_url("img/user-avatar.jpg") ?>)"></div>
                            <div>
                                <?php echo "{$instructor->instructor_first_name} {$instructor->instructor_last_name}" ?>
                                <small class="d-block text-muted"><?php echo $instructor->email ?></small>
                            </div>
                        </div>
                    <?php endforeach ?>
                    <p class="mt-5">Last updated <?php echo $course->modified ? date('M d, Y', strtotime($course->created)) : date('M d, Y', strtotime($course->created)) ?></p>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <?php echo alerts() ?>
            <div class="card">
                <div class="card-header">
                    Lessons
                </div>
                <table class="table card-table">
                    <tbody>
                        <?php $count = 1 ?>
                        <?php foreach ($course->lessons as $key => $lesson): ?>
                            <tr>
                                <td width="1"><?php echo $count++ ?></td>
                                <td><?php echo $lesson->title ?><div class="text-muted"><?php echo time_length_label($lesson->length) ?></div></td>
                                <td class="text-right align-middle">
                                    <?php if (!empty($lesson->status)): ?>
                                        <?php if ($lesson->status == 'complete'): ?>
                                            <a href="<?php echo base_url("lesson/view/{$lesson->id}") ?>" class="text-decoration-none"><small class="text-muted">Complete</small><i class="ml-2 fe fe-check text-success font-weight-bold"></i></a>
                                            <?php $show_start_on_next_lesson = true ?>
                                        <?php elseif ($lesson->status == 'started'): ?>
                                            <a href="<?php echo base_url("lesson/view/{$lesson->id}") ?>" class="btn btn-primary">Continue<i class="ml-2 fe fe-chevron-right"></i></a>
                                            <?php $show_start_on_next_lesson = false ?>
                                        <?php endif ?>
                                    <?php else: ?>
                                        <?php if ($key == 0 || !empty($show_start_on_next_lesson) || !$course->force_order_lesson_completion): ?>
                                            <a href="<?php echo base_url("lesson/view/{$lesson->id}") ?>" class="btn btn-primary">Start<i class="ml-2 fe fe-chevron-right"></i></a>
                                            <?php $show_start_on_next_lesson = false ?>
                                        <?php else: ?>
                                            
                                        <?php endif ?>
                                    <?php endif ?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
                <div class="card-footer text-right">
                    <?php $complete = array_count_values(array_column($course->lessons, 'status'))['complete'] ?? 0 ?>
                    <small class="mb-3 text-muted">Completed <?php echo $complete ?> of <?php echo count($course->lessons) ?> lessons</small>
                    <?php if ($progress->status == 'complete'): ?>
                        <small class="text-muted">- Course complete</small>
                    <?php else: ?>
                        <?php if ($complete == count($course->lessons)): ?>
                            <a href="<?php echo base_url("course/complete/{$course->id}") ?>" class="btn btn-primary ml-3"><i class="mr-2 fe fe-check"></i> Complete Course</a>
                        <?php endif  ?>
                    <?php endif ?>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <?php echo $course->description ?>
                    <div class="font-weight-semibold mt-5">What You'll Learn</div>
                    <ul class="list-unstyled what-youll-learn">
                        <?php foreach (explode("\n", $course->whatyoulllearn) as $point): ?>
                            <?php if (!empty($point)): ?>
                                <li><?php echo $point ?></li>
                            <?php endif ?>
                        <?php endforeach ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('footer') ?>