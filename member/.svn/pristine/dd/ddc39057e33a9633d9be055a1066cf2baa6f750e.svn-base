<?php $this->load->view('admin/header') ?>
<?php $this->load->view('admin/menu') ?>
<div class="page px-5">
    <div class="page-main">
        <div class="page-header">
            <h1 class="page-title">
                Offerings
            </h1>
        </div>
        <a href="<?php echo base_url('admin/offering') ?>" class="btn btn-secondary mb-5"><i class="fe fe-chevron-left"></i> Back</a>
        <?php alerts() ?>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mr-auto">Offerings</h3>
                <div class="pr-0">

                    <form class="input-icon">
                        <input type="search" class="form-control header-search data-table-search" placeholder="Search…" tabindex="1">
                        <div class="input-icon-addon">
                            <i class="fe fe-search"></i>
                        </div>
                    </form>
                </div>
            </div>
                <table class="table card-table table-hover data-table data-table-offerings-all-columns">
                    <thead>
                        <tr>
                            <?php foreach($offerings[0] as $key => $value): ?>
                                <td><?php echo $key ?></td>
                            <?php endforeach ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($offerings as $offering) : ?>
                            <tr>

                                <?php foreach($offering as $key => $value): ?>
                                    <td><?php echo ucwords($value) ?></td>
                                <?php endforeach ?>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
        </div>
        <div class="data-table-pagination float-left"></div>
        <div class="data-table-records-info d-none"></div>
        <div class="data-table-show-per-page float-right"></div>
    </div>
</div>

<style>
    #DataTables_Table_0_wrapper {
        overflow-x: auto;
    }
    #DataTables_Table_0 {
        width: 12000px !important;
    }
</style>
<?php $this->load->view('admin/footer') ?>