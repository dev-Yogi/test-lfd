<?php $this->load->view('admin/header') ?>
<?php $this->load->view('admin/menu') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">
                    Contact Form
                </h1>
            </div>
            <div class="row">
                <div class="col">
                    <div class="card">
                        <table class="table card-table table-hover">
                            <thead>
                                <tr>
                                    <th class="pl-5">ID</th>
                                    <th>Type</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Message</th>
                                    <th class="text-right">Created</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($submissions as $submission) : ?>
                                    <tr>
                                        <td class="pl-5 text-muted"><?php echo $submission->id ?></td>
                                        <td><?php echo ucwords($submission->type) ?></td>
                                        <td><?php echo $submission->first_name . " " . $submission->last_name ?></td>
                                        <td><?php echo $submission->email ?></td>
                                        <td>
                                            <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#submission<?php echo $submission->id ?>">View</button>
                                            <div class="modal fade" id="submission<?php echo $submission->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-body">
                                                            <?php echo $submission->message ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-right">
                                            <span class="date-for-sort d-none"><?php echo $submission->created ?></span>
                                            <?php echo date('j M, Y', strtotime($submission->created)) ?>
                                        </td>
                                        <td class="text-right">
                                            
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