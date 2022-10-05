<?php $this->load->view('header') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="page-header">
                        <h1 class="page-title"><?php echo $message->title ?? 'Message not found' ?></h1>
                    </div>
                    <a href="<?php echo base_url('inbox') ?>" class="btn btn-secondary mb-3"><i class="fe fe-chevron-left mr-2"></i>Back to Inbox</a>
                    <?php echo alerts() ?>
                    <div class="card">
                        <table class="table card-table">
                            <tbody>
                                <tr>
                                    <th width="130">Title</th>
                                    <td><b><?php echo $message->title ?></b></td>
                                </tr>
                                <tr>
                                    <th>Program</th>
                                    <td><?php echo $message->program_name ?></td>
                                </tr>
                                <tr>
                                    <th>Received</th>
                                    <td><?php echo date('j M, Y H:ia', strtotime($message->created)) ?></td>
                                </tr>
                                <tr>
                                    <th>Message</th>
                                    <td height="200"><div class="pb-5"><?php echo nl2br($message->message) ?></div></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <?php foreach($message->replies as $reply): ?>
                        <a name="<?php echo $reply->id ?>"></a>
                        <div class="card post mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="font-weight-semibold pt-2"><?php echo $reply->first_name . " " . $reply->last_name ?></div>
                                    </div>
                                    <div class="col-lg-9 pt-1">
                                        <?php if (!$reply->removed): ?>
                                            <?php echo nl2br($reply->message) ?>
                                        <?php else: ?>
                                            <i class="text-muted">Post has been removed</i>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0 border-0">
                                <div class="row">
                                    <div class="col-lg-9 offset-3 d-flex">
                                        <div class="small text-muted">Sent <?php echo date('d/m/Y H:ia', strtotime($reply->created)) ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>

                    <?php if ($message->can_reply): ?>
                        <div class="card post">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-3">
                                    </div>
                                    <div class="col-lg-9">
                                        <form method="post">
                                            <div class="form-group">
                                                <label class="form-label">Send reply</label>
                                                <textarea type="text" rows="5" class="form-control" name="message"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <input type="submit" value="Reply" class="btn btn-primary">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $css = array('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css') ?>
<?php $this->load->view('footer', compact('css')) ?>