<?php $this->load->view('header') ?>
<div class="bg-cyan-darker text-light">
    <?php echo alerts() ?>
    <?php if ($this->session->flashdata('lesson_course_warning')): ?>
        <div class="alert alert-warning alert-dismissible p-0 m-0 py-5" role="alert">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <i class="fe fe-alert-circle"></i> To view the lesson, start the course. Click "Start Course" to get started - <a href="<?php echo base_url("course/start/{$course->id}") ?>" class="btn btn-primary btn-sm">Start Course</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>
    <div class="container">

        <div class="row">
            <div class="col-lg-8 py-5 my-5">
                <h1><?php echo $course->name ?></h1>
                <?php echo nl2br($course->description) ?>
                <p>Category: 
                    <?php foreach ($course->categories as $category): ?>
                        <b><a href="<?php echo base_url("course/category/{$category->id}") ?>"><?php echo $category->name ?></a></b>
                    <?php endforeach ?>
                </p>
            </div>

            <div class="col-lg-4">
                <div class="card course-details text-dark">
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
                        <p class="mt-5">Last update <?php echo $course->modified ? date('M d, Y', strtotime($course->created)) : date('M d, Y', strtotime($course->created)) ?></p>
                        <?php if (!empty($progress)): ?>
                            <?php if ($progress->status == 'started'): ?>
                                <a href="<?php echo base_url("course/lessons/{$course->id}") ?>" class="btn btn-primary btn-lg mt-3">Continue</a>
                                <?php elseif ($progress->status == 'complete'): ?>
                                    <a href="<?php echo base_url("course/lessons/{$course->id}") ?>" class="btn btn-secondary btn-lg mt-3"><i class="text-green mr-2 fe fe-check"></i>Course Complete</a>
                                <?php endif ?>
                                <?php else: ?>
                                    <!-- <a href="#" class="btn btn-secondary btn-lg mt-1">Save for Later</a> -->
                                    <a href="<?php echo base_url("course/start/{$course->id}") ?>" class="btn btn-primary btn-lg mt-3">Start Course</a>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                </div>  
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-lg-8 pt-5 mt-5">
                    <h2>What You'll Learn</h2>
                    <ul class="list-unstyled what-youll-learn">
                        <?php foreach (explode("\n", $course->whatyoulllearn) as $point): ?>
                         <?php if (!empty($point)): ?>
                          <li><?php echo $point ?></li>
                      <?php endif ?>
                  <?php endforeach ?>
              </ul>
              <h2 class="mt-5 pt-5">Lessons</h2>
              <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><?php echo count($course->lessons) == 1 ? count($course->lessons) . " Lesson" : count($course->lessons) . " Lessons" ?></h4>
                </div>
                <table class="table card-table">
                    <tbody>
                        <?php $count = 1 ?>
                        <?php foreach ($course->lessons as $lesson): ?>
                            <tr>
                                <td width="1"><?php echo $count++ ?></td>
                                <td><?php echo $lesson->title ?></td>
                                <td class="text-right text-muted"><?php echo time_length_label($lesson->length) ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
            <?php if (!empty($progress)): ?>
                <?php if ($progress->status == 'started'): ?>
                    <a href="<?php echo base_url("course/lessons/{$course->id}") ?>" class="btn btn-primary btn-lg mt-3">Continue</a>
                    <?php elseif ($progress->status == 'complete'): ?>
                        <a href="<?php echo base_url("course/lessons/{$course->id}") ?>" class="btn btn-secondary btn-lg mt-3"><i class="text-green mr-2 fe fe-check"></i>Course Complete</a>
                    <?php endif ?>
                    <?php else: ?>
                        <a href="<?php echo base_url("course/start/{$course->id}") ?>" class="btn btn-primary btn-lg">Start Course</a>
                    <?php endif ?>
                </div>
            </div>
        </div>
        <?php $this->load->view('footer') ?>