<table class="table card-table table-hover">
    <thead>
        <tr>
            <th class="pl-5">ID</th>
            <th>Recipient</th>
            <th>Status</th>
            <th>Product</th>
            <th>Type</th>
            <th class="text-right">Created</th>
            <th class="text-right">Modified</th>
            <th class="text-right pr-5">Amount</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($invoices as $invoice) : ?>
            <tr>
                <td class="pl-5"><?php echo $invoice->id ?></td>
                <td><?php echo $invoice->first_name ?> <?php echo $invoice->last_name ?></td>
                <td><?php echo ucwords($invoice->status) ?></td>
                <td><?php echo $invoice->label ?></td>
                <td><?php echo ucwords($invoice->type) ?></td>
                <td class="text-right">
                    <span class="date-for-sort d-none"><?php echo $invoice->created ?></span>
                    <span class="date" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="<?php echo date('H:ia', strtotime($invoice->created)) ?>"><?php echo date('j M, Y', strtotime($invoice->created)) ?></span>
                </td>
                <td class="text-right">
                    <span class="date-for-sort d-none"><?php echo $invoice->modified ?></span>
                    <span class="date" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="<?php echo date('H:ia', strtotime($invoice->modified)) ?>"><?php echo date('j M, Y', strtotime($invoice->modified)) ?></span>
                </td>
                <td class="text-right pr-5">$<?php echo number_format($invoice->amount, 2) ?></td>
                <td class="text-right">
                    <?php if ($invoice->status == 'sent'): ?>
                        <?php $hash = md5($invoice->created) ?>
                        <a href="<?php echo base_url("admin/invoice/void/{$invoice->id}") ?>" target="_blank" onclick="return confirm('Are you sure you want to void this invoice?')" class="btn btn-secondary btn-sm"><i class="fe fe-alert-triangle"></i> Void</a>
                        <a href="<?php echo base_url("payment/pay/{$invoice->id}/{$hash}") ?>" target="_blank" class="btn btn-secondary btn-sm"><i class="fe fe-credit-card"></i> Payment Link</a>
                        <!-- <a href="<?php echo base_url("employer/invoice/view/{$invoice->id}") ?>" class="btn btn-secondary btn-sm"><i class="fe fe-eye"></i> View</a> -->
                    <?php endif ?>
                </td>
                </a>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>