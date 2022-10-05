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
				<?php if ($course->lessons > 0): ?>
					<div class="card card-aside">
						<a href="<?php echo base_url("course/view/{$course->id}") ?>" class="card-aside-column bg-cyan-light d-none d-md-block" style="background-image: url(<?php echo course_image_url($course->image ?? '') ?>)"></a>
						<div class="card-body d-flex flex-column">
							<h4><a href="<?php echo base_url("course/view/{$course->id}") ?>" class="text-inherit"><?php echo $course->name ?></a></h4>
							<div class="text-muted"><a href="<?php echo base_url("course/view/{$course->id}") ?>" class="text-inherit text-decoration-none"><?php echo character_limiter(strip_tags($course->description), 180) ?></a></div>
							<div class="d-flex align-items-center pt-5 mt-auto">
								<small class="d-block text-muted"><a href="#" class="text-inherit text-decoration-none"><?php echo $course->lessons ?> <?php echo $course->lessons == 1 ? 'lesson' : 'lessons' ?></a></small>
							</div>
						</div>
					</div>
				<?php endif ?>
			<?php endforeach ?>
			<?php if (empty($courses)): ?>
				<div class="mb-5">There are no courses listed <?php echo get_title() ? " for <b>" . get_title() . "</b>" : null ?>.</div>
			<?php endif ?>

			<?php $this->load->view('pagination', compact('pagination')) ?>
		</div>
	</div>
</div>
<?php $this->load->view('footer') ?>