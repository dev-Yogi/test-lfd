<?php $this->load->view('admin/header') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="page-header">
                        <h1 class="page-title">
                            Video <span class="text-muted small"><span class="p-2">&middot;</span> <?php echo empty($lesson) ? "New" : "Edit" ?></span>
                        </h1>
                    </div>
                    <form method="post">
                        <?php echo alerts() ?>
                        <div class="card p-5 mb-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Lesson</label>
                                        <input type="text" class="form-control-plaintext" name="title" value="<?php echo $lesson->title ?>">
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label class="form-label">Video Name</label>
                                        <input type="text" class="form-control <?php echo is_valid('label') ?>" name="label" value="<?php echo set_value('label', ($video->label ?? null)) ?>">
                                        <?php echo form_error('label') ?>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Video Description <small class="text-muted">(Optional)</small></label>
                                        <input type="text" class="form-control <?php echo is_valid('description') ?>" name="description" value="<?php echo set_value('description', ($video->description ?? null)) ?>">
                                        <?php echo form_error('description') ?>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Video URL</label>
                                        <input type="text" class="form-control <?php echo is_valid('url') ?>" name="url" value="<?php echo set_value('url', ($video->url ?? null)) ?>">
                                        <small class="form-text text-muted">Your Youtube or Google Meet URL e.g. https://meet.google.com/ytm-sggp-abc</small>
                                        <?php echo form_error('url') ?>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox mt-3 mr-sm-2">
                                            <input type="checkbox" class="custom-control-input" name="is_live_conference" id="is_live_conference" value="1" <?php echo set_checkbox('is_live_conference', '1', !empty($video->start_time)) ?>>
                                            <label class="custom-control-label" for="is_live_conference">This is a live conference</label>
                                        </div>
                                    </div>
                                    <?php $formatted_date = ($video->start_time ?? null) ? date('m/d/Y', strtotime($video->start_time)) : null ?>
                                    <?php $formatted_hour = ($video->start_time ?? null) ? date('H', strtotime($video->start_time)) : null ?>
                                    <?php $formatted_minute = ($video->start_time ?? null) ? date('i', strtotime($video->start_time)) : null ?>
                                    <fieldset class="form-fieldset" style="display:none">
                                        <div class="form-group mr-2">
                                        <label class="form-label">Conference Date</label>
                                            <div class="input-group <?php echo is_valid('start_date') ?>">
                                                <div class="input-group-prepend">
                                                    <button type="button" class="btn btn-secondary dropdown-toggle open-date-picker" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fe fe-calendar"></i>
                                                    </button>
                                                </div>
                                                <input type="text" class="form-control date-picker <?php echo is_valid('start_date') ?>" name="start_date" data-inputmask-alias="datetime" data-inputmask-inputformat="MM/DD/YYYY" placeholder="MM/DD/YYYY" value="<?php echo set_value('start_date', $formatted_date) ?>">
                                                <?php echo form_error('start_date') ?>
                                            </div>
                                        </div>
                                        <label class="form-label">Start Time</label>
                                        <div class="form-inline">
                                            <div class="form-group d-flex">
                                                <select class="form-control <?php echo is_valid('start_time_hour') ?>" name="start_time_hour">
                                                    <option></option>
                                                    <option value="0" <?php echo set_select('start_time_hour', '0', ($video->start_time ?? null) ? $formatted_hour == '0' : false ) ?>>00</option>
                                                    <option value="1" <?php echo set_select('start_time_hour', '1', ($video->start_time ?? null) ? $formatted_hour == '1' : false ) ?>>01</option>
                                                    <option value="2" <?php echo set_select('start_time_hour', '2', ($video->start_time ?? null) ? $formatted_hour == '2' : false ) ?>>02</option>
                                                    <option value="3" <?php echo set_select('start_time_hour', '3', ($video->start_time ?? null) ? $formatted_hour == '3' : false ) ?>>03</option>
                                                    <option value="4" <?php echo set_select('start_time_hour', '4', ($video->start_time ?? null) ? $formatted_hour == '4' : false ) ?>>04</option>
                                                    <option value="5" <?php echo set_select('start_time_hour', '5', ($video->start_time ?? null) ? $formatted_hour == '5' : false ) ?>>05</option>
                                                    <option value="6" <?php echo set_select('start_time_hour', '6', ($video->start_time ?? null) ? $formatted_hour == '6' : false ) ?>>06</option>
                                                    <option value="7" <?php echo set_select('start_time_hour', '7', ($video->start_time ?? null) ? $formatted_hour == '7' : false ) ?>>07</option>
                                                    <option value="8" <?php echo set_select('start_time_hour', '8', ($video->start_time ?? null) ? $formatted_hour == '8' : false ) ?>>08</option>
                                                    <option value="9" <?php echo set_select('start_time_hour', '9', ($video->start_time ?? null) ? $formatted_hour == '9' : false ) ?>>09</option>
                                                    <option value="10" <?php echo set_select('start_time_hour', '10', ($video->start_time ?? null) ? $formatted_hour == '10' : false ) ?>>10</option>
                                                    <option value="11" <?php echo set_select('start_time_hour', '11', ($video->start_time ?? null) ? $formatted_hour == '11' : false ) ?>>11</option>
                                                    <option value="12" <?php echo set_select('start_time_hour', '12', ($video->start_time ?? null) ? $formatted_hour == '12' : false ) ?>>12</option>
                                                    <option value="13" <?php echo set_select('start_time_hour', '13', ($video->start_time ?? null) ? $formatted_hour == '13' : false ) ?>>13</option>
                                                    <option value="14" <?php echo set_select('start_time_hour', '14', ($video->start_time ?? null) ? $formatted_hour == '14' : false ) ?>>14</option>
                                                    <option value="15" <?php echo set_select('start_time_hour', '15', ($video->start_time ?? null) ? $formatted_hour == '15' : false ) ?>>15</option>
                                                    <option value="16" <?php echo set_select('start_time_hour', '16', ($video->start_time ?? null) ? $formatted_hour == '16' : false ) ?>>16</option>
                                                    <option value="17" <?php echo set_select('start_time_hour', '17', ($video->start_time ?? null) ? $formatted_hour == '17' : false ) ?>>17</option>
                                                    <option value="18" <?php echo set_select('start_time_hour', '18', ($video->start_time ?? null) ? $formatted_hour == '18' : false ) ?>>18</option>
                                                    <option value="19" <?php echo set_select('start_time_hour', '19', ($video->start_time ?? null) ? $formatted_hour == '19' : false ) ?>>19</option>
                                                    <option value="20" <?php echo set_select('start_time_hour', '20', ($video->start_time ?? null) ? $formatted_hour == '20' : false ) ?>>20</option>
                                                    <option value="21" <?php echo set_select('start_time_hour', '21', ($video->start_time ?? null) ? $formatted_hour == '21' : false ) ?>>21</option>
                                                    <option value="22" <?php echo set_select('start_time_hour', '22', ($video->start_time ?? null) ? $formatted_hour == '22' : false ) ?>>22</option>
                                                    <option value="23" <?php echo set_select('start_time_hour', '23', ($video->start_time ?? null) ? $formatted_hour == '23' : false ) ?>>23</option>
                                                </select>
                                                :
                                                <select class="form-control <?php echo is_valid('start_time_minute') ?>" name="start_time_minute">
                                                    <option></option>
                                                    <option value="0" <?php echo set_select('start_time_minute', '0', ($video->start_time ?? null) ? $formatted_minute == '0' : false ) ?>>00</option>
                                                    <option value="10" <?php echo set_select('start_time_minute', '10', ($video->start_time ?? null) ? $formatted_minute == '10' : false ) ?>>10</option>
                                                    <option value="20" <?php echo set_select('start_time_minute', '20', ($video->start_time ?? null) ? $formatted_minute == '20' : false ) ?>>20</option>
                                                    <option value="30" <?php echo set_select('start_time_minute', '30', ($video->start_time ?? null) ? $formatted_minute == '30' : false ) ?>>30</option>
                                                    <option value="40" <?php echo set_select('start_time_minute', '40', ($video->start_time ?? null) ? $formatted_minute == '40' : false ) ?>>40</option>
                                                    <option value="50" <?php echo set_select('start_time_minute', '50', ($video->start_time ?? null) ? $formatted_minute == '50' : false ) ?>>50</option>
                                                </select>
                                                <?php echo form_error('start_time_hour') ?>
                                                <?php echo form_error('start_time_minute') ?>
                                            </div>
                                        </div>

                                        <?php $formatted_hour = ($video->stop_time ?? null) ? date('H', strtotime($video->stop_time)) : null ?>
                                        <?php $formatted_minute = ($video->stop_time ?? null) ? date('i', strtotime($video->stop_time)) : null ?>
                                        <label class="form-label mt-3">End Time</label>
                                        <div class="form-inline">
                                            <div class="form-group d-flex">
                                                <select class="form-control <?php echo is_valid('stop_time_hour') ?>" name="stop_time_hour">
                                                    <option></option>
                                                    <option value="0" <?php echo set_select('stop_time_hour', '0', ($video->stop_time ?? null) ? $formatted_hour == '0' : false ) ?>>00</option>
                                                    <option value="1" <?php echo set_select('stop_time_hour', '1', ($video->stop_time ?? null) ? $formatted_hour == '1' : false ) ?>>01</option>
                                                    <option value="2" <?php echo set_select('stop_time_hour', '2', ($video->stop_time ?? null) ? $formatted_hour == '2' : false ) ?>>02</option>
                                                    <option value="3" <?php echo set_select('stop_time_hour', '3', ($video->stop_time ?? null) ? $formatted_hour == '3' : false ) ?>>03</option>
                                                    <option value="4" <?php echo set_select('stop_time_hour', '4', ($video->stop_time ?? null) ? $formatted_hour == '4' : false ) ?>>04</option>
                                                    <option value="5" <?php echo set_select('stop_time_hour', '5', ($video->stop_time ?? null) ? $formatted_hour == '5' : false ) ?>>05</option>
                                                    <option value="6" <?php echo set_select('stop_time_hour', '6', ($video->stop_time ?? null) ? $formatted_hour == '6' : false ) ?>>06</option>
                                                    <option value="7" <?php echo set_select('stop_time_hour', '7', ($video->stop_time ?? null) ? $formatted_hour == '7' : false ) ?>>07</option>
                                                    <option value="8" <?php echo set_select('stop_time_hour', '8', ($video->stop_time ?? null) ? $formatted_hour == '8' : false ) ?>>08</option>
                                                    <option value="9" <?php echo set_select('stop_time_hour', '9', ($video->stop_time ?? null) ? $formatted_hour == '9' : false ) ?>>09</option>
                                                    <option value="10" <?php echo set_select('stop_time_hour', '10', ($video->stop_time ?? null) ? $formatted_hour == '10' : false ) ?>>10</option>
                                                    <option value="11" <?php echo set_select('stop_time_hour', '11', ($video->stop_time ?? null) ? $formatted_hour == '11' : false ) ?>>11</option>
                                                    <option value="12" <?php echo set_select('stop_time_hour', '12', ($video->stop_time ?? null) ? $formatted_hour == '12' : false ) ?>>12</option>
                                                    <option value="13" <?php echo set_select('stop_time_hour', '13', ($video->stop_time ?? null) ? $formatted_hour == '13' : false ) ?>>13</option>
                                                    <option value="14" <?php echo set_select('stop_time_hour', '14', ($video->stop_time ?? null) ? $formatted_hour == '14' : false ) ?>>14</option>
                                                    <option value="15" <?php echo set_select('stop_time_hour', '15', ($video->stop_time ?? null) ? $formatted_hour == '15' : false ) ?>>15</option>
                                                    <option value="16" <?php echo set_select('stop_time_hour', '16', ($video->stop_time ?? null) ? $formatted_hour == '16' : false ) ?>>16</option>
                                                    <option value="17" <?php echo set_select('stop_time_hour', '17', ($video->stop_time ?? null) ? $formatted_hour == '17' : false ) ?>>17</option>
                                                    <option value="18" <?php echo set_select('stop_time_hour', '18', ($video->stop_time ?? null) ? $formatted_hour == '18' : false ) ?>>18</option>
                                                    <option value="19" <?php echo set_select('stop_time_hour', '19', ($video->stop_time ?? null) ? $formatted_hour == '19' : false ) ?>>19</option>
                                                    <option value="20" <?php echo set_select('stop_time_hour', '20', ($video->stop_time ?? null) ? $formatted_hour == '20' : false ) ?>>20</option>
                                                    <option value="21" <?php echo set_select('stop_time_hour', '21', ($video->stop_time ?? null) ? $formatted_hour == '21' : false ) ?>>21</option>
                                                    <option value="22" <?php echo set_select('stop_time_hour', '22', ($video->stop_time ?? null) ? $formatted_hour == '22' : false ) ?>>22</option>
                                                    <option value="23" <?php echo set_select('stop_time_hour', '23', ($video->stop_time ?? null) ? $formatted_hour == '23' : false ) ?>>23</option>
                                                </select>
                                                :
                                                <select class="form-control <?php echo is_valid('stop_time_minute') ?>" name="stop_time_minute">
                                                    <option></option>
                                                    <option value="0" <?php echo set_select('stop_time_minute', '0', ($video->stop_time ?? null) ? $formatted_minute == '0' : false ) ?>>00</option>
                                                    <option value="10" <?php echo set_select('stop_time_minute', '10', ($video->stop_time ?? null) ? $formatted_minute == '10' : false ) ?>>10</option>
                                                    <option value="20" <?php echo set_select('stop_time_minute', '20', ($video->stop_time ?? null) ? $formatted_minute == '20' : false ) ?>>20</option>
                                                    <option value="30" <?php echo set_select('stop_time_minute', '30', ($video->stop_time ?? null) ? $formatted_minute == '30' : false ) ?>>30</option>
                                                    <option value="40" <?php echo set_select('stop_time_minute', '40', ($video->stop_time ?? null) ? $formatted_minute == '40' : false ) ?>>40</option>
                                                    <option value="50" <?php echo set_select('stop_time_minute', '50', ($video->stop_time ?? null) ? $formatted_minute == '50' : false ) ?>>50</option>
                                                </select>
                                                <?php echo form_error('stop_time_hour') ?>
                                                <?php echo form_error('stop_time_minute') ?>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col mt-5">
                                    <button class="btn btn-primary"><?php echo empty($video) ? "Create" : "Update" ?></button>
                                    <a href="<?php echo base_url("admin/video/list/{$lesson->id}") ?>" class="btn btn-secondary ml-2">Cancel</a>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($video)): ?>
                            <a href="<?php echo base_url("admin/video/remove/{$video->id}") ?>" onclick="return confirm('Are you sure you want to remove this video?')" class="btn btn-link float-right mr-3"><i class="fe fe-trash"></i> Remove Video</a>
                        <?php endif ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $script = <<<'EOT'
$('[name=is_live_conference]').on('change', function() {
    var specify_each = $('[name=is_live_conference]').is(':checked');
    if(specify_each) {
        $('.form-fieldset').slideDown('fast');
    } else {
        $('.form-fieldset').slideUp('fast');
    }
});
$('[name=is_live_conference]').change();
EOT;
?>
<?php $this->load->view('admin/footer', compact('script')) ?>