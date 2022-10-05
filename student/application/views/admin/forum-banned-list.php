<?php $this->load->view('admin/header') ?>
<div class="page">
	<div class="page-main">
		<div class="container">
			<div class="page-header">
				<h1 class="page-title">
					Banned Users <span class="text-muted small"><span class="p-2">&middot;</span> Forum</span>
				</h1>
			</div>
			<a href="<?php echo base_url('admin/forum') ?>" class="btn btn-secondary">Back to Forums</a>
			<?php echo alerts() ?>

			<div class="row">
				<div class="col">
					<div class="card mt-5">
						<table class="table card-table table-vcenter table-sm table-hover">
							<thead>
								<tr>
									<th>Name</th>
									<th>Forum Username</th>
									<th>Ban Date</th>
									<th>Banned By</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($banned_users as $user): ?>
									<tr>
										<td><?php echo display_name($user) ?></td>
										<td><?php echo $user->username ?></td>
										<td><?php echo date('m/d/y', strtotime($user->banned_at)) ?></td>
										<td><?php echo $user->admin_last_name ?>, <?php echo $user->admin_first_name ?></td>
										<td class="text-right">
											<a href="<?php echo base_url("admin/forum/unban/{$user->member_id}") ?>" class="btn btn-sm btn-secondary" title="Ban User"><i class="fe fe-user-check mr-2"></i> Unban</a>
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
<?php $this->load->view('admin/footer') ?>