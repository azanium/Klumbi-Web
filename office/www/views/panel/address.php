<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
$(document).ready(function() { 
    $('#back-to-top').click(function(){
        $('body,html').animate({
            scrollTop: 0
        }, 500);
        return false;
    });
});
</script>
<footer role="contentinfo">
    <div class="clearfix">
        <ul class="list-unstyled list-inline">
            <li><?php echo $this->config->item('aplicationname'); ?> &copy; 2014</li>
            <button class="pull-right btn btn-inverse btn-xs" id="back-to-top" style="margin-top: -1px; text-transform: uppercase;"><i class="icon-arrow-up"> </i></button>
        </ul>
    </div>
</footer>
<script type='text/javascript' src='<?php echo base_url()."resources/js/application.js"; ?>'></script>
<script type='text/javascript' src='<?php echo base_url()."resources/js/resourceadd.js"; ?>'></script>