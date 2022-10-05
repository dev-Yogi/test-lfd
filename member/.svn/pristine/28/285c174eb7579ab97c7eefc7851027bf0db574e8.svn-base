<?php $this->load->view('admin/header') ?>
<?php $this->load->view('admin/menu') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="page-header">
                        <h1 class="page-title">
                            NetSuite <span class="text-muted small"><span class="p-2">&middot;</span> Contact Lookup</span>
                        </h1>
                    </div>
                    <?php echo alerts() ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <form method="post" action="<?php echo base_url('admin/netsuite/contact_lookup') ?>">
                        <div class="card p-5 mb-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Contact Email</label>
                                        <input type="text" class="form-control <?php echo is_valid('email') ?>" name="email" value="<?php echo set_value('email', $link->email ?? "" ) ?>">
                                        <?php echo form_error('email') ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <button class="btn btn-primary">Check</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-6">
                    <form method="post" enctype="multipart/form-data" action="<?php echo base_url('admin/netsuite/contact_lookup_upload') ?>">
                        <div class="card p-5 mb-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">CSV File</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input <?php echo is_valid('csv') ?>" name="csv">
                                            <label class="custom-file-label">Choose file</label>
                                            <?php echo form_error('csv') ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <button class="btn btn-primary float-left">Upload</button>
                                    <div class="small text-muted d-inline-block mt-2 ml-3">~30 secs/100 records</div>
                                    <button type="button" class="btn btn-secondary btn-sm float-right mt-2" data-toggle="modal" data-target="#importExample">Import File Instructions</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <?php if ($this->input->method() == 'post' || ($this->session->flashdata('netsuite_contacts_results'))): ?>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title mr-auto">NetSuite Contacts</h3>
                                <?php if ($this->session->flashdata('netsuite_contacts_results')): ?>
                                    <?php if (empty($this->input->get('missing_only')) || $this->input->get('missing_only') == 'false'): ?>
                                        <a href="<?php echo base_url("admin/netsuite/contact_lookup_upload/?missing_only=true") ?>" class="btn btn-secondary btn-sm mr-3"><span><i class="fe fe-eye"></i> Missing Contacts Only</span></a>
                                    <?php else: ?>
                                        <a href="<?php echo base_url("admin/netsuite/contact_lookup_upload/?missing_only=false") ?>" class="btn btn-secondary btn-sm mr-3"><span><i class="fe fe-eye"></i> All Contacts</span></a>
                                    <?php endif ?>
                                <?php endif ?>
                                <div class="pr-0">
                                    <form class="input-icon">
                                        <input type="search" class="form-control header-search data-table-search" placeholder="Search this results set…" tabindex="1">
                                        <div class="input-icon-addon">
                                            <i class="fe fe-search"></i>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table card-table table-hover data-table-netsuite-contacts">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Title</th>
                                            <th>Company</th>
                                            <th>Email</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($contacts)): ?>
                                            <tr>
                                                <td colspan="4" class="text-muted">No matching contacts found</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach($contacts as $contact): ?>
                                                <tr>
                                                    <td><?php echo $contact->internalId ?? null ?></td>
                                                    <td><?php echo $contact->firstName ?? null ?></td>
                                                    <td><?php echo $contact->lastName ?? null ?></td>
                                                    <td><?php echo $contact->title ?? null ?></td>
                                                    <td><?php echo ($contact->company_name?? null) ? $contact->company_name : ($contact->company->name ?? null) ?></td>
                                                    <td><?php echo $contact->email ?? null ?></td>
                                                </tr>
                                            <?php endforeach ?>
                                        <?php endif ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="data-table-pagination float-left"></div>
                        <div class="data-table-records-info d-none"></div>
                        <div class="data-table-show-per-page float-right"></div>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="importExample" tabindex="-1" role="dialog" aria-labelledby="importExampleLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importExampleLabel">Import Instructions</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <img src="<?php echo base_url('img/admin/netsuite import example.png') ?>">
                <p class="mt-4">Details:</p>
                <ul>
                    <li>Columns must be first name, last name, title, company, email</li>
                    <li>Must be saved as a comma-separated file (CSV)</li>
                    <li>The email will be used to look up Netsuite contacts</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('admin/footer') ?>