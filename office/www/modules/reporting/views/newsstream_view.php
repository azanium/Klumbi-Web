<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
var dttable;
var chart,chart2;
$(document).ready(function() {
    $('a.panel-collapse').click(function() {
        $(this).children().toggleClass("icon-chevron-down icon-chevron-up");
        $(this).closest(".panel-heading").next().toggleClass("in");
        $(this).closest(".panel-heading").toggleClass('rounded-bottom');
        return false;
    });
    chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container',
                type: 'column',
                margin: [ 50, 50, 100, 80]
            },
            title: {
                text: 'News Love'
            },
            subtitle: {
                text: 'Chart View love count.'
            },
            xAxis: {
                categories: [
                    <?php
                        if($listconfig)
                        {
                            foreach($listconfig as $dt)
                            {
                                echo "'".$dt['title']."',";
                            }
                        }
                        else
                        {
                            echo "'No Label'";
                        }
                    ?>
                ],
                labels: {
                    rotation: -45,
                    align: 'right',
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Count Love'
                }
            },
            legend: {
                enabled: false
            },
            tooltip: {
                formatter: function() {
                    return '<b>'+ this.x +'</b><br/>'+
                        'Count: '+ Highcharts.numberFormat(this.y, 1) +
                        ' Love';
                }
            },
            series: [{
                name: 'Love',
                data: [
                    <?php
                        if($listconfig)
                        {
                            $datanilai="";
                            foreach($listconfig as $dt)
                            {
                                $this->mongo_db->select_db("Social");
                                $this->mongo_db->select_collection("Social");
                                $datanilai .= $this->mongo_db->count2(array('id'=>(string)$dt['_id'],'type'=>'NewsLove')).", ";
                            }
                            echo $datanilai;
                        }
                        else
                        {
                            echo "0";
                        }
                    ?>
                ],
                dataLabels: {
                    enabled: true,
                    rotation: -90,
                    color: '#FFFFFF',
                    align: 'right',
                    x: 4,
                    y: 10,
                    style: {
                        fontSize: '8px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            }]
        });
        chart2 = new Highcharts.Chart({
            chart: {
                renderTo: 'container2',
                type: 'line'
            },
            title: {
                text: 'Comment News'
            },
            subtitle: {
                text: 'Total Comments'
            },
            xAxis: {
                categories: [
                    <?php
                    $arrayvalfilter=array();
                    $this->mongo_db->select_db("Social");
                    $this->mongo_db->select_collection("Social");
                    if($listconfig)
                    {
                        $firstdate = $this->mongo_db->find(array(),0,1,array('datetime'=>-1));
                        $lastdate = $this->mongo_db->find(array(),0,1,array('datetime'=>1));
                        foreach($firstdate as $nilai)
                        {
                            $tempfirst=$nilai;
                        }
                        foreach($lastdate as $nilai)
                        {
                            $templast=$nilai;
                        }
                        $blnfirsttemp=  strtotime(date('Y-m-d 23:59:59',$tempfirst['datetime']->sec));
                        $blnlasttemp=$templast['datetime']->sec;
                        $texttgl="";
                        do 
                        {                                                                               
                           $newdate = $blnlasttemp;                                                                      
                           $texttgl .= "'".date('d M Y', $newdate)."', "; 
                           $arrayvalfilter[] = array(
                               'awal'=>$this->mongo_db->time(strtotime(date('Y-m-d 00:00:00',$newdate))),
                               'akhir'=>$this->mongo_db->time(strtotime(date('Y-m-d 23:59:59',$newdate))),
                           );
                           $blnlasttemp = strtotime('+1 day',$blnlasttemp); 
                        } 
                        while ($blnfirsttemp >= $blnlasttemp);
                        echo $texttgl;
                    }
                    else
                    {
                        echo "'No Label'";
                    }
                    ?>
                ]
            },
            yAxis: {
                title: {
                    text: 'Count Comment'
                },
                min:0
            },
            tooltip: {
                enabled: false,
                formatter: function() {
                    return '<b>'+ this.series.name +'</b><br/>'+
                        this.x +': '+ this.y +' Count';
                }
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: false
                }
            },
            series: [
                <?php
                if($listconfig)
                {
                    foreach($listconfig as $dt)
                    {
                        echo "{";
                        echo "name: '".$dt['title']."',";
                        echo "data: [";
                        foreach ($arrayvalfilter as $tempfilter)
                        {
                            $arrayfilter=array(
                               'type'=>'NewsComment',
                               "id"=>(string)$dt['_id'],
                               'datetime'=>array(
                                   '$gte'=>$tempfilter['awal'],
                                   '$lt'=>$tempfilter['akhir'],
                               )
                            );
                            echo (int)$this->mongo_db->count2($arrayfilter).",";
                        }
                        echo "]";
                        echo "},";
                    }
                }
                else
                {
                    echo "{name: 'unknown', data: [1]}";
                }
                ?>]
        });
});
</script>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-purple">
                <div class="panel-heading">
                    <h4>News Report</h4>
                    <div class="options">
                        <a href="javascript:;" class="panel-collapse"><i class="icon-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <div id="accordioninpanel" class="accordion-group">
                        <div class="accordion-item">
                            <a class="accordion-title" data-toggle="collapse" data-parent="#accordioninpanel" href="#collapsekomment"><h4>Chart Loves</h4></a>
                            <div id="collapsekomment" class="collapse">
                                <div class="accordion-body">
                                    <div id="container" style="min-width: 400px; height: 400px;width: 75%;">&nbsp;</div>  
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <a class="accordion-title" data-toggle="collapse" data-parent="#accordioninpanel" href="#collapselove"><h4>Chart Comments</h4></a>
                            <div id="collapselove" class="collapse">
                                <div class="accordion-body">
                                    <div id="container2" style="min-width: 400px; height: 400px;width: 75%;">&nbsp;</div>
                                </div>
                            </div>
                        </div> 
                        <div class="accordion-item">
                            <a class="accordion-title" data-toggle="collapse" data-parent="#accordioninpanel" href="#collapsetable"><h4>Tables</h4></a>
                            <div id="collapsetable" class="in">
                                <div class="accordion-body">
                                    <table id="datatable" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0" border="0">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="75%">News Title</th>
                                                <th width="10%">Love</th>
                                                <th width="10%">Comments</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if($listconfig)
                                            {
                                                $i= $startfrom + 1;
                                                foreach($listconfig as $dt)
                                                {
                                                    $this->mongo_db->select_db("Social");
                                                    $this->mongo_db->select_collection("Social");
                                                    $totallike = (int)$this->mongo_db->count(array('id'=>(string)$dt['_id'],'type'=>'NewsLove'));  
                                                    $totalcomment = (int)$this->mongo_db->count(array('id'=>(string)$dt['_id'],'type'=>'NewsComment')); 
                                                    echo "<tr>";
                                                    echo "<td>".$i."</td>";
                                                    echo "<td>".$dt['title']."</td>";
                                                    echo "<td>".$totallike."</td>";
                                                    echo "<td>".$totalcomment."</td>";
                                                    echo "</tr>";
                                                    $i++;
                                                }                                
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center panel-footer">
                    <?php echo $paging; ?>
                    <div class="text-info">Count Data <strong><?php echo $count; ?></strong></div>
                </div>
            </div>
        </div>
    </div>
</div>