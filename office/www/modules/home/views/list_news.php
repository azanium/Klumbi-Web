<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-danger">
              <div class="panel-heading"><h4>News Stream</h4></div>
              <div class="panel-body">
                  <?php
                  $i=0;
                  $arraythema=array('alert-info','alert-warning','alert-success','alert-danger');
                  foreach($datalist as $dt)
                  {
                      if($i>3)
                      {
                          $i=0;
                      }
                      echo "<div class='alert alert-dismissable ".$arraythema[$i]."'>";
                      echo "<h3><a href='".$this->template_admin->link("home/news/detail/". urlencode($dt['alias']))."'>".$dt['title']."</a></h3> ";
                      echo word_limiter($dt['text'],30);
                      echo "<p><a class='error' href='".$this->template_admin->link("home/news/detail/".urlencode($dt['alias']))."'>Read More..</a></p>";
                      $this->mongo_db->select_db("Social");
                      $this->mongo_db->select_collection("Social");
                      $jmlcomment = $this->mongo_db->count2(array('id'=>(string)$dt['_id'],'type'=>'NewsComment'));
                      $jmllike = $this->mongo_db->count2(array('id'=>(string)$dt['_id'],'type'=>'NewsLove'));
                      echo "<p><span class='badge badge-info'>".$jmlcomment."</span> <i class='icon-comments-alt'></i>, <span class='badge badge-danger'>".$jmllike."</span> <i class='icon-thumbs-up'></i></p>";
                      echo "</div>";
                      $i++;
                  }
                  ?>
              </div>
              <div class="text-center panel-footer">
                  <?php echo $paging; ?>
                  <div class="text-info">Count Data <strong><?php echo $count; ?></strong></div>
              </div>
            </div>
        </div>
    </div>
</div>