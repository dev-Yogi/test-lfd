<?php $this->load->view('admin/header') ?>
<div class="page">
	<div class="page-main">
		<div class="container">
			<div class="page-header">
				<h1 class="page-title">
					Forum <span class="text-muted small"><span class="p-2">&middot;</span> All</span>
				</h1>
			</div>
			<a href="<?php echo base_url('admin/forum') ?>" class="btn btn-secondary">Back to Forums</a>
			<?php echo alerts() ?>
			<div class="row">
				<div class="col">
					<div class="card mt-5">
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
											<td class="text-right" width="120">
												<?php if (is_moderator()): ?>
													<a href="<?php echo base_url("admin/forum/delete/{$post->id}") ?>" onclick="return confirm('Are you sure you want to remove this post?')" class="btn btn-sm btn-secondary" title="Delete Post"><i class="fe fe-trash"></i></a>
													<a href="<?php echo base_url("admin/forum/ban/{$post->created_by}") ?>" onclick="return confirm('Are you sure you want to ban this user?')" class="btn btn-sm btn-secondary" title="Ban User"><i class="fe fe-user-x"></i></a>
												<?php endif ?>
											</td>
										</tr>
									<?php endforeach ?>
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