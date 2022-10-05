<?php $this->load->view('admin/header') ?>
<?php $this->load->view('admin/menu') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">
                    Products
                </h1>
            </div>
            <div class="row">
                <div class="col">
                    <a href="<?php echo base_url('admin/product/create') ?>" class="btn btn-primary mb-5"><i class="fe fe-plus"></i> Create Product</a>
                    <?php alerts() ?>
                    <div class="card">
                        <table class="table card-table table-hover">
                            <thead>
                                <tr>
                                    <th class="pl-5">ID</th>
                                    <th>Label</th>
                                    <th class="text-right">Price</th>
                                    <th class="text-right pr-5">Modified</th>
                                    <th class="text-center w-1">Version</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $product) : ?>
                                    <tr>
                                        <td class="pl-5"><?php echo $product->id ?></td>
                                        <td><?php echo $product->label ?></td>
                                        <td class="text-right">
                                            <div class="small"><span class="text-muted">Annual: </span><?php echo ($product->price_annual > 0) ? "$" . number_format($product->price_annual, 2) : '-' ?></div>
                                            <div class="small"><span class="text-muted">Monthly: </span><?php echo ($product->price_monthly > 0) ? "$" . number_format($product->price_monthly, 2) : '-' ?></div>
                                            <div class="small"><span class="text-muted">One-Time: </span><?php echo ($product->price_one_time > 0) ? "$" . number_format($product->price_one_time, 2) : '-' ?></div>
                                        </td>
                                        <td class="text-right">
                                            <span class="date-for-sort d-none"><?php echo $product->created ?></span>
                                            <span class="date" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="<?php echo date('H:ia', strtotime($product->created)) ?>"><?php echo date('j M, Y', strtotime($product->created)) ?></span>
                                        </td>
                                        <td class="text-center"><?php echo $product->version ?></td>
                                        <td class="text-right">
                                            <a href="<?php echo base_url("admin/product/edit/{$product->id}") ?>" class="btn btn-secondary btn-sm"><i class="fe fe-edit-2"></i> Edit</a>

                                        </td>
                                        </a>
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