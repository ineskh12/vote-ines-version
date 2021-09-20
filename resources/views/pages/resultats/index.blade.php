<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon.ico') }}">    
	<title>Vote {{ date('Y') }}  - Vote results</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
</head>
<style type="text/css">
    .img-res {
        height: 40px;
        max-height: 100%;
    }
</style>
<body>
    <div class="chart-container" style="position: relative; width:80vw; height: 80vh; margin: auto; display:flex; flex-direction: column;" id="container">
        <input type="hidden" id="event_id" value="{{ request()->get('event_id') }}">
		<canvas id="myChart" style="height: 100%;"></canvas>
        <div id="images"></div>
	</div>
</body>
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js"></script>
<script>
var APP_URL = {!! json_encode(url('/')) !!}
function convertHex(hex,opacity){
    hex = hex.replace('#','');
    r = parseInt(hex.substring(0,2), 16);
    g = parseInt(hex.substring(2,4), 16);
    b = parseInt(hex.substring(4,6), 16);

    result = 'rgba('+r+','+g+','+b+','+opacity/100+')';
    return result;
}

var labels = [];
var colors = [];
var bg_colors = [];
var datas = [];
var imgs = "";

$.ajax({
    url: APP_URL+"/admin/resultats/victorys",
    type: "POST",
    data: {
        _token : $('meta[name="csrf-token"]').attr('content'),
        eventId : $('#event_id').val()
    },
    async: false,
    success: function (data) {
        var array = jQuery.parseJSON(JSON.stringify(data));
        console.log(array);
        var somme = 0;
        var eventName = '';
        for (i = 0; i < array.length; i ++) {
            labels.push(array[i].titre);
            colors.push('#'+array[i].color);
            bg_colors.push(convertHex('#'+array[i].color,30));
            datas.push(array[i].note);
            imgs+='<div style="display: inline-block; width: '+(100/array.length)+'%;"><div class="img-res" style="background:url('+array[i].logo+'); background-size:contain; background-position:center; background-repeat:no-repeat; margin: auto;">&nbsp;</div></div>';
            somme += array[i].note;
            eventName = array[i].eventName;
        }

        var data = {
        labels: labels,
        datasets: [{
            data: datas,
            backgroundColor: bg_colors,
            borderColor: colors,
            borderWidth: 1
        }]
        }


        if(somme> 0){
            // mp3.pause();
            // mp3.play();

            var music = new Audio("{{ asset('assets/son/tambour.mp3') }}");
            var p = music.play();


            $('#images').html(imgs);
            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: data,
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    events: [],
                    animation:{
                        duration : 6000,
                        easing : 'easeInBounce',
                        xAxis: false,
                        yAxis: true,
                    },
                    tooltips:{
                        enabled : false
                    },
                    hover: {
                        mode: null
                    },
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'VOTE '+eventName,
                        fontColor: '#ff7900',
                        fontSize: '40',
                        fontFamily: 'Helvetica Neue, Helvetica, Arial, sans-serif',
                        fontStyle: 'bold',
                        position: 'top',
                        padding: 150
                    },
                    scales: {
                        yAxes: [{
                            gridLines: {
                                show: false,
                                display:false,
                                drawBorder: false,
                                lineWidth: 0,
                                drawOnChartArea: false
                            },
                            ticks: {
                                beginAtZero: true,
                                display: false,
                                suggestedMax: Math.max(...data.datasets[0].data)
                            }
                        }],
                        xAxes: [{
                            gridLines: {
                                show: false,
                                lineWidth: 0,
                                display:false,
                                drawBorder: false,
                                drawOnChartArea: false,
                            },
                            ticks: {
                                show: false,
                                display:false,
                            }
                        }]
                    }
                }
            });

            Chart.plugins.register({
                afterRender: function(chart, easing) {
                    // To only draw at the end of animation, check for easing === 1
                    var ctx = chart.ctx;

                    chart.data.datasets.forEach(function (dataset, i) {
                        var meta = chart.getDatasetMeta(i);
                        if (!meta.hidden) {
                            meta.data.forEach(function(element, index) {
                                // Draw the text in black, with the specified font
                                ctx.fillStyle = 'rgb(255, 121, 0)';
                                ctx.fillStyle = 'rgb(0, 0, 0)';

                                var fontSize = 60;
                                var fontStyle = 'bold';
                                var fontFamily = 'Helvetica Neue';
                                ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);

                                // Just naively convert to string for now
                                var dataString = dataset.data[index].toString() + '%';

                                // Make sure alignment settings are correct
                                ctx.textAlign = 'center';
                                ctx.textBaseline = 'middle';

                                var padding = 5;
                                var position = element.tooltipPosition();
                                ctx.fillText(dataString, position.x, position.y - (fontSize / 2) - padding);

                                            

                            });
                        }
                    });
                }
            });
        }else{
            $('#container').html("<div style='margin:auto;'>Information non disponible.</div>");
        }
    },
    error: function(e) {
        console.log(e);
       $('#container').html("<div style='margin:auto;'>Une erreur s'est produite. Merci de réessayer plus tard.</div>");
    }
});
</script>
</html>