<?php $this->load->view('header') ?>
<div class="container">
	<div class="page-header">
		<h1 class="page-title">Search Results <?php echo empty($keywords) ? null : "for '$keywords'" ?></h1>
	</div>

	<div class="row">
		<div class="col-lg-9">
			<?php foreach ($results as $result): ?>
				<div class="card">
					<table class="table card-table table-vcenter">
						<tbody>
							<tr>
								<td width="100" class="text-muted text-center text-uppercase">
									<?php echo ucwords($result['type']) ?>
								</td>
								<td>
									<a href="<?php echo $result['url'] ?>" class="text-inherit text-decoration-none"><h4 class="mb-1 mt-1"><?php echo $result['title'] ?></h4>
									<div><?php echo $result['subtitle'] ?></div>
									<div class="text-muted"><?php echo $result['description'] ?></div></a>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			<?php endforeach ?>
			<?php if (empty($results)): ?>
				<p class="text-muted">No results found</p>
			<?php endif ?>
		</div>
		<div class="col-lg-3">
			
		</div>
	</div>
</div>
<?php $this->load->view('footer') ?>
