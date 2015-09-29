<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
$(document).ready(function() { 
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
    $('#reload').click(function(){
        $('#calendar-drag').fullCalendar('refetchEvents');
    });
    $('#calendar-drag').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        editable: false,
        droppable: false,
        selectable: false,
        loading: function (bool) {
            if (bool)
            {
                $("#loadingprocess").html('<div class="alert alert-dismissable alert-warning">' +
                                    '<strong>Warning!</strong> ' +
                                    '<img src="<?php echo base_url(); ?>resources/image/1s.gif" alt="loading" />' +
                                    '<i class="process">Wait a minute, Your request being processed</i>' +
                                    '</div>').slideDown(100);
            }
            else
            {
                $("#loadingprocess").slideUp(100);
            }
        },
        selectHelper: true,
        eventSources: [
            {
                url: '<?php echo $this->template_admin->link("home/event/list_data"); ?>',
                error: function() {
                    $("#loadingprocess").html('<div class="alert alert-dismissable alert-warning">' +
                                    '<strong>Warning!</strong> ' +
                                    '<i class="error">Error load data</i>' +
                                    '</div>').slideDown(100);
                }
            }            
        ], 
        eventClick: function(event, jsEvent, view) {
            if (event.id) 
            {
                var datagambar = "";
                if(event.picture!="")
                {
                    datagambar = "<p><img src='<?php echo $this->config->item('path_asset_img') . "preview_images/"; ?>" + event.picture + "' alt='' class='img-responsive' /></p>";
                }
                $('#innercalenderdet').html("<h2>" + event.title + "</h2>" + 
                                "<p><strong>Start</strong> : " + event.start_date + ", " + event.start_time + "</p>" +
                                "<p><strong>End</strong> : " + event.end_date + ", " + event.end_time + "</p>" +
                                "<p><strong>Link : </strong>" + event.url + "</p>" +
                                "<p class=\"error\">Description :</p>" +
                                "<p>" + event.description + "</p>" + 
                                datagambar
                );
                $('#editdata').modal('show');
            }
            return false;
        },
        buttonText: {
            prev: '<i class="icon-angle-left"></i>',
            next: '<i class="icon-angle-right"></i>',
            prevYear: '<i class="icon-double-angle-left"></i>',
            nextYear: '<i class="icon-double-angle-right"></i>',
            today:    'Today',
            month:    'Month',
            week:     'Week',
            day:      'Day'
        }
    });
});
</script>
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-danger calendar">
                <div class="panel-heading">
                    <h4>Our Event</h4>
                </div>
                <div class="panel-body">
                    <div class="alert alert-info">Hi guys, Let's check up all our event, Click for more info on event :D</div>
                    <p align="right">
                        <a id="reload" class="btn btn-sm btn-success"><i class="icon-refresh"></i> Reload Data</a>
                    </p>
                    <div id='calendar-drag'>&nbsp;</div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="editdata" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Detail Event</h4>
            </div>
            <div class="modal-body">
                <div id="innercalenderdet">&nbsp;</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>                
            </div>
        </div>
    </div>
</div>