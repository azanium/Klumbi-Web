 <?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-danger calendar">
                <div class="panel-heading">
                    <h4>Verify Data Account</h4>
                </div>
                <div class="panel-body">
                    <?php
                    if($status)
                    {
                        echo "<div class='alert alert-info'>Congratulations!!, You've successfully validate your email</div>";
                    }
                    else
                    {
                        echo "<div class='alert alert-danger'>Fail, Your Account is wrong, Key is not active or wrong. request new key.</div>";
                    }
                    ?> 
                    <p>Go to <a href="<?php echo $this->template_admin->link("home/index"); ?>" class="blue"><i class="icon-home"></i> home</a> site </p>
                </div>
            </div>
        </div>
    </div>
</div>