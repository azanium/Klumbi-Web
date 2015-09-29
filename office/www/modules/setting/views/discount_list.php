<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
var dttable;
$(document).ready(function() {
    $('.select').select2({width: 'resolve'});
    $('textarea.autosize').autosize({append: "\n"});
    $( "#generate" ).click(function() { 
        generatecodernd();
        return false; 
    });
    $( "#generate2" ).click(function() { 
        generatecodernd();
        return false; 
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
    $("#txttimebegin , #txttimeend , #txttimebegin2 , #txttimeend2").timepicker({
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
    dttable=$('#datatable').dataTable( {
        "bJQueryUI": true,
        "bProcessing": true,
        "bFilter": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("setting/discount/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "aoColumns": [ { "sClass" : "alignRight","bSortable": false }, null, null, null, null, null, null, null, { "sClass" : "text-center","bSortable": false }]
    });
    $('.dataTables_filter input').addClass('form-control').attr('placeholder','Search...');
    $('.dataTables_length select').addClass('form-control');
    $('a.panel-collapse').click(function() {
        $(this).children().toggleClass("icon-chevron-down icon-chevron-up");
        $(this).closest(".panel-heading").next().toggleClass("in");
        $(this).closest(".panel-heading").toggleClass('rounded-bottom');
        return false;
    });
    $('#reload').click(function(){
        dttable.fnClearTable(0);
	dttable.fnDraw();
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
    $("#editbrandfrm").validate({
        submitHandler: function(form) {
            var url=$("#editbrandfrm").attr('action');
            var datapost=$("#editbrandfrm").serialize();
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
                        $('#editdata').modal('hide');
                        $("input, textarea").val("");
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
function ubahdata(id,code,brand,desc,sdate,stime,edate,etime,type,price)
{
    $('#txtid2').val(id);
    $('#code2').val(code);
    $('#brand_id2').val(brand);
    $('#description2').val(desc);
    $('#txtdatebegin2').val(sdate);
    $('#txttimebegin2').val(stime);
    $('#txtdateend2').val(edate);
    $('#txttimeend2').val(etime);
    $('#type2').val(type);
    $('#txtprice2').val(price);
}
function generatecodernd()
{
    $.ajax({
        type: "GET",
        url: '<?php echo $this->template_admin->link("redimcode/generatecode"); ?>',
        data:'',
        dataType: "json",
        beforeSend: function ( xhr ) {
            $("#loadingprocess").html('<div class="alert alert-dismissable alert-warning">' +
                                            '<strong>Warning!</strong> ' +
                                            '<img src="<?php echo base_url(); ?>resources/image/1s.gif" alt="loading" />' +
                                            '<i class="process">Wait a minute, Your request being processed</i>' +
                                            '</div>').slideDown(100);
        },
        success: function (data, textStatus) {             
            if(data['success']==true)
            {                        
                $("#code").val(data['data']); 
                $("#code2").val(data['data']);
            }
            else
            {
                $.pnotify({
                    title: "Fail",
                    text: data['message'],
                    type: 'info'
                });
            }
            $("#loadingprocess").slideUp(100);
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
function hapusdata(id,pesan)
{
    BootstrapDialog.confirm(pesan, function(result){
        if(result) 
        {
            var url='<?php echo base_url(); ?>setting/discount/delete/'+id;
            $.ajax({
                type: "POST",
                url: url,
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
                        $("input, textarea").val("");
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
                    <h4>Promo</h4>
                    <div class="options">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#listdata" data-toggle="tab"><i class="icon-table"></i> Promo</a></li>
                          <li><a href="#addnewdata" data-toggle="tab"><i class="icon-plus"></i> Create Promo</a></li>
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
                            <table id="datatable" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0" border="0">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="5%">Code</th>
                                        <th width="20%">Store</th>
                                        <th width="25%">Desc</th>
                                        <th width="10%">Start</th>
                                        <th width="10%">End</th>
                                        <th width="10%">Type</th>
                                        <th width="10%">Price</th>
                                        <th width="5%">Operation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="9">No Data</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="addnewdata">
                            <form method="POST" class="form-horizontal" id="brandfrm" action="<?php echo $this->template_admin->link("setting/discount/cruid_discount"); ?>">
                                <?php
                                if($this->session->userdata('brand')=="")
                                {
                                ?>
                                <div class="form-group">
                                    <label for="brand_id" class="col-sm-3 control-label">Store</label>
                                    <div class="col-sm-6">
                                        <select id="brand_id" name="brand_id" class="select" style="width:100%">
                                            <option value="">&nbsp;</option>
                                            <?php 
                                            foreach($listbrand as $dt)
                                            {
                                                echo "<option value='".$dt['brand_id']."'>".$dt['name']."</option>";
                                            }
                                            ?>
                                       </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Store of promo!</p>
                                    </div>
                                </div>
                                <?php
                                }
                                ?>
                                <div class="form-group">
                                    <input type="hidden" name="txtid" id="txtid" value="" />
                                    <label for="code" class="col-sm-3 control-label">Code</label>
                                    <div class="col-sm-6">
                                      <input type="text" name="code" id="code" value="" maxlength="255" placeholder="Code" class="form-control {required:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                    <button id="generate" type="button" class="btn-sky btn"><i class="icon-credit-card"></i> <span>Generate</span></button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="description" class="col-sm-3 control-label">Descriptions</label>
                                    <div class="col-sm-6">
                                        <textarea name="description" id="description" class="form-control autosize {required:true}" cols="55" rows="3"></textarea>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Descriptions of promo!</p>
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
                                        if($this->m_checking->actions("Promo","module5","Add",TRUE,FALSE,"home"))
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
    </div>
</div>
<div id="editdata" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Data</h4>
            </div>
            <div class="modal-body">
                <form method="POST" id="editbrandfrm" action="<?php echo $this->template_admin->link("setting/discount/cruid_discount"); ?>">
                    <table class="table table-striped table-bordered table-hover" cellpadding="0" cellspacing="0" border="0">
                        <?php
                        if($this->session->userdata('brand')=="")
                        {
                        ?>
                        <tr>
                            <td>                                
                                <label for="brand_id2">Store</label>
                            </td>
                            <td>
                                <select id="brand_id2" name="brand_id" class="form-control">
                                    <option value="">&nbsp;</option>
                                    <?php 
                                    foreach($listbrand as $dt)
                                    {
                                        echo "<option value='".$dt['brand_id']."'>".$dt['name']."</option>";
                                    }
                                    ?>
                               </select>
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                        <tr>
                            <td>
                                <input type="hidden" name="txtid" id="txtid2" value="" />
                                <label for="code2">Code</label>
                            </td>
                            <td>
                                <div class="col-sm-8">
                                    <input type="text" name="code" id="code2" value="" maxlength="255" placeholder="Code" class="form-control {required:true}" />
                                </div>
                                <div class="col-sm-4">
                                    <button id="generate2" type="button" class="btn-sky btn"><i class="icon-credit-card"></i> <span>Generate</span></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="description2">Descriptions</label></td>
                            <td><textarea name="description" id="description2" class="form-control autosize {required:true}" cols="55" rows="3"></textarea></td>
                        </tr>
                        <tr>
                            <td><label for="txtdatebegin2">Date Bagin</label></td>
                            <td><input type="text" name="txtdatebegin" id="txtdatebegin2" value="" maxlength="255" placeholder="Date Begin" data-type="dateIso" class="form-control {required:true}" /></td>
                        </tr>
                        <tr>
                            <td><label for="txttimebegin2">Time Begin</label></td>
                            <td><input type="text" name="txttimebegin" id="txttimebegin2" value="" maxlength="255" placeholder="Time Begin" class="form-control {required:true}" /></td>
                        </tr>
                        <tr>
                            <td><label for="txtdateend2">Date End</label></td>
                            <td><input type="text" name="txtdateend" id="txtdateend2" value="" maxlength="255" placeholder="Date End" data-type="dateIso" class="form-control {required:true}" /></td>
                        </tr>
                        <tr>
                            <td><label for="txttimeend2">Time End</label></td>
                            <td><input type="text" name="txttimeend" id="txttimeend2" value="" maxlength="255" placeholder="Time End" class="form-control {required:true}" /></td>
                        </tr>
                        <tr>
                            <td><label for="type2">Type</label></td>
                            <td>
                                <select id="type2" name="type" class="form-control">
                                    <option value="Number">Number</option>
                                    <option value="Percent">Percent</option>
                               </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="txtprice2">Price</label></td>
                            <td><input type="number" name="txtprice" id="txtprice2" value="" step="1" min="0" max="1000000" maxlength="255" placeholder="Price" class="form-control {number:true}" /></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                                <?php 
                                if($this->m_checking->actions("Promo","module5","Edit",TRUE,FALSE,"home"))
                                {
                                    echo '<button type="submit" class="btn-green btn"> <i class="icon-save"></i> <span>Update</span></button>&nbsp;&nbsp;'; 
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>