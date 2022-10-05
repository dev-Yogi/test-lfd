<div class="col-lg-3 mb-4">
	<div class="list-group list-group-transparent mb-0">
		<a href="<?php echo base_url('course/me') ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(2) == 'me' && !$this->uri->segment(3)) ? 'active' : '' ?>"><span class="icon mr-3"><i class="fe fe-inbox"></i></span>My Courses</a>
		<a href="<?php echo base_url('course/me/completed') ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(3) == 'completed') ? 'active' : '' ?>"><span class="icon mr-3"><i class="fe fe-flag"></i></span>Completed Courses</a>
	</div>
	<hr>
	<button class="btn btn-secondary ml-auto mb-3 categories-menu-button"><i class="fe mr-1 text-muted fe-chevron-down"></i> View All Courses</button>
	<div class="list-group list-group-transparent mb-0 categories-menu" <?php if ($this->uri->segment(2) == 'me') echo 'style="display:none"' ?>>
		<a href="<?php echo base_url("course/category/all") ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(3) == 'all' || !$this->uri->segment(2)) ? 'active' : '' ?>"><span class="icon mr-3"><i class="fe fe-bookmark"></i></span>All</a>
		<?php foreach ($categories as $category): ?>
			<a href="<?php echo base_url("course/category/{$category->id}") ?>" class="list-group-item list-group-item-action <?php echo ($this->uri->segment(3) == $category->id) ? 'active' : '' ?>"><span class="icon mr-3"><i class="fe fe-bookmark"></i></span><?php echo $category->name ?></a>
		<?php endforeach ?>
	</div>
</div>