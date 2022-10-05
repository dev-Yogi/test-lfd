<?php $this->load->view('admin/header') ?>
<?php $this->load->view('admin/menu') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="page-header">
                        <h1 class="page-title">
                            Products <span class="text-muted small"><span class="p-2">&middot;</span> <?php echo empty($product) ? "New" : "Edit" ?></span>
                        </h1>
                    </div>
                    <form method="post">
                        <?php echo alerts() ?>
                        <div class="card p-5 mb-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Product Label</label>
                                        <input type="text" class="form-control <?php echo is_valid('label') ?>" name="label" value="<?php echo set_value('label', $product->label ?? "" ) ?>">
                                        <?php echo form_error('label') ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Product Description</label>
                                        <input type="text" class="form-control <?php echo is_valid('description') ?>" name="description" value="<?php echo set_value('description', $product->description ?? "" ) ?>">
                                        <?php echo form_error('description') ?>
                                    </div>
                                </div>
                            </div>

                            <fieldset class="form-fieldset col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Price - Annual</label>
                                    <input type="number" class="form-control <?php echo is_valid('price_annual') ?>" name="price_annual" value="<?php echo set_value('price_annual', $product->price_annual ?? "" ) ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Price - Monthly</label>
                                    <input type="number" class="form-control <?php echo is_valid('price_monthly') ?>" name="price_monthly" value="<?php echo set_value('price_monthly', $product->price_monthly ?? "" ) ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Price - One-Time</label>
                                    <input type="number" class="form-control <?php echo is_valid('price_one_time') ?>" name="price_one_time" value="<?php echo set_value('price_one_time', $product->price_one_time ?? "" ) ?>">
                                </div>
                            </fieldset>

                            <button class="btn btn-primary btn-block"><?php echo empty($product) ? "Create" : "Update" ?></button>
                        </div>

                        <?php if (!empty($product)): ?>
                            <a href="<?php echo base_url("admin/product/remove/{$product->id}") ?>" onclick="return confirm('Are you sure you want to remove this product?')" class="btn btn-link float-right mr-3"><i class="fe fe-trash"></i> Remove Product</a>
                        <?php endif ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var products = <?php echo json_encode($products) ?>;
</script>
<?php $js = array(base_url('js/invoice-form.js')); ?>
<?php $this->load->view('admin/footer', compact('js')) ?>