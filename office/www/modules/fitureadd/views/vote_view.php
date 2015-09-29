<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
var dttable;
$(document).ready(function() {
    $('textarea.autosize').autosize({append: "\n"});
    dttable=$('#datatable').dataTable( {
        "bJQueryUI": true,
        "bProcessing": true,
        "bFilter": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("fitureadd/vote/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        'aoColumns': [ { "sClass" : "alignRight","bSortable": false }, null, null, null, null, { "sClass" : "text-center","bSortable": false }]
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
        var url='<?php echo $this->template_admin->link("fitureadd/vote/list_data_load"); ?>';
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
                            $("#listdatavotes").html('');
                            var content="";
                            for(var i=0; i<data['list'].length;i++)
                            {
                                content ="<div class='col-xs-6 col-sm-4 col-lg-3'>";
                                content +="<label for='checkbox"+i+"' >";
                                content +="<input type='checkbox' name='namapilihan[]' value='"+data['list'][i]+"' id='checkbox"+i+"' />";
                                content +=data['list'][i]+"</label>";
                                content +="</div>";
                                $("#listdatavotes").append(content);
                            }
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
    });
    $( "#showchart" ).click(function() { 
        $('#panelcharcheck').slideToggle('slow');
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
                            $("input, textarea").val("");
                            $('#editdata').modal('hide');
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
    $("#votechartfrm").submit(function(){
        var url=$("#votechartfrm").attr('action');
        var datapost=$("#votechartfrm").serialize();
        $.ajax({
            type: "POST",
            url: url,
            data:datapost,
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
                $("#responsemenu").html(data);
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
    });
});
function ubahdata(id,name,question,enabled_show,count)
{
    $('#txtid2').val(id);
    $('#name2').val(name);
    $('#questions2').val(question);
    $('#enable_show2').val(enabled_show);
    $('#count2').val(count);
    $('#editdata').modal('show');
}
function hapusdata(id,pesan)
{
    BootstrapDialog.confirm(pesan, function(result){
        if(result) 
        {
            var url='<?php echo base_url(); ?>fitureadd/vote/delete/'+id;
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
<?php 
if($this->m_checking->actions("Votes","module13","Export",TRUE,FALSE,"home"))
{    
?>
<div id="page-heading">
    <ol class="breadcrumb">
        <li><a href="<?php echo $this->template_admin->link("home/index"); ?>">Home</a></li>
        <li>Aplication</li>
        <li class="active">Vote</li>
    </ol>
    <h1>Vote</h1>
    <div class="options">
        <div class="btn-toolbar">
            <div class="btn-group hidden-xs">
                <a href='#' class="btn btn-muted dropdown-toggle" data-toggle='dropdown'><i class="icon-cloud-download"></i><span class="hidden-sm"> Export as  </span><span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo $this->template_admin->link("fitureadd/vote/import/html"); ?>" target="_blank">HTML File (*.html)</a></li>
                    <li><a href="<?php echo $this->template_admin->link("fitureadd/vote/import/doc"); ?>" target="_blank">Word File (*.doc)</a></li>
                    <li><a href="<?php echo $this->template_admin->link("fitureadd/vote/import/xls"); ?>" target="_blank">Excel File (*.xlsx)</a></li>
                </ul>
            </div>
            <a href="#" class="btn btn-muted"><i class="icon-cog"></i></a>
        </div>
    </div>
</div>
<?php 
}  
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-purple">
                <div class="panel-heading">
                    <h4>Votes User</h4>
                    <div class="options">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#listdata" data-toggle="tab"><i class="icon-table"></i> Votes</a></li>
                          <li><a href="#addnewdata" data-toggle="tab"><i class="icon-plus"></i> Create Vote</a></li>
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
                                        <th width="30%">Name</th>
                                        <th width="30%">Options</th>
                                        <th width="10%">Enable</th>
                                        <th width="20%">Count</th>
                                        <th width="5%">Operation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="6">No Data</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="addnewdata">
                            <form method="POST" class="form-horizontal" id="brandfrm" action="<?php echo $this->template_admin->link("fitureadd/vote/cruid_vote"); ?>">
                                <div class="form-group">
                                    <input type="hidden" name="txtid" id="txtid" value="" />
                                    <label for="name" class="col-sm-3 control-label">Name</label>
                                    <div class="col-sm-6">
                                      <input type="text" name="name" id="name" value="" maxlength="255" placeholder="Name" class="form-control {required:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Name of vote!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="questions" class="col-sm-3 control-label">Question</label>
                                    <div class="col-sm-6">
                                        <textarea name="questions" id="questions" class="form-control autosize {required:true}" cols="55" rows="3"></textarea>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Question of vote!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="enable_show" class="col-sm-3 control-label">Enable Show</label>
                                    <div class="col-sm-6">
                                        <select id="enable_show" name="enable_show" class="form-control">
                                            <option value='true'>Show</option>
                                            <option value='false'>Hide</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Set active or noactive vote!</p>
                                    </div>
                                </div>
                                <div id="frmchildoptions"></div>
                                <div class="form-group">
                                    <label for="count" class="col-sm-3 control-label">Count</label>
                                    <div class="col-sm-6">                                      
                                      <input type="number" name="count" id="count" value="0" step="1" min="0" max="1000000" maxlength="255" class="form-control {required:true, number:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Value count of poll!</p>
                                    </div>
                                </div>                                
                                <div class="form-group">
                                    <div class="col-sm-3">&nbsp;</div>
                                    <div class="col-sm-9">
                                        <?php 
                                        if($this->m_checking->actions("Votes","module13","Add",TRUE,FALSE,"home"))
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
                <?php 
                if($this->m_checking->actions("Votes","module13","Chart Report",TRUE,FALSE,"home"))
                {
                    echo '<div class="panel-footer">'; 
                    echo '<button  type="button" id="showchart" class="btn-magenta btn"><i class="icon-bar-chart"></i> <span>Result</span></button>';
                    echo '<div id="panelcharcheck" style="display: none;">';
                    echo '<form method="POST" id="votechartfrm" action="'.$this->template_admin->link("fitureadd/vote/chart").'">';
                    echo '<p>Check 2 or more button to generate chat</p>';
                    echo '<div id="listdatavotes">';
                    $i=0;
                    foreach($listdata as $dt)
                    { 
                        echo "<div class='col-xs-6 col-sm-4 col-lg-3'>";
                        echo "<label for='checkbox".$i."' >";
                        echo "<input type='checkbox' name='namapilihan[]' value='".$dt['name']."' id='checkbox".$i."' /> ";
                        echo $dt['name']."</label>";
                        echo "</div>";
                        $i++;
                    }
                    echo '</div>';
                    echo '<button type="submit" class="btn-green btn"> <i class="icon-magic"></i> <span>Generate Chart</span></button>';
                    echo '</form>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>
<div id="responsemenu"></div>
<div id="editdata" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Data</h4>
            </div>
            <div class="modal-body">
                <form method="POST" id="editbrandfrm" action="<?php echo $this->template_admin->link("fitureadd/vote/cruid_vote"); ?>">
                    <table id="tableeditdt" class="table table-striped table-bordered table-hover" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td>
                                <input type="hidden" name="txtid" id="txtid2" value="" />
                                <label for="name2">Name</label>
                            </td>
                            <td>
                                <input type="text" name="name" id="name2" value="" maxlength="255" placeholder="Name" class="form-control {required:true}" />
                            </td>
                        </tr>
                        <tr>
                            <td><label for="questions2">Question</label></td>
                            <td><textarea name="questions" id="questions2" class="form-control autosize {required:true}" cols="55" rows="3"></textarea></td>
                        </tr>
                        <tr>
                            <td><label for="enable_show2">Enable Show</label></td>
                            <td>
                                <select id="enable_show2" name="enable_show" class="form-control">
                                    <option value='true'>Show</option>
                                    <option value='false'>Hide</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="count2" class="col-sm-3 control-label">Count</label></td>
                            <td><input type="number" name="count" id="count2" value="0" step="1" min="0" max="1000000" maxlength="255" class="form-control {required:true, number:true}" /></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                                <?php 
                                if($this->m_checking->actions("Votes","module13","Edit",TRUE,FALSE,"home"))
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