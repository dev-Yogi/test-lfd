<?php $this->load->view('header-sya') ?>

<div class="page offering offering-view">
    <div class="page-main">
        <div class="heading">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 py-lg-5">
                        <h1><?php echo $offering->title ?></h1>
                        <p>An offering by <a href="<?php echo base_url("offering/organization/?organization={$offering->organization}") ?>" class="text-inherit"><?php echo $offering->organization ?></a></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="offering">
            <div class="container bg-white">
                <div class="row">
                    <div class="col-lg-8 py-5 pr-lg-5">
                        <?php echo nl2br($offering->description) ?>
                    </div>
                    
                    <div class="offering-side col-lg-4 py-4 pl-4 shadow mx-3 mx-lg-0">
                        <?php if ($offering->image): ?>
                            <img src="<?php echo base_url("img/offering/{$offering->image}") ?>">
                        <?php endif ?>

                        <div class="label font-weight-normal text-muted <?php if (empty($offering->image)) { echo "mt-0"; } ?>">Audience</div>
                        <div><?php echo ucwords($offering->audience_type) ?></div>

                        <?php if ($offering->internship_type): ?>
                            <div class="label font-weight-normal text-muted">Internship</div>
                            <div><?php echo ucwords($offering->internship_type) ?>, <?php echo ucwords($offering->internship_term) ?></div>
                        <?php endif ?>
                        
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="label font-weight-normal text-muted">Format</div>
                                <div><?php echo ucwords($offering->format) ?></div>
                            </div>
                        </div>

                        <?php if ($offering->location): ?>
                            <div class="label font-weight-normal text-muted">Location</div>
                            <div><?php echo $offering->location ?></div>
                        <?php endif ?>

                        <div class="row">
                            <?php if ($offering->repeat): ?>
                                <div class="col-lg-12">
                                    <div class="label font-weight-normal text-muted">Date</div>
                                    <div><?php echo date('jS F, Y', strtotime($offering->next_date)) ?></div>
                                </div>
                                <?php if (strtotime(date('Y-m-d')) > strtotime($offering->start_date)): ?>
                                    <div class="col-lg-12">
                                        <div class="label font-weight-normal text-muted">Offering Since</div>
                                        <div><?php echo date('jS F, Y', strtotime($offering->start_date)) ?></div>
                                    </div>
                                <?php endif ?>
                            <?php else: ?>
                                <div class="col-lg-6">
                                    <div class="label font-weight-normal text-muted">Start Date</div>
                                    <div><?php echo date('jS F, Y', strtotime($offering->start_date)) ?></div>
                                </div>
                            <?php endif ?>

                            <?php if ($offering->end_date): ?>
                                <div class="col-lg-6">
                                    <div class="label font-weight-normal text-muted">End Date</div>
                                <div><?php echo date('jS F, Y', strtotime($offering->end_date)) ?></div>
                                </div>
                            <?php endif ?>
                        </div>

                        <div class="row">
                            <?php if ($offering->start_time): ?>
                                <div class="col-lg-6">
                                    <div class="label font-weight-normal text-muted">Start Time</div>
                                <div><?php echo date('g:i A', strtotime($offering->start_time)) ?></div>
                                </div>
                            <?php endif ?>
                            <?php if ($offering->end_time): ?>
                                <div class="col-lg-6">
                                    <div class="label font-weight-normal text-muted">End Time</div>
                                <div><?php echo date('g:i A', strtotime($offering->end_time)) ?></div>
                                </div>
                            <?php endif ?>
                        </div>

                        <?php if ($offering->repeat): ?>
                            <div class="label font-weight-normal text-muted">Repeats</div>
                            <div>Repeats every <?php echo $offering->repeat_every_unit ?></div>
                            <?php if ($offering->repeat_every_unit == 'week' && $offering->repeat_every_weekdays): ?>
                                <div>on <?php echo ucwords(implode(", ", json_decode($offering->repeat_every_weekdays))) ?></div>
                            <?php endif ?>
                        <?php endif ?>

                        <div class="label font-weight-normal text-muted">Fee</div>
                        <div><?php echo $offering->fee ? "$" . $offering->fee_price : "Free" ?></div>

                        <div class="label font-weight-normal text-muted">Category</div>
                        <div><?php echo ucwords($offering->category) ?> - <?php echo $offering->subcategory ?></div>

                        <?php if ($offering->ose_grade == 1 || $offering->ose_grade == 2): ?>
                            <img class="ose-approved" src="<?php echo base_url('img/offering/approved.png') ?>">
                        <?php endif ?>

                        <?php if ($offering->url): ?>
                            <a href="<?php echo base_url("offering/url/{$offering->id}") ?>" target="_blank" class="btn btn-lg btn-primary btn-block mt-4">Visit Page</a>
                        <?php endif ?>

                        <?php if ($offering->registration_url): ?>
                            <a href="<?php echo base_url("offering/registration_url/{$offering->id}") ?>" target="_blank" class="btn btn-lg btn-primary btn-block mt-4">Register</a>
                        <?php endif ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col text-center my-5"><a href="<?php echo base_url('offering') ?>" class="btn btn-link"><i class="fa fa-chevron-left mr-2" aria-hidden="true"></i> Back to offering list</a></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('footer-aim') ?>