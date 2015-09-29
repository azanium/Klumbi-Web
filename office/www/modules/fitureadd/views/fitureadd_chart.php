<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript" src="<?php echo base_url(); ?>resources/plugin/Highcharts-2.3.3/js/highcharts.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>resources/plugin/Highcharts-2.3.3/js/modules/exporting.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#modal').modal('show');
    var chart;
    chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: '<?php echo $judul; ?>'
            },
            tooltip: {
        	pointFormat: '{series.name}: <b>{point.percentage}%</b>',
            	percentageDecimals: 1
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
//                        color: '#000000',
//                        connectorColor: '#000000',
//                        formatter: function() {
//                            return '<b>'+ this.point.name +'</b>: '+ this.percentage +' %';
//                        }
                    },
                    showInLegend: true
                }
            },
            series: [{
                type: 'pie',
                name: 'Result Values',
                data: [
                    <?php
                    if($data)
                    {
                        $i=0;
                        $hasil="";
                        foreach($data as $dt)
                        {
                            if($i==0)
                            {
                                $hasil .="{name:'".$dt['option']."',y:".$dt['values'].",sliced: true,selected: true},";
                            }
                            else 
                            {
                                 $hasil .="['".$dt['option']."',".$dt['values']."],";
                            }
                        }
                        echo substr($hasil, 0, strlen($hasil)-1);
                    }
                    ?>
                ]
            }]
        });
});
</script>
<div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo $judul; ?></h4>
            </div>
            <div class="modal-body">
                <div align="center" id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
                <?php echo "<p class='error'>".$date."</p>"; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>        
    </div>
</div>