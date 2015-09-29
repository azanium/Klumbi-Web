<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script language="javascript">
$(document).ready(function() {
    $('#slidesimage').bxSlider({
        prev_image: '<?php echo base_url(); ?>resources/plugin/bslide/changeleft.png',
        next_image: '<?php echo base_url(); ?>resources/plugin/bslide/changeright.png',
        wrapper_class: 'slides1_wrap',
        margin: 70,
        auto: true,
        auto_controls: true
    });
});
SyntaxHighlighter.all();
</script>
<div class="container">
    <div class="row">
        <div class="col-md-12"> 
            <div class="panel panel-primary">
                <div id="content">
                    <div id="content_inner">
                    <?php
                    $this->mongo_db->select_db("Articles");
                    $this->mongo_db->select_collection("Slideshow");
                    $datatemp=$this->mongo_db->find(array(),0,0,array('no'=>1));
                    echo "<ul id='slidesimage'>";
                    if($datatemp)
                    {
                        foreach($datatemp as $dt)
                        {
                            echo "<li>";
                            echo "<img src='".$dt['image']."' width='210' height='210' alt='".$dt['title']."' />";
                            echo "<div class='content'>";
                            echo "<h3>".$dt['title']."</h3>";
                            echo "<h4>".$dt['link']."</h4>";
                            echo "<p>".$dt['description']."</p>";
                            echo "</div>";
                            echo "<div class='clear'></div>";
                            echo "</li>";
                        }
                    }
                    echo "</ul> ";
                    ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>