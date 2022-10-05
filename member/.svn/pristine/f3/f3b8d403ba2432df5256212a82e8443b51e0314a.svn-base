<?php $this->load->view('admin/header') ?>
<?php $this->load->view('admin/menu') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">
                    Zipcodes
                </h1>
            </div>
            <div class="row">
                <div class="col">
                    <a href="<?php echo base_url('admin/offering') ?>" class="btn btn-secondary mb-5"><i class="fe fe-chevron-left"></i>  Back to Offerings</a>
                    <?php alerts() ?>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title mr-auto">Zipcodes</h3>
                            <div class="pr-0">
                                <form class="input-icon">
                                    <input type="search" class="form-control header-search data-table-search" placeholder="Search…" tabindex="1">
                                    <div class="input-icon-addon">
                                        <i class="fe fe-search"></i>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table card-table table-hover data-table data-table-offering-zipcodes">
                                <thead>
                                    <tr>
                                        <th class="pl-5">ZIP</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($records as $record) : ?>
                                        <tr>
                                            <td class="pl-5">
                                                <?php echo $record->zip ?>
                                            </td>
                                            <td>
                                                <?php echo $record->created ?>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="data-table-pagination float-left"></div>
                    <div class="data-table-records-info d-none"></div>
                    <div class="data-table-show-per-page float-right"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('admin/footer') ?>