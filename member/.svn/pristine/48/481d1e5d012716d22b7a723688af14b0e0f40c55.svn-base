<?php $this->load->view('header') ?>
<div class="page-content">
    <div class="container">
        
        <?php if (has_tag(Tag::STAFF)): ?>
            <div class="page-header">
                <div>
                    <h1 class="page-title">AIM Staff Portal</h1>
                </div>
            </div>
            <div class="row">
                    <div class="col-md-6">
                        <a href="/student" class="text-decoration-none">
                            <div class="card">
                                <div class="card-body">
                                    <ul class="list-unstyled">
                                        <?php foreach ($staff_links as $link): ?>
                                            <li><a href="<?php echo $link->url ?>" target="_blank" class="btn btn-secondary btn-block text-left mb-2"><?php echo $link->label ?><i class="fe fe-chevron-right float-right mt-1"></i></a></li>
                                        <?php endforeach ?>
                                        <?php if (empty($staff_links)): ?>
                                            <div class="text-muted">There are no links in the staff portal.</div>
                                        <?php endif ?>
                                    </ul>
                                </div>
                            </div>
                        </a>
                    </div>
            </div>
        <?php endif ?>
        <div class="page-header">
            <div>
                <h1 class="page-title">Platform Home <small class="ml-2 text-muted">- Select a destination</small></h1>
            </div>
        </div>
        <div class="row">
            <?php if (has_tag(Tag::STUDENT) || has_tag(Tag::INSTRUCTOR) || has_tag(Tag::STAFF)): ?>
                <div class="col-md-3">
                    <a href="/student" class="text-decoration-none" data-toggle="popover" title="Participant Portal" data-placement="bottom" data-content="AIM's Participant Portal offers a range of online programs and courses.">
                        <div class="card text-center">
                            <i class="h1 fe fe-book-open pt-5 mt-5"></i>
                            <h5 class="pb-5 mb-5 text-dark">Participant Portal</h5>
                        </div>
                    </a>
                </div>
            <?php endif ?>
            <?php if (has_tag(Tag::OFFERING_SUBMITTER)): ?>
                <div class="col-md-3">
                    <a href="<?php echo base_url('offering/all') ?>" class="text-decoration-none" data-toggle="popover" title="Offerings" data-placement="bottom" data-content="View and update your submitted offerings for the STEM Platform.">
                        <div class="card text-center">
                            <i class="h1 fe fe-calendar pt-5 mt-5"></i>
                            <h5 class="pb-5 mb-5 text-dark">Offerings</h5>
                        </div>
                    </a>
                </div>
            <?php endif ?>
            <?php if (empty($this->member->tags)): ?>
                <div class="col-md-12">
                    <p class="text-muted">Your member account does not have access to any AIM products.</p>
                </div>
            <?php endif ?>
            <!-- <div class="col-md-3">
                <a href="" class="text-decoration-none">
                    <div class="card text-center">
                        <i class="h1 fe fe-briefcase pt-5 mt-5"></i>
                        <h5 class="pb-5 mb-5 text-dark">ATS</h5>
                    </div>
                </a>
            </div> -->
        </div>
    </div>
</div>
</div>

<?php
$this->load->view('footer');
?>