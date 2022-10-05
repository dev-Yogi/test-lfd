<?php $this->load->view('admin/header') ?>
<div class="container lesson">
    <div class="page-header">
        <h1 class="page-title">
            Fields
            <small class="ml-2 text-muted"><?php echo $this->program->name ?></small>
        </h1>
    </div>
    <div class="row">

        <div class="col-lg-3 mb-4 lesson-side">
            <a href="<?php echo base_url("admin/settings") ?>" class="btn btn-secondary btn-block mb-3"><i class="fe fe-chevron-left mr-2"></i>Back to Settings</a>
            <a href="<?php echo base_url("admin/settings/field_add_to_program") ?>" class="btn btn-primary btn-block mb-3"><i class="fe fe-plus mr-2"></i>Add Field</a>
            <a href="<?php echo base_url("admin/settings/field/all") ?>" class="btn btn-secondary btn-block mb-3"><i class="fe fe-list mr-2"></i>Manage Fields</a>
            <div class="card">
                <div class="card-body">
                    <b>Fields</b> can be added to the student creation form for additional information about the student, such as school name and company name. You can add existing fields to this program, or create a new one.
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <?php echo alerts() ?>
            <div class="card">
                <div class="card-header">
                    <?php echo $this->program->name ?>'s Fields
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
                                        <a href="<?php echo base_url("admin/settings/field_remove_from_program/{$field->id}") ?>" class="btn btn-secondary btn-sm" onclick="return confirm('Are you sure you want to remove this field from this program\'s student forms? This will not affect other programs.')" ><i class="fe fe-trash"></i> Remove from this Program</a>
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