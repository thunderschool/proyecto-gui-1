<template>
    <main class="main">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Inicio</li>
                <li class="breadcrumb-item"><a href="#">Admin</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Asistentes y no asistentes
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <canvas id="chart"></canvas>
                        </div>
                        <div class="col-md-6">
                            <ul>
                                <li><label>10 asistentes confirmados</label></li>
                                <li><label>5 asistentes sin confirmar</label></li>
                            </ul>
                            <div id="liveclck">
                                <p class="date">{{ date }}</p>
                                <p class="pakatime">{{ pakatime }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>
<script>
export default {
    mounted(){
        var data = {
            datasets : [{
                    data : [10, 5],
                    backgroundColor : ['rgb(77, 189, 116)', 'rgb(255, 0, 0)'],
                    borderColor : ['rgb(77, 189, 116)', 'rgb(255, 0, 0)'],
                    label : "Asistencias"
                }],
                labels : ["Asistentes confirmados", "Asistentes sin confirmar"]
        };
        var pieOptions = {
            events: false,
            animation: {
                duration: 500,
                easing: "easeOutQuart",
                onComplete: function () {
                    var ctx = this.chart.ctx;
                    ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontFamily, 'normal', Chart.defaults.global.defaultFontFamily);
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'bottom';
                    ctx.font='35px Arial';

                    this.data.datasets.forEach(function (dataset) {

                        for (var i = 0; i < dataset.data.length; i++) {
                            var model = dataset._meta[Object.keys(dataset._meta)[0]].data[i]._model,
                                total = dataset._meta[Object.keys(dataset._meta)[0]].total,
                                mid_radius = model.innerRadius + (model.outerRadius - model.innerRadius)/2,
                                start_angle = model.startAngle,
                                end_angle = model.endAngle,
                                mid_angle = start_angle + (end_angle - start_angle)/2;

                            var x = mid_radius * Math.cos(mid_angle);
                            var y = mid_radius * Math.sin(mid_angle);

                            ctx.fillStyle = '#fff';
                            if (i == 3){ // Darker text color for lighter background
                                ctx.fillStyle = '#444';
                            }
                            var percent = String(Math.round(dataset.data[i]/total*100)) + "%";
                            //ctx.fillText(dataset.data[i], model.x + x, model.y + y); //Muestra cantidades
                            // Display percent in another line, line break doesn't work for fillText
                            ctx.fillText(percent, model.x + x, model.y + y + 15); //Muestra porcentajes
                        }
                    });               
                }
            }
        };
        var ctx = document.getElementById('chart');
        var chart = new Chart(ctx, {
            type : 'pie',
            data : data,
            options: pieOptions
        });
        var liveclck = new Vue({
            el: '#liveclck',
            data: {
                pakatime: '',
                date: ''
            }
        });
        var custom_date = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
        var timerID = setInterval(activeUpdateDateTime, 1000);
        activeUpdateDateTime();
        function activeUpdateDateTime() { 
            var mycurrentDate = new Date();
            liveclck.pakatime = sysPaddingZro(mycurrentDate.getHours(), 2) + ':' +sysPaddingZro(mycurrentDate.getMinutes(), 2) + ':' + sysPaddingZro(mycurrentDate.getSeconds(), 2);
            liveclck.date = sysPaddingZro(mycurrentDate.getFullYear(), 4) + '-' + sysPaddingZro(mycurrentDate.getMonth()+1, 2) + '-' + sysPaddingZro(mycurrentDate.getDate(), 2) + ' ' + custom_date[mycurrentDate.getDay];
        };
        function sysPaddingZro(lnum, mydgt){
            var clckzro = '';
            for(var i = 0; i < mydgt; i++){
                clckzro += '0';
            }
            return (clckzro + lnum).slice(-mydgt);
        }
    }
    /*computed: {
        ShowTime: function(){
            var date = new Date();
            var h = date.getHours(); // 0 -23
            var m = date.getMinutes(); // 0 - 59
            var s = date.getSeconds(); // 0 - 59

            var time = h + ":" + m + ":" + s;

        }.then(function (ShowTime){
            document.getElementById("MyClock").innerText = time;
            document.getElementById("MyClock").textContent = time;
        })
    }*/
}
</script>