<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); 
$this->mongo_db->select_db("Game");
$this->mongo_db->select_collection("PlayerVisit");
?>
<script type="text/javascript" src="<?php echo base_url(); ?>resources/plugin/Highcharts-2.3.3/js/highcharts.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>resources/plugin/Highcharts-2.3.3/js/highcharts-more.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>resources/plugin/Highcharts-2.3.3/js/modules/exporting.js"></script>
<script type="text/javascript">
var chart,chart2,chart3,chart4,chart5;
$(document).ready(function() {
    $('a.panel-collapse').click(function() {
        $(this).children().toggleClass("icon-chevron-down icon-chevron-up");
        $(this).closest(".panel-heading").next().toggleClass("in");
        $(this).closest(".panel-heading").toggleClass('rounded-bottom');
        return false;
    });
    //berdasarkan Room
    chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container',
                type: 'column',
                width:800
            },
            title: {
                text: 'Player Visits'
            },
            subtitle: {
                text: 'Source: <?php echo base_url(); ?>'
            },
            xAxis: {
                categories: [
                    <?php
                    if($listdt)
                    {
                        $hasil="";
                        asort($listdt['values']);
                        foreach($listdt['values']  as $dt)
                        {
                            $hasil .="'".$dt."',";
                        }
                        echo substr($hasil, 0, strlen($hasil)-1);
                    }
                    ?>
                ]
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Count (Players)'
                },
                stackLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                    }
                }
            },
            legend: {
                align: 'right',
                x: -100,
                verticalAlign: 'top',
                y: 40,
                floating: true,
                backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColorSolid) || 'white',
                borderColor: '#CCC',
                borderWidth: 1,
                shadow: false
            },
            tooltip: {
                formatter: function() {
                    return '<b>'+ this.x +'</b><br/>'+
                        this.series.name +': '+ this.y +'<br/>'+
                        'Visit : '+ this.point.stackTotal;
                }
            },
            plotOptions: {
                column: {
                    stacking: 'normal',
                    dataLabels: {
                        enabled: true,
                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                    }
                }
            },
                series: [
                    <?php
                    foreach($listroom['values'] as $dtroom)
                    {
                        $name=$dtroom;
                        if($name=="Loading")
                        {
                            $name="Bounce";
                        }
                        echo "{";
                        echo "name: '".$name."',";
                        echo "data: [";                        
                        $hasil="";
                        asort($listdt['values']);
                        foreach($listdt['values']  as $dt)
                        {
                            $count=$this->mongo_db->count(array('date'=>$dt,'room'=>$dtroom));             //"bounce" => TRUE,               
                            $hasil .=$count.",";
                        }
                        echo substr($hasil, 0, strlen($hasil)-1);
                        echo "]";
                        echo "},";
                    }
                    ?>]
        });
        //berdasarkan bounce
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container5',
                type: 'column',
                width: 800
            },
            title: {
                text: 'Player Visits'
            },
            subtitle: {
                text: 'Source: <?php echo base_url(); ?>'
            },
            xAxis: {
                categories: [
                    <?php
                    if($listdt)
                    {
                        $hasil="";
                        asort($listdt['values']);
                        foreach($listdt['values']  as $dt)
                        {
                            $hasil .="'".$dt."',";
                        }
                        echo substr($hasil, 0, strlen($hasil)-1);
                    }
                    ?>
                ]
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Count (Players)'
                }
            },
            legend: {
                layout: 'vertical',
                backgroundColor: '#FFFFFF',
                align: 'left',
                verticalAlign: 'top',
                x: 100,
                y: 70,
                floating: true,
                shadow: true
            },
            tooltip: {
                formatter: function() {
                    return ''+ this.x +': '+ this.y +' players';
                }
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
                series: [{
                name: 'Bounce',
                data: [
                    <?php
                    if($listdt)
                    {
                        $hasil="";
                        asort($listdt['values']);
                        foreach($listdt['values']  as $dt)
                        {
                            $count=$this->mongo_db->count(array("bounce" => TRUE,'date'=>$dt));                            
                            $hasil .=$count.",";
                        }
                        echo substr($hasil, 0, strlen($hasil)-1);
                    }
                    ?>
              ]
    
            }, {
                name: 'Not Bounce',
                data: [
                    <?php
                    if($listdt)
                    {
                        $hasil="";
                        asort($listdt['values']);
                        foreach($listdt['values']  as $dt)
                        {
                            $count=$this->mongo_db->count(array("bounce" => FALSE,'date'=>$dt));                            
                            $hasil .=$count.",";
                        }
                        echo substr($hasil, 0, strlen($hasil)-1);
                    }
                    ?>
                ]
    
            }]
        });
        //berdasarkan durasi  
    	chart2 = new Highcharts.Chart({    	
		    chart: {
		        renderTo: 'container2',
		        type: 'arearange',
                        width: 800
		    },		    
		    title: {
		        text: 'Length of Stay Total Durations (Top=Bounce, Bottom=Online)'
		    },	
                    subtitle: {
                        text: 'Source: <?php echo base_url(); ?>'
                    },
                    xAxis: {
                        categories: [
                            <?php
                            if($listdt)
                            {
                                $hasil="";
                                asort($listdt['values']);
                                foreach($listdt['values']  as $dt)
                                {
                                    $hasil .="'".$dt."',";
                                }
                                echo substr($hasil, 0, strlen($hasil)-1);
                            }
                            ?>
                        ]
                    },
		    yAxis: {
		        title: {
		            text: 'Sum of Durations'
		        }
		    },		
		    tooltip: {
		        crosshairs: true,
		        shared: true,
		        valueSuffix: ' Minute'
		    },
		    
		    legend: {
		        enabled: false
		    },		
		    series: [{
		        name: 'Durations',
		        data:
                            <?php
                            $this->mongo_db->select_db("Game");
                            $this->mongo_db->select_collection("PlayerVisit");
                            $output=array();
                            if($listdt)
                            {
                                asort($listdt['values']);
                                foreach($listdt['values']  as $dt)
                                {
                                    $data=$this->mongo_db->find(array('date'=>$dt),0,0,array()); 
                                    $bounce=0;
                                    $notbounce=0;
                                    foreach($data as $dt2)
                                    {
                                        
                                        if($dt2['bounce']==FALSE)
                                        {
                                            $bounce +=$dt2['duration'];
                                        }   
                                        else 
                                        {
                                            $notbounce +=$dt2['duration'];
                                        }       
                                    }
                                    $output[] = array(
                                        $dt,
                                        $bounce,
                                        $notbounce,
                                    ); 
                                }
                            }
                            echo json_encode( $output );
                            ?>
		    }]		
		});
                //rata-rata online
                chart3 = new Highcharts.Chart({
                        chart: {
                            renderTo: 'container3',
                            type: 'spline',
                            inverted: false,
                            width: 800,
                            style: {
                                margin: '0 auto'
                            }
                        },
                        title: {
                            text: 'Average Length of Stay'
                        },
                        subtitle: {
                            text: 'Source <?php echo base_url(); ?>'
                        },
                        xAxis: {
                            reversed: false,
                            title: {
                                enabled: true,
                                text: 'Date'
                            },
                            labels: {
                                formatter: function() {
                                    return this.value;
                                }
                            },
                            maxPadding: 0.05,
                            showLastLabel: true,
                            categories: [
                                <?php
                                if($listdt)
                                {
                                    $hasil="";
                                    asort($listdt['values']);
                                    foreach($listdt['values']  as $dt)
                                    {
                                        $hasil .="'".$dt."',";
                                    }
                                    echo substr($hasil, 0, strlen($hasil)-1);
                                }
                                ?>
                            ]
                        },
                        yAxis: {
                            title: {
                                text: 'Minute'
                            },
                            labels: {
                                formatter: function() {
                                    return this.value;
                                }
                            },
                            lineWidth: 2
                        },
                        legend: {
                            enabled: false
                        },
                        tooltip: {
                            formatter: function() {
                                return ''+
                                    ' Date: '+this.x +', '+ this.y +' Minute';
                            }
                        },
                        plotOptions: {
                            spline: {
                                marker: {
                                    enable: false
                                }
                            }
                        },
                        series: [{
                            name: 'Average Bounce',
                            data: [
                                <?php
                                    if($listdt)
                                    {
                                        $hasil="";
                                        asort($listdt['values']);
                                        foreach($listdt['values']  as $dt)
                                        {
                                            $data=$this->mongo_db->find(array('bounce'=>FALSE,'date'=>$dt),0,0,array()); 
                                            $bounce=0;
                                            $i=1;
                                            foreach($data as $dt2)
                                            {
                                                $bounce +=$dt2['duration']; 
                                                $i++;
                                            }
                                            $bounce=$bounce/$i;
                                            $hasil .=$bounce.",";
                                        }
                                        echo substr($hasil, 0, strlen($hasil)-1);
                                    }
                                    ?>
                            ]
                        }]
                    });
             //rata-rata Bounce
                chart4 = new Highcharts.Chart({
                        chart: {
                            renderTo: 'container4',
                            type: 'spline',
                            inverted: false,
                            width: 800,
                            style: {
                                margin: '0 auto'
                            }
                        },
                        title: {
                            text: 'Average of Bounce Durations'
                        },
                        subtitle: {
                            text: 'Source <?php echo base_url(); ?>'
                        },
                        xAxis: {
                            reversed: false,
                            title: {
                                enabled: true,
                                text: 'Date'
                            },
                            labels: {
                                formatter: function() {
                                    return this.value;
                                }
                            },
                            maxPadding: 0.05,
                            showLastLabel: true,
                            categories: [
                                <?php
                                if($listdt)
                                {
                                    $hasil="";
                                    asort($listdt['values']);
                                    foreach($listdt['values']  as $dt)
                                    {
                                        $hasil .="'".$dt."',";
                                    }
                                    echo substr($hasil, 0, strlen($hasil)-1);
                                }
                                ?>
                            ]
                        },
                        yAxis: {
                            title: {
                                text: 'Minute'
                            },
                            labels: {
                                formatter: function() {
                                    return this.value;
                                }
                            },
                            lineWidth: 2
                        },
                        legend: {
                            enabled: false
                        },
                        tooltip: {
                            formatter: function() {
                                return ' Date: '+this.x +', '+ this.y +' Minute';
                            }
                        },
                        plotOptions: {
                            spline: {
                                marker: {
                                    enable: false
                                }
                            }
                        },
                        series: [{
                            name: 'Average Bounce',
                            data: [
                                <?php
                                    if($listdt)
                                    {
                                        $hasil="";
                                        asort($listdt['values']);
                                        foreach($listdt['values']  as $dt)
                                        {
                                            $data=$this->mongo_db->find(array('bounce'=>TRUE,'date'=>$dt),0,0,array()); 
                                            $bounce=0;
                                            $i=1;
                                            foreach($data as $dt2)
                                            {
                                                $bounce +=$dt2['duration']; 
                                                $i++;
                                            }
                                            $bounce=$bounce/$i;
                                            $hasil .=$bounce.",";
                                        }
                                        echo substr($hasil, 0, strlen($hasil)-1);
                                    }
                                    ?>
                            ]
                        }]
                    });
});
</script>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-purple">
                <div class="panel-heading">
                    <h4>Length of Stay</h4>
                    <div class="options">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#tab-1" data-toggle="tab"><i class="icon-resize-small"></i> Statistics</a></li>
                          <li><a href="#tab-2" data-toggle="tab"><i class="icon-screenshot"></i> Bounce Data</a></li>
                          <li><a href="#tab-3" data-toggle="tab"><i class="icon-asterisk"></i> Average Online Durations</a></li>
                          <li><a href="#tab-4" data-toggle="tab"><i class="icon-resize-full"></i> Average of Bounce Durations</a></li>
                        </ul>
                        <a href="javascript:;" class="panel-collapse"><i class="icon-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-1">
                            <div align="center" id="container" style="min-width: 500px; min-height: 500px; margin: 0 auto">&nbsp;</div>
                            <hr style="border: 2px solid #c77405;" />
                            <div align="center" id="container2" style="min-width: 500px; min-height: 500px; margin: 0 auto">&nbsp;</div>
                        </div>
                        <div class="tab-pane" id="tab-2">
                            <div align="center" id="container5" style="min-width: 500px; min-height: 500px; margin: 0 auto">&nbsp;</div>
                        </div>
                        <div class="tab-pane" id="tab-3">
                            <div align="center" id="container3" style="min-width: 500px; min-height: 500px; margin: 0 auto">&nbsp;</div>
                        </div>
                        <div class="tab-pane" id="tab-4">
                            <div align="center" id="container4" style="min-width: 500px; min-height: 500px; margin: 0 auto">&nbsp;</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>