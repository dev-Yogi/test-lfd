<?php $this->load->view('admin/header') ?>
<?php $this->load->view('admin/menu') ?>

<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">
                    Offering Catalog Columns
                </h1>
            </div>
            <div class="row">
                <div class="col">
                    <a href="<?php echo base_url('admin/offering') ?>" class="btn btn-secondary mb-5"><i class="fe fe-chevron-left"></i>  Back to Offerings</a>
                    <?php alerts() ?>
                </div>
            </div>
            <div class="row">
                
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title mr-auto">Columns</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <form method="post" action="<?php echo base_url('admin/offering/update_columns') ?>">
                                            <input type="hidden" name="columns">
                                            <ul class="p-0 offering-columns">
                                                <?php foreach($columns as $column): ?>
                                                    <li class="card p-2 bg-light font-weight-semibold" data-id="<?php echo $column->id ?>">
                                                        <div>
                                                            <i class="fe fe-more-vertical text-muted"></i><i class="fe fe-more-vertical text-muted"></i> <?php echo $column->label ?>
                                                            <label class="custom-switch float-right">
                                                                <input type="checkbox" name="<?php echo $column->id . "-active" ?>" value="1" class="custom-switch-input" <?php echo $column->active ? "checked" : null ?>>
                                                                <span class="custom-switch-indicator"></span>
                                                            </label>
                                                        </div>
                                                    </li>
                                                <?php endforeach ?>
                                            </ul>

                                            <input type="submit" value="Save" class="btn btn-primary">
                                            <a href="<?php echo base_url("admin/offering") ?>" class="btn btn-seconday">Cancel</a>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title mr-auto">Filters</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <form method="post" action="<?php echo base_url('admin/offering/update_filters') ?>">
                                            <ul class="p-0 list-unstyled">
                                                <?php foreach($filters as $filter): ?>
                                                    <li class="p-2 font-weight-semibold" data-id="<?php echo $filter->id ?>">
                                                        <div><?php echo filter_category($filter->filter) ?>
                                                            <label class="custom-switch float-right">
                                                                <input type="checkbox" name="<?php echo $filter->id ?>" value="1" class="custom-switch-input" <?php echo $filter->active ? "checked" : null ?>>
                                                                <span class="custom-switch-indicator"></span>
                                                            </label>
                                                        </div>
                                                    </li>
                                                <?php endforeach ?>
                                            </ul>

                                            <input type="submit" value="Save" class="btn btn-primary">
                                            <a href="<?php echo base_url("admin/offering") ?>" class="btn btn-seconday">Cancel</a>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
<?php $js = array(base_url('js/admin/offering-columns.js')); ?>
<?php $this->load->view('admin/footer', compact('js')) ?>