<?php $this->load->view('header') ?>
<div class="container">
    <div class="page-header">
        <div>
            <h1 class="page-title">Offerings <small class="ml-2 text-muted">All</small></h1>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header d-flex">
            <h3 class="card-title">Your Offerings</h3>
            <a href="<?php echo base_url('offering/submit') ?>" class="btn btn-primary ml-auto"><i class="fe fe-plus"></i>Submit New Offering</a>
        </div>

        <?php alerts() ?>
        <table class="table card-table table-hover data-table data-table-offerings">
            <thead>
                <tr>
                    <th class="pl-5">ID</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th>Start Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($offerings as $offering) : ?>
                    <tr>
                        <td class="pl-5 text-muted"><?php echo $offering->id ?></td>
                        <td><?php echo $offering->title ?></td>
                        <td><?php echo ucwords($offering->status) ?></td>
                        <td><?php echo date('j M, Y', strtotime($offering->created)) ?></td>
                        <td><?php echo date('j M, Y', strtotime($offering->start_date)) ?></td>
                        <td class="text-right">
                            <a href="<?php echo base_url("offering/edit/{$offering->id}") ?>" class="btn btn-secondary btn-sm"><i class="fe fe-edit-2"></i> Edit</a>
                        </td>
                    </tr>
                <?php endforeach ?>
                <?php if (empty($offerings)): ?>
                    <tr>
                        <td class="text-muted" colspan="6">You do not have any submitted offerings - Click "Submit New Offering" to create a new one.</td>
                    </tr>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</div>
    <?php $this->load->view('footer') ?>