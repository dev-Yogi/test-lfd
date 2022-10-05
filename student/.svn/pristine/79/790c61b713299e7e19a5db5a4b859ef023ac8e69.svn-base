<?php $this->load->view('admin/header') ?>
<div class="page">
	<div class="page-main">
		<div class="container">
			<div class="page-header">
				<h1 class="page-title">
					Forum <span class="text-muted small"><span class="p-2">&middot;</span> All</span>
				</h1>
			</div>
			<a href="<?php echo base_url('forum/post') ?>" class="btn btn-primary mb-5"><i class="fe fe-plus"></i> New Thread</a>
			<?php echo alerts() ?>
			<div class="row">
				<div class="col">
					<div class="card-deck">
						<div class="card">
							<div class="table-responsive">
								<table class="table card-table table-vcenter table-sm table-hover">
									<thead>
										<tr>
											<th>Recent Threads</th>
											<th></th>
										</tr>
									</thead>
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
												<td class=" text-right w-25">
													<?php if (is_moderator()): ?>
														<a href="<?php echo base_url("admin/forum/delete/{$post->id}") ?>" onclick="return confirm('Are you sure you want to remove this post?')" class="btn btn-sm btn-secondary" title="Delete Post"><i class="fe fe-trash"></i></a>
														<a href="<?php echo base_url("admin/forum/ban/{$post->created_by}") ?>" onclick="return confirm('Are you sure you want to ban this user?')" class="btn btn-sm btn-secondary" title="Ban User"><i class="fe fe-user-x"></i></a>
													<?php endif ?>
												</td>
											</tr>
										<?php endforeach ?>
										<?php if (empty($threads)): ?>
											<tr>
												<td class="text-muted">No posts</td>
											</tr>
										<?php endif ?>
									</tbody>
								</table>
							</div>
							<div class="card-body p-0"></div>
							<div class="card-footer text-right no-bg">
								<a href="<?php echo base_url("admin/forum/threads") ?>" class="btn btn-secondary">View All Threads</a>
							</div>
						</div>

						<div class="card">
							<div class="table-responsive">
								<table class="table card-table table-vcenter table-sm table-hover">
									<thead>
										<tr>
											<th>Recent Replies</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($replies as $post): ?>
											<tr>
												<td>
													<a href="<?php echo base_url("forum/view/{$post->thread_id}") ?>" class="text-dark text-decoration-none">
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
												<td class="text-right w-25">
													<?php if (is_moderator()): ?>
														<a href="<?php echo base_url("admin/forum/delete/{$post->id}") ?>" onclick="return confirm('Are you sure you want to remove this post?')" class="btn btn-sm btn-secondary" title="Delete Post"><i class="fe fe-trash"></i></a>
														<a href="<?php echo base_url("admin/forum/ban/{$post->created_by}") ?>" onclick="return confirm('Are you sure you want to ban this user?')" class="btn btn-sm btn-secondary" title="Ban User"><i class="fe fe-user-x"></i></a>
													<?php endif ?>
												</td>
											</tr>
										<?php endforeach ?>
										<?php if (empty($replies)): ?>
											<tr>
												<td class="text-muted">No posts</td>
											</tr>
										<?php endif ?>
									</tbody>
								</table>
							</div>
							<div class="card-body p-0"></div>
							<div class="card-footer text-right no-bg">
								<a href="<?php echo base_url("admin/forum/replies") ?>" class="btn btn-secondary">View All Replies</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6">
					<div class="card mt-5">
						<table class="table card-table table-vcenter table-sm table-hover">
							<thead>
								<tr>
									<th>Banned Users</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($banned_users as $user): ?>
									<tr>
										<td>
											<?php echo $user->last_name ?> <?php echo $user->first_name ?> <span class="text-muted">(<?php echo $user->username ?>)</span>
											<div class="small text-muted">Banned <?php echo date('m/d/y', strtotime($user->banned_at)) ?></div>
										</td>
										<td class="text-right">
											<?php if (is_moderator()): ?>
												<a href="<?php echo base_url("admin/forum/unban/{$user->member_id}") ?>" class="btn btn-sm btn-secondary" title="Ban User"><i class="fe fe-user-check mr-2"></i> Unban</a>
											<?php endif ?>
										</td>
									</tr>
								<?php endforeach ?>
								<?php if (empty($banned_users)): ?>
									<tr>
										<td class="text-muted">No banned users</td>
									</tr>
								<?php endif ?>
							</tbody>
						</table>
						<div class="card-footer text-right no-bg">
							<a href="<?php echo base_url("admin/forum/banned") ?>" class="btn btn-secondary">View All Banned Users</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('admin/footer') ?>