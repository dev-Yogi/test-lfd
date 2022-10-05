<?php $this->load->view('admin/header') ?>
<?php $this->load->view('admin/menu') ?>

<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">
                    Staff Portal
                </h1>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <a href="<?php echo base_url('admin/dashboard') ?>" class="btn btn-secondary mb-5"><i class="fe fe-chevron-left"></i>  Back to Dashboard</a>
                    <?php alerts() ?>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title mr-auto">Links</h3>
                            <a href="<?php echo base_url('admin/staff/link_create') ?>" class="btn btn-sm btn-primary"><i class="fe fe-plus"></i> Create Link</a>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($links)): ?>
                                <form method="post" action="<?php echo base_url('admin/staff/set_order_links') ?>">
                                    <input type="hidden" name="links_order">
                                    <ul class="p-0 links">
                                        <?php foreach($links as $link): ?>
                                            <li class="card p-2 bg-light font-weight-semibold" data-id="<?php echo $link->id ?>">
                                                <div>
                                                    <i class="fe fe-more-vertical text-muted"></i><i class="fe fe-more-vertical text-muted"></i> <?php echo $link->label ?>
                                                    <a href="<?php echo base_url("admin/staff/link_remove/{$link->id}") ?>" class="float-right btn btn-sm btn-secondary" onclick="return confirm('Are you sure you want to remove this link?')" ><i class="fe fe-trash"></i></a>
                                                    <a href="<?php echo base_url("admin/staff/link_edit/{$link->id}") ?>" class="float-right btn btn-sm btn-secondary mr-2"><i class="fe fe-edit-2"></i></a>
                                                    <a href="<?php echo $link->url ?>" target="_blank" class="float-right btn btn-sm btn-secondary mr-2" data-toggle="tooltip" data-placement="top" title="<?php echo $link->url ?>"><i class="fe fe-external-link"></i></a>
                                                </div>
                                            </li>
                                        <?php endforeach ?>
                                    </ul>
                                    <p class="text-muted">Drag items to change ordering. Click "Save Ordering" once done.</p>
                                    <input type="submit" value="Save Order" class="btn btn-primary">
                                </form>
                            <?php else: ?>
                                <div class="text-muted">There are no links - click "Create Link" to create a link.</div>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $js = array(base_url('js/admin/staff-portal-links.js')); ?>
<?php $this->load->view('admin/footer', compact('js')) ?>