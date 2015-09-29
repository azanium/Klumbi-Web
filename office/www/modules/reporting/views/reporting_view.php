<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
var dttable;
var chart;
$(document).ready(function() {
   $('a.panel-collapse').click(function() {
        $(this).children().toggleClass("icon-chevron-down icon-chevron-up");
        $(this).closest(".panel-heading").next().toggleClass("in");
        $(this).closest(".panel-heading").toggleClass('rounded-bottom');
        return false;
    });
   var colors = Highcharts.getOptions().colors,
            categories = [
                <?php
                    if($listbrand)
                    {
                        foreach($listbrand as $dt)
                        {
                            echo "'".$dt['name']."',";
                        }
                    }
                    else
                    {
                        echo "'No Label'";
                    }
                ?>
            ],
            name = 'Love store',
            data = [
                <?php
                    if($listbrand)
                    {
                        $index=0;
                        $text="";
                        foreach($listbrand as $dt)
                        {
                            $this->mongo_db->select_db("Social");
                            $this->mongo_db->select_collection("Social");
                            $text .="{";
                            $text .="y:".$this->mongo_db->count2(array('brand_id'=>(string)$dt['_id'],'type'=>'BrandLove')).",";                            
                            $text .="color: colors[".(int)$index."],";
                            $text .="drilldown: {";
                            $text .="name: 'Comments ".$dt['name']."',";                           
                            $texttgl = "";
                            $textvalue = "";
                            if($this->mongo_db->count2(array("brand_id"=>(string)$dt['_id'])))
                            {
                                $firstdate = $this->mongo_db->find(array("brand_id"=>(string)$dt['_id']),0,1,array('datetime'=>-1));
                                $lastdate = $this->mongo_db->find(array("brand_id"=>(string)$dt['_id']),0,1,array('datetime'=>1));
                                foreach($firstdate as $nilai)
                                {
                                    $tempfirst=$nilai;
                                }
                                foreach($lastdate as $nilai)
                                {
                                    $templast=$nilai;
                                }
                                $texttgl = "";
                                $textvalue = "";
                                $blnfirsttemp=  strtotime(date('Y-m-d 23:59:59',$tempfirst['datetime']->sec));
                                $blnlasttemp=$templast['datetime']->sec;
                                do 
                                {                                                                               
                                   $newdate = $blnlasttemp;                                                                      
                                   $texttgl .= "'".date('d M Y', $newdate)."', ";                                   
                                   $arrayfilter=array(
                                       "brand_id"=>(string)$dt['_id'],
                                       'type'=>'BrandComment',
                                       'datetime'=>array(
                                           '$gte'=>$this->mongo_db->time(strtotime(date('Y-m-d 00:00:00',$newdate))),
                                           '$lt'=>$this->mongo_db->time(strtotime(date('Y-m-d 23:59:59',$newdate))),
                                       )
                                   );
                                   $textvalue .= (int)$this->mongo_db->count2($arrayfilter).",";
                                   $blnlasttemp = strtotime('+1 day',$blnlasttemp); 
                                } 
                                while ($blnfirsttemp >= $blnlasttemp);
                            }
                            else
                            {
                                $texttgl = "'null'";
                                $textvalue = "0";
                            }                            
                            $text .="categories: [".$texttgl."],";
                            $text .="data: [".$textvalue."],";
                            $text .="color: colors[".(int)$index."],";
                            $text .="}";                            
                            $text .="},";
                            $index++;
                        }
                        echo $text;
                    }
                    else
                    {
                        echo "{y: 1,color: colors[0],drilldown: {name: 'No Label',categories: ['Null'],data: [1],color: colors[0]}}";
                    }
                ?>];    
        function setChart(name, categories, data, color) {
			chart.xAxis[0].setCategories(categories, false);
			chart.series[0].remove(false);
			chart.addSeries({
				name: name,
				data: data,
				color: color || 'white'
			}, false);
			chart.redraw();
        }    
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container',
                type: 'column'
            },
            title: {
                text: 'Reporting Store'
            },
            subtitle: {
                text: 'Click the columns to view comments. Click again to view store.'
            },
            xAxis: {
                categories: categories
            },
            yAxis: {
                title: {
                    text: 'Total value '
                }
            },
            plotOptions: {
                column: {
                    cursor: 'pointer',
                    point: {
                        events: {
                            click: function() {
                                var drilldown = this.drilldown;
                                if (drilldown) {
                                    setChart(drilldown.name, drilldown.categories, drilldown.data, drilldown.color);
                                } else {
                                    setChart(name, categories, data);
                                }
                            }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        color: colors[0],
                        style: {
                            fontWeight: 'bold'
                        },
                        formatter: function() {
                            return this.y;
                        }
                    }
                }
            },
            tooltip: {
                formatter: function() {
                    var point = this.point,
                        s = this.x +':<b>'+ this.y +' </b><br/>';
                    if (point.drilldown) {
                        s += 'Click to view '+ point.category +' Comments';
                    } else {
                        s += 'Click to back to list store';
                    }
                    return s;
                }
            },
            series: [{
                name: name,
                data: data,
                color: 'white'
            }],
            exporting: {
                enabled: false
            }
        });    
});
</script>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-purple">
                <div class="panel-heading">
                    <h4>Store Report</h4>
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
                            <a class="accordion-title" data-toggle="collapse" data-parent="#accordioninpanel" href="#collapsetable"><h4>Tables</h4></a>
                            <div id="collapsetable" class="in">
                                <div class="accordion-body">
                                    <table id="datatable" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0" border="0">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="75%">Store Name</th>
                                                <th width="10%">Love</th>
                                                <th width="10%">Comments</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if($listbrand)
                                            {
                                                $i= $startfrom + 1;
                                                foreach($listbrand as $dt)
                                                {
                                                    $this->mongo_db->select_db("Social");
                                                    $this->mongo_db->select_collection("Social");
                                                    $totallike = (int)$this->mongo_db->count(array('brand_id'=>(string)$dt['_id'],'type'=>'BrandLove'));  
                                                    $totalcomment = (int)$this->mongo_db->count(array('brand_id'=>(string)$dt['_id'],'type'=>'BrandComment')); 
                                                    echo "<tr>";
                                                    echo "<td>".$i."</td>";
                                                    echo "<td>".$dt['name']."</td>";
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