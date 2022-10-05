<form method="post" enctype="multipart/form-data">

    <!-- PROGRAM -->
    <h2 class="mt-2 mb-4"><i class="fa fa-chevron-right" aria-hidden="true"></i>Offering</h2>

    <?php if (!empty($offering) && in_array($offering->status, ['draft', 'published'])): ?>
        <div class="form-group">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input <?php echo is_valid('status') ?>" name="status" value="published" id="customCheck1" <?php echo set_checkbox('status', '1', !isset($offering->status) ? null : $offering->status == 'published') ?> >
                <label class="custom-control-label" for="customCheck1">Published</label>
                <small class="form-text text-muted mt-0">Unchecking this box will take the offering off the catalogue</small>
                <?php echo form_error('status') ?>
            </div>
        </div>
    <?php endif ?>

    <div class="form-group">
        <label class="required">Offering Name</label>
        <input type="text" class="form-control <?php echo is_valid('title') ?>" name="title" value="<?php echo set_value('title', $offering->title ?? null) ?>">
        <?php echo form_error('title') ?>
    </div>

    <div class="form-group">
        <label class="required">Offering Description</label>
        <textarea class="form-control <?php echo is_valid('description') ?>" rows="8" name="description"><?php echo set_value('description', $offering->description ?? null) ?></textarea>
        <?php echo form_error('description') ?>
    </div>

    <div class="form-group">
        <label class="optional">Offering Page URL</label>
        <input type="text" class="form-control <?php echo is_valid('url') ?>" name="url" value="<?php echo set_value('url', $offering->url ?? null) ?>">
        <small class="form-text text-muted">Website or event page where participants can get information.</small>
        <?php echo form_error('url') ?>
    </div>

    <div class="form-group">
        <label class="optional">Registration/Apply URL</label>
        <input type="text" class="form-control <?php echo is_valid('registration_url') ?>" name="registration_url" value="<?php echo set_value('registration_url', $offering->registration_url ?? null) ?>">
        <small class="form-text text-muted">Link to register for the offering.</small>
        <?php echo form_error('registration_url') ?>
    </div>

    <div class="form-group">
        <label class="required">Internship Type</label>
        <div class="form-check">
            <input class="form-check-input" type="radio" <?php echo set_radio('internship_type', 'paid', !isset($offering->internship_type) ? null : $offering->internship_type == 'paid') ?> name="internship_type" id="internship-type-paid" value="paid">
            <label class="form-check-label" for="internship-type-paid">Paid</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" <?php echo set_radio('internship_type', 'unpaid', !isset($offering->internship_type) ? null : $offering->internship_type == 'unpaid') ?> name="internship_type" id="internship-type-unpaid" value="unpaid">
            <label class="form-check-label" for="internship-type-unpaid">Unpaid</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" <?php echo set_radio('internship_type', '0', !isset($offering->internship_type) ? true : $offering->internship_type == null) ?> name="internship_type" id="internship-type-0" value="0">
            <label class="form-check-label" for="internship-type-0">Not an internship</label>
        </div>
        <?php echo form_error('internship_type') ?>
    </div>

    <div class="form-group" data-expand="internship_type" data-expand-if="paid,unpaid">
        <label class="required">Internship Term</label>
        <div class="form-check">
            <input class="form-check-input" type="radio" <?php echo set_radio('internship_term', 'summer', !isset($offering->internship_term) ? null : $offering->internship_term == 'summer') ?> name="internship_term" id="internchip-term-summer" value="summer">
            <label class="form-check-label" for="internchip-term-summer">Summer</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" <?php echo set_radio('internship_term', 'year-long', !isset($offering->internship_term) ? null : $offering->internship_term == 'year-long') ?> name="internship_term" id="internchip-term-year-long" value="year-long">
            <label class="form-check-label" for="internchip-term-year-long">Year-long</label>
        </div>
        <?php echo form_error('internship_term') ?>
    </div>

    <div class="form-group">
        <label class="required">Format</label>
        <select class="form-control <?php echo is_valid('format') ?>" name="format" value="<?php echo set_value('format', $offering->format ?? null) ?>">
            <option value="">Select</option>
            <option <?php echo set_select('format', 'online', !isset($offering->format) ? null : $offering->format == 'online') ?> value="online">Online</option>
            <option <?php echo set_select('format', 'in-person', !isset($offering->format) ? null : $offering->format == 'in-person') ?> value="in-person">In-person</option>
            <option <?php echo set_select('format', 'hybrid', !isset($offering->format) ? null : $offering->format == 'hybrid') ?> value="hybrid">Hybrid</option>
        </select>
        <?php echo form_error('format') ?>
    </div>

    <div class="form-group" data-expand="format" data-expand-if="in-person,hybrid">
        <label class="required">Location</label>
        <select class="form-control <?php echo is_valid('location') ?>" name="location" value="<?php echo set_value('location', $offering->location ?? null) ?>" placeholder="Start typing city name ...">
            <option value="">Select</option>
            <?php foreach ($locations as $location): ?>
                <option <?php echo set_select('location', $location, !isset($offering->location) ? null : $offering->location == $location) ?>><?php echo $location ?></option>
            <?php endforeach ?>
        </select>
        <small class="form-text text-muted">e.g. Omaha, NE. Enter "Virtual" for online offerings.</small>
        <?php echo form_error('location') ?>
    </div>

    <div class="form-row align-items-center">
        <div class="form-group col-md-4">
            <label class="required">Start Date</label>
            <input type="text" class="form-control date-picker <?php echo is_valid('start_date') ?>" name="start_date" data-inputmask-alias="datetime" data-inputmask-inputformat="MM/DD/YYYY" placeholder="MM/DD/YYYY" value="<?php echo set_value('start_date', !isset($offering->start_date) ? null : date('m/d/Y', strtotime($offering->start_date))) ?>">
            <?php echo form_error('start_date') ?>
        </div>

        <div class="form-group col-md-4">
            <label class="required">End Date</label>
            <input type="text" class="form-control date-picker <?php echo is_valid('end_date') ?>" name="end_date" data-inputmask-alias="datetime" data-inputmask-inputformat="MM/DD/YYYY" placeholder="MM/DD/YYYY" value="<?php echo set_value('end_date', !isset($offering->end_date) ? null : date('m/d/Y', strtotime($offering->end_date))) ?>">
            <?php echo form_error('end_date') ?>
        </div>
        
        <div class="form-group col-md-4">
            <label></label>
            <div class="custom-control custom-checkbox">
                <input type="checkbox" <?php echo set_checkbox('repeat', '1', !isset($offering->repeat) ? null : $offering->repeat == '1') ?> class="custom-control-input" name="repeat" id="repeat" value="1">
                <label class="custom-control-label" for="repeat">Repeating</label>
            </div>
        </div>
    </div>

    <!-- REPEAT EVERY -->
    <div class="card" data-expand="repeat" data-expand-if="1">
        <div class="card-body">

            <div class="form-row align-items-center">
                <div class="col-auto">
                    <div class="form-group">
                        <label class="required">Repeat every</label>
                    </div>
                </div>
                <!-- <div class="col-auto">
                    <div class="form-group">
                        <input type="number" class="form-control <?php echo is_valid('repeat_every_value') ?>" name="repeat_every_value" value="<?php echo set_value('repeat_every_value', $offering->repeat_every_value ?? null) ?>">
                    </div>
                </div> -->
                <div class="col-auto">
                    <div class="form-group">
                        <select class="form-control <?php echo is_valid('repeat_every_unit') ?>" name="repeat_every_unit" value="<?php echo set_value('repeat_every_unit', $offering->repeat_every_unit ?? null) ?>">
                            <option value="">Select</option>
                            <option <?php echo set_select('repeat_every_unit', 'day', !isset($offering->repeat_every_unit) ? null : $offering->repeat_every_unit == 'day') ?> value="day">Day</option>
                            <option <?php echo set_select('repeat_every_unit', 'week', !isset($offering->repeat_every_unit) ? null : $offering->repeat_every_unit == 'week') ?> value="week">Week</option>
                            <option <?php echo set_select('repeat_every_unit', 'month', !isset($offering->repeat_every_unit) ? null : $offering->repeat_every_unit == 'month') ?> value="month">Month</option>
                            <option <?php echo set_select('repeat_every_unit', 'year', !isset($offering->repeat_every_unit) ? null : $offering->repeat_every_unit == 'year') ?> value="year">Year</option>
                        </select>
                        <?php echo form_error('repeat_every_unit') ?>
                    </div>
                </div>
            </div>


            <div class="form-group" data-expand="repeat_every_unit" data-expand-if="week">
                <label class="required">Repeat on</label>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" <?php echo set_checkbox('repeat_every_weekdays', 'monday', !isset($offering->repeat_every_weekdays) ? null : in_array('monday', $offering->repeat_every_weekdays)) ?> name="repeat_every_weekdays[]" value="monday" id="repeat_every_weekdays_monday">
                    <label class="custom-control-label" for="repeat_every_weekdays_monday">Monday</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" <?php echo set_checkbox('repeat_every_weekdays', 'tuesday', !isset($offering->repeat_every_weekdays) ? null : in_array('tuesday', $offering->repeat_every_weekdays)) ?> name="repeat_every_weekdays[]" value="tuesday" id="repeat_every_weekdays_tuesday">
                    <label class="custom-control-label" for="repeat_every_weekdays_tuesday">Tuesday</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" <?php echo set_checkbox('repeat_every_weekdays', 'wednesday', !isset($offering->repeat_every_weekdays) ? null : in_array('wednesday', $offering->repeat_every_weekdays)) ?> name="repeat_every_weekdays[]" value="wednesday" id="repeat_every_weekdays_wednesday">
                    <label class="custom-control-label" for="repeat_every_weekdays_wednesday">Wednesday</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" <?php echo set_checkbox('repeat_every_weekdays', 'thursday', !isset($offering->repeat_every_weekdays) ? null : in_array('thursday', $offering->repeat_every_weekdays)) ?> name="repeat_every_weekdays[]" value="thursday" id="repeat_every_weekdays_thursday">
                    <label class="custom-control-label" for="repeat_every_weekdays_thursday">Thursday</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" <?php echo set_checkbox('repeat_every_weekdays', 'friday', !isset($offering->repeat_every_weekdays) ? null : in_array('friday', $offering->repeat_every_weekdays)) ?> name="repeat_every_weekdays[]" value="friday" id="repeat_every_weekdays_friday">
                    <label class="custom-control-label" for="repeat_every_weekdays_friday">Friday</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" <?php echo set_checkbox('repeat_every_weekdays', 'saturday', !isset($offering->repeat_every_weekdays) ? null : in_array('saturday', $offering->repeat_every_weekdays)) ?> name="repeat_every_weekdays[]" value="saturday" id="repeat_every_weekdays_saturday">
                    <label class="custom-control-label" for="repeat_every_weekdays_saturday">Saturday</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" <?php echo set_checkbox('repeat_every_weekdays', 'sunday', !isset($offering->repeat_every_weekdays) ? null : in_array('sunday', $offering->repeat_every_weekdays)) ?> name="repeat_every_weekdays[]" value="sunday" id="repeat_every_weekdays_sunday">
                    <label class="custom-control-label" for="repeat_every_weekdays_sunday">Sunday</label>
                </div>
                <?php echo form_error('repeat_every_weekdays') ?>

            </div>

            <div class="form-group">

                <label class="required">Repeat Period</label>
                <div class="custom-control custom-radio">
                    <input type="radio" <?php echo set_radio('repeat_end_type', 'end_date', !isset($offering->repeat_end_type) ? null : $offering->repeat_end_type == 'end_date') ?> id="repeats_end_on" name="repeat_end_type" value="end_date" class="custom-control-input">
                    <label class="custom-control-label" for="repeats_end_on">Until end date</label>
                </div>
                <div class="form-row align-items-center my-2">
                    <div class="col-auto">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" <?php echo set_radio('repeat_end_type', 'occurence', !isset($offering->repeat_end_type) ? null : $offering->repeat_end_type == 'occurence') ?> id="repeats_end_occurence" name="repeat_end_type" value="occurence" class="custom-control-input w-50">
                            <label class="custom-control-label" for="repeats_end_occurence">After</label>
                        </div>
                    </div>
                    <div class="col-2">
                        <input type="number" class="form-control <?php echo is_valid('repeat_end_occurence_value') ?>" name="repeat_end_occurence_value" value="<?php echo set_value('repeat_end_occurence_value', $offering->repeat_end_occurence_value ?? null) ?>">  
                    </div>
                    <div class="col-auto">
                        occurences
                    </div>
                    <?php echo form_error('repeat_end_occurence_value') ?>
                </div>
                <div class="custom-control custom-radio">
                    <input type="radio" <?php echo set_radio('repeat_end_type', 'forever', !isset($offering->repeat_end_type) ? true : $offering->repeat_end_type == 'forever') ?> id="repeats_end_forever" name="repeat_end_type" value="forever" class="custom-control-input">
                    <label class="custom-control-label" for="repeats_end_forever">Forever</label>
                </div>
            </div>

        </div>
    </div>
    
    <div class="form-group">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" <?php echo set_checkbox('is_expire_after_start', '1', !isset($offering->is_expire_after_start) ? null : $offering->is_expire_after_start == '1') ?> class="custom-control-input" name="is_expire_after_start" id="is_expire_after_start" value="1">
            <label class="custom-control-label" for="is_expire_after_start">Registration closed after start date</label>
            <small class="form-text text-muted">If participants are not able to register after the start date, check the box above.</small>
        </div>
    </div>

    <div class="form-row align-items-center" data-expand="internship_type" data-expand-if="0">
        <div class="col-auto">
            <div class="form-group">
                <label class="required">Start Time</label>
                <select class="form-control <?php echo is_valid('start_time') ?>" name="start_time">
                    <option value="">Select</option>
                    <option <?php echo set_select('start_time', '00:00:00', !isset($offering->start_time) ? null : $offering->start_time == '00:00:00') ?> value="00:00:00">12:00am</option>
                    <option <?php echo set_select('start_time', '00:15:00', !isset($offering->start_time) ? null : $offering->start_time == '00:15:00') ?> value="00:15:00">12:15am</option>
                    <option <?php echo set_select('start_time', '00:30:00', !isset($offering->start_time) ? null : $offering->start_time == '00:30:00') ?> value="00:30:00">12:30am</option>
                    <option <?php echo set_select('start_time', '00:45:00', !isset($offering->start_time) ? null : $offering->start_time == '00:45:00') ?> value="00:45:00">12:45am</option>
                    <option <?php echo set_select('start_time', '01:00:00', !isset($offering->start_time) ? null : $offering->start_time == '01:00:00') ?> value="01:00:00">1:00am</option>
                    <option <?php echo set_select('start_time', '01:15:00', !isset($offering->start_time) ? null : $offering->start_time == '01:15:00') ?> value="01:15:00">1:15am</option>
                    <option <?php echo set_select('start_time', '01:30:00', !isset($offering->start_time) ? null : $offering->start_time == '01:30:00') ?> value="01:30:00">1:30am</option>
                    <option <?php echo set_select('start_time', '01:45:00', !isset($offering->start_time) ? null : $offering->start_time == '01:45:00') ?> value="01:45:00">1:45am</option>
                    <option <?php echo set_select('start_time', '02:00:00', !isset($offering->start_time) ? null : $offering->start_time == '02:00:00') ?> value="02:00:00">2:00am</option>
                    <option <?php echo set_select('start_time', '02:15:00', !isset($offering->start_time) ? null : $offering->start_time == '02:15:00') ?> value="02:15:00">2:15am</option>
                    <option <?php echo set_select('start_time', '02:30:00', !isset($offering->start_time) ? null : $offering->start_time == '02:30:00') ?> value="02:30:00">2:30am</option>
                    <option <?php echo set_select('start_time', '02:45:00', !isset($offering->start_time) ? null : $offering->start_time == '02:45:00') ?> value="02:45:00">2:45am</option>
                    <option <?php echo set_select('start_time', '03:00:00', !isset($offering->start_time) ? null : $offering->start_time == '03:00:00') ?> value="03:00:00">3:00am</option>
                    <option <?php echo set_select('start_time', '03:15:00', !isset($offering->start_time) ? null : $offering->start_time == '03:15:00') ?> value="03:15:00">3:15am</option>
                    <option <?php echo set_select('start_time', '03:30:00', !isset($offering->start_time) ? null : $offering->start_time == '03:30:00') ?> value="03:30:00">3:30am</option>
                    <option <?php echo set_select('start_time', '03:45:00', !isset($offering->start_time) ? null : $offering->start_time == '03:45:00') ?> value="03:45:00">3:45am</option>
                    <option <?php echo set_select('start_time', '04:00:00', !isset($offering->start_time) ? null : $offering->start_time == '04:00:00') ?> value="04:00:00">4:00am</option>
                    <option <?php echo set_select('start_time', '04:15:00', !isset($offering->start_time) ? null : $offering->start_time == '04:15:00') ?> value="04:15:00">4:15am</option>
                    <option <?php echo set_select('start_time', '04:30:00', !isset($offering->start_time) ? null : $offering->start_time == '04:30:00') ?> value="04:30:00">4:30am</option>
                    <option <?php echo set_select('start_time', '04:45:00', !isset($offering->start_time) ? null : $offering->start_time == '04:45:00') ?> value="04:45:00">4:45am</option>
                    <option <?php echo set_select('start_time', '05:00:00', !isset($offering->start_time) ? null : $offering->start_time == '05:00:00') ?> value="05:00:00">5:00am</option>
                    <option <?php echo set_select('start_time', '05:15:00', !isset($offering->start_time) ? null : $offering->start_time == '05:15:00') ?> value="05:15:00">5:15am</option>
                    <option <?php echo set_select('start_time', '05:30:00', !isset($offering->start_time) ? null : $offering->start_time == '05:30:00') ?> value="05:30:00">5:30am</option>
                    <option <?php echo set_select('start_time', '05:45:00', !isset($offering->start_time) ? null : $offering->start_time == '05:45:00') ?> value="05:45:00">5:45am</option>
                    <option <?php echo set_select('start_time', '06:00:00', !isset($offering->start_time) ? null : $offering->start_time == '06:00:00') ?> value="06:00:00">6:00am</option>
                    <option <?php echo set_select('start_time', '06:15:00', !isset($offering->start_time) ? null : $offering->start_time == '06:15:00') ?> value="06:15:00">6:15am</option>
                    <option <?php echo set_select('start_time', '06:30:00', !isset($offering->start_time) ? null : $offering->start_time == '06:30:00') ?> value="06:30:00">6:30am</option>
                    <option <?php echo set_select('start_time', '06:45:00', !isset($offering->start_time) ? null : $offering->start_time == '06:45:00') ?> value="06:45:00">6:45am</option>
                    <option <?php echo set_select('start_time', '07:00:00', !isset($offering->start_time) ? null : $offering->start_time == '07:00:00') ?> value="07:00:00">7:00am</option>
                    <option <?php echo set_select('start_time', '07:15:00', !isset($offering->start_time) ? null : $offering->start_time == '07:15:00') ?> value="07:15:00">7:15am</option>
                    <option <?php echo set_select('start_time', '07:30:00', !isset($offering->start_time) ? null : $offering->start_time == '07:30:00') ?> value="07:30:00">7:30am</option>
                    <option <?php echo set_select('start_time', '07:45:00', !isset($offering->start_time) ? null : $offering->start_time == '07:45:00') ?> value="07:45:00">7:45am</option>
                    <option <?php echo set_select('start_time', '08:00:00', !isset($offering->start_time) ? null : $offering->start_time == '08:00:00') ?> value="08:00:00">8:00am</option>
                    <option <?php echo set_select('start_time', '08:15:00', !isset($offering->start_time) ? null : $offering->start_time == '08:15:00') ?> value="08:15:00">8:15am</option>
                    <option <?php echo set_select('start_time', '08:30:00', !isset($offering->start_time) ? null : $offering->start_time == '08:30:00') ?> value="08:30:00">8:30am</option>
                    <option <?php echo set_select('start_time', '08:45:00', !isset($offering->start_time) ? null : $offering->start_time == '08:45:00') ?> value="08:45:00">8:45am</option>
                    <option <?php echo set_select('start_time', '09:00:00', !isset($offering->start_time) ? null : $offering->start_time == '09:00:00') ?> value="09:00:00">9:00am</option>
                    <option <?php echo set_select('start_time', '09:15:00', !isset($offering->start_time) ? null : $offering->start_time == '09:15:00') ?> value="09:15:00">9:15am</option>
                    <option <?php echo set_select('start_time', '09:30:00', !isset($offering->start_time) ? null : $offering->start_time == '09:30:00') ?> value="09:30:00">9:30am</option>
                    <option <?php echo set_select('start_time', '09:45:00', !isset($offering->start_time) ? null : $offering->start_time == '09:45:00') ?> value="09:45:00">9:45am</option>
                    <option <?php echo set_select('start_time', '10:00:00', !isset($offering->start_time) ? null : $offering->start_time == '10:00:00') ?> value="10:00:00">10:00am</option>
                    <option <?php echo set_select('start_time', '10:15:00', !isset($offering->start_time) ? null : $offering->start_time == '10:15:00') ?> value="10:15:00">10:15am</option>
                    <option <?php echo set_select('start_time', '10:30:00', !isset($offering->start_time) ? null : $offering->start_time == '10:30:00') ?> value="10:30:00">10:30am</option>
                    <option <?php echo set_select('start_time', '10:45:00', !isset($offering->start_time) ? null : $offering->start_time == '10:45:00') ?> value="10:45:00">10:45am</option>
                    <option <?php echo set_select('start_time', '11:00:00', !isset($offering->start_time) ? null : $offering->start_time == '11:00:00') ?> value="11:00:00">11:00am</option>
                    <option <?php echo set_select('start_time', '11:15:00', !isset($offering->start_time) ? null : $offering->start_time == '11:15:00') ?> value="11:15:00">11:15am</option>
                    <option <?php echo set_select('start_time', '11:30:00', !isset($offering->start_time) ? null : $offering->start_time == '11:30:00') ?> value="11:30:00">11:30am</option>
                    <option <?php echo set_select('start_time', '11:45:00', !isset($offering->start_time) ? null : $offering->start_time == '11:45:00') ?> value="11:45:00">11:45am</option>
                    <option <?php echo set_select('start_time', '12:00:00', !isset($offering->start_time) ? null : $offering->start_time == '12:00:00') ?> value="12:00:00">12:00pm</option>
                    <option <?php echo set_select('start_time', '12:15:00', !isset($offering->start_time) ? null : $offering->start_time == '12:15:00') ?> value="12:15:00">12:15pm</option>
                    <option <?php echo set_select('start_time', '12:30:00', !isset($offering->start_time) ? null : $offering->start_time == '12:30:00') ?> value="12:30:00">12:30pm</option>
                    <option <?php echo set_select('start_time', '12:45:00', !isset($offering->start_time) ? null : $offering->start_time == '12:45:00') ?> value="12:45:00">12:45pm</option>
                    <option <?php echo set_select('start_time', '13:00:00', !isset($offering->start_time) ? null : $offering->start_time == '13:00:00') ?> value="13:00:00">1:00pm</option>
                    <option <?php echo set_select('start_time', '13:15:00', !isset($offering->start_time) ? null : $offering->start_time == '13:15:00') ?> value="13:15:00">1:15pm</option>
                    <option <?php echo set_select('start_time', '13:30:00', !isset($offering->start_time) ? null : $offering->start_time == '13:30:00') ?> value="13:30:00">1:30pm</option>
                    <option <?php echo set_select('start_time', '13:45:00', !isset($offering->start_time) ? null : $offering->start_time == '13:45:00') ?> value="13:45:00">1:45pm</option>
                    <option <?php echo set_select('start_time', '14:00:00', !isset($offering->start_time) ? null : $offering->start_time == '14:00:00') ?> value="14:00:00">2:00pm</option>
                    <option <?php echo set_select('start_time', '14:15:00', !isset($offering->start_time) ? null : $offering->start_time == '14:15:00') ?> value="14:15:00">2:15pm</option>
                    <option <?php echo set_select('start_time', '14:30:00', !isset($offering->start_time) ? null : $offering->start_time == '14:30:00') ?> value="14:30:00">2:30pm</option>
                    <option <?php echo set_select('start_time', '14:45:00', !isset($offering->start_time) ? null : $offering->start_time == '14:45:00') ?> value="14:45:00">2:45pm</option>
                    <option <?php echo set_select('start_time', '15:00:00', !isset($offering->start_time) ? null : $offering->start_time == '15:00:00') ?> value="15:00:00">3:00pm</option>
                    <option <?php echo set_select('start_time', '15:15:00', !isset($offering->start_time) ? null : $offering->start_time == '15:15:00') ?> value="15:15:00">3:15pm</option>
                    <option <?php echo set_select('start_time', '15:30:00', !isset($offering->start_time) ? null : $offering->start_time == '15:30:00') ?> value="15:30:00">3:30pm</option>
                    <option <?php echo set_select('start_time', '15:45:00', !isset($offering->start_time) ? null : $offering->start_time == '15:45:00') ?> value="15:45:00">3:45pm</option>
                    <option <?php echo set_select('start_time', '16:00:00', !isset($offering->start_time) ? null : $offering->start_time == '16:00:00') ?> value="16:00:00">4:00pm</option>
                    <option <?php echo set_select('start_time', '16:15:00', !isset($offering->start_time) ? null : $offering->start_time == '16:15:00') ?> value="16:15:00">4:15pm</option>
                    <option <?php echo set_select('start_time', '16:30:00', !isset($offering->start_time) ? null : $offering->start_time == '16:30:00') ?> value="16:30:00">4:30pm</option>
                    <option <?php echo set_select('start_time', '16:45:00', !isset($offering->start_time) ? null : $offering->start_time == '16:45:00') ?> value="16:45:00">4:45pm</option>
                    <option <?php echo set_select('start_time', '17:00:00', !isset($offering->start_time) ? null : $offering->start_time == '17:00:00') ?> value="17:00:00">5:00pm</option>
                    <option <?php echo set_select('start_time', '17:15:00', !isset($offering->start_time) ? null : $offering->start_time == '17:15:00') ?> value="17:15:00">5:15pm</option>
                    <option <?php echo set_select('start_time', '17:30:00', !isset($offering->start_time) ? null : $offering->start_time == '17:30:00') ?> value="17:30:00">5:30pm</option>
                    <option <?php echo set_select('start_time', '17:45:00', !isset($offering->start_time) ? null : $offering->start_time == '17:45:00') ?> value="17:45:00">5:45pm</option>
                    <option <?php echo set_select('start_time', '18:00:00', !isset($offering->start_time) ? null : $offering->start_time == '18:00:00') ?> value="18:00:00">6:00pm</option>
                    <option <?php echo set_select('start_time', '18:15:00', !isset($offering->start_time) ? null : $offering->start_time == '18:15:00') ?> value="18:15:00">6:15pm</option>
                    <option <?php echo set_select('start_time', '18:30:00', !isset($offering->start_time) ? null : $offering->start_time == '18:30:00') ?> value="18:30:00">6:30pm</option>
                    <option <?php echo set_select('start_time', '18:45:00', !isset($offering->start_time) ? null : $offering->start_time == '18:45:00') ?> value="18:45:00">6:45pm</option>
                    <option <?php echo set_select('start_time', '19:00:00', !isset($offering->start_time) ? null : $offering->start_time == '19:00:00') ?> value="19:00:00">7:00pm</option>
                    <option <?php echo set_select('start_time', '19:15:00', !isset($offering->start_time) ? null : $offering->start_time == '19:15:00') ?> value="19:15:00">7:15pm</option>
                    <option <?php echo set_select('start_time', '19:30:00', !isset($offering->start_time) ? null : $offering->start_time == '19:30:00') ?> value="19:30:00">7:30pm</option>
                    <option <?php echo set_select('start_time', '19:45:00', !isset($offering->start_time) ? null : $offering->start_time == '19:45:00') ?> value="19:45:00">7:45pm</option>
                    <option <?php echo set_select('start_time', '20:00:00', !isset($offering->start_time) ? null : $offering->start_time == '20:00:00') ?> value="20:00:00">8:00pm</option>
                    <option <?php echo set_select('start_time', '20:15:00', !isset($offering->start_time) ? null : $offering->start_time == '20:15:00') ?> value="20:15:00">8:15pm</option>
                    <option <?php echo set_select('start_time', '20:30:00', !isset($offering->start_time) ? null : $offering->start_time == '20:30:00') ?> value="20:30:00">8:30pm</option>
                    <option <?php echo set_select('start_time', '20:45:00', !isset($offering->start_time) ? null : $offering->start_time == '20:45:00') ?> value="20:45:00">8:45pm</option>
                    <option <?php echo set_select('start_time', '21:00:00', !isset($offering->start_time) ? null : $offering->start_time == '21:00:00') ?> value="21:00:00">9:00pm</option>
                    <option <?php echo set_select('start_time', '21:15:00', !isset($offering->start_time) ? null : $offering->start_time == '21:15:00') ?> value="21:15:00">9:15pm</option>
                    <option <?php echo set_select('start_time', '21:30:00', !isset($offering->start_time) ? null : $offering->start_time == '21:30:00') ?> value="21:30:00">9:30pm</option>
                    <option <?php echo set_select('start_time', '21:45:00', !isset($offering->start_time) ? null : $offering->start_time == '21:45:00') ?> value="21:45:00">9:45pm</option>
                    <option <?php echo set_select('start_time', '22:00:00', !isset($offering->start_time) ? null : $offering->start_time == '22:00:00') ?> value="22:00:00">10:00pm</option>
                    <option <?php echo set_select('start_time', '22:15:00', !isset($offering->start_time) ? null : $offering->start_time == '22:15:00') ?> value="22:15:00">10:15pm</option>
                    <option <?php echo set_select('start_time', '22:30:00', !isset($offering->start_time) ? null : $offering->start_time == '22:30:00') ?> value="22:30:00">10:30pm</option>
                    <option <?php echo set_select('start_time', '22:45:00', !isset($offering->start_time) ? null : $offering->start_time == '22:45:00') ?> value="22:45:00">10:45pm</option>
                    <option <?php echo set_select('start_time', '23:00:00', !isset($offering->start_time) ? null : $offering->start_time == '23:00:00') ?> value="23:00:00">11:00pm</option>
                    <option <?php echo set_select('start_time', '23:15:00', !isset($offering->start_time) ? null : $offering->start_time == '23:15:00') ?> value="23:15:00">11:15pm</option>
                    <option <?php echo set_select('start_time', '23:30:00', !isset($offering->start_time) ? null : $offering->start_time == '23:30:00') ?> value="23:30:00">11:30pm</option>
                    <option <?php echo set_select('start_time', '23:45:00', !isset($offering->start_time) ? null : $offering->start_time == '23:45:00') ?> value="23:45:00">11:45pm</option>
                </select>
            </div>
        </div>
        <div class="col-auto">
            <div class="form-group">
                <label class="required">End Time</label>
                <select class="form-control <?php echo is_valid('end_time') ?>" name="end_time">
                    <option value="">Select</option>
                    <option <?php echo set_select('end_time', '00:00:00', !isset($offering->end_time) ? null : $offering->end_time == '00:00:00') ?> value="00:00:00">12:00am</option>
                    <option <?php echo set_select('end_time', '00:15:00', !isset($offering->end_time) ? null : $offering->end_time == '00:15:00') ?> value="00:15:00">12:15am</option>
                    <option <?php echo set_select('end_time', '00:30:00', !isset($offering->end_time) ? null : $offering->end_time == '00:30:00') ?> value="00:30:00">12:30am</option>
                    <option <?php echo set_select('end_time', '00:45:00', !isset($offering->end_time) ? null : $offering->end_time == '00:45:00') ?> value="00:45:00">12:45am</option>
                    <option <?php echo set_select('end_time', '01:00:00', !isset($offering->end_time) ? null : $offering->end_time == '01:00:00') ?> value="01:00:00">1:00am</option>
                    <option <?php echo set_select('end_time', '01:15:00', !isset($offering->end_time) ? null : $offering->end_time == '01:15:00') ?> value="01:15:00">1:15am</option>
                    <option <?php echo set_select('end_time', '01:30:00', !isset($offering->end_time) ? null : $offering->end_time == '01:30:00') ?> value="01:30:00">1:30am</option>
                    <option <?php echo set_select('end_time', '01:45:00', !isset($offering->end_time) ? null : $offering->end_time == '01:45:00') ?> value="01:45:00">1:45am</option>
                    <option <?php echo set_select('end_time', '02:00:00', !isset($offering->end_time) ? null : $offering->end_time == '02:00:00') ?> value="02:00:00">2:00am</option>
                    <option <?php echo set_select('end_time', '02:15:00', !isset($offering->end_time) ? null : $offering->end_time == '02:15:00') ?> value="02:15:00">2:15am</option>
                    <option <?php echo set_select('end_time', '02:30:00', !isset($offering->end_time) ? null : $offering->end_time == '02:30:00') ?> value="02:30:00">2:30am</option>
                    <option <?php echo set_select('end_time', '02:45:00', !isset($offering->end_time) ? null : $offering->end_time == '02:45:00') ?> value="02:45:00">2:45am</option>
                    <option <?php echo set_select('end_time', '03:00:00', !isset($offering->end_time) ? null : $offering->end_time == '03:00:00') ?> value="03:00:00">3:00am</option>
                    <option <?php echo set_select('end_time', '03:15:00', !isset($offering->end_time) ? null : $offering->end_time == '03:15:00') ?> value="03:15:00">3:15am</option>
                    <option <?php echo set_select('end_time', '03:30:00', !isset($offering->end_time) ? null : $offering->end_time == '03:30:00') ?> value="03:30:00">3:30am</option>
                    <option <?php echo set_select('end_time', '03:45:00', !isset($offering->end_time) ? null : $offering->end_time == '03:45:00') ?> value="03:45:00">3:45am</option>
                    <option <?php echo set_select('end_time', '04:00:00', !isset($offering->end_time) ? null : $offering->end_time == '04:00:00') ?> value="04:00:00">4:00am</option>
                    <option <?php echo set_select('end_time', '04:15:00', !isset($offering->end_time) ? null : $offering->end_time == '04:15:00') ?> value="04:15:00">4:15am</option>
                    <option <?php echo set_select('end_time', '04:30:00', !isset($offering->end_time) ? null : $offering->end_time == '04:30:00') ?> value="04:30:00">4:30am</option>
                    <option <?php echo set_select('end_time', '04:45:00', !isset($offering->end_time) ? null : $offering->end_time == '04:45:00') ?> value="04:45:00">4:45am</option>
                    <option <?php echo set_select('end_time', '05:00:00', !isset($offering->end_time) ? null : $offering->end_time == '05:00:00') ?> value="05:00:00">5:00am</option>
                    <option <?php echo set_select('end_time', '05:15:00', !isset($offering->end_time) ? null : $offering->end_time == '05:15:00') ?> value="05:15:00">5:15am</option>
                    <option <?php echo set_select('end_time', '05:30:00', !isset($offering->end_time) ? null : $offering->end_time == '05:30:00') ?> value="05:30:00">5:30am</option>
                    <option <?php echo set_select('end_time', '05:45:00', !isset($offering->end_time) ? null : $offering->end_time == '05:45:00') ?> value="05:45:00">5:45am</option>
                    <option <?php echo set_select('end_time', '06:00:00', !isset($offering->end_time) ? null : $offering->end_time == '06:00:00') ?> value="06:00:00">6:00am</option>
                    <option <?php echo set_select('end_time', '06:15:00', !isset($offering->end_time) ? null : $offering->end_time == '06:15:00') ?> value="06:15:00">6:15am</option>
                    <option <?php echo set_select('end_time', '06:30:00', !isset($offering->end_time) ? null : $offering->end_time == '06:30:00') ?> value="06:30:00">6:30am</option>
                    <option <?php echo set_select('end_time', '06:45:00', !isset($offering->end_time) ? null : $offering->end_time == '06:45:00') ?> value="06:45:00">6:45am</option>
                    <option <?php echo set_select('end_time', '07:00:00', !isset($offering->end_time) ? null : $offering->end_time == '07:00:00') ?> value="07:00:00">7:00am</option>
                    <option <?php echo set_select('end_time', '07:15:00', !isset($offering->end_time) ? null : $offering->end_time == '07:15:00') ?> value="07:15:00">7:15am</option>
                    <option <?php echo set_select('end_time', '07:30:00', !isset($offering->end_time) ? null : $offering->end_time == '07:30:00') ?> value="07:30:00">7:30am</option>
                    <option <?php echo set_select('end_time', '07:45:00', !isset($offering->end_time) ? null : $offering->end_time == '07:45:00') ?> value="07:45:00">7:45am</option>
                    <option <?php echo set_select('end_time', '08:00:00', !isset($offering->end_time) ? null : $offering->end_time == '08:00:00') ?> value="08:00:00">8:00am</option>
                    <option <?php echo set_select('end_time', '08:15:00', !isset($offering->end_time) ? null : $offering->end_time == '08:15:00') ?> value="08:15:00">8:15am</option>
                    <option <?php echo set_select('end_time', '08:30:00', !isset($offering->end_time) ? null : $offering->end_time == '08:30:00') ?> value="08:30:00">8:30am</option>
                    <option <?php echo set_select('end_time', '08:45:00', !isset($offering->end_time) ? null : $offering->end_time == '08:45:00') ?> value="08:45:00">8:45am</option>
                    <option <?php echo set_select('end_time', '09:00:00', !isset($offering->end_time) ? null : $offering->end_time == '09:00:00') ?> value="09:00:00">9:00am</option>
                    <option <?php echo set_select('end_time', '09:15:00', !isset($offering->end_time) ? null : $offering->end_time == '09:15:00') ?> value="09:15:00">9:15am</option>
                    <option <?php echo set_select('end_time', '09:30:00', !isset($offering->end_time) ? null : $offering->end_time == '09:30:00') ?> value="09:30:00">9:30am</option>
                    <option <?php echo set_select('end_time', '09:45:00', !isset($offering->end_time) ? null : $offering->end_time == '09:45:00') ?> value="09:45:00">9:45am</option>
                    <option <?php echo set_select('end_time', '10:00:00', !isset($offering->end_time) ? null : $offering->end_time == '10:00:00') ?> value="10:00:00">10:00am</option>
                    <option <?php echo set_select('end_time', '10:15:00', !isset($offering->end_time) ? null : $offering->end_time == '10:15:00') ?> value="10:15:00">10:15am</option>
                    <option <?php echo set_select('end_time', '10:30:00', !isset($offering->end_time) ? null : $offering->end_time == '10:30:00') ?> value="10:30:00">10:30am</option>
                    <option <?php echo set_select('end_time', '10:45:00', !isset($offering->end_time) ? null : $offering->end_time == '10:45:00') ?> value="10:45:00">10:45am</option>
                    <option <?php echo set_select('end_time', '11:00:00', !isset($offering->end_time) ? null : $offering->end_time == '11:00:00') ?> value="11:00:00">11:00am</option>
                    <option <?php echo set_select('end_time', '11:15:00', !isset($offering->end_time) ? null : $offering->end_time == '11:15:00') ?> value="11:15:00">11:15am</option>
                    <option <?php echo set_select('end_time', '11:30:00', !isset($offering->end_time) ? null : $offering->end_time == '11:30:00') ?> value="11:30:00">11:30am</option>
                    <option <?php echo set_select('end_time', '11:45:00', !isset($offering->end_time) ? null : $offering->end_time == '11:45:00') ?> value="11:45:00">11:45am</option>
                    <option <?php echo set_select('end_time', '12:00:00', !isset($offering->end_time) ? null : $offering->end_time == '12:00:00') ?> value="12:00:00">12:00pm</option>
                    <option <?php echo set_select('end_time', '12:15:00', !isset($offering->end_time) ? null : $offering->end_time == '12:15:00') ?> value="12:15:00">12:15pm</option>
                    <option <?php echo set_select('end_time', '12:30:00', !isset($offering->end_time) ? null : $offering->end_time == '12:30:00') ?> value="12:30:00">12:30pm</option>
                    <option <?php echo set_select('end_time', '12:45:00', !isset($offering->end_time) ? null : $offering->end_time == '12:45:00') ?> value="12:45:00">12:45pm</option>
                    <option <?php echo set_select('end_time', '13:00:00', !isset($offering->end_time) ? null : $offering->end_time == '13:00:00') ?> value="13:00:00">1:00pm</option>
                    <option <?php echo set_select('end_time', '13:15:00', !isset($offering->end_time) ? null : $offering->end_time == '13:15:00') ?> value="13:15:00">1:15pm</option>
                    <option <?php echo set_select('end_time', '13:30:00', !isset($offering->end_time) ? null : $offering->end_time == '13:30:00') ?> value="13:30:00">1:30pm</option>
                    <option <?php echo set_select('end_time', '13:45:00', !isset($offering->end_time) ? null : $offering->end_time == '13:45:00') ?> value="13:45:00">1:45pm</option>
                    <option <?php echo set_select('end_time', '14:00:00', !isset($offering->end_time) ? null : $offering->end_time == '14:00:00') ?> value="14:00:00">2:00pm</option>
                    <option <?php echo set_select('end_time', '14:15:00', !isset($offering->end_time) ? null : $offering->end_time == '14:15:00') ?> value="14:15:00">2:15pm</option>
                    <option <?php echo set_select('end_time', '14:30:00', !isset($offering->end_time) ? null : $offering->end_time == '14:30:00') ?> value="14:30:00">2:30pm</option>
                    <option <?php echo set_select('end_time', '14:45:00', !isset($offering->end_time) ? null : $offering->end_time == '14:45:00') ?> value="14:45:00">2:45pm</option>
                    <option <?php echo set_select('end_time', '15:00:00', !isset($offering->end_time) ? null : $offering->end_time == '15:00:00') ?> value="15:00:00">3:00pm</option>
                    <option <?php echo set_select('end_time', '15:15:00', !isset($offering->end_time) ? null : $offering->end_time == '15:15:00') ?> value="15:15:00">3:15pm</option>
                    <option <?php echo set_select('end_time', '15:30:00', !isset($offering->end_time) ? null : $offering->end_time == '15:30:00') ?> value="15:30:00">3:30pm</option>
                    <option <?php echo set_select('end_time', '15:45:00', !isset($offering->end_time) ? null : $offering->end_time == '15:45:00') ?> value="15:45:00">3:45pm</option>
                    <option <?php echo set_select('end_time', '16:00:00', !isset($offering->end_time) ? null : $offering->end_time == '16:00:00') ?> value="16:00:00">4:00pm</option>
                    <option <?php echo set_select('end_time', '16:15:00', !isset($offering->end_time) ? null : $offering->end_time == '16:15:00') ?> value="16:15:00">4:15pm</option>
                    <option <?php echo set_select('end_time', '16:30:00', !isset($offering->end_time) ? null : $offering->end_time == '16:30:00') ?> value="16:30:00">4:30pm</option>
                    <option <?php echo set_select('end_time', '16:45:00', !isset($offering->end_time) ? null : $offering->end_time == '16:45:00') ?> value="16:45:00">4:45pm</option>
                    <option <?php echo set_select('end_time', '17:00:00', !isset($offering->end_time) ? null : $offering->end_time == '17:00:00') ?> value="17:00:00">5:00pm</option>
                    <option <?php echo set_select('end_time', '17:15:00', !isset($offering->end_time) ? null : $offering->end_time == '17:15:00') ?> value="17:15:00">5:15pm</option>
                    <option <?php echo set_select('end_time', '17:30:00', !isset($offering->end_time) ? null : $offering->end_time == '17:30:00') ?> value="17:30:00">5:30pm</option>
                    <option <?php echo set_select('end_time', '17:45:00', !isset($offering->end_time) ? null : $offering->end_time == '17:45:00') ?> value="17:45:00">5:45pm</option>
                    <option <?php echo set_select('end_time', '18:00:00', !isset($offering->end_time) ? null : $offering->end_time == '18:00:00') ?> value="18:00:00">6:00pm</option>
                    <option <?php echo set_select('end_time', '18:15:00', !isset($offering->end_time) ? null : $offering->end_time == '18:15:00') ?> value="18:15:00">6:15pm</option>
                    <option <?php echo set_select('end_time', '18:30:00', !isset($offering->end_time) ? null : $offering->end_time == '18:30:00') ?> value="18:30:00">6:30pm</option>
                    <option <?php echo set_select('end_time', '18:45:00', !isset($offering->end_time) ? null : $offering->end_time == '18:45:00') ?> value="18:45:00">6:45pm</option>
                    <option <?php echo set_select('end_time', '19:00:00', !isset($offering->end_time) ? null : $offering->end_time == '19:00:00') ?> value="19:00:00">7:00pm</option>
                    <option <?php echo set_select('end_time', '19:15:00', !isset($offering->end_time) ? null : $offering->end_time == '19:15:00') ?> value="19:15:00">7:15pm</option>
                    <option <?php echo set_select('end_time', '19:30:00', !isset($offering->end_time) ? null : $offering->end_time == '19:30:00') ?> value="19:30:00">7:30pm</option>
                    <option <?php echo set_select('end_time', '19:45:00', !isset($offering->end_time) ? null : $offering->end_time == '19:45:00') ?> value="19:45:00">7:45pm</option>
                    <option <?php echo set_select('end_time', '20:00:00', !isset($offering->end_time) ? null : $offering->end_time == '20:00:00') ?> value="20:00:00">8:00pm</option>
                    <option <?php echo set_select('end_time', '20:15:00', !isset($offering->end_time) ? null : $offering->end_time == '20:15:00') ?> value="20:15:00">8:15pm</option>
                    <option <?php echo set_select('end_time', '20:30:00', !isset($offering->end_time) ? null : $offering->end_time == '20:30:00') ?> value="20:30:00">8:30pm</option>
                    <option <?php echo set_select('end_time', '20:45:00', !isset($offering->end_time) ? null : $offering->end_time == '20:45:00') ?> value="20:45:00">8:45pm</option>
                    <option <?php echo set_select('end_time', '21:00:00', !isset($offering->end_time) ? null : $offering->end_time == '21:00:00') ?> value="21:00:00">9:00pm</option>
                    <option <?php echo set_select('end_time', '21:15:00', !isset($offering->end_time) ? null : $offering->end_time == '21:15:00') ?> value="21:15:00">9:15pm</option>
                    <option <?php echo set_select('end_time', '21:30:00', !isset($offering->end_time) ? null : $offering->end_time == '21:30:00') ?> value="21:30:00">9:30pm</option>
                    <option <?php echo set_select('end_time', '21:45:00', !isset($offering->end_time) ? null : $offering->end_time == '21:45:00') ?> value="21:45:00">9:45pm</option>
                    <option <?php echo set_select('end_time', '22:00:00', !isset($offering->end_time) ? null : $offering->end_time == '22:00:00') ?> value="22:00:00">10:00pm</option>
                    <option <?php echo set_select('end_time', '22:15:00', !isset($offering->end_time) ? null : $offering->end_time == '22:15:00') ?> value="22:15:00">10:15pm</option>
                    <option <?php echo set_select('end_time', '22:30:00', !isset($offering->end_time) ? null : $offering->end_time == '22:30:00') ?> value="22:30:00">10:30pm</option>
                    <option <?php echo set_select('end_time', '22:45:00', !isset($offering->end_time) ? null : $offering->end_time == '22:45:00') ?> value="22:45:00">10:45pm</option>
                    <option <?php echo set_select('end_time', '23:00:00', !isset($offering->end_time) ? null : $offering->end_time == '23:00:00') ?> value="23:00:00">11:00pm</option>
                    <option <?php echo set_select('end_time', '23:15:00', !isset($offering->end_time) ? null : $offering->end_time == '23:15:00') ?> value="23:15:00">11:15pm</option>
                    <option <?php echo set_select('end_time', '23:30:00', !isset($offering->end_time) ? null : $offering->end_time == '23:30:00') ?> value="23:30:00">11:30pm</option>
                    <option <?php echo set_select('end_time', '23:45:00', !isset($offering->end_time) ? null : $offering->end_time == '23:45:00') ?> value="23:45:00">11:45pm</option>
                </select>
            </div>
        </div>
    </div>

    <!-- CATEGORY -->
    <h2 class="mt-5 mb-4"><i class="fa fa-chevron-right" aria-hidden="true"></i>Category</h2>

    <div class="form-group">
        <label class="required">Category Best fit</label>
        <select class="form-control <?php echo is_valid('category') ?>" name="category" value="<?php echo set_value('category', $offering->category ?? null) ?>">
            <option value="">Select</option>
            <option <?php echo set_select('category', 'science', !isset($offering->category) ? null : $offering->category == 'science') ?> value="science">Science</option>
            <option <?php echo set_select('category', 'technology', !isset($offering->category) ? null : $offering->category == 'technology') ?> value="technology">Technology</option>
            <option <?php echo set_select('category', 'engineering', !isset($offering->category) ? null : $offering->category == 'engineering') ?> value="engineering">Engineering</option>
            <option <?php echo set_select('category', 'mathematics', !isset($offering->category) ? null : $offering->category == 'mathematics') ?> value="mathematics">Mathematics</option>
        </select>
        <?php echo form_error('category') ?>
    </div>

    <div class="form-group">
        <label class="required">Subcategory Best Fit</label>
        <select class="form-control <?php echo is_valid('subcategory') ?>" name="subcategory" value="<?php echo set_value('subcategory', $offering->subcategory ?? null) ?>">
        </select>
        <?php echo form_error('subcategory') ?>
    </div>

    <!-- SUBMITTER -->
    <h2 class="mt-5 mb-4"><i class="fa fa-chevron-right" aria-hidden="true"></i>Submitter</h2>

    <div class="form-group">
        <label class="required">Your Name</label>
        <input type="text" class="form-control <?php echo is_valid('submitter_name') ?>" name="submitter_name" value="<?php echo set_value('submitter_name', $offering->submitter_name ?? null) ?>">
        <?php echo form_error('submitter_name') ?>
    </div>

    <div class="form-group">
        <label class="required">Your Email address</label>
        <input type="email" class="form-control <?php echo is_valid('submitter_email') ?>" name="submitter_email" value="<?php echo set_value('submitter_email', $offering->submitter_email ?? null) ?>">
        <?php echo form_error('submitter_email') ?>
    </div>


    <div class="form-group">
        <label class="required">Are you the offering's contact?</label>
        <div class="form-check">
            <input class="form-check-input" type="radio" <?php echo set_radio('submitter_is_offering_contact', '1', !isset($offering->submitter_is_offering_contact) ? null : $offering->submitter_is_offering_contact == '1') ?> name="submitter_is_offering_contact" id="owner-1" value="1">
            <label class="form-check-label" for="owner-1">Yes</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" <?php echo set_radio('submitter_is_offering_contact', '0', !isset($offering->submitter_is_offering_contact) ? null : $offering->submitter_is_offering_contact == '0') ?> name="submitter_is_offering_contact" id="owner-0" value="0">
            <label class="form-check-label" for="owner-0">No</label>
        </div>
        <?php echo form_error('submitter_is_offering_contact') ?>
    </div>

    <div data-expand="submitter_is_offering_contact" data-expand-if="0">
        <div class="form-group">
            <label class="required">Offering Contact Name</label>
            <input type="text" class="form-control <?php echo is_valid('contact_name') ?>" name="contact_name" value="<?php echo set_value('contact_name', $offering->contact_name ?? null) ?>">
            <?php echo form_error('contact_name') ?>
        </div>

        <div class="form-group">
            <label class="required">Offering Contact Email</label>
            <input type="email" class="form-control <?php echo is_valid('contact_email') ?>" name="contact_email" value="<?php echo set_value('contact_email', $offering->contact_email ?? null) ?>">
            <?php echo form_error('contact_email') ?>
        </div>
    </div>

    <div class="form-group">
        <label class="required">Organization</label>

        <select class="form-control <?php echo is_valid('organization') ?>" name="organization" placeholder="Start typing organization name ...">
            <option value="">Select</option>
            <?php foreach ($organizations as $organization): ?>
                <option <?php echo set_select('organization', $organization->organization, !isset($offering->organization) ? null : $offering->organization == $organization->organization) ?>><?php echo $organization->organization ?></option>
            <?php endforeach ?>
        </select>
        <small class="form-text text-muted">Select an organization from the drop-down, or type the organization and select to add the organization.</small>
        <?php echo form_error('organization') ?>
    </div>
        
    <div class="form-group">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" name="has_department" id="has_department" value="1" <?php echo set_checkbox('has_department', '1', !empty($offering->department ?? null)) ?>>
            <label class="custom-control-label" for="has_department">Enter organization department information</label>
        </div>
    </div>

    <div data-expand="has_department" data-expand-if="1">
        <div class="form-group">
            <label>Department</label>

            <select class="form-control <?php echo is_valid('department') ?>" name="department" placeholder="Start typing department name ...">
                <option value="">Select</option>
                <?php foreach ($departments as $department): ?>
                    <option <?php echo set_select('department', $department->department, !isset($offering->department) ? null : $offering->department == $department->department) ?>><?php echo $department->department ?></option>
                <?php endforeach ?>
            </select>
            <small class="form-text text-muted">Select an department from the drop-down, or type the department and select to add the department.</small>
            <?php echo form_error('department') ?>
        </div>
    </div>

    <!-- AUDIENCE -->
    <h2 class="mt-5 mb-4"><i class="fa fa-chevron-right" aria-hidden="true"></i>Audience</h2>

    <div class="form-group">
        <label class="required">Audience Type</label>
        <select class="form-control <?php echo is_valid('audience_type') ?>" name="audience_type" value="<?php echo set_value('audience_type', $offering->audience_type ?? null) ?>">
            <option value="">Select</option>
            <option <?php echo set_select('audience_type', 'student', !isset($offering->audience_type) ? null : $offering->audience_type == 'student') ?> value="student">Student</option>
            <option <?php echo set_select('audience_type', 'professional', !isset($offering->audience_type) ? null : $offering->audience_type == 'professional') ?> value="professional">Professional</option>
            <option <?php echo set_select('audience_type', 'educator', !isset($offering->audience_type) ? null : $offering->audience_type == 'educator') ?> value="educator">Educator</option>
        </select>
        <?php echo form_error('audience_type') ?>
    </div>


    <div class="form-group" data-expand="audience_type" data-expand-if="student">
        <label class="required">Age Groups</label>
        <div class="custom-control custom-checkbox">
            <input type="checkbox" <?php echo set_checkbox('audience_age_group', '3-6', !isset($offering->audience_age_group) ? null : in_array('3-6', $offering->audience_age_group)) ?> class="custom-control-input" name="audience_age_group[]" value="3-6" id="age-3-6">
            <label class="custom-control-label" for="age-3-6">3-6</label>
        </div>
        <div class="custom-control custom-checkbox">
            <input type="checkbox" <?php echo set_checkbox('audience_age_group', '7-10', !isset($offering->audience_age_group) ? null : in_array('7-10', $offering->audience_age_group)) ?> class="custom-control-input" name="audience_age_group[]" value="7-10" id="age-7-10">
            <label class="custom-control-label" for="age-7-10">7-10</label>
        </div>
        <div class="custom-control custom-checkbox">
            <input type="checkbox" <?php echo set_checkbox('audience_age_group', '11-13', !isset($offering->audience_age_group) ? null : in_array('11-13', $offering->audience_age_group)) ?> class="custom-control-input" name="audience_age_group[]" value="11-13" id="age-11-13">
            <label class="custom-control-label" for="age-11-13">11-13</label>
        </div>
        <div class="custom-control custom-checkbox">
            <input type="checkbox" <?php echo set_checkbox('audience_age_group', '14-18', !isset($offering->audience_age_group) ? null : in_array('14-18', $offering->audience_age_group)) ?> class="custom-control-input" name="audience_age_group[]" value="14-18" id="age-14-18">
            <label class="custom-control-label" for="age-14-18">14-18</label>
        </div>
        <div class="custom-control custom-checkbox">
            <input type="checkbox" <?php echo set_checkbox('audience_age_group', 'post-secondary', !isset($offering->audience_age_group) ? null : in_array('post-secondary', $offering->audience_age_group)) ?> class="custom-control-input" name="audience_age_group[]" value="post-secondary" id="age-post-secondary">
            <label class="custom-control-label" for="age-post-secondary">Post Secondary</label>
        </div>
        <?php echo form_error('audience_age_group') ?>
    </div>

    <div class="form-group" data-expand="audience_type" data-expand-if="student">
        <label class="required">Parental Supervision Required</label>
        <div class="form-check">
            <input class="form-check-input" type="radio" <?php echo set_radio('audience_is_supervision_required', '1', !isset($offering->audience_is_supervision_required) ? null : $offering->audience_is_supervision_required == '1') ?> name="audience_is_supervision_required" id="supervision-1" value="1">
            <label class="form-check-label" for="supervision-1">Yes</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" <?php echo set_radio('audience_is_supervision_required', '0', !isset($offering->audience_is_supervision_required) ? null : $offering->audience_is_supervision_required == '0') ?> name="audience_is_supervision_required" id="supervision-0" value="0">
            <label class="form-check-label" for="supervision-0">No</label>
        </div>
        <?php echo form_error('audience_is_supervision_required') ?>
    </div>

    <div class="form-group" data-expand="audience_type" data-expand-if="educator">
        <label class="required">Educator Target Group</label>
        <div class="custom-control custom-checkbox">
            <input type="checkbox" <?php echo set_checkbox('audience_educator_target', 'k-12', !isset($offering->audience_educator_target) ? null : in_array('k-12', $offering->audience_educator_target)) ?> class="custom-control-input" name="audience_educator_target[]" id="educator-k-12" value="k-12">
            <label class="custom-control-label" for="educator-k-12">K-12</label>
        </div>
        <div class="custom-control custom-checkbox">
            <input type="checkbox" <?php echo set_checkbox('audience_educator_target', 'post secondary', !isset($offering->audience_educator_target) ? null : in_array('post secondary', $offering->audience_educator_target)) ?> class="custom-control-input" name="audience_educator_target[]" id="educator-post-secondary" value="post secondary">
            <label class="custom-control-label" for="educator-post-secondary">Post Secondary</label>
        </div>
        <div class="custom-control custom-checkbox">
            <input type="checkbox" <?php echo set_checkbox('audience_educator_target', 'tech trainer', !isset($offering->audience_educator_target) ? null : in_array('tech trainer', $offering->audience_educator_target)) ?> class="custom-control-input" name="audience_educator_target[]" id="educator-tech-trainer" value="tech trainer">
            <label class="custom-control-label" for="educator-tech-trainer">Tech Trainer</label>
        </div>
        <?php echo form_error('audience_educator_target') ?>
    </div>

    <!-- PARTICIPATION FEE -->
    <div data-expand="internship_type" data-expand-if="0">
        <h2 class="mt-5 mb-4"><i class="fa fa-chevron-right" aria-hidden="true"></i>Participation Fee</h2>

        <div class="form-group">
            <label class="required">Is this offering free or does it require a fee?</label>
            <select class="form-control <?php echo is_valid('fee') ?>" name="fee" value="<?php echo set_value('fee', $offering->fee ?? null) ?>">
                <option value="">Select</option>
                <option <?php echo set_select('fee', '0', !isset($offering->fee) ? null : $offering->fee == '0') ?> value="0">Free</option>
                <option <?php echo set_select('fee', '1', !isset($offering->fee) ? null : $offering->fee == '1') ?> value="1">Fee for Participation</option>
            </select>
            <?php echo form_error('fee') ?>
        </div>

        <div class="form-group" data-expand="fee" data-expand-if="1">
            <label class="required">Price</label>
            <div class="input-group mb-3 <?php echo is_valid('fee_price') ?>">
                <div class="input-group-prepend">
                    <span class="input-group-text">$</span>
                </div>
                <input type="number" class="form-control <?php echo is_valid('fee_price') ?>" name="fee_price" value="<?php echo set_value('fee_price', $offering->fee_price ?? null) ?>" step=".01">
            </div>
            <small class="form-text text-muted">Please enter one numeric value</small>
            <?php echo form_error('fee_price') ?>
        </div>

        <div class="form-group" data-expand="fee" data-expand-if="1">
            <label class="required">Are there scholarships or funding available?</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" <?php echo set_radio('fee_has_scholarship', '1', !isset($offering->fee_has_scholarship) ? null : $offering->fee_has_scholarship == '1') ?> name="fee_has_scholarship" id="fee-scholarship-1" value="1">
                <label class="form-check-label" for="fee-scholarship-1">Yes</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" <?php echo set_radio('fee_has_scholarship', '0', !isset($offering->fee_has_scholarship) ? null : $offering->fee_has_scholarship == '0') ?> name="fee_has_scholarship" id="fee-scholarship-0" value="0">
                <label class="form-check-label" for="fee-scholarship-0">No</label>
            </div>
            <?php echo form_error('fee_has_scholarship') ?>
        </div>

        <div class="form-group" data-expand="fee_has_scholarship" data-expand-if="1">
            <label class="required">Scholarship URL/Contact Information</label>
            <input type="text" class="form-control <?php echo is_valid('fee_scholarship_contact') ?>" name="fee_scholarship_contact" value="<?php echo set_value('fee_scholarship_contact', $offering->fee_scholarship_contact ?? null) ?>">
            <small class="form-text text-muted">Website or event page where participants can get information.</small>
            <?php echo form_error('fee_scholarship_contact') ?>
        </div>
    </div>
    

    <!-- OTHER -->
    <h2 class="mt-5 mb-4"><i class="fa fa-chevron-right" aria-hidden="true"></i>Other</h2>

    <div class="form-group" data-expand="internship_type" data-expand-if="0">
        <label class="required">Group Size</label>
        <div class="custom-control custom-checkbox">
            <input type="checkbox" <?php echo set_checkbox('group_size', '1-4', !isset($offering->group_size) ? null : in_array('1-4', $offering->group_size)) ?> class="custom-control-input" name="group_size[]" value="1-4" id="group-1-4">
            <label class="custom-control-label" for="group-1-4">1-4</label>
        </div>
        <div class="custom-control custom-checkbox">
            <input type="checkbox" <?php echo set_checkbox('group_size', '5-10', !isset($offering->group_size) ? null : in_array('5-10', $offering->group_size)) ?> class="custom-control-input" name="group_size[]" value="5-10" id="group-5-10">
            <label class="custom-control-label" for="group-5-10">5-10</label>
        </div>
        <div class="custom-control custom-checkbox">
            <input type="checkbox" <?php echo set_checkbox('group_size', '11-20', !isset($offering->group_size) ? null : in_array('11-20', $offering->group_size)) ?> class="custom-control-input" name="group_size[]" value="11-20" id="group-11-20">
            <label class="custom-control-label" for="group-11-20">11-20</label>
        </div>
        <div class="custom-control custom-checkbox">
            <input type="checkbox" <?php echo set_checkbox('group_size', '21-35', !isset($offering->group_size) ? null : in_array('21-35', $offering->group_size)) ?> class="custom-control-input" name="group_size[]" value="21-35" id="group-21-35">
            <label class="custom-control-label" for="group-21-35">21-35</label>
        </div>
        <div class="custom-control custom-checkbox">
            <input type="checkbox" <?php echo set_checkbox('group_size', '36', !isset($offering->group_size) ? null : in_array('36', $offering->group_size)) ?> class="custom-control-input" name="group_size[]" value="36" id="group-36">
            <label class="custom-control-label" for="group-36">36+</label>
        </div>
        <?php echo form_error('group_size') ?>
    </div>

    <div class="form-group">
        <label class="required">Is the offering/location ADA compliant?</label>
        <div class="form-check">
            <input class="form-check-input" type="radio" <?php echo set_radio('is_ada', '1', !isset($offering->is_ada) ? null : $offering->is_ada == '1') ?> name="is_ada" id="ada-1" value="1">
            <label class="form-check-label" for="ada-1">Yes</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" <?php echo set_radio('is_ada', '0', !isset($offering->is_ada) ? null : $offering->is_ada == '0') ?> name="is_ada" id="ada-0" value="0">
            <label class="form-check-label" for="ada-0">Call for details</label>
        </div>
        <?php echo form_error('is_ada') ?>
    </div>

    <div class="form-group" data-expand="is_ada" data-expand-if="0">
        <label class="required">Contact for ADA Information</label>
        <input type="text" class="form-control <?php echo is_valid('ada_contact') ?>" name="ada_contact" value="<?php echo set_value('ada_contact', $offering->ada_contact ?? null) ?>">
        <?php echo form_error('ada_contact') ?>
    </div>


    <div class="form-group">
        <label class="form-label">Banner Image</label>
        <?php if (isset($offering) && $offering->image): ?>
            <img src="<?php echo base_url("img/offering/{$offering->image}") ?>" class="w-50 rounded mb-2">
        <?php endif ?>
        <div class="custom-file">
            <input type="file" class="custom-file-input <?php echo is_valid('image') ?>" id="image" name="image">
            <label class="custom-file-label" for="image">Choose file</label>
            <?php echo form_error('image') ?>
        </div>
        <small class="form-text text-muted">If you have an image to use for this offering, select it above.</small>
        <small class="form-text text-muted">PNG/JPEG/JPG &middot; &lt;1MB filesize. Recommended dimensions 360px x 210px.</small>
    </div>
    
    <?php if (!isset($offering)): ?>
        <button type="submit" class="btn btn-primary">Submit</button>
    <?php elseif ($this->router->fetch_method() == 'review'): ?>
        <small class="text-muted">The submitter and offering organizer (if any) will be notified via e-mail of the status once submitted.</small>
        <input type="submit" value="Approve & Publish" name="approve" class="btn btn-primary btn-block">
        <div class='text-muted p-3 text-center'>- or -</div>
        <input type="submit" value="Reject" name="reject" class="btn btn-secondary btn-block">
    <?php elseif ($this->router->fetch_method() == 'edit'): ?>
        <button type="submit" class="btn btn-primary">Save</button>
    <?php endif ?>
</form>
<script type="text/javascript">
    var subcategory = '<?php echo addslashes($offering->subcategory ?? null) ?>';
    var previous_input_organization = '<?php echo addslashes($this->input->post('organization')) ?>';
    var previous_input_department = '<?php echo addslashes($this->input->post('department')) ?>';
</script>