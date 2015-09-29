<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
var dttable;
$(document).ready(function() {
    $(".resetform").click(function(){
        $("input, textarea").val("");
    });
    $("#txtdatebegin, #txtdatebegin2").datepicker({
        dateFormat:"yy-mm-dd",
        selectOtherMonths: true,
        yearRange: '2013:2020',
        defaultDate: +7,
        autoSize: true,
        appendText: '(yyyy-mm-dd)'
    });
    $("#txtdatebegin , #txtdatebegin2").change(function() {
        var test = $(this).datepicker('getDate');
        var testm = new Date(test.getTime());
        testm.setDate(testm.getDate());
        $("#txtdateend").datepicker("option", "minDate", testm);
        $("#txtdateend2").datepicker("option", "minDate", testm);
        $( "#txtdatebegin" ).focus();
        $( "#txtdatebegin2" ).focus();
    });
    $( "#txtdateend , #txtdateend2" ).datepicker({
        selectOtherMonths: true,
        appendText: '(yyyy-mm-dd)',
        dateFormat: 'yy-mm-dd'
    });
    $('#datepickerbeginbtn').click(function () {
        $('#txtdatebegin').datepicker("show");
    });
    $('#datepickerendbtn').click(function () {
        $('#txtdateend').datepicker("show");
    });
    $('a.panel-collapse').click(function() {
        $(this).children().toggleClass("icon-chevron-down icon-chevron-up");
        $(this).closest(".panel-heading").next().toggleClass("in");
        $(this).closest(".panel-heading").toggleClass('rounded-bottom');
        return false;
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
                        $("input, textarea").val("");
                        $("#filepreview").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
                        $("#filepreviewbanner").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
                    }
                    else
                    {
                        $.pnotify({
                            title: "Fail",
                            text: data['message'],
                            type: 'info'
                        });
                    }
                    dttable.fnClearTable(0);
                    dttable.fnDraw();                       
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
</script>
<div id="confirmdlg">&nbsp;</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-purple">
                <div class="panel-heading">
                    <h4>Manage Contract</h4>
                    <div class="options">
                        <a href="javascript:;" class="panel-collapse"><i class="icon-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <p align="right">
                        <a href="<?php echo $this->template_admin->link("brand/index"); ?>" class="btn btn-sm btn-info"><i class="icon-arrow-left"></i> Back</a>
                    </p>
                    <form method="POST" class="form-horizontal" id="brandfrm" action="<?php echo $this->template_admin->link("brand/cruid_contract"); ?>">
                        <div class="form-group">
                            <input type="hidden" name="txtid" id="txtid" value="" />
                            <label for="txtnumber" class="col-sm-3 control-label">Number</label>
                            <div class="col-sm-6">
                              <input type="text" name="txtnumber" id="txtnumber" value="" maxlength="255" placeholder="Number" class="form-control {required:true}" />
                            </div>
                            <div class="col-sm-3">
                                <p class="help-block">Number of Contract Store!</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="descriptions" class="col-sm-3 control-label">Descriptions</label>
                            <div class="col-sm-6">
                                <textarea name="descriptions" id="descriptions" class="form-control" cols="55" rows="3"></textarea>
                            </div>
                            <div class="col-sm-3">
                                <p class="help-block">Description of Contract Store!</p>
                            </div>
                        </div>     
                        <div class="form-group">
                            <label for="txtdatebegin" class="col-sm-3 control-label">Start Date</label>
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
                            <label for="txtdateend" class="col-sm-3 control-label">Expire Date</label>
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
                            <label for="type" class="col-sm-3 control-label">Type</label>
                            <div class="col-sm-6">
                                <select id="type" name="type" class="form-control">
                                    <option value="Number">Number</option>
                                    <option value="Percent">Percent</option>
                               </select>
                            </div>
                            <div class="col-sm-3">
                                <p class="help-block">Type of promo!</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="txtprice" class="col-sm-3 control-label">Price</label>
                            <div class="col-sm-6">
                              <input type="number" name="txtprice" id="txtprice" value="" step="1" min="0" max="1000000" maxlength="255" placeholder="Price" class="form-control {number:true}" />
                            </div>
                            <div class="col-sm-3">
                                <p class="help-block">Price of promo!</p>
                            </div>
                        </div>
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        <div class="form-group">
                            <div class="col-sm-3">&nbsp;</div>
                            <div class="col-sm-9">
                                <?php 
                                if($this->m_checking->actions("Brand","module2","Manage Contract",TRUE,FALSE,"home"))
                                {
                                    echo '<button type="submit" class="btn-green btn"> <i class="icon-save"></i> <span>Save</span></button>&nbsp;&nbsp;'; 
                                }
                                ?>
                                <button type="reset" class="btn-default btn resetform"><i class="icon-file-alt"></i> <span>Reset</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>