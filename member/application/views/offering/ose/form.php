<?php $this->load->view('header-sya') ?>
<div class="page offering offering-form">
    <div class="page-main">
        <div class="heading">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 py-5 py-5">
                        <h1>Best Practices Assessment</h1>
                        <p>Omaha STEM Ecosystem Best Practices Program Assessment Tool</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="offerings">
            <div class="container bg-white">
                <div class="row">
                    <div class="col-lg-12 p-5 mb-5">
                        <p>You are currently completeing the Omaha STEM Ecosystem Best Practices Program Assessment Tool for the offering:</p>
                        <p>
                            <b><?php echo $offering->title ?></b><br>
                            <span class="text-muted"><?php echo nl2br($offering->description) ?></span>
                        </p>
                        <p>For each goal below, please select one option from the right side corresponding to the offering's current status with the goal.</p>

                        <?php alerts() ?>
                        <form method="post">
                            <h2 class="mt-5"><i class="fa fa-chevron-right" aria-hidden="true"></i>Infrastructure</h2>
                            <table class="table ose-table">
                                <thead>
                                    <th>Goal</th>
                                    <th>Offering's Goal Status</th>
                                </thead>
                                <?php foreach ($questions['infrastructure'] as $question): ?>
                                    <tr>
                                        <td width="500px"><?php echo $question->text ?></td>
                                        <td>
                                            <div>
                                                <label class="ose-label">
                                                    <input type="radio" class="float-left mt-1" name="<?php echo $question->id ?>" value="1" <?php echo set_radio($question->id, 1) ?> required> 
                                                    <div class="ml-4"><b>Developing</b></div>
                                                    <div class="text-muted small ml-4"><?php echo $question->options->developing->text ?? null ?></div>
                                                </label>
                                            </div>
                                            <div>
                                                <label class="ose-label">
                                                    <input type="radio" class="float-left mt-1" name="<?php echo $question->id ?>" value="2" <?php echo set_radio($question->id, 2) ?> required> 
                                                    <div class="ml-4"><b>Proficient</b></div>
                                                    <div class="text-muted small ml-4"><?php echo $question->options->proficient->text ?? null ?></div>
                                                </label>
                                            </div>
                                            <div>
                                                <label class="ose-label">
                                                    <input type="radio" class="float-left mt-1" name="<?php echo $question->id ?>" value="0" <?php echo set_radio($question->id, 0) ?> required> 
                                                    <div class="ml-4"><b>Neither</b></div>
                                                    <div class="text-muted small ml-4"><?php echo $question->options->neither->text ?? null ?></div>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </table>


                            <h2 class="mt-5"><i class="fa fa-chevron-right" aria-hidden="true"></i>Goals and Evaluation</h2>
                            <table class="table ose-table">
                                <thead>
                                    <th>Goal</th>
                                    <th>Offering's Goal Status</th>
                                </thead>
                                <?php foreach ($questions['goals and evaluation'] as $question): ?>
                                    <tr>
                                        <td width="500px"><?php echo $question->text ?></td>
                                        <td>
                                            <div>
                                                <label class="ose-label">
                                                    <input type="radio" class="float-left mt-1" name="<?php echo $question->id ?>" value="1" <?php echo set_radio($question->id, 1) ?> required> 
                                                    <div class="ml-4"><b>Developing</b></div>
                                                    <div class="text-muted small ml-4"><?php echo $question->options->developing->text ?? null ?></div>
                                                </label>
                                            </div>
                                            <div>
                                                <label class="ose-label">
                                                    <input type="radio" class="float-left mt-1" name="<?php echo $question->id ?>" value="2" <?php echo set_radio($question->id, 2) ?> required> 
                                                    <div class="ml-4"><b>Proficient</b></div>
                                                    <div class="text-muted small ml-4"><?php echo $question->options->proficient->text ?? null ?></div>
                                                </label>
                                            </div>
                                            <div>
                                                <label class="ose-label">
                                                    <input type="radio" class="float-left mt-1" name="<?php echo $question->id ?>" value="0" <?php echo set_radio($question->id, 0) ?> required> 
                                                    <div class="ml-4"><b>Neither</b></div>
                                                    <div class="text-muted small ml-4"><?php echo $question->options->neither->text ?? null ?></div>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </table>


                            <h2 class="mt-5"><i class="fa fa-chevron-right" aria-hidden="true"></i>Program Components</h2>
                            <table class="table ose-table">
                                <thead>
                                    <th>Goal</th>
                                    <th>Offering's Goal Status</th>
                                </thead>
                                <?php foreach ($questions['program components'] as $question): ?>
                                    <tr>
                                        <td width="500px"><?php echo $question->text ?></td>
                                        <td>
                                            <div>
                                                <label class="ose-label">
                                                    <input type="radio" class="float-left mt-1" name="<?php echo $question->id ?>" value="1" <?php echo set_radio($question->id, 1) ?> required> 
                                                    <div class="ml-4"><b>Developing</b></div>
                                                    <div class="text-muted small ml-4"><?php echo $question->options->developing->text ?? null ?></div>
                                                </label>
                                            </div>
                                            <div>
                                                <label class="ose-label">
                                                    <input type="radio" class="float-left mt-1" name="<?php echo $question->id ?>" value="2" <?php echo set_radio($question->id, 2) ?> required> 
                                                    <div class="ml-4"><b>Proficient</b></div>
                                                    <div class="text-muted small ml-4"><?php echo $question->options->proficient->text ?? null ?></div>
                                                </label>
                                            </div>
                                            <div>
                                                <label class="ose-label">
                                                    <input type="radio" class="float-left mt-1" name="<?php echo $question->id ?>" value="0" <?php echo set_radio($question->id, 0) ?> required> 
                                                    <div class="ml-4"><b>Neither</b></div>
                                                    <div class="text-muted small ml-4"><?php echo $question->options->neither->text ?? null ?></div>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </table>


                            <h2 class="mt-5"><i class="fa fa-chevron-right" aria-hidden="true"></i>STEM Practices</h2>
                            <table class="table ose-table">
                                <thead>
                                    <th>Goal</th>
                                    <th>Offering's Goal Status</th>
                                </thead>
                                <?php foreach ($questions['stem practices'] as $question): ?>
                                    <tr>
                                        <td width="500px"><?php echo $question->text ?></td>
                                        <td>
                                            <div>
                                                <label class="ose-label">
                                                    <input type="radio" class="float-left mt-1" name="<?php echo $question->id ?>" value="1" <?php echo set_radio($question->id, 1) ?> required> 
                                                    <div class="ml-4"><b>Developing</b></div>
                                                    <div class="text-muted small ml-4"><?php echo $question->options->developing->text ?? null ?></div>
                                                </label>
                                            </div>
                                            <div>
                                                <label class="ose-label">
                                                    <input type="radio" class="float-left mt-1" name="<?php echo $question->id ?>" value="2" <?php echo set_radio($question->id, 2) ?> required> 
                                                    <div class="ml-4"><b>Proficient</b></div>
                                                    <div class="text-muted small ml-4"><?php echo $question->options->proficient->text ?? null ?></div>
                                                </label>
                                            </div>
                                            <div>
                                                <label class="ose-label">
                                                    <input type="radio" class="float-left mt-1" name="<?php echo $question->id ?>" value="0" <?php echo set_radio($question->id, 0) ?> required> 
                                                    <div class="ml-4"><b>Neither</b></div>
                                                    <div class="text-muted small ml-4"><?php echo $question->options->neither->text ?? null ?></div>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </table>
                            <input type="submit" class="btn btn-outline-primary btn-sm mt-4" value="Submit">
                        </form>
                        

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    [data-expand] {
        display: none;
    }
    h2 .fa {
        color: #007fa4;
    }
    .fa-chevron-right {
        margin-right: 0.5rem;
    }
</style>
<?php 
$js = array(
    'https://www.google.com/recaptcha/api.js', 
    base_url('/js/selectize.js'),
    base_url('/js/offering-submit.js'),
    base_url('/js/bs-custom-file-input.js')
);
$css = array(
    'https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css',
);
?>
<?php $this->load->view('footer-aim', compact('js', 'css')) ?>