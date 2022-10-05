<?php $this->load->view('header') ?>
<div class="container">
	<div class="row">
		<div class="col-lg-3">
			<div class="page-header mb-0 mb-sm-5">
				<h1 class="page-title">
					Courses
				</h1>
			</div>
		</div>
		<div class="col-lg-9">
			<div class="page-header mt-0 mt-sm-5">
				<h1 class="page-title">
					<small class="text-muted"><?php echo get_title() ?></small>
				</h1>
			</div>
		</div>
	</div>
	<div class="row">
		<?php $this->load->view('course/sidebar') ?>
		<div class="col-lg-9">
			<?php echo alerts() ?>
			<?php foreach ($courses as $course): ?>
				<div class="card card-aside">
					<a href="<?php echo base_url("course/lessons/{$course->id}") ?>" class="card-aside-column bg-cyan-light d-none d-md-block" style="background-image: url(<?php echo course_image_url($course->image ?? '') ?>)"></a>
					<div class="card-body d-flex flex-column">
						<h4><a href="<?php echo base_url("course/lessons/{$course->id}") ?>" class="text-inherit"><?php echo $course->name ?></a></h4>
						<div class="text-muted"><a href="<?php echo base_url("course/lessons/{$course->id}") ?>" class="text-inherit text-decoration-none"><?php echo $course->description ?></a></div>
						<div class="d-flex align-items-center pt-5 mt-auto">
							<?php if ($this->uri->segment(3) == 'completed'): ?>
								<i class="mr-2 fe fe-check text-success font-weight-bold"></i> <small class="text-muted mr-2">Complete</small>
							<?php else: ?>
                            	<a href="<?php echo base_url("course/lessons/{$course->id}") ?>" class="btn btn-primary mr-4">Continue <i class="fe fe-chevron-right"></i></a>
								<small class="d-block text-muted"><a href="#" class="text-inherit text-decoration-none"><?php echo count($course->lessons) ?> <?php echo count($course->lessons) == 1 ? 'lesson' : 'lessons' ?></a></small>
							<?php endif ?>
						</div>
					</div>
				</div>
			<?php endforeach ?>

			<?php if (empty($courses)): ?>
				<?php if ($this->uri->segment(3) == 'completed'): ?>
					<div class="card">
						<div class="card-body">
							You haven't completed any courses yet.
							<div class="text-muted">To complete a course, complete all its lessons and click "Complete Course".</div>
						</div>
					</div>
				<?php else: ?>
					<div class="card">
						<div class="card-body">
							You're ready to start some courses!
							<div class="text-muted">To start a course, click "Start Course" on a course page.</div>
						</div>
					</div>
				<?php endif ?>
			<?php endif ?>	
		</div>
	</div>
</div>
<?php $this->load->view('footer') ?>