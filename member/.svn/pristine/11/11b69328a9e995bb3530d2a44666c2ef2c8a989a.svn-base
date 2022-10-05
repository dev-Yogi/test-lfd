<?php $this->load->view('admin/header') ?>
<?php $this->load->view('admin/menu') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="page-header">
                        <h1 class="page-title">
                            Invoice <span class="text-muted small"><span class="p-2">&middot;</span> New</span>
                        </h1>
                    </div>
                    <form method="post">
                        <?php echo alerts() ?>
                        <div class="card">
                            <div class="row p-5">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Member</label>
                                        <select id="member_id" class="selectize form-control <?php echo is_valid('member_id') ?>" name="member_id" onchange="updateForm()">
                                            <option value="">Select a member</option>
                                            <?php foreach ($members as $member) : ?>
                                                <option 
                                                    value="<?php echo $member->id ?>" 
                                                    data-first_name="<?php echo $member->first_name ?>" 
                                                    data-last_name="<?php echo $member->last_name ?>" 
                                                    data-email="<?php echo $member->email ?>" 
                                                    <?php echo set_select('member_id') ?> >
                                                    <?php echo $member->last_name ?>, <?php echo $member->first_name ?>
                                                </option>
                                            <?php endforeach ?>
                                        </select>
                                        <?php echo form_error('member_id') ?>
                                    </div>
                                </div>
                                <div class="col-md-6 d-none">
                                    <div class="form-group">
                                        <label class="form-label">First Name</label>
                                        <input type="hidden" class="form-control <?php echo is_valid('first_name') ?>" name="first_name" value="<?php echo set_value('first_name') ?>">
                                        <?php echo form_error('first_name') ?>
                                    </div>
                                </div>
                                <div class="col-md-6 d-none">
                                    <div class="form-group">
                                        <label class="form-label">Last Name</label>
                                        <input type="hidden" class="form-control <?php echo is_valid('last_name') ?>" name="last_name" value="<?php echo set_value('last_name') ?>">
                                        <?php echo form_error('last_name') ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Billing Address</label>
                                        <input type="text" class="form-control <?php echo is_valid('billing_address') ?>" name="billing_address" value="<?php echo set_value('billing_address') ?>">
                                        <?php echo form_error('billing_address') ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Billing City, State</label>
                                        <input type="text" class="form-control <?php echo is_valid('billing_city_st') ?>" name="billing_city_st" value="<?php echo set_value('billing_city_st') ?>">
                                        <?php echo form_error('billing_city_st') ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Billing ZIP Code</label>
                                        <input type="text" class="form-control <?php echo is_valid('billing_zip') ?>" name="billing_zip" value="<?php echo set_value('billing_zip') ?>">
                                        <?php echo form_error('billing_zip') ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Email</label>
                                        <input type="text" class="form-control <?php echo is_valid('email') ?>" name="email" value="<?php echo set_value('email') ?>">
                                        <?php echo form_error('email') ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Phone</label>
                                        <input type="text" class="form-control <?php echo is_valid('phone') ?>" name="phone" value="<?php echo set_value('phone') ?>">
                                        <?php echo form_error('phone') ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Notes <small class="text-muted">(Optional)</small></label>
                                        <input type="text" class="form-control <?php echo is_valid('notes') ?>" name="notes" value="<?php echo set_value('notes') ?>">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Product</label>
                                        <select id="product_id" class="selectize form-control <?php echo is_valid('product_id') ?>" name="product_id" onchange="updatePriceTable()">
                                            <option value="">Select a product</option>
                                            <?php foreach ($products as $product) : ?>
                                                <option value="<?php echo $product->id ?>" <?php echo set_select('product_id') ?>>
                                                    <?php echo $product->label ?>
                                                </option>
                                            <?php endforeach ?>
                                        </select>
                                        <?php echo form_error('product_id') ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="form-label">Payment Type</div>
                                        <div class="custom-controls-stacked">
                                            <label class="custom-control custom-radio custom-control-inline mr-2">
                                                <input type="radio" class="custom-control-input" name="type" id="type" onchange="updatePriceTable()" value="annual" <?php echo set_checkbox('type', 'annual', true); ?>>
                                                <span class="custom-control-label">Annual</span>
                                            </label>
                                            <label class="custom-control custom-radio custom-control-inline mr-2">
                                                <input type="radio" class="custom-control-input" name="type" id="type" onchange="updatePriceTable()" value="monthly" <?php echo set_checkbox('type', 'monthly'); ?>>
                                                <span class="custom-control-label">Monthly</span>
                                            </label>
                                            <label class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="type" id="type" onchange="updatePriceTable()" value="one-time" <?php echo set_checkbox('type', 'one-time'); ?>>
                                                <span class="custom-control-label">One-Time</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-card m-0">
                                <tr>
                                    <th class="pl-5">Product</th>
                                    <th class="text-center">Type</th>
                                    <th class="text-right pr-5">Price</th>
                                </tr>
                                <tr>
                                    <td id="product_label" class="pl-5">-</td>
                                    <td id="product_type" class="text-center">-</td>
                                    <td id="product_price" class="text-right pr-5">-</td>
                                </tr>
                                <tr class="bg-light">
                                    <td></td>
                                    <td class="text-muted text-right">Total</td>
                                    <td id="product_total" class="text-right font-weight-semibold pr-5">-</td>
                                </tr>
                            </table>
                        </div>
                        <button class="btn btn-primary btn-block">Create</button>
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