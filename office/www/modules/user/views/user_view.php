<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
var dttable;
$(document).ready(function() {
    dttable=$('#datatable').dataTable( {
        "bJQueryUI": true,
        "bProcessing": true,
        "bFilter": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("user/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        'aoColumns': [ { "sClass" : "alignRight","bSortable": false }, {"bSortable": false }, {"bSortable": false }, {"bSortable": false }, {"bSortable": false }, { "sClass" : "text-center","bSortable": false }]
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
});
function hapusdata(id,pesan)
{
    BootstrapDialog.confirm(pesan, function(result){
        if(result) 
        {
            var url='<?php echo base_url(); ?>user/delete/'+id;
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
function setdataartist(id,valdatauser,pesan)
{
    BootstrapDialog.confirm(pesan, function(result){
        if(result) 
        {
            var url='<?php echo base_url(); ?>user/setasartist/'+id + "/" + valdatauser;
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
        }
    });
}
</script>
<?php 
if($this->m_checking->actions("User","module2","Export",TRUE,FALSE,"home"))
{    
?>
<div id="page-heading">
    <ol class="breadcrumb">
        <li><a href="<?php echo $this->template_admin->link("home/index"); ?>">Home</a></li>
        <li>Users</li>
        <li class="active">User Account</li>
    </ol>
    <h1>Users</h1>
    <div class="options">
        <div class="btn-toolbar">
            <div class="btn-group hidden-xs">
                <a href='#' class="btn btn-muted dropdown-toggle" data-toggle='dropdown'><i class="icon-cloud-download"></i><span class="hidden-sm"> Export as  </span><span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo $this->template_admin->link("user/import/html"); ?>" target="_blank">HTML File (*.html)</a></li>
                    <li><a href="<?php echo $this->template_admin->link("user/import/doc"); ?>" target="_blank">Word File (*.doc)</a></li>
                    <li><a href="<?php echo $this->template_admin->link("user/import/xls"); ?>" target="_blank">Excel File (*.xlsx)</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php 
}  
?>
<div id="confirmdlg">&nbsp;</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-purple">
                <div class="panel-heading">
                    <h4>User Account</h4>
                    <div class="options">
                        <a href="javascript:;" class="panel-collapse"><i class="icon-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <p align="right">
                        <a id="reload" class="btn btn-sm btn-success"><i class="icon-refresh"></i> Reload Data</a>
                    </p>
                    <table id="datatable" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0" border="0">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">UserName</th>
                                <th width="20%">Email</th>
                                <th width="20%">Name</th>
                                <th width="10%">Phone</th>
                                <th width="30%">Operation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="6">No Data</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>