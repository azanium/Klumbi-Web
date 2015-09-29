<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-danger">
              <div class="panel-heading"><h4><?php echo $title; ?></h4></div>
              <div class="panel-body"><?php echo $this->tambahan_fungsi->filter_text($data); ?></div>
            </div>
        </div>
    </div>
</div>