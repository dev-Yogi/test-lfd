<?php $this->load->view('admin/header') ?>
<?php $this->load->view('admin/menu') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">
                    Dashboard
                </h1>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <a href="<?php echo base_url() ?>" class="btn btn-secondary"><i class="fe fe-corner-up-left mr-2"></i>Go to Member Portal</a>
                    <hr>
                </div>
                <div class="col-lg-6">
                    <div class="card p-5">
                        <a href="<?php echo base_url('admin/member/create') ?>" class="btn btn-primary text-left mb-3"><i class="fe fe-plus mr-2"></i> Set up new member</a>
                        <a href="<?php echo base_url('admin/dashboard/staff_users') ?>" class="btn btn-secondary text-left mb-3 disabled"><i class="fe fe-settings mr-2"></i> Staff Users <i class="fe fe-chevron-right float-right mt-1"></i></a>
                        <a href="" class="btn btn-secondary text-left mb-3 disabled"><i class="fe fe-settings mr-2"></i> User Groups <i class="fe fe-chevron-right float-right mt-1"></i></a>
                        <?php if (has_tag(Tag::ADMIN)): ?>
                            <a href="<?php echo base_url('admin/staff') ?>" class="btn btn-secondary text-left mb-3"><i class="fe fe-settings mr-2"></i> Manage Staff Portal <i class="fe fe-chevron-right float-right mt-1"></i></a>
                        <?php endif ?>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card p-5">
                        <a href="<?php echo base_url('admin/dashboard/contact_form') ?>" class="btn btn-secondary text-left mb-3"><i class="fe fe-file-text mr-2"></i> Contact Form Submissions <i class="fe fe-chevron-right float-right mt-1"></i></a>
                        <a href="<?php echo base_url('admin/eoc') ?>" class="btn btn-secondary text-left mb-3"><i class="fe fe-file-text mr-2"></i> EOC Submissions <i class="fe fe-chevron-right float-right mt-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('admin/footer') ?>