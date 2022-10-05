<?php $this->load->view('header') ?>
<div class="container">

	<div class="page-header">
		<h1 class="page-title">Welcome, <?php echo $this->member->first_name ?></h1>
	</div>
	<?php echo alerts() ?>
	<hr>
	<div class="page-header">
		<p class="text-muted">
			My Courses
		</p>
		<a href="<?php echo base_url("course/me") ?>" class="btn btn-secondary btn-sm ml-auto">Go to My Courses »</a>
	</div>
	<div class="card-deck course-deck">
		<?php foreach (array_slice($my_courses, 0, 4) as $course): ?>
			<div class="card col-lg-3">
				<a href="<?php echo base_url("course/lessons/{$course->id}") ?>"><div class="card-header bg-cyan-light" style="background-image: url(<?php echo course_image_url($course->image) ?>);"></div></a>
				<div class="card-body">
					<h5 class="card-title"><a href="<?php echo base_url("course/lessons/{$course->id}") ?>"><?php echo $course->name ?></a></h5>
					<p class="card-text small text-muted"><?php echo character_limiter(strip_tags($course->description), 80) ?></p>
				</div>
			</div>
		<?php endforeach ?>
	</div>
	<?php if (empty($my_courses)): ?>
		<small class="text-muted">You have not started any courses - <a href="<?php echo base_url("course") ?>">View courses</a></small>
	<?php endif ?>

	<hr>
	<div class="page-header">
		<p class="text-muted">
			Recently Updated Courses
		</p>
		<a href="<?php echo base_url("course") ?>" class="btn btn-secondary btn-sm ml-auto">Go to Courses »</a>
	</div>
	<div class="card-deck course-deck">
		<?php foreach (array_slice($recent_courses, 0, 4) as $course): ?>
			<div class="card col-lg-3">
				<a href="<?php echo base_url("course/view/{$course->id}") ?>"><div class="card-header bg-cyan-light" style="background-image: url(<?php echo course_image_url($course->image) ?>);"></div></a>
				<div class="card-body">
					<h5 class="card-title"><a href="<?php echo base_url("course/view/{$course->id}") ?>"><?php echo $course->name ?></a></h5>
					<p class="card-text small text-muted"><?php echo character_limiter(strip_tags($course->description), 80) ?></p>
				</div>
			</div>
		<?php endforeach ?>
	</div>
</div>
<?php $this->load->view('footer') ?>
