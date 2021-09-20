<meta name="csrf-token" content="{{ csrf_token() }}">
<audio  id="sond" src="{{ asset('assets/son/tambour.mp3') }}" preload="auto" autoplay />
<div id="container" style="width:100%; display: table; min-height: 600px; margin: 0 auto"></div>
<script type="text/javascript">
    var categoryImgs = [];
    var colors = ["#fed130","#f2822f","#262a32"];
    var victorys = [{}];
    var pathname = window.location.pathname;
    $.ajax({
    url: pathname+"/resultats/victorys",
    type: "POST",
    data: {
        _token : $('meta[name="csrf-token"]').attr('content')
    },
    async: false,
    success: function (data) {
        var array = jQuery.parseJSON(JSON.stringify(data));
        var somme = 0;
        for (i = 0; i < array.length; i ++) {
            categoryImgs.push(array[i].logo);
            victorys[i]= {name:array[i].titre_fr+' - '+array[i].titre_ar, color:'#'+array[i].color, y:array[i].note};
            somme += array[i].note;
        }

        if(somme> 0){
            $('#container').html("<div style='display: table-cell; vertical-align: middle; text-align:center'>Une erreur s'est produite. Merci de réessayer plus tard.</div>");
            var max= 100;
            $('#container').highcharts({
                    chart: {
                        type:'bar',          
                    },
                    title: {
                        text: '',
                        style: {fontWeight: 'bolder',fontSize:'25px',color:'#ec782b;',fontFamily: "orangeFont"},
                    },
                    credits: {
                          enabled: false
                      },
                    plotOptions: {
                        series: {
                            pointWidth:50,
                            animation: {
                                duration: 6000
                            },
                            shadow:true,
                            borderWidth:2,
                            dataLabels:{                            
                                inside:true,
                                color: '#FFF',
                                style: {fontWeight: 'bolder',fontSize:'18px',fontFamily:'orangeFont'},
                                enabled:true,
                                formatter:function() {
                                    var note = this.y+'';
                                    if(note.split('.')[1] && (note.split('.')[1]).length == 1)
                                        note+='0';
                                    return note+ ' %';
                                }
                            }
                        }
                    },
                    xAxis:{
                        backgroundColor: '#FFF',
                        gridLineWidth: 0, 
                        tickLength: 0,
                        lineWidth: 0,
                        title:'',
                        min:0,
                        labels: {
                            tickInterval: 1,
                            useHTML: true,
                            formatter: function () {
                                return '<div class="img-res" style="background:url('+ categoryImgs[this.value] +'); background-size:contain; background-position:center; background-repeat:no-repeat;">&nbsp;</div>';
                            }                     
                        }
                    },                            
                    yAxis:{  
                        min:0,
                        max:100, 
                        title:'',
                    }, 
                    exporting: { enabled: false },    
                    series: [{
                        pointPadding: 5,
                        showInLegend: false,
                        data: victorys                            
                    }],

                    responsive: {
                        rules: [{
                            condition: {
                                maxWidth: 400
                            },
                            chartOptions: {
                                legend: {
                                    align: 'center',
                                    verticalAlign: 'bottom',
                                    layout: 'horizontal'
                                },
                                yAxis: {
                                    labels: {
                                        align: 'left',
                                        x: 0,
                                        y: -5
                                    },
                                    title: {
                                        text: null
                                    }
                                },
                                subtitle: {
                                    text: null
                                },
                                credits: {
                                    enabled: false
                                }
                            }
                        }]
                    } 
            });

        }else{
            $('#sond').removeAttr('autoplay');
            $('#container').html("<div style='display: table-cell; vertical-align: middle; text-align:center '>Information non disponible.</div>");
        }

    },
    error: function() {
       $('#sond').removeAttr('autoplay');
       $('#container').html("<div style='display: table-cell; vertical-align: middle; text-align:center'>Une erreur s'est produite. Merci de réessayer plus tard.</div>");
    }
    });
</script>