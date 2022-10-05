<?php $this->load->view('header-sya') ?>
<div class="page offering offering-quiz">
    <div class="page-main">
        <div class="heading">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h1>Offerings Quiz</h1>
                        <p>Find out which one of our offering would be right for you.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="offerings quiz">
            <div class="container bg-white">
                <div class="row">
                    <div class="col-lg-6 offset-lg-3 py-5 mb-5">
                        <h5 class="mb-3 font-weight-bold"><?php echo $question['question'] ?></h5>
                        <?php alerts() ?>
                        <form method="post">
                            <div class="list-group">
                                <?php foreach ($question['answers'] as $answer): ?>
                                    <label class="list-group-item">
                                        <?php if ($question['filter_key'] == 'category[]'): ?>
                                            <input class="form-check-input d-none" type="checkbox" name="<?php echo $question['filter_key'] ?>" value="<?php echo $answer['filter_value'] ?>">

                                        <?php else: ?>
                                            <input class="form-check-input d-none" type="radio" name="<?php echo $question['filter_key'] ?>" value="<?php echo $answer['filter_value'] ?>">
                                        <?php endif ?>
                                      <?php echo $answer['label'] ?>
                                    </label>
                                <?php endforeach ?>
                            </div>
                              <input type="submit" value="Next" class="btn btn-primary btn-sm my-3">
                          </form>
                          <a href="<?php echo base_url('offering') ?>" class="small text-muted">Back to Offerings List</a>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
<?php 
$script = <<<EOF
    $("[type=checkbox]").change(function() {
        if(this.checked) {
            $(this).parent().addClass('active');
        } else {
            $(this).parent().removeClass('active');

        }
    });
    $("[type=radio]").change(function() {
        $('form label').removeClass('active');
        if(this.checked) {
            $(this).parent().addClass('active');
        }
    });
EOF;

 ?>
  <?php $this->load->view('footer-aim', compact('script')) ?>