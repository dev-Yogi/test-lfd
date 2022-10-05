<?php $this->load->view('admin/header') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="page-header">
                        <h1 class="page-title">
                            Students <span class="text-muted small"><span class="p-2">&middot;</span> Import</span>
                        </h1>
                    </div>

                    <form method="post" enctype="multipart/form-data">
                        <?php echo alerts() ?>
                        <div class="card p-5 mb-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php foreach ($messages as $message): ?>
                                        <b><?php echo strtoupper($message[0]) ?></b> - <?php echo $message[1] ?><br>
                                    <?php endforeach ?>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col mt-5 d-flex">
                                    <a href="<?php echo base_url('admin/student/import') ?>" class="btn btn-primary">Back to Import Page</a>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($student)): ?>
                            <!-- <a href="<?php echo base_url("admin/student/remove/{$student->id}") ?>" onclick="return confirm('Are you sure you want to remove this student?')" class="btn btn-link float-right mr-3"><i class="fe fe-trash"></i> Remove Student</a> -->
                        <?php endif ?>
                    </form>
                </div>
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
        <img src="<?php echo base_url('img/admin/student_import_example.png') ?>">
        <p class="mt-4">Details:</p>
        <ul>
            <li>Columns must be Last Name, First Name, Email in order</li>
            <li>Must be saved as a comma-separated file (CSV)</li>
            <li>Rows holding emails that already exist in the portal will not be imported</li>
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('admin/footer') ?>