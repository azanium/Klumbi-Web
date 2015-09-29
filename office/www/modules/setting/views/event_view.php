<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
$(document).ready(function() {
    $('a.panel-collapse').click(function() {
        $(this).children().toggleClass("icon-chevron-down icon-chevron-up");
        $(this).closest(".panel-heading").next().toggleClass("in");
        $(this).closest(".panel-heading").toggleClass('rounded-bottom');
        return false;
    });
    $(".resetform").click(function(){
        $("input, textarea").val("");
        $("#filepreview").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
    });
    $('#fileimage').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploader"); ?>',
        dataType: 'json',
        done: function (e, data) {
            if(data.result.success==true)
            {
                $.pnotify({
                    title: "Success",
                    text: "File success uploaded",
                    type: 'success'
                });
                $("#txtfileimgname").val(data.result.name);
                $("#filepreview").attr("src", '<?php echo $this->config->item('path_asset_img'); ?>preview_images/'+data.result.name);
            }
            else
            {
                $.pnotify({
                    title: "Error Upload file",
                    text: data.result.message,
                    type: 'error'
                });
            }
        }
    });
    $('.deletedata').click(function(){
            BootstrapDialog.confirm("Are you sure want to delete this data ?", function(result){
                if(result) 
                {
                    $.ajax({
                        type: "POST",
                        url: $('#deletedata').attr('href'),
                        data:"",
                        dataType: "json",
                        beforeSend: function ( xhr ) {
                            $("#loadingprocess").html('<div class="alert alert-dismissable alert-warning">' +
                                                        '<strong>Warning!</strong> ' +
                                                        '<img src="<?php echo base_url(); ?>resources/image/1s.gif" alt="loading" />' +
                                                        '<i class="process">Wait a minute, Your request being processed</i>' +
                                                        '</div>').slideDown(100);
                        },
                        success: function (data, textStatus) {
                            $("#loadingprocess").slideUp(100);
                            if(data['success']==true)
                            {                        
                                $.pnotify({
                                    title: "Success",
                                    text: data['message'],
                                    type: 'success'
                                });
                                $('#editdata').modal('hide');
                                $('#calendar-drag').fullCalendar('refetchEvents');
                            }
                            else
                            {
                                $.pnotify({
                                    title: "Fail",
                                    text: data['message'],
                                    type: 'info'
                                });
                            }                      
                        },
                        error: function (xhr, textStatus, errorThrown) {
                            $("#loadingprocess").slideUp(100);
                            $.pnotify({
                                        title: textStatus + " " + xhr.status,
                                        text: (errorThrown ? errorThrown : xhr.status),
                                        type: 'error'
                                    });
                        }
                    });
                }
            });
            return false;
    });
    $('#reload').click(function(){
        $('#calendar-drag').fullCalendar('refetchEvents');
    });
    $('.cpicker').colorpicker();
    $('textarea.autosize').autosize({append: "\n"});
    $("#txtdatebegin").datepicker({
        dateFormat:"yy-mm-dd",
        selectOtherMonths: true,
        yearRange: '2013:2020',
        defaultDate: +7,
        autoSize: true,
        appendText: '(yyyy-mm-dd)'
    });
    $(".resetdev").click(function(){
        $("#txtchkall").val("1");
    });
    $("#txtdatebegin").change(function() {
        var test = $(this).datepicker('getDate');
        var testm = new Date(test.getTime());
        testm.setDate(testm.getDate());
        $("#txtdateend").datepicker("option", "minDate", testm);
        $( "#txtdatebegin" ).focus();
    });
    $( "#txtdateend" ).datepicker({
        selectOtherMonths: true,
        dateFormat: 'yy-mm-dd'
    });
    $('#datepickerbeginbtn').click(function () {
        $('#txtdatebegin').datepicker("show");
    });
    $('#datepickerendbtn').click(function () {
        $('#txtdateend').datepicker("show");
    });
    $("#txttimebegin , #txttimeend").timepicker({
       hours: { starts: 4, ends: 22 },
       minutes: { interval: 5 },
       rows: 3,
       showPeriodLabels: true,
       showPeriod: true,
       showLeadingZero: true,
       timeFormat: 'H:i:s',
       minuteText: 'Min'
    });
    $('#timepickerbeginbtn').click(function () {
        $('#txttimebegin').timepicker("show");
    });
    $('#timepickerendbtn').click(function () {
        $('#txttimeend').timepicker("show");
    });     
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
    var can_edit = <?php 
                    if($this->m_checking->actions("Calendar Event","module9","Edit",TRUE,FALSE,"home"))
                    {
                        echo "true;";
                    }
                    else
                    {
                        echo "false;";
                    }
                    ?>
    var can_add = <?php 
                    if($this->m_checking->actions("Calendar Event","module9","Add",TRUE,FALSE,"home"))
                    {
                        echo "true;";
                    }
                    else
                    {
                        echo "false;";
                    }
                    ?>
    $('#calendar-drag').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        editable: can_edit,
        droppable: can_edit,
        selectable: can_add,
        draggable: can_edit,
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
            if (event.code !="") 
            {
                var datagambar = "";
                if(event.picture!="")
                {
                    datagambar = "<p><img src='<?php echo $this->config->item('path_asset_img') . "preview_images/"; ?>" + event.picture + "' alt='' class='img-responsive' /></p>";
                }
                $('#innercalenderdet').html("<h2>" + event.title + "</h2>" + 
                                "<p><strong>Start</strong> : " + event.start_date + ", " + event.start_time + "</p>" +
                                "<p><strong>End</strong> : " + event.end_date + ", " + event.end_time + "</p>" +
                                "<p><strong>All Day : </strong>" + event.allDay + "</p>" +
                                "<p><strong>Link : </strong>" + event.url + "</p>" +
                                "<p class=\"error\">Description :</p>" +
                                "<p>" + event.description + "</p>" + 
                                datagambar
                );                
                $('#editdata').modal('show');
                var newurl = '<?php echo base_url()."setting/event/delete/"; ?>' + event.code;
                $('#deletedata').attr('href', newurl);
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
        },
        select: function(start, end, allDay) {
            <?php 
            if($this->m_checking->actions("Calendar Event","module9","Add",TRUE,FALSE,"home"))
            {
            ?>
            $('ul.nav-tabs').children().removeClass('active');
            $('a[href=#addnewdata]').parents('li:first').addClass('active');
            $('div.tab-content').children().removeClass('active');
            $('#addnewdata').addClass('active');
            var start_date = $.fullCalendar.formatDate(start, "yyyy-MM-dd");
            var end_date = $.fullCalendar.formatDate(end, "yyyy-MM-dd");
            var start_time = $.fullCalendar.formatDate(start, "HH:mm:ss");
            var end_time = $.fullCalendar.formatDate(end, "HH:mm:ss");
            $('#txttimebegin').val(start_time);
            $('#txttimeend').val(end_time);
            $("#txtdateend").val(end_date);
            $("#txtdatebegin").val(start_date);
            $("#txtcolor").val("#C25845");
            $("#txtchkall").val("1");
            if(allDay)
            {
                $('#txtchkall').attr('checked','checked');
            }
            else
            {
                $('#txtchkall').removeAttr('checked');
            }
            $('#calendar').fullCalendar('unselect');
            <?php
            }
            ?>
        },        
        eventResize: function (event, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view) {
            <?php  
            if($this->m_checking->actions("Calendar Event","module9","Edit",TRUE,FALSE,"home"))
            {
            ?>
            var start_date = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd");
            var end_date = $.fullCalendar.formatDate(event.end, "yyyy-MM-dd");
            var start_time = $.fullCalendar.formatDate(event.start, "HH:mm:ss");
            var end_time = $.fullCalendar.formatDate(event.end, "HH:mm:ss");
            var datapost = "&txtid=" + event.code;
            datapost += "&name=" + event.title;
            datapost += "&txtdatebegin=" + start_date;
            datapost += "&txttimebegin=" + start_time;
            datapost += "&txtdateend=" + end_date;
            datapost += "&txttimeend=" + end_time;
            datapost += "&txtcolor=" + event.color;
            datapost += "&txtdescriptions=" + event.description;
            datapost += "&txturl=" + event.url;
            if(allDay)
            {
                datapost += "&txtchkall=1";
            }
            ubahdata(datapost);
            <?php
            }
            ?>
        },
        eventDrop: function (event, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view) {
            <?php 
            if($this->m_checking->actions("Calendar Event","module9","Edit",TRUE,FALSE,"home"))
            {
            ?>
            var start_date = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd");
            var end_date = $.fullCalendar.formatDate(event.end, "yyyy-MM-dd");
            var start_time = $.fullCalendar.formatDate(event.start, "HH:mm:ss");
            var end_time = $.fullCalendar.formatDate(event.end, "HH:mm:ss");
            var datapost = "&txtid=" + event.code;
            datapost += "&name=" + event.title;
            datapost += "&txtdatebegin=" + start_date;
            datapost += "&txttimebegin=" + start_time;
            datapost += "&txtdateend=" + end_date;
            datapost += "&txttimeend=" + end_time;
            datapost += "&txtcolor=" + event.color;
            datapost += "&txtdescriptions=" + event.description;
            datapost += "&txturl=" + event.url;
            if(allDay)
            {
                datapost += "&txtchkall=1";
            }
            ubahdata(datapost);
            <?php 
            }
            ?>
        }
    });
    $("#brandfrm").validate({
        submitHandler: function(form) {
            var url=$("#brandfrm").attr('action');
            var datapost=$("#brandfrm").serialize();
            $.ajax({
                type: "POST",
                url: url,
                data:datapost,
                dataType: "json",
                beforeSend: function ( xhr ) {
                    $("#loadingprocess").html('<div class="alert alert-dismissable alert-warning">' +
                                            '<strong>Warning!</strong> ' +
                                            '<img src="<?php echo base_url(); ?>resources/image/1s.gif" alt="loading" />' +
                                            '<i class="process">Wait a minute, Your request being processed</i>' +
                                            '</div>').slideDown(100);
                },
                success: function (data, textStatus) {
                    $("#loadingprocess").slideUp(100);
                    if(data['success']==true)
                    {                        
                        $.pnotify({
                            title: "Success",
                            text: data['message'],
                            type: 'success'
                        });
                        $("#filepreview").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
                        $("input, textarea").val("");
                        $("#txtchkall").val("1");
                        $('#calendar-drag').fullCalendar('refetchEvents');
                        $('ul.nav-tabs').children().removeClass('active');
                        $('a[href=#listdata]').parents('li:first').addClass('active');
                        $('div.tab-content').children().removeClass('active');
                        $('#listdata').addClass('active');
                    }
                    else
                    {
                        $.pnotify({
                            title: "Fail",
                            text: data['message'],
                            type: 'info'
                        });
                    }                   
                },
                error: function (xhr, textStatus, errorThrown) {
                    $("#loadingprocess").slideUp(100);
                    $.pnotify({
                        title: textStatus + " " + xhr.status,
                        text: (errorThrown ? errorThrown : xhr.status),
                        type: 'error'
                    });
                }
           });     
           return false;
        }
    });
});
function ubahdata(datapost)
{
    $.ajax({
        type: "POST",
        url: $('#brandfrm').attr('action'),
        data:datapost,
        dataType: "json",
        beforeSend: function() {
            $("#loadingprocess").html('<div class="alert alert-dismissable alert-warning">' +
                                            '<strong>Warning!</strong> ' +
                                            '<img src="<?php echo base_url(); ?>resources/image/1s.gif" alt="loading" />' +
                                            '<i class="process">Wait a minute, Your request being processed</i>' +
                                            '</div>').slideDown(100);
       },
       success: function(data, textStatus){
            $("#loadingprocess").slideUp(100);
            if(data['success']==true)
            {                        
                $.pnotify({
                    title: "Success",
                    text: data['message'],
                    type: 'success'
                });
                $("input, textarea").val("");
                $("#txtchkall").val("1");
                $('#calendar-drag').fullCalendar('refetchEvents');
            }
            else
            {
                $.pnotify({
                    title: "Fail",
                    text: data['message'],
                    type: 'info'
                });
            }
        },
        error: function (xhr, textStatus, errorThrown) {
            $("#loadingprocess").slideUp(100);
            $.pnotify({
                title: textStatus + " " + xhr.status,
                text: (errorThrown ? errorThrown : xhr.status),
                type: 'error'
            });
        }
    });   
}
</script>
<div id="confirmdlg">&nbsp;</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-purple">
                <div class="panel-heading">
                    <h4>Calendar Event</h4>
                    <div class="options">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#listdata" data-toggle="tab"><i class="icon-table"></i> Event</a></li>
                          <li><a href="#addnewdata" data-toggle="tab"><i class="icon-plus"></i> Create Event</a></li>
                        </ul>
                        <a href="javascript:;" class="panel-collapse"><i class="icon-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <div class="tab-content">
                        <div class="tab-pane active" id="listdata">     
                            <p align="right">
                                <a id="reload" class="btn btn-sm btn-success"><i class="icon-refresh"></i> Reload Data</a>
                            </p>
                            <div id='calendar-drag'>&nbsp;</div>
                        </div>
                        <div class="tab-pane" id="addnewdata">
                            <form method="POST" class="form-horizontal" id="brandfrm" action="<?php echo $this->template_admin->link("setting/event/cruid_event"); ?>">
                                <div class="form-group">
                                    <input type="hidden" name="txtid" id="txtid" value="" />
                                    <input type="hidden" name="txtfileimgname" id="txtfileimgname" value="" />
                                    <label for="name" class="col-sm-3 control-label">Name</label>
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="icon-book"></i></span>
                                            <input type="text" name="name" id="name" value="" maxlength="255" placeholder="Event Name" class="form-control {required:true}" />
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Name of event!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="txtdatebegin" class="col-sm-3 control-label">Date Begin</label>
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <input type="text" name="txtdatebegin" id="txtdatebegin" value="" maxlength="255" placeholder="Date Begin" data-type="dateIso" class="form-control {required:true}" />
                                            <span class="input-group-addon" id="datepickerbeginbtn"><i class="icon-calendar"></i></span>
                                        </div>                                      
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">format yyyy-mm-dd</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="txttimebegin" class="col-sm-3 control-label">Time Begin</label>
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <input type="text" name="txttimebegin" id="txttimebegin" value="" maxlength="255" placeholder="Time Begin" class="form-control {required:true}" />
                                            <span class="input-group-addon" id="timepickerbeginbtn"><i class="icon-time"></i></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Format H:i:s</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="txtdateend" class="col-sm-3 control-label">Date End</label>
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <input type="text" name="txtdateend" id="txtdateend" value="" maxlength="255" placeholder="Date End" data-type="dateIso" class="form-control {required:true}" />
                                            <span class="input-group-addon" id="datepickerendbtn"><i class="icon-calendar"></i></span>
                                        </div>                                      
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">format yyyy-mm-dd</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="txttimeend" class="col-sm-3 control-label">Time End</label>
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <input type="text" name="txttimeend" id="txttimeend" value="" maxlength="255" placeholder="Time End" class="form-control {required:true}" />
                                            <span class="input-group-addon" id="timepickerendbtn"><i class="icon-time"></i></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Format H:i:s</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="txtcolor" class="col-sm-3 control-label">Color</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="txtcolor" id="txtcolor" class="form-control cpicker {required:true}" value="#5c19a3" />
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Backgroud color event!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="txturl" class="col-sm-3 control-label">Url Link</label>
                                    <div class="col-sm-6">
                                      <input type="url" name="txturl" id="txturl" value="" maxlength="255" placeholder="http://" class="form-control {url:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Link to another site!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Full All Day</label>
                                    <div class="col-sm-6">
                                      <label class="checkbox-inline"><input type="checkbox" id="txtchkall" name="txtchkall" value="1" /> All day</label>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Check this if event for all day!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="txtdescriptions" class="col-sm-3 control-label">Descriptions</label>
                                    <div class="col-sm-9">
                                        <textarea name="txtdescriptions" id="txtdescriptions" class="form-control autosize {required:true}"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="fileimage" class="col-sm-3 control-label">Picture</label>
                                    <div class="col-sm-6">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose file...</span>
                                            <input type="file" name="fileimage" id="fileimage" />
                                        </span>
                                    </div>
                                    <div class="col-sm-3">
                                        <img id="filepreview" src="<?php echo base_url(); ?>resources/image/none.jpg" alt="No Image" class="img-thumbnail" style="max-width:100px; max-height:100px;" />
                                        <p class="help-block">Image file of Event!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3">&nbsp;</div>
                                    <div class="col-sm-9">
                                        <?php 
                                        if($this->m_checking->actions("Calendar Event","module9","Add",TRUE,FALSE,"home"))
                                        {
                                            echo '<button type="submit" class="btn-green btn"> <i class="icon-save"></i> <span>Save</span></button>&nbsp;&nbsp;'; 
                                        }
                                        ?>
                                        <button type="reset" class="btn-default btn resetdev resetform"><i class="icon-file-alt"></i> <span>Reset</span></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
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
                <?php
                if($this->m_checking->actions("Calendar Event","module9","Delete",TRUE,FALSE,"home"))
                {
                    echo "<a id='deletedata' href='#' class='btn btn-danger deletedata'>Delete</a>";
                }
                ?>
            </div>
        </div>
    </div>
</div>