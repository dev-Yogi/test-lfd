<?php $this->load->view('admin/header') ?>
<div class="container lesson">
    <div class="page-header">
        <h1 class="page-title">
            Fields
            <small class="ml-2 text-muted">All Fields</small>
        </h1>
    </div>
    <div class="row">

        <div class="col-lg-3 mb-4 lesson-side">
            <a href="<?php echo base_url("admin/settings/field") ?>" class="btn btn-secondary btn-block mb-3"><i class="fe fe-chevron-left mr-2"></i>Back to Program Fields</a>
            <a href="<?php echo base_url("admin/settings/field_add") ?>" class="btn btn-primary btn-block mb-3"><i class="fe fe-plus mr-2"></i>Add Field</a>
            <div class="card">
                <div class="card-body">
                    This is the list of all fields that can be used across the programs.
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <?php echo alerts() ?>
            <div class="card">
                <div class="card-header">
                    Global Fields
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter table-hover">
                        <thead>
                            <tr>
                                <th>Field</th>
                                <th>Type</th>
                                <th width="120">Created</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($fields as $field): ?>
                                <tr>
                                    <td>
                                        <?php echo $field->label ?><br>
                                    </td>
                                    <td><?php echo ucwords($field->type) ?></td>
                                    <td><?php echo date('j M, Y', strtotime($field->created)) ?></td>
                                    <td class="text-right">
                                        <?php if ($field->type == 'select'): ?>
                                            <a href="<?php echo base_url("admin/settings/field_edit/{$field->id}") ?>" class="btn btn-secondary btn-sm" ><i class="fe fe-edit-2"></i> Edit</a>
                                        <?php endif ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                            <?php if (empty($fields)): ?>
                                <tr>
                                    <td class="text-muted">No fields</td>
                                </tr>
                            <?php endif ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('admin/footer') ?>