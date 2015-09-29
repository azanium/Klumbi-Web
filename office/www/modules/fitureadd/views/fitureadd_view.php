<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
var dttable;
var counter=0;
$(document).ready(function() {
    $('textarea.autosize').autosize({append: "\n"});
    $("#dateshow , #dateshow2").datepicker({
        dateFormat:"yy-mm-dd",
        yearRange: '2013:2020',
        defaultDate: +7,
        autoSize: true,
        appendText: '(yyyy-mm-dd)',
        showOtherMonths: true,
        selectOtherMonths: true,
        showButtonPanel: true,
        changeMonth: true,
        changeYear: true
    });
    $( "#adddialogid" ).click(function() { 
        tambah_option();
        return false; 
    });
    $( "#adddialogid2" ).click(function() { 
        tambah_option();
        return false; 
    });
    dttable=$('#datatable').dataTable( {
        "bJQueryUI": true,
        "bProcessing": true,
        "bFilter": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("fitureadd/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        'aoColumns': [ { "sClass" : "alignRight","bSortable": false }, null, null, null, {"bSortable": false }, {"bSortable": false }, { "sClass" : "text-center","bSortable": false }]
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
        counter=0;
        $('#frmchildoptions').html('');
        $('#frmchildoptions2').html('');
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
                        $('#frmchildoptions').html('');
                        $('#frmchildoptions2').html('');
                    }
                    else
                    {
                        $.pnotify({
                            title: "Fail",
                            text: data['message'],
                            type: 'info'
                        });
                    }                    
                    counter=0;
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
                        $('#frmchildoptions').html('');
                        $('#frmchildoptions2').html('');
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
                    counter=0;
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
function tambah_option()
{
    var texthtml="";
    var texthtml2="";
    texthtml= texthtml + "<div  class='form-group' id='lstable"+counter+"'>";
    texthtml= texthtml + "<div class='col-sm-3' align='right'>";
    texthtml= texthtml + "<button type='button' class='btn-danger btn' onclick='removeoption(\""+counter+"\");return false;'><i class='icon-remove'></i> <span>Remove</span></button>";
    texthtml= texthtml + "</div>";
    texthtml= texthtml + "<div class='col-sm-6'>";
    texthtml= texthtml + "<input type='text' name='optionchild[]' value='' maxlength='255' placeholder='Option' class='form-control {required:true}' />";
    texthtml= texthtml + "</div>";
    texthtml= texthtml + "<div class='col-sm-3'>";
    texthtml= texthtml + "<input type='number' name='valueschild[]' value='0' maxlength='255' placeholder='Values' class='form-control {required:true, number:true}' />";
    texthtml= texthtml + "</div>";    
    texthtml= texthtml + "</div>";  
    texthtml2= texthtml2 +"<div  style='margin-top:30px;' id='lstable2_"+counter+"'>";
    texthtml2= texthtml2 +"<table width='100%'>";
    texthtml2= texthtml2 +"<tr>";
    texthtml2= texthtml2 +"<td colspan='2' align='right'><button type='button' class='btn-danger btn' onclick='removeoption(\"2_"+counter+"\");return false;'><i class='icon-remove'></i> <span>Remove</span></button></td>";
    texthtml2= texthtml2 +"</tr>";
    texthtml2= texthtml2 +"<tr>";
    texthtml2= texthtml2 +"<td width='25%'><label>Option</label></td>";
    texthtml2= texthtml2 +"<td width='75%'><input type='text' name='optionchild[]' value='' maxlength='255' placeholder='Option' class='form-control {required:true}' /></td>";
    texthtml2= texthtml2 +"</tr>";
    texthtml2= texthtml2 +"<tr>";
    texthtml2= texthtml2 +"<td><label>Values</label></td>";
    texthtml2= texthtml2 +"<td><input type='text' name='valueschild[]' value='0' maxlength='255' placeholder='Values' class='form-control {required:true, number:true}' /></td>";
    texthtml2= texthtml2 +"</tr>";
    texthtml2= texthtml2 +"</table>";
    texthtml2= texthtml2 +"<hr style='border: 2px solid #0073ea;margin-top:10px;'/>";
    texthtml2= texthtml2 +"</div>";
    $("#frmchildoptions").append(texthtml);
    $("#frmchildoptions2").append(texthtml2);
    counter++;
}
function removeoption(divid)
{
    $('#lstable'+divid).remove();  
}
function ubahdata(id)
{
    counter=0;
    $.ajax({
        type: "POST",
        url: '<?php echo base_url(); ?>fitureadd/detail/'+id,
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
                $("input, textarea").val("");
                $('#frmchildoptions').html('');
                $('#frmchildoptions2').html('');
                $('#txtid2').val(data['_id']);
                $('#name2').val(data['name']);
                $('#questions2').val(data['question']);
                $('#dateshow2').val(data['date']);
                $('#enable_show2').val(data['enabled']);
                if(data['options'].length>0)
                {
                    var texthtml="";
                    for(i=0; i<data['options'].length;i++)
                    {                    
                        texthtml= texthtml +"<div  style='margin-top:30px;' id='lstable2_"+i+"'>";
                        texthtml= texthtml +"<table width='100%'>";
                        texthtml= texthtml +"<tr>";
                        texthtml= texthtml +"<td colspan='2' align='right'><button type='button' class='btn-danger btn' onclick='removeoption(\"2_"+i+"\");return false;'><i class='icon-remove'></i> <span>Remove</span></button></td>";
                        texthtml= texthtml +"</tr>";
                        texthtml= texthtml +"<tr>";
                        texthtml= texthtml +"<td width='15%'><label>Option</label></td>";
                        texthtml= texthtml +"<td width='85%'><input type='text' name='optionchild[]' value='"+data['options'][i]['option']+"' maxlength='255' placeholder='Option' class='form-control {required:true}' /></td>";
                        texthtml= texthtml +"</tr>";
                        texthtml= texthtml +"<tr>";
                        texthtml= texthtml +"<td><label>Values</label></td>";
                        texthtml= texthtml +"<td><input type='text' name='valueschild[]' value='"+data['options'][i]['values']+"' maxlength='255' placeholder='Values' class='form-control {required:true, number:true}' /></td>";
                        texthtml= texthtml +"</tr>";
                        texthtml= texthtml +"</table>";
                        texthtml= texthtml +"<hr style='border: 2px solid #0073ea;margin-top:10px;'/>";
                        texthtml= texthtml +"</div>";  
                        counter=i+1;
                    }
                    $("#frmchildoptions2").append(texthtml);
                }
                $('#editdata').modal('show');
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
function hapusdata(id,pesan)
{
    BootstrapDialog.confirm(pesan, function(result){
        if(result) 
        {
            var url='<?php echo base_url(); ?>fitureadd/delete/'+id;
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
                            $('#frmchildoptions').html('');
                            $('#frmchildoptions2').html('');
                        }
                        else
                        {
                            $.pnotify({
                                title: "Fail",
                                text: data['message'],
                                type: 'info'
                            });
                        }                        
                        counter=0;
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
function seeresult(id)
{
    $.ajax({
        type: "POST",
        url: '<?php echo base_url(); ?>fitureadd/chart/'+id,
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
}
</script>
<?php 
if($this->m_checking->actions("Polls","module13","Export",TRUE,FALSE,"home"))
{    
?>
<div id="page-heading">
    <ol class="breadcrumb">
        <li><a href="<?php echo $this->template_admin->link("home/index"); ?>">Home</a></li>
        <li>Aplication</li>
        <li class="active">Poll</li>
    </ol>
    <h1>Polls</h1>
    <div class="options">
        <div class="btn-toolbar">
            <div class="btn-group hidden-xs">
                <a href='#' class="btn btn-muted dropdown-toggle" data-toggle='dropdown'><i class="icon-cloud-download"></i><span class="hidden-sm"> Export as  </span><span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo $this->template_admin->link("fitureadd/import/html"); ?>" target="_blank">HTML File (*.html)</a></li>
                    <li><a href="<?php echo $this->template_admin->link("fitureadd/import/doc"); ?>" target="_blank">Word File (*.doc)</a></li>
                    <li><a href="<?php echo $this->template_admin->link("fitureadd/import/xls"); ?>" target="_blank">Excel File (*.xlsx)</a></li>
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
                    <h4>Polls User</h4>
                    <div class="options">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#listdata" data-toggle="tab"><i class="icon-table"></i> Polls</a></li>
                          <li><a href="#addnewdata" data-toggle="tab"><i class="icon-plus"></i> Create Poll</a></li>
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
                                        <th width="10%">Name</th>
                                        <th width="30%">Questions</th>
                                        <th width="10%">Enable</th>
                                        <th width="20%">Date</th>
                                        <th width="20%">Create</th>
                                        <th width="5%">Operation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="7">No Data</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="addnewdata">
                            <form method="POST" class="form-horizontal" id="brandfrm" action="<?php echo $this->template_admin->link("fitureadd/cruid_poll"); ?>">
                                <div class="form-group">
                                    <input type="hidden" name="txtid" id="txtid" value="" />
                                    <label for="name" class="col-sm-3 control-label">Name</label>
                                    <div class="col-sm-6">
                                      <input type="text" name="name" id="name" value="" maxlength="255" placeholder="Name" class="form-control {required:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Name of Poll!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="questions" class="col-sm-3 control-label">Question</label>
                                    <div class="col-sm-6">
                                        <textarea name="questions" id="questions" class="form-control autosize {required:true}" cols="55" rows="3"></textarea>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Question of poll!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="dateshow" class="col-sm-3 control-label">Date Show</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="dateshow" id="dateshow" value="" maxlength="255" placeholder="Date" data-type="dateIso" class="form-control {required:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Date!</p>
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
                                        <p class="help-block">Set active or noactive poll!</p>
                                    </div>
                                </div>
                                <hr />
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">&nbsp;</label>
                                    <div class="col-sm-6">
                                        <input type='text' name='optionchild[]' value='' maxlength='255' placeholder='Option' class='form-control {required:true}' />
                                    </div>
                                    <div class="col-sm-3">
                                        <input type='number' name='valueschild[]' value='0' maxlength='255' placeholder='Values' class='form-control {required:true, number:true}' />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">&nbsp;</label>
                                    <div class="col-sm-6">
                                        <input type='text' name='optionchild[]' value='' maxlength='255' placeholder='Option' class='form-control {required:true}' />
                                    </div>
                                    <div class="col-sm-3">
                                        <input type='number' name='valueschild[]' value='0' maxlength='255' placeholder='Values' class='form-control {required:true, number:true}' />
                                    </div>
                                </div>                                
                                <div id="frmchildoptions"></div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Options</label>
                                    <div class="col-sm-6">                                      
                                      <button type="button" id="adddialogid" class="btn-info btn"><i class="icon-edit"></i> <span>Add New Option</span></button>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Option of poll!</p>
                                    </div>
                                </div>   
                                <hr />
                                <div class="form-group">
                                    <div class="col-sm-3">&nbsp;</div>
                                    <div class="col-sm-9">
                                        <?php 
                                        if($this->m_checking->actions("Polls","module13","Add",TRUE,FALSE,"home"))
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
<div id="responsemenu"></div>
<div id="editdata" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Data</h4>
            </div>
            <div class="modal-body">
                <form method="POST" id="editbrandfrm" action="<?php echo $this->template_admin->link("fitureadd/cruid_poll"); ?>">
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
                            <td><label for="dateshow2">Date Show</label></td>
                            <td><input type="text" name="dateshow" id="dateshow2" value="" maxlength="255" placeholder="Date" data-type="dateIso" class="form-control {required:true}" /></td>
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
                            <td><label>Options</label></td>
                            <td>
                                <div id="frmchildoptions2">&nbsp;</div>
                                <button type="button" id="adddialogid2" class="btn-info btn"><i class="icon-edit"></i> <span>Add New Option</span></button>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                                <?php 
                                if($this->m_checking->actions("Polls","module13","Edit",TRUE,FALSE,"home"))
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