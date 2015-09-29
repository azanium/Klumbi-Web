<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
var dttable;
$(document).ready(function() {
    $('.select').select2({width: 'resolve'});
    dttable=$('#datatable').dataTable( {
        "bJQueryUI": true,
        "bProcessing": true,
        "bFilter": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("avatar/gesticon/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "aoColumns": [ { "sClass" : "alignRight","bSortable": false }, null, null, { "sClass" : "text-center","bSortable": false }]
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
                            $("input[type=hidden], input[type=text], textarea").val("");
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
                        if(data['success']==true)
                        {                        
                            $.pnotify({
                                title: "Success",
                                text: data['message'],
                                type: 'success'
                            });
                            $('#editdata').modal('hide');
                            $("input[type=hidden], input[type=text], textarea").val("");
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
function ubahdata(id,command,gender,animation,radio)
{
    $('#txtid2').val(id);
    $('#command2').val(command);
    $('#gender2').val(gender);
    if(radio)
    {
        $('#radio2 input:radio[name=radioloop]').filter('[value="true"]').attr('checked', 'checked');
    }
    else
    {
        $('#radio2 input:radio[name=radioloop]').filter('[value="false"]').attr('checked', 'checked');
    }
    var url='<?php echo base_url(); ?>avatar/gesticon/get_combo/'+gender;
    $.ajax({
        type: "POST",
        url: url,
        data:"",
        dataType: "html",
        beforeSend: function ( xhr ) {
            $("#loadingprocess").html('<div class="alert alert-dismissable alert-warning">' +
                                            '<strong>Warning!</strong> ' +
                                            '<img src="<?php echo base_url(); ?>resources/image/1s.gif" alt="loading" />' +
                                            '<i class="process">Wait a minute, Your request being processed</i>' +
                                            '</div>').slideDown(100);
        },
        success: function (data, textStatus) {
            $("#loadingprocess").slideUp(100);
            $('#animation').html(data);       
            $('#animation').val(animation);
            $('#animation2').html(data);       
            $('#animation2').val(animation);
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
            var url='<?php echo base_url(); ?>avatar/gesticon/delete/'+id;
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
                            $("input[type=hidden], input[type=text], textarea").val("");
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
function changecombo(gender)
{
    var url='<?php echo base_url(); ?>avatar/gesticon/get_combo/'+gender;
    $.ajax({
                type: "POST",
                url: url,
                data:"",
                dataType: "html",
                beforeSend: function ( xhr ) {
                    $("#loadingprocess").html('<div class="alert alert-dismissable alert-warning">' +
						'<strong>Warning!</strong> ' +
                                                '<img src="<?php echo base_url(); ?>resources/image/1s.gif" alt="loading" />' +
                                                '<i class="process">Wait a minute, Your request being processed</i>' +
						'</div>').slideDown(100);
                },
                success: function (data, textStatus) {
                    $("#loadingprocess").slideUp(100);
                    $('#animation').html(data);   
                    $('#animation2').html(data);                     
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
                    <h4>EMO</h4>
                    <div class="options">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#listdata" data-toggle="tab"><i class="icon-table"></i> EMO</a></li>
                          <li><a href="#addnewdata" data-toggle="tab"><i class="icon-plus"></i> Create EMO</a></li>
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
                                        <th width="45%">Command</th>
                                        <th width="45%">Animation</th>
                                        <th width="5%">Operation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="4">No Data</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="addnewdata">
                            <form method="POST" class="form-horizontal" id="brandfrm" action="<?php echo $this->template_admin->link("avatar/gesticon/cruid_gesticon"); ?>">
                                <div class="form-group">
                                    <input type="hidden" name="txtid" id="txtid" value="" />
                                    <label for="command" class="col-sm-3 control-label">Command</label>
                                    <div class="col-sm-6">
                                      <input type="text" name="command" id="command" value="" maxlength="255" placeholder="Command" class="form-control {required:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Command of EMO!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="gender" class="col-sm-3 control-label">Gender</label>
                                    <div class="col-sm-6">
                                        <select id="gender" name="gender" onchange="changecombo(this.value);" class="form-control">
                                            <?php
                                            foreach($this->tambahan_fungsi->list_gender() as $dt=>$value)
                                            {
                                                $isicombo=$dt;
                                                if($value=="Unisex")
                                                {
                                                    $isicombo="";
                                                }
                                                echo "<option value='".$isicombo."'>".$value."</option>";
                                            }
                                            ?>
                                            <option value="">Unisex</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Gender of EMO!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="animation" class="col-sm-3 control-label">Animation</label>
                                    <div class="col-sm-6">
                                        <select id="animation" name="animation" class="form-control">
                                            <?php
                                            foreach($animation as $dt=>$value)
                                            {
                                                $tempdt=  explode("@",str_replace(".unity3d", "", $value['animation_file']));                                    
                                                if(isset($tempdt[1]))
                                                {
                                                    $nilai=$tempdt[1];
                                                    if(substr($nilai, 0, 1)!="_")
                                                    {
                                                        echo "<option value='".$nilai."'>".$value['name']."</option>";
                                                    }                                        
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Animation of EMO!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Is Loop</label>
                                    <div class="col-sm-6">
                                        <input type="radio" id="radioreturn1" name="radioloop" value="false" /><label for="radioreturn1"><span class='ui-icon ui-icon-closethick'>Unchecked</span></label>
                                        <input type="radio" id="radioreturn2" name="radioloop" value="true" checked="checked" /><label for="radioreturn2"><span class='ui-icon ui-icon-check'>Checked</span></label>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Loop of EMO!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3">&nbsp;</div>
                                    <div class="col-sm-9">
                                        <?php 
                                        if($this->m_checking->actions("EMO","module6","Add",TRUE,FALSE,"home"))
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
                <form method="POST" id="editbrandfrm" action="<?php echo $this->template_admin->link("avatar/gesticon/cruid_gesticon"); ?>">
                    <table class="table table-striped table-bordered table-hover" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td>
                                <input type="hidden" name="txtid" id="txtid2" value="" />
                                <label for="command2">Command</label>
                            </td>
                            <td>
                                <input type="text" name="command" id="command2" value="" maxlength="255" placeholder="Command" class="form-control {required:true}" />
                            </td>
                        </tr>
                        <tr>
                            <td><label for="gender2">Gender</label></td>
                            <td>
                                <select id="gender2" name="gender" onchange="changecombo(this.value);" class="form-control">
                                    <?php
                                    foreach($this->tambahan_fungsi->list_gender() as $dt=>$value)
                                    {
                                        $isicombo=$dt;
                                        if($value=="Unisex")
                                        {
                                            $isicombo="";
                                        }
                                        echo "<option value='".$isicombo."'>".$value."</option>";
                                    }
                                    ?>
                                    <option value="">Unisex</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="animation2">Animation</label></td>
                            <td>
                                <select id="animation2" name="animation" class="form-control">
                                    <?php
                                    foreach($animation as $dt=>$value)
                                    {
                                        $tempdt=  explode("@",str_replace(".unity3d", "", $value['animation_file']));                                    
                                        if(isset($tempdt[1]))
                                        {
                                            $nilai=$tempdt[1];
                                            if(substr($nilai, 0, 1)!="_")
                                            {
                                                echo "<option value='".$nilai."'>".$value['name']."</option>";
                                            }                                        
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label>Is Loop</label></td>
                            <td>
                                <input type="radio" id="radioreturn1" name="radioloop" value="false" /><label for="radioreturn1"><span class='ui-icon ui-icon-closethick'>Unchecked</span></label>
                                <input type="radio" id="radioreturn2" name="radioloop" value="true" checked="checked" /><label for="radioreturn2"><span class='ui-icon ui-icon-check'>Checked</span></label>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                                <?php 
                                if($this->m_checking->actions("EMO","module6","Edit",TRUE,FALSE,"home"))
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