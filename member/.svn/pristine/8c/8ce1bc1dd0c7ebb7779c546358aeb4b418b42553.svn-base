<?php $this->load->view('admin/header') ?>
<?php $this->load->view('admin/menu') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="page-header">
                        <h1 class="page-title">
                            <?php echo $offering->title ?> <span class="text-muted small"><span class="p-2">&middot;</span> Best Practices Assessment</span>
                        </h1>
                    </div>
                    <a href="<?php echo base_url('admin/offering') ?>" class="btn btn-secondary mb-3"><i class="fe fe-chevron-left"></i> Back to Offerings </a>
                    <?php echo alerts() ?>
                    <div class="row">
                        <div class="col-lg-3">

                            <div class="card">
                                <div class="card-header"><h3 class="card-title mr-auto">Results Summary</h3></div>
                                <table class="card-table table">
                                    <tr>
                                        <th>Proficient</th>
                                        <td><?php echo $results['proficient'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Developing</th>
                                        <td><?php echo $results['developing'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Neither</th>
                                        <td><?php echo $results['neither'] ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header"><h3 class="card-title mr-auto">Assessment</h3></div>
                        <div class="card-body">
                            <h2 class=""><i class="fa fa-chevron-right" aria-hidden="true"></i>Infrastructure</h2>
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
                                                <label class="ose-label w-100 p-2 rounded <?php echo has_ose_answer($question->id, 1, $submissions) ? 'bg-blue-lt' : '' ?>">
                                                    <div><b>Developing</b></div>
                                                    <div class="text-muted small"><?php echo $question->options->developing->text ?? null ?></div>
                                                </label>
                                            </div>
                                            <div>
                                                <label class="ose-label w-100 p-2 rounded <?php echo has_ose_answer($question->id, 2, $submissions) ? 'bg-blue-lt' : '' ?>">
                                                    <div><b>Proficient</b></div>
                                                    <div class="text-muted small"><?php echo $question->options->proficient->text ?? null ?></div>
                                                </label>
                                            </div>
                                            <div>
                                                <label class="ose-label w-100 p-2 rounded <?php echo has_ose_answer($question->id, 0, $submissions) ? 'bg-blue-lt' : '' ?>">
                                                    <div><b>Neither</b></div>
                                                    <div class="text-muted small"><?php echo $question->options->neither->text ?? null ?></div>
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
                                                <label class="ose-label w-100 p-2 rounded <?php echo has_ose_answer($question->id, 1, $submissions) ? 'bg-blue-lt' : '' ?>">
                                                    <div><b>Developing</b></div>
                                                    <div class="text-muted small"><?php echo $question->options->developing->text ?? null ?></div>
                                                </label>
                                            </div>
                                            <div>
                                                <label class="ose-label w-100 p-2 rounded <?php echo has_ose_answer($question->id, 2, $submissions) ? 'bg-blue-lt' : '' ?>">
                                                    <div><b>Proficient</b></div>
                                                    <div class="text-muted small"><?php echo $question->options->proficient->text ?? null ?></div>
                                                </label>
                                            </div>
                                            <div>
                                                <label class="ose-label w-100 p-2 rounded <?php echo has_ose_answer($question->id, 0, $submissions) ? 'bg-blue-lt' : '' ?>">
                                                    <div><b>Neither</b></div>
                                                    <div class="text-muted small"><?php echo $question->options->neither->text ?? null ?></div>
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
                                                <label class="ose-label w-100 p-2 rounded <?php echo has_ose_answer($question->id, 1, $submissions) ? 'bg-blue-lt' : '' ?>">
                                                    <div><b>Developing</b></div>
                                                    <div class="text-muted small"><?php echo $question->options->developing->text ?? null ?></div>
                                                </label>
                                            </div>
                                            <div>
                                                <label class="ose-label w-100 p-2 rounded <?php echo has_ose_answer($question->id, 2, $submissions) ? 'bg-blue-lt' : '' ?>">
                                                    <div><b>Proficient</b></div>
                                                    <div class="text-muted small"><?php echo $question->options->proficient->text ?? null ?></div>
                                                </label>
                                            </div>
                                            <div>
                                                <label class="ose-label w-100 p-2 rounded <?php echo has_ose_answer($question->id, 0, $submissions) ? 'bg-blue-lt' : '' ?>">
                                                    <div><b>Neither</b></div>
                                                    <div class="text-muted small"><?php echo $question->options->neither->text ?? null ?></div>
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
                                                <label class="ose-label w-100 p-2 rounded <?php echo has_ose_answer($question->id, 1, $submissions) ? 'bg-blue-lt' : '' ?>">
                                                    <div><b>Developing</b></div>
                                                    <div class="text-muted small"><?php echo $question->options->developing->text ?? null ?></div>
                                                </label>
                                            </div>
                                            <div>
                                                <label class="ose-label w-100 p-2 rounded <?php echo has_ose_answer($question->id, 2, $submissions) ? 'bg-blue-lt' : '' ?>">
                                                    <div><b>Proficient</b></div>
                                                    <div class="text-muted small"><?php echo $question->options->proficient->text ?? null ?></div>
                                                </label>
                                            </div>
                                            <div>
                                                <label class="ose-label w-100 p-2 rounded <?php echo has_ose_answer($question->id, 0, $submissions) ? 'bg-blue-lt' : '' ?>">
                                                    <div><b>Neither</b></div>
                                                    <div class="text-muted small"><?php echo $question->options->neither->text ?? null ?></div>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </table>
                        </div>
                    </div>


                    <a href="<?php echo base_url('admin/offering') ?>" class="btn btn-secondary mb-3"><i class="fe fe-chevron-left"></i> Back to Offerings </a>
            </div>
        </div>
    </div>
</div>
</div>
<?php $this->load->view('admin/footer') ?>