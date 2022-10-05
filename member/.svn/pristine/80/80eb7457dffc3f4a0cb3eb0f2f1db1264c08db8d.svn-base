<?php $this->load->view('header-naked') ?>
<div class="page p-5">
    <div class="page-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-8 col-sm-10 offset-lg-3 offset-md-2 offset-sm-1">
                    <div class="page-header">
                        <h1 class="page-title">
                            Payment
                        </h1>
                    </div>
                    <?php echo alerts() ?>

                    <div class="card">
                        <table class="table table-card m-0">
                            <tr>
                                <th class="pl-5">Product</th>
                                <th class="text-center">Type</th>
                                <th class="text-right pr-5">Price</th>
                            </tr>
                            <tr>
                                <td class="pl-5"><?php echo $product->label ?></td>
                                <td class="text-center"><?php echo ucwords($invoice->type) ?></td>
                                <td class="text-right pr-5"><?php echo number_format($invoice->amount, 2) ?></td>
                            </tr>
                            <tr class="bg-light">
                                <td></td>
                                <td class="text-muted text-right">Total</td>
                                <td class="text-right font-weight-semibold pr-5">$<?php echo number_format($invoice->amount, 2) ?></td>
                            </tr>
                        </table>
                        <div class="card-body">
                            <div id="iframe_holder" class="center-block" style="width:100%;max-width: 1000px">
                                <iframe id="add_payment" class="embed-responsive-item panel" name="add_payment" width="100%" height="900px" frameborder="0">
                                </iframe>
                            </div>
                            <form id="send_token" action="https://test.authorize.net/payment/payment" method="post" target="add_payment">
                                <input type="hidden" name="token" value="<?php echo $token->getToken() ?>" />
                            </form>
                            <?php if ($invoice->pay_later_option == 1) : ?>
                                <a href="<?php echo base_url('employer') ?>" class="btn btn-secondary float-right btn-sm">Pay later <i class="fe fe-chevron-right"></i></a>
                            <?php endif ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $js = array(base_url('js/payment.js')); ?>
<?php $this->load->view('footer-naked', compact('js')) ?>