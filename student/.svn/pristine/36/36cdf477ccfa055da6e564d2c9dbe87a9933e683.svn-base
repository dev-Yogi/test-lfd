<?php $this->load->view('admin/header') ?>
<div class="page">
	<div class="page-main">
		<div class="container">
			<div class="page-header">
				<h1 class="page-title">
					<?php echo display_name($student) ?> <span class="text-muted small"><span class="p-2">&middot;</span> Student</span>
				</h1>
			</div>
			<a href="<?php echo base_url('admin/student') ?>" class="btn btn-secondary mb-5"><i class="fe fe-chevron-left mr-2"></i>Back to Students</a>
			<div class="row">
				<div class="col">
					<?php echo alerts() ?>
					<div class="card p-md-5">
						<div class="card-body">
							<h1><?php echo display_name($student) ?> <a href="<?php echo base_url("admin/student/edit/{$student->id}") ?>" class="btn btn-link btn-sm <?php echo is_staff() ? null : 'disabled' ?>"><i class="fe fe-edit-2"></i> Edit</a></h1>

							<table class="table">
								<?php foreach ($extra_fields as $field): ?>
									<tr>
										<th><?php echo $field->label ?></th>
										<?php if ($field->type == 'select'): ?>
											<td><?php echo $student->extra_fields[$field->id]->option_label ?? '-' ?></td>
										<?php else: ?>
											<td><?php echo $student->extra_fields[$field->id]->value ?? '-' ?></td>
										<?php endif ?>
									</tr>
								<?php endforeach ?>

								<tr>
									<th width="180">Instructor</th>
									<td><?php echo display_name($instructor) ?></td>
								</tr>
								<tr>
									<th width="180">Profile created</th>
									<td><?php echo date('j M, Y', strtotime($student->created)) ?></td>
								</tr>
								<tr>
									<th>Last logged in at</th>
									<?php if ($student->last_login): ?>
										<td><?php echo date('j M, Y H:iA', strtotime($student->last_login)) ?></td>
										<?php else: ?>
											<td class="text-muted">Never</td>
										<?php endif ?>
									</tr>
								</table>
								<div class="row">

									<div class="col-12">
										<h4 class="mt-5 pt-5 mb-5">Courses</h4>
										<div class="table-responsive">
											<table class="table">
												<tbody>
													<tr>
														<th width="500">Name</th>
														<th>Progress</th>
														<th>Status</th>
														<th>Started</th>
														<th>Finished</th>
													</tr>
													<?php foreach ($courses as $course): ?>
														<tr>
															<td>
																<a href="<?php echo base_url("admin/course/view/{$course->id}") ?>"><?php echo $course->name ?></a>
																<div class="collapse" id="lessons-completed-<?php echo $course->id?>">
																	<div class="card mt-3">
																		<table class="card-table table-sm">
																			<thead>
																				<tr>
																					<th>Lesson Title</th>
																					<th>Student Status</th>
																				</tr>
																			</thead>
																			<?php foreach ($course->lessons as $lesson): ?>
																				<tr>
																					<td><?php echo $lesson->title ?></td>
																					<td><?php echo $lesson->status ? ucwords($lesson->status) : '<span class="text-muted">Not Started</span>' ?></td>
																				</tr>
																			<?php endforeach ?>
																		</table>
																	</div>
																</div>
															</td>
															<td><?php echo ucwords($course->status) ?></td>
															<td>
																<a data-toggle="collapse" href="#lessons-completed-<?php echo $course->id?>" role="button" aria-expanded="false" aria-controls="lessons-completed-<?php echo $course->id?>">
																	<?php echo $course->completed_lessons_count ?> <span class="text-muted">of</span> <?php echo count($course->lessons) ?>
																</a>
															</td>
															<td><?php echo date('j M, Y', strtotime($course->created)) ?></td>
															<td><?php echo $course->modified ? date('j M, Y', strtotime($course->modified)) : null ?></td>
														</tr>
														<tr class="collapse">
															<td colspan="5">
															</td>
														</tr>
													<?php endforeach ?>
													<?php if (empty($courses)): ?>
														<tr><td class="text-muted" colspan="5">No courses started</td></tr>
													<?php endif ?>
												</tbody>
											</table>
										</div>
									</div>

									<div class="col-12">
										<h4 class="mt-5 pt-5 mb-5">Forum Profile</h4>
										<div class="row">
											<div class="col-lg-2">
												<div class="forum-avatar <?php echo $profile->avatar_color ?? 'bg-cyan' ?>">
													<i class="fas fa-<?php echo $profile->avatar_icon ?? 'smile' ?>"></i>
												</div>
											</div>
											<div class="col">
												<?php if (empty($profile)): ?>
													Forum profile not set up
													<?php else: ?>
														<?php echo forum_username($profile->username) ?><br>
														<?php echo $profile->post_count ?> Posts
													<?php endif ?>
												</div>
											</div>
										</div>

										<div class="col-lg-6">
											<h4 class="mt-5 pt-5 mb-5">Forum Threads</h4>

											<div class="table-responsive">
												<table class="table table-vcenter table-sm">
													<tbody>
														<?php foreach ($threads as $post): ?>
															<tr>
																<td>
																	<a href="<?php echo base_url("forum/view/{$post->id}") ?>" class="text-dark text-decoration-none">
																		<div class="thread">
																			<span class="text-inherit font-weight-semibold"><?php echo $post->title ?></span>
																			<span class="text-muted">&middot; <?php echo $post->replies ?? 0 ?> &middot;</span>
																			<span class="text-muted"><?php echo character_limiter($post->message, 100) ?></span>
																		</div>
																	</a>
																	<div class="text-muted small">
																		<?php echo date('d/m/Y H:i:s', strtotime($post->created)) ?>
																	</div>
																</td>
															</tr>
														<?php endforeach ?>
														<?php if (empty($threads)): ?>
															<tr><td class="text-muted" colspan="5">No threads started</td></tr>
														<?php endif ?>
													</tbody>
												</table>
											</div>
										</div>

										<div class="col-lg-6">
											<h4 class="mt-5 pt-5 mb-5">Forum Replies</h4>
											<div class="table-responsive">
												<table class="table table-vcenter table-sm">
													<tbody>
														<?php foreach ($replies as $post): ?>
															<tr>
																<td>
																	<a href="<?php echo base_url("forum/view/{$post->id}") ?>" class="text-dark text-decoration-none">
																		<div class="thread">
																			<?php echo character_limiter(strip_tags($post->message), 100) ?>
																		</div>
																	</a>
																	<div class="text-muted small">
																		<?php echo date('d/m/Y H:i:s', strtotime($post->created)) ?>
																		<span class="text-muted"> &middot;</span>
																		<span class="text-inherit font-weight-semibold"><?php echo $post->thread_title ?></span>
																	</div>
																</td>
															</tr>
														<?php endforeach ?>
														<?php if (empty($replies)): ?>
															<tr><td class="text-muted" colspan="5">No replies posted</td></tr>
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

				</div>
			</div>
		</div>
		<?php $css = array('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css') ?>
		<?php $this->load->view('admin/footer', compact('css')) ?>