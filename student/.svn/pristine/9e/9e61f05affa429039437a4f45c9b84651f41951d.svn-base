<?php $this->load->view('header') ?>
<div class="container">
	<div class="page-header">
		<h1 class="page-title">Upcoming Video Conferences</h1>
	</div>

	<div class="row">
		<div class="col-lg-9">
			<?php if ($live_videos): ?>
				<?php foreach ($live_videos as $video): ?>
					<div class="card">
						<table class="table card-table">
							<tbody>
								<tr>
									<td width="50" class="text-muted text-center ">
										<div class="video-month font-weight-semibold my-1"><?php echo date('M', strtotime($video->start_time)) ?></div>
										<div class="video-day"><?php echo date('d', strtotime($video->start_time)) ?></div>
									</td>
									<td>
										<a href="<?php echo base_url("lesson/view/{$video->lesson_id}") ?>" class="text-inherit"><h4 class="mb-1 mt-1"><span class="tag tag-red font-weight-semibold">LIVE!</span> <?php echo $video->label ?></h4></a>
										<div><?php echo date('g:iA', strtotime($video->start_time)) ?> - <?php echo date('g:iA', strtotime($video->stop_time)) ?> for <a href="<?php echo base_url("lesson/view/{$video->lesson_id}") ?>" class="font-weight-semibold text-dark"><?php echo $video->lesson_title ?></a></div>
										<div class="text-muted"><?php echo $video->course_name ?></div>
									</td>
									<td width="50" class="align-middle">
										<a href="<?php echo base_url("lesson/view/{$video->lesson_id}") ?>" class="btn btn-primary"><i class="fe fe-chevron-right"></i><br>to Lesson</a>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				<?php endforeach ?>
				<hr>
			<?php endif ?>


			<?php foreach ($videos as $video): ?>
				<div class="card">
					<table class="table card-table">
						<tbody>
							<tr>
								<td width="50" class="text-muted text-center ">
									<div class="video-month font-weight-semibold my-1"><?php echo date('M', strtotime($video->start_time)) ?></div>
									<div class="video-day"><?php echo date('d', strtotime($video->start_time)) ?></div>
								</td>
								<td>
									<a href="<?php echo base_url("lesson/view/{$video->lesson_id}") ?>" class="text-inherit"><h4 class="mb-1 mt-1"><?php echo $video->label ?></h4></a>
									<div><?php echo date('g:iA', strtotime($video->start_time)) ?> - <?php echo date('g:iA', strtotime($video->stop_time)) ?> for <a href="<?php echo base_url("lesson/view/{$video->lesson_id}") ?>" class="font-weight-semibold text-dark"><?php echo $video->lesson_title ?></a></div>
									<div class="text-muted"><?php echo $video->course_name ?></div>
								</td>
								<td width="50" class="align-middle">
									<a href="<?php echo base_url("lesson/view/{$video->lesson_id}") ?>" class="btn btn-primary"><i class="fe fe-chevron-right"></i><br>to Lesson</a>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			<?php endforeach ?>
			<?php if (empty($video)): ?>
				<p class="text-muted">There are no live videos coming up at the moment - check back soon!</p>
			<?php endif ?>
			
			<?php $this->load->view('pagination', compact('pagination')) ?>
		</div>
		<div class="col-lg-3">
			
		</div>
	</div>
</div>
<?php $this->load->view('footer') ?>