<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
var dttable;
$(document).ready(function() {
    $("#parenttipe , #parenttipe2").hide('fast');
    $("#resetval , #resetval2").click(function(){
        $("#parenttipe , #parenttipe2").slideUp("fast");
    });
    $("#rdhaveparen").click(function(){
        var state=document.getElementById("rdhaveparen");
        if(state.checked)
        {
          createtag(''); 
          document.getElementById("rdhaveparen2").checked=true;
        }
        else
        {
          $("#parenttipe").slideUp("slow");      
          $("#parenttipe2").slideUp("slow"); 
        }
    });
    $("#rdhaveparen2").click(function(){
        var state=document.getElementById("rdhaveparen2");
        if(state.checked)
        {
          createtag('');    
          document.getElementById("rdhaveparen").checked=true;;
        }
        else
        {
          $("#parenttipe").slideUp("slow");      
          $("#parenttipe2").slideUp("slow"); 
        }
    });
    dttable=$('#datatable').dataTable( {
        "bJQueryUI": true,
        "bFilter": true,
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("brand/tipe/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "aoColumns": [ { "sClass" : "alignRight","bSortable": false }, null, null, null, { "sClass" : "text-center","bSortable": false }]
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
function createtag(terpilih)
{
    $.ajax({
        type: 'POST',
        url: '<?php echo $this->template_admin->link("brand/tipe/list_data_load"); ?>',
        data: "",
        dataType: 'json',
        beforeSend: function ( xhr ) {
            $("#loadingprocess").html('<div class="alert alert-dismissable alert-warning">' +
                                            '<strong>Warning!</strong> ' +
                                            '<img src="<?php echo base_url(); ?>resources/image/1s.gif" alt="loading" />' +
                                            '<i class="process">Wait a minute, Your request being processed</i>' +
                                            '</div>').slideDown(100);
            $("#parenttipe").html("");
            $("#parenttipe2").html("");
        },
        error: function (xhr, textStatus, errorThrown) {
            $("#loadingprocess").slideUp(100);
            $.pnotify({
                title: textStatus + " " + xhr.status,
                text: (errorThrown ? errorThrown : xhr.status),
                type: 'error'
            });
        },
        success: function(data, textStatus) {
            $("#loadingprocess").slideUp(100);
            if(data['success']==true)
            {                        
                var selecttext='<select id="cmbtipe" name="cmbtipe" class="form-control">';
                var selecttext2='<select id="cmbtipe2" name="cmbtipe" class="form-control">';
                var chentang='';
                
                
                
                for(var i=0; i< data['data'].length;i++)
                {
                    chentang=''
                    if(terpilih==data['data'][i])
                    {
                       chentang=' selected="selected" '     
                    }
                    selecttext= selecttext + '<option value="'+data['data'][i]+'" '+chentang+'>'+data['data'][i]+'</option>';
                    selecttext2= selecttext2 + '<option value="'+data['data'][i]+'" '+chentang+'>'+data['data'][i]+'</option>';
                }
                
                
                
                selecttext =selecttext +'</select>';
                selecttext2 =selecttext2 +'</select>';
                $("#parenttipe").html(selecttext);
                $("#parenttipe2").html(selecttext2);
            }
            else
            {
                $.pnotify({
                    title: "Fail",
                    text: data['message'],
                    type: 'info'
                });
            }
          }
    });
    $("#parenttipe").slideDown("slow");
    $("#parenttipe2").slideDown("slow");
}
function ubahdata(id,name,title,parent)
{
    $('#txtid2').val(id);
    $('#title2').val(title);
    $('#name2').val(name);
    if(parent!='')
    {
        createtag(parent);
        document.getElementById("rdhaveparen").checked=true;
        document.getElementById("rdhaveparen2").checked=true;
    }
    else
    {
        document.getElementById("rdhaveparen").checked=false;
        document.getElementById("rdhaveparen2").checked=false;
        $("#parenttipe").slideUp("slow");
        $("#parenttipe2").slideUp("slow");
    }
}
function hapusdata(id,pesan)
{
    BootstrapDialog.confirm(pesan, function(result){
        if(result) 
        {
        var url='<?php echo base_url(); ?>brand/tipe/delete/'+id;
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
                    <h4>Avatar Body Part</h4>
                    <div class="options">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#listdata" data-toggle="tab"><i class="icon-table"></i> Avatar Body Part</a></li>
                          <li><a href="#addnewdata" data-toggle="tab"><i class="icon-plus"></i> Create Avatar Body Part</a></li>
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
                                        <th width="30%">Title</th>
                                        <th width="30%">Name</th>
                                        <th width="30%">Parent</th>
                                        <th width="5%">Operation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="5">No Data</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="addnewdata">
                            <form method="POST" class="form-horizontal" id="brandfrm" action="<?php echo $this->template_admin->link("brand/tipe/cruid_tipe"); ?>">
                                <div class="form-group">
                                    <input type="hidden" name="txtid" id="txtid" value="" />
                                    <label for="title" class="col-sm-3 control-label">Title</label>
                                    <div class="col-sm-6">
                                      <input type="text" name="title" id="title" value="" maxlength="255" placeholder="Avatar Body Part Type Title" class="form-control {required:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Title of Avatar Body Part Type!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-3 control-label">Name</label>
                                    <div class="col-sm-6">
                                      <input type="text" name="name" id="name" value="" maxlength="255" placeholder="Avatar Body Part Type Name" class="form-control {required:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Name of Avatar Body Part Type!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Have Parent</label>
                                    <div class="col-sm-9">
                                      <label for="rdhaveparen"><input type="checkbox" name="rdhaveparen" id="rdhaveparen" value="1" /> Check if type have a parent</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3">&nbsp;</div>
                                    <div class="col-sm-6">
                                      <div id="parenttipe">&nbsp;</div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3">&nbsp;</div>
                                    <div class="col-sm-9">
                                        <?php 
                                        if($this->m_checking->actions("Avatar Body Part Type","module6","Add",TRUE,FALSE,"home"))
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
                <form method="POST" id="editbrandfrm" action="<?php echo $this->template_admin->link("brand/tipe/cruid_tipe"); ?>">
                    <table class="table table-striped table-bordered table-hover" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td>
                                <input type="hidden" name="txtid" id="txtid2" value="" />
                                <label for="title2">Title</label>
                            </td>
                            <td>
                                <input type="text" name="title" id="title2" value="" maxlength="255" placeholder="Avatar Body Part Type Title" class="form-control {required:true}" />
                            </td>
                        </tr>
                        <tr>
                            <td><label for="name2">Name</label></td>
                            <td><input type="text" name="name" id="name2" value="" maxlength="255" placeholder="Avatar Body Part Type Name" class="form-control {required:true}" /></td>
                        </tr>
                        <tr>
                            <td><label>Have Parent</label></td>
                            <td><label for="rdhaveparen2"><input type="checkbox" name="rdhaveparen" id="rdhaveparen2" value="1" /> Check if type have a parent</label></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td><div id="parenttipe2">&nbsp;</div></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                                <?php 
                                if($this->m_checking->actions("Avatar Body Part Type","module6","Edit",TRUE,FALSE,"home"))
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