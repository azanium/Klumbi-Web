<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
var dialogfrm;
var datatable;
$(document).ready(function(){
    datatable=$('#chiledatatable').dataTable( {
            "sEmptyTable": "No Cases available for selection",
            'aoColumns': [ null, null, null, null, null],
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
                            <th width="20%">Duration</th>
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