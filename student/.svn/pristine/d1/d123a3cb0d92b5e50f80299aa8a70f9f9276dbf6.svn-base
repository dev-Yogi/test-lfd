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
										<th>Thread</th>
										<th>Preview</th>
										<th>Author</th>
										<th>Replies</th>
										<th>Created</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($threads as $post): ?>
										<tr>
											<td>
												<a href="<?php echo base_url("forum/view/{$post->id}") ?>" class="text-dark text-decoration-none">
													<span class="text-inherit font-weight-semibold"><?php echo $post->title ?></span>
												</a>
											</td>
											<td><span class="text-muted"><?php echo character_limiter($post->message, 100) ?></span></td>
											<td><?php echo display_name($post) ?></td>
											<td><?php echo $post->replies ?? 0 ?></td>
											<td><?php echo date('d/m/Y H:i:s', strtotime($post->created)) ?></td>
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