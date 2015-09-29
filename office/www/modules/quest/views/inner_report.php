<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
var datatable;
$(document).ready(function(){
    datatable=$('#chiledatatable').dataTable( {
            "sEmptyTable": "No Cases available for selection",
            <?php
            if($jns==TRUE)
            { ?>
            'aoColumns': [ null, null, null, null, null, { "sClass" : "text-center" }],
            <?php
            }
            else
            {
            ?>
            'aoColumns': [ null, null, null, null, null],          
            <?php
            }
            ?>
            "bJQueryUI": true,
            "bDestroy": true,
            "bAutoWidth": false,
            "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page",
                "sSearch": ""
            },
            "bRetrieve":true
        }); 
        $('.dataTables_filter input').addClass('form-control').attr('placeholder','Search...');
        $('.dataTables_length select').addClass('form-control');
        $('#modal').modal('show');
}); 
function hapusquest(pertanyaan,id,user)
{
    BootstrapDialog.confirm(pertanyaan, function(result){
        if(result) 
        {
            var url='<?php echo base_url(); ?>quest/report/delete/'+user+'/'+id;
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
                        $('#modal').modal('hide');
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
}
</script>
<div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Quest Detail</h4>
            </div>
            <div class="modal-body">
                <table id="chiledatatable">
                    <thead>
                        <tr>
                            <th width="5%">Quest ID</th>
                            <th width="45%">Description</th>
                            <th width="15%">Start Date</th>
                            <th width="15%">End Date</th>
                            <th width="15%">Duration</th>
                            <?php
                            if($jns==TRUE)
                            {
                                echo "<th width='5%'>Operation</th>";
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach($isitable as $key)
                        {
                            $tglexpire="";
                            $tglstart="";
                            $tglend="";
                            if($key['start_date']!="")
                            {
                                $tglstart=$key['start_date'];
                            }
                            if($key['end_date']!="")
                            {
                                $tglend=$key['end_date'];
                            }
                            if($key['end_date']!="" && $key['start_date']!="")
                            {
                                $duration = $this->tambahan_fungsi->unitytounixtime(trim($key['end_date']))-$this->tambahan_fungsi->unitytounixtime(trim($key['start_date']));
                                $tglexpire= $this->tambahan_fungsi->sec2hms($duration);
                            }
                            echo "<tr>";
                            echo "<td>".$key['ID']."</td>";
                            echo "<td>".$key['Description']."</td>";
                            echo "<td>".$tglstart."</td>";
                            echo "<td>".$tglend."</td>";
                            echo "<td>".$tglexpire."</td>";
                            if($jns==TRUE)
                            {
                                $imgdelete=$this->template_icon->detail_onclick("hapusquest('Are you sure want to undone Quest ".$key['ID']." for this player ?','".(string)$key['ID']."','".$id_user."')","",'Undone',"delete.png","","linkdelete");
                                echo "<td>".$imgdelete."</td>";
                            }
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>        
    </div>
</div>