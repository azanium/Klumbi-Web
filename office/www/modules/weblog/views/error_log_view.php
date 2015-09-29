<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
var dttable;
$(document).ready(function() {
    dttable=$('#datatable').dataTable( {
        "bJQueryUI": true,
        "bFilter": true,
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("weblog/error_log/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "aoColumns": [ { "sClass" : "alignRight","bSortable": false }, null, null, null, null, { "sClass" : "text-center","bSortable": false }]
    });
    $('.dataTables_filter input').addClass('form-control').attr('placeholder','Search...');
    $('.dataTables_length select').addClass('form-control');
    $('a.panel-collapse').click(function() {
        $(this).children().toggleClass("icon-chevron-down icon-chevron-up");
        $(this).closest(".panel-heading").next().toggleClass("in");
        $(this).closest(".panel-heading").toggleClass('rounded-bottom');
        return false;
    });
    $("#reload").click(function(){   
        dttable.fnClearTable(0);
	dttable.fnDraw();
    });
    $('#truncatelog').click(function(){
        BootstrapDialog.confirm("Are You sure want to remove all Log Error ?", function(result){
            if(result) 
            {
                var url='<?php echo $this->template_admin->link("weblog/error_log/truncate"); ?>';
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
    });
});
function hapusdata(id,pesan)
{
    BootstrapDialog.confirm(pesan, function(result){
        if(result) 
        {
            var url='<?php echo base_url(); ?>weblog/error_log/delete/'+id;
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
<div id="confirmdlg">&nbsp;</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">                        
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4>Error Actions</h4>
                    <div class="options">
                        <a href="javascript:;" class="panel-collapse"><i class="icon-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <p align="right">
                        <?php 
                        if($this->m_checking->actions("Error Visits","module11","Delete",TRUE,FALSE,"home"))
                        {
                            echo '<a id="truncatelog" class="btn btn-sm btn-danger"><i class="icon-trash"></i> Delete All Error Actions</a>&nbsp;&nbsp;'; 
                        }
                        ?>
                        <a id="reload" class="btn btn-sm btn-success"><i class="icon-refresh"></i> Reload Data</a>
                    </p>
                    <table id="datatable" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0" border="0">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="35%">Message</th>
                                <th width="30%">Url WEB</th>
                                <th width="15%">Date Time</th>
                                <th width="10%">IP</th>
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
            </div>
        </div>
    </div>
</div>