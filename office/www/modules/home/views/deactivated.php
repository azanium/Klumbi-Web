<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div id="confirmdlg">&nbsp;</div>
<div class="container">
    <div class="panel panel-danger">
        <div class="panel-heading">
            <h4>Are you sure want to deactivated your account ?</h4>
        </div>
        <div class="panel-body">
            <p>When you deactivate your account, your Timeline and all information associated with it disappears from <?php echo $this->config->item('aplicationname'); ?> immediately. People on <?php echo $this->config->item('aplicationname'); ?> will not be able to search for you or view any of your information.</p>
            <p class="text-center">
                <a href="<?php echo $this->template_admin->link("home/deactivated/process"); ?>" class="btn btn-sky btn-lg">Yes, I'm sure.</a>&nbsp;&nbsp;&nbsp;
                <a href="<?php echo $this->template_admin->link("home/eaccount/index"); ?>" class="btn btn-orange btn-lg">Cancel</a>
            </p>
        </div>
    </div>
</div>