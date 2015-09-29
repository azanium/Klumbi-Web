<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
$(document).ready(function() { 
    var route = new GMaps({
        el:'#advance-route',
        lat:-6.273298,
        lng:106.818142
    });
    $('#start_travel').click(function (e) {
        $('#instructions').html("");
        e.preventDefault();
        route.travelRoute({
            origin:[-6.273298, 106.818142],
            destination:[-6.2105372,106.8311915],
            travelMode:'driving',
            step:function (e) {
                $('#instructions').append('<li>' + e.instructions + '</li>');
                $('#instructions li:eq(' + e.step_number + ')').delay(450 * e.step_number).fadeIn(300, function () {
                    route.setCenter(e.end_location.lat(), e.end_location.lng());
                    route.drawPolyline({
                        path:e.path,
                        strokeColor:'#131540',
                        strokeOpacity:0.6,
                        strokeWeight:6
                    });
                });
            }
        });
    });
});
</script>
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h4>Location on Google Maps</h4>
                </div>
                <div class="panel-body">
                    <a href="#" id="start_travel" class="btn btn-primary" style="margin: 15px 0">Start Travel</a>
                    <ul id="instructions"></ul>
                    <div id="advance-route" style="height: 400px"></div>                    
                </div>
            </div>
        </div>
    </div>
</div>