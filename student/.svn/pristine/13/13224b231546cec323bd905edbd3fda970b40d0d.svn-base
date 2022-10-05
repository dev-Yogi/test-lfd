<?php $this->load->view('header') ?>
<div class="page">
    <div class="page-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="page-header">
                        <h1 class="page-title">Inbox</h1>
                    </div>
                    <div class="card">
                        <table class="table card-table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Program</th>
                                    <th>Received</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($messages as $message): ?>
                                    <tr>
                                        <td><a href="<?php echo base_url("inbox/view/{$message->id}") ?>"><?php echo $message->title ?></a></td>
                                        <td><?php echo $message->program_name ?></td>
                                        <td><?php echo date('j M, Y H:ia', strtotime($message->created)) ?></td>
                                    </tr>
                                <?php endforeach ?>
                                <?php if (empty($messages)): ?>
                                    <tr>
                                        <td class="text-muted">You have no messages in your inbox.</td>
                                    </tr>
                                <?php endif ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $css = array('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css') ?>
<?php $this->load->view('footer', compact('css')) ?>