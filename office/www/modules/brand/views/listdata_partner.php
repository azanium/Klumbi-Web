<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
var dttable;
$(document).ready(function() {
    $( "#generate , #generate2" ).click(function() { 
        var id_html =  Math.floor(Math.random() * 100000);
        $("#partner_id").val(id_html); 
        $("#partner_id2").val(id_html);
        return false; 
    });
    dttable=$('#datatable').dataTable( {
        "bJQueryUI": true,
        "bFilter": true,
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("brand/partner/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "aoColumns": [ { "sClass" : "alignRight","bSortable": false }, null, null, null, null, {"bSortable": false }, { "sClass" : "text-center","bSortable": false }]
    });
    $(".resetform").click(function(){
        $("input, textarea").val("");
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
function ubahdata(id,partner_id,name,pic,phone,mobile,email,website,address)
{
    $('#txtid2').val(id);
    $('#partner_id2').val(partner_id);
    $('#name2').val(name);
    $('#pic2').val(pic);
    $('#phone2').val(phone);
    $('#mobile2').val(mobile);
    $('#email2').val(email);
    $('#website2').val(website);
    $('#address2').val(address);   
}
function changestate(id,state)
{
    var url='<?php echo base_url(); ?>brand/partner/changestate/' + id + '/' + state;
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
function hapusdata(id,pesan)
{
    BootstrapDialog.confirm(pesan, function(result){
        if(result) 
        {
            var url='<?php echo base_url(); ?>brand/partner/delete/'+id;
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
        return false;
    });
}
</script>
<div id="confirmdlg">&nbsp;</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-purple">
                <div class="panel-heading">
                    <h4>Partner</h4>
                    <div class="options">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#listdata" data-toggle="tab"><i class="icon-table"></i> Partner</a></li>
                          <li><a href="#addnewdata" data-toggle="tab"><i class="icon-plus"></i> Create Partner</a></li>
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
                            <div class="table-responsive table-flipscroll">
                                <table id="datatable" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0" border="0">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="20%">ID</th>
                                            <th width="15%">Name</th>
                                            <th width="15%">Email</th>
                                            <th width="20%">Website</th>
                                            <th width="10%">State</th>
                                            <th width="15%">Operation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="7">No Data</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>                            
                        </div>
                        <div class="tab-pane" id="addnewdata">
                            <form method="POST" class="form-horizontal" id="brandfrm" action="<?php echo $this->template_admin->link("brand/partner/cruid_partner"); ?>">
                                <div class="form-group">
                                    <input type="hidden" name="txtid" id="txtid" value="" />
                                    <label for="partner_id" class="col-sm-3 control-label">ID</label>
                                    <div class="col-sm-6">
                                      <input type="text" name="partner_id" id="partner_id" value="" maxlength="255" placeholder="ID" class="form-control {required:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <button id="generate" class="btn-sky btn"><i class="icon-credit-card"></i> <span>Generate</span></button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-3 control-label">Name</label>
                                    <div class="col-sm-6">
                                      <input type="text" name="name" id="name" value="" maxlength="255" placeholder="Name" class="form-control {required:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Name of Partner!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="address" class="col-sm-3 control-label">Address</label>
                                    <div class="col-sm-6">
                                        <textarea name="address" id="address" class="form-control" cols="55" rows="3"></textarea>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Address of Partner!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="pic" class="col-sm-3 control-label">PIC</label>
                                    <div class="col-sm-6">
                                      <input type="text" name="pic" id="pic" value="" maxlength="255" placeholder="PIC" class="form-control {required:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">PIC of Partner!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="phone" class="col-sm-3 control-label">Phone Number</label>
                                    <div class="col-sm-6">
                                      <input type="text" name="phone" id="phone" value="" maxlength="255" placeholder="Phone Number" class="form-control {required:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Phone Number of Partner!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="mobile" class="col-sm-3 control-label">Mobile Phone</label>
                                    <div class="col-sm-6">
                                      <input type="text" name="mobile" id="mobile" value="" maxlength="255" placeholder="Mobile Phone" class="form-control {required:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Mobile Phone of Partner!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="col-sm-3 control-label">Email</label>
                                    <div class="col-sm-6">
                                      <input type="text" name="email" id="email" value="" maxlength="255" placeholder="Email" class="form-control {required:true, email:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Email of Partner!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="website" class="col-sm-3 control-label">Website</label>
                                    <div class="col-sm-6">
                                      <input type="text" name="website" id="website" value="" maxlength="255" placeholder="http://" class="form-control {url:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Website of Partner!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3">&nbsp;</div>
                                    <div class="col-sm-9">
                                        <?php 
                                        if($this->m_checking->actions("Partner","module2","Add",TRUE,FALSE,"home"))
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
                <form method="POST" id="editbrandfrm" action="<?php echo $this->template_admin->link("brand/partner/cruid_partner"); ?>">
                    <table width="100%" class="table table-striped table-bordered table-hover" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td width="15%">
                                <label for="partner_id2">ID</label>
                                <input type="hidden" name="txtid" id="txtid2" value="" />
                            </td>
                            <td width="85%">
                                <div class="col-sm-8">
                                  <input type="text" name="partner_id" id="partner_id2" value="" maxlength="255" placeholder="ID" class="form-control {required:true}" />
                                </div>
                                <div class="col-sm-4">
                                    <button id="generate2" class="btn-sky btn"><i class="icon-credit-card"></i> <span>Generate</span></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="name2">Name</label></td>
                            <td><input type="text" name="name" id="name2" value="" maxlength="255" placeholder="Name" class="form-control {required:true}" /></td>
                        </tr> 
                        <tr>
                            <td><label for="address2">Address</label></td>
                            <td>
                                <textarea name="address" id="address2" class="form-control" cols="55" rows="3"></textarea>
                            </td>
                        </tr> 
                        <tr>
                            <td><label for="pic2">PIC</label></td>
                            <td><input type="text" name="pic" id="pic2" value="" maxlength="255" placeholder="PIC" class="form-control {required:true}" /></td>
                        </tr>
                        <tr>
                            <td><label for="phone2">Phone Number</label></td>
                            <td><input type="text" name="phone" id="phone2" value="" maxlength="255" placeholder="Phone Number" class="form-control {required:true}" /></td>
                        </tr>
                        <tr>
                            <td><label for="mobile2">Mobile Phone</label></td>
                            <td><input type="text" name="mobile" id="mobile2" value="" maxlength="255" placeholder="Mobile Phone" class="form-control {required:true}" /></td>
                        </tr>
                        <tr>
                            <td><label for="email2">Email</label></td>
                            <td><input type="text" name="email" id="email2" value="" maxlength="255" placeholder="Email" class="form-control {required:true, email:true}" /></td>
                        </tr>
                        <tr>
                            <td><label for="website2">Website</label></td>
                            <td><input type="text" name="website" id="website2" value="" maxlength="255" placeholder="http://" class="form-control {url:true}" /></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                            <?php 
                            if($this->m_checking->actions("Partner","module2","Edit",TRUE,FALSE,"home"))
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