"use strict";
var BarChart = function (data) {
    var eChart_3 = echarts.init(document.getElementById('e_chart_3'));
    var option3 = {
        tooltip: {
            show: true,
            trigger: 'axis',
            borderRadius: 6,
            padding: 6,
            axisPointer: {
                lineStyle: {
                    width: 0,
                }
            },
        },
        xAxis: {
            type: 'category',
            boundaryGap: false,
            data: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            axisLine: {
                show: false
            },
            axisTick: {
                show: false
            },
            axisLabel: {
                textStyle: {
                    color: '#5e7d8a'
                }
            }
        },
        yAxis: {
            type: 'value',
            axisLine: {
                show: false
            },
            axisTick: {
                show: false
            },
            axisLabel: {
                textStyle: {
                    color: '#5e7d8a'
                }
            },
            splitLine: {
                lineStyle: {
                    color: '#eaecec',
                }
            }
        },
        grid: {
            top: '3%',
            left: '3%',
            right: '3%',
            bottom: '3%',
            containLabel: true
        },
        series: [{
            data: data,
            type: 'line',
            symbolSize: 6,
            itemStyle: {
                color: '#7a5449',
            },
            lineStyle: {
                color: '#7a5449',
                width: 2,
            },
            areaStyle: {
                color: '#7a5449',
            },
        }]
    };
    eChart_3.setOption(option3);
    eChart_3.resize();
}

function PieChart(data) {
    var myChart = echarts.init(document.getElementById('e_chart_2'));
    var option1 = {
        title: {
            text: "Server",
            left: 'center'
        },
        tooltip: {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        legend: {
            bottom: '0',
            left: 'center',
            data: data.legend
        },
        series: [{
            type: 'pie',
            name: 'Radius',
            radius: '60%',
            center: ['50%', '50%'],
            data: data.series,
            label: {
                normal: {
                    formatter: '{b}\n{d}%'
                },

            }
        }]
    };
    myChart.setOption(option1);
    myChart.resize();
}
var char, chart, charts;

function bgColor(data) {
    if (data >= 0 && data <= 24) {
        return 'bg-success';
    } else if (data >= 25 && data <= 49) {
        return 'bg-info';
    } else if (data >= 50 && data <= 74) {
        return 'bg-warning';
    } else if (data >= 75 && data <= 100) {
        return 'bg-danger';
    }
}

function dataBite(data) {
    var type = ['bps', 'kbps', 'Mbps', 'Gbps', 'Tbps'];
    if (data == 0) {
        return '0bps';
    } else {
        var i = parseInt(Math.floor(Math.log(data) / Math.log(1024)));
        return parseFloat((data / Math.pow(1024, i)).toFixed(2)) + type[i];
    }
}

function DataCPU() {
    $.ajax({
        url: "./api/",
        headers: {
            "Api": $.cookie("BSK_API"),
            "Key": $.cookie("BSK_KEY"),
            "Accept": "application/json"
        },
        method: "GET",
        dataType: "JSON",
        data: "traffic",
        success: function (traffic) {
            var loads = '';
            $.each(traffic.data.cpu, function (i, mcpu) {
                var series = char.series[0],
                    shift = series.data.length > 19;
                char.series[i].addPoint(mcpu.data, true, shift);
                loads += '<div class="progress-lb-wrap mb-5">';
                loads += '<label class="progress-label">' + mcpu.name + '</label>';
                loads += '<div class="progress">';
                loads += '<div class="progress-bar progress-bar-striped ' + bgColor(mcpu.data[1]) + '" role="progressbar" style="width: ' + mcpu.data[1] + '%" aria-valuenow="' + mcpu.data[1] + '" aria-valuemin="0" aria-valuemax="100">' + mcpu.data[1] + '%';
                loads += '</div>';
                loads += '</div>';
                loads += '</div>';
            });
            $('#load-cpu').html(loads);
        },
        cache: false
    });
}

function DataTX() {
    $.ajax({
        url: "./api/",
        headers: {
            "Api": $.cookie("BSK_API"),
            "Key": $.cookie("BSK_KEY"),
            "Accept": "application/json"
        },
        method: "GET",
        dataType: "JSON",
        data: "traffic",
        success: function (traffic) {
            $.each(traffic.data.tx, function (i, tx) {
                var series = chart.series[0],
                    shift = series.data.length > 19;
                chart.series[i].addPoint(tx.data, true, shift);
            });
        },
        cache: false
    });
}

function DataRX() {
    $.ajax({
        url: "./api/",
        headers: {
            "Api": $.cookie("BSK_API"),
            "Key": $.cookie("BSK_KEY"),
            "Accept": "application/json"
        },
        method: "GET",
        dataType: "JSON",
        data: "traffic",
        success: function (traffic) {
            $.each(traffic.data.rx, function (i, rx) {
                var series = charts.series[0],
                    shift = series.data.length > 19;
                charts.series[i].addPoint(rx.data, true, shift);
            });
        },
        cache: false
    });
}

function LineCPU(data) {
    char = new Highcharts.Chart({
        chart: {
            type: 'spline',
            height: 300,
            renderTo: 'trafik-pc',
            animation: Highcharts.svg,
            events: {
                load: function () {
                    setInterval(function () {
                        DataCPU();
                    }, 3000);
                }
            }
        },
        title: {
            text: 'CPU'
        },
        xAxis: {
            type: 'datetime',
            tickPixelInterval: 150,
            maxZoom: 20 * 1000,
        },
        yAxis: {
            max: 100,
            minPadding: 0.1,
            maxPadding: 0.1,
            title: {
                text: null
            },
            labels: {
                formatter: function () {
                    return this.value + "%";
                }
            },
        },
        tooltip: {
            headerFormat: '<b>{series.name}</b><br />',
            pointFormat: '{point.x:%H:%M:%S}<br/>CPU: {point.y}%'
        },
        series: data
    });
}

function LineTX(data) {
    chart = new Highcharts.Chart({
        chart: {
            type: 'spline',
            height: 300,
            renderTo: 'trafik-tx',
            animation: Highcharts.svg,
            events: {
                load: function () {
                    setInterval(function () {
                        DataTX();
                    }, 3000);
                }
            }
        },
        title: {
            text: 'Transmiter'
        },
        xAxis: {
            type: 'datetime',
            tickPixelInterval: 150,
            maxZoom: 20 * 1000,
        },
        yAxis: {
            minPadding: 0.1,
            maxPadding: 0.1,
            title: {
                text: null
            },
            labels: {
                formatter: function () {
                    return dataBite(this.value);
                },
            },
        },
        tooltip: {
            formatter: function () {
                return '<b>' + this.series.name + '</b><br/>' +
                    Highcharts.dateFormat('%H:%M:%S',
                        new Date(this.x)) +
                    '<br/>TX: ' + dataBite(this.y);
            },
        },
        series: data
    });
}

function LineRX(data) {
    charts = new Highcharts.Chart({
        chart: {
            type: 'spline',
            height: 300,
            renderTo: 'trafik-rx',
            animation: Highcharts.svg,
            events: {
                load: function () {
                    setInterval(function () {
                        DataRX();
                    }, 3000);
                }
            }
        },
        title: {
            text: 'Receiver'
        },
        xAxis: {
            type: 'datetime',
            tickPixelInterval: 150,
            maxZoom: 20 * 1000,
        },
        yAxis: {
            minPadding: 0.1,
            maxPadding: 0.1,
            title: {
                text: null
            },
            labels: {
                formatter: function () {
                    return dataBite(this.value);
                },
            },
        },
        tooltip: {
            formatter: function () {
                return '<b>' + this.series.name + '</b><br/>' +
                    Highcharts.dateFormat('%H:%M:%S',
                        new Date(this.x)) +
                    '<br/>RX: ' + dataBite(this.y);
            },
        },
        series: data
    });
}

function Circle(data) {
    $('div[id^=server-]').easyPieChart({
        easing: 'easeOutBounce',
        size: 200,
        scaleColor: '#7a5449',
        trackColor: '#f5f5f6',
        barColor: function (percent) {
            return (percent < 50 ? '#5cb85c' : percent < 85 ? '#f0ad4e' : '#cb3935');
        },
        onStep: function (from, to, percent) {
            $(this.el).find('.percent').text(Math.round(percent));
        }
    });
}

function Servers() {
    $.ajax({
        url: "./api/",
        headers: {
            "Api": $.cookie("BSK_API"),
            "Key": $.cookie("BSK_KEY"),
            "Accept": "application/json"
        },
        method: "GET",
        dataType: "JSON",
        data: "server",
        success: function (result) {
            $.each(result.data, function (i, val) {
                if (i != 'temp') {
                    $('#server-' + i).data('easyPieChart').update(val.percent);
                } else if (val.percent) {
                    $('#temp_server').html('<div class="progress-bar progress-bar-striped progress-bar-animated ' + bgColor(val.percent) + '" role="progressbar" style="width: ' + val.percent + '%" aria-valuenow="' + val.percent + '">' + val.percent + '%');
                }
                $('.' + i + '_title').html(val.title);
                $('.' + i + '_output').text(val.output ? val.output.join('\n') : '');
            });
        }
    });
}

function Traffic() {
    $.ajax({
        url: "./api/",
        headers: {
            "Api": $.cookie("BSK_API"),
            "Key": $.cookie("BSK_KEY"),
            "Accept": "application/json"
        },
        method: "GET",
        dataType: "JSON",
        data: {
            "traffic": "false"
        },
        success: function (result) {
            LineTX(result.data.tx);
            LineRX(result.data.rx);
            LineCPU(result.data.cpu);
        }
    });
}

function Radius() {
    $.ajax({
        url: "./api/",
        headers: {
            "Api": $.cookie("BSK_API"),
            "Key": $.cookie("BSK_KEY"),
            "Accept": "application/json"
        },
        method: "GET",
        dataType: "JSON",
        data: "radius",
        success: function (radius) {
            if (radius.status) {
                $.each(radius.data, function (i, show) {
                    $('.show_' + i).html(show);
                });
            }
        }
    });
}
var History = function () {
    var html = '';
    $.ajax({
        url: "./api/",
        headers: {
            "Api": $.cookie("BSK_API"),
            "Key": $.cookie("BSK_KEY"),
            "Accept": "application/json"
        },
        method: "GET",
        dataType: "JSON",
        data: "log",
        success: function (histori) {
            if (histori.status) {
                $.each(histori.data, function (i, logs) {
                    html += '<tr>';
                    html += '<td>' + logs.time + '</td>';
                    html += '<td>' + logs.username + '</td>';
                    html += '<td>' + logs.info + '</td></tr>';
                });
            } else {
                html += '';
            }
            $('#show_log').html(html);
        }
    });
}
var Charts = function () {
    $.ajax({
        url: "./api/",
        headers: {
            "Api": $.cookie("BSK_API"),
            "Key": $.cookie("BSK_KEY"),
            "Accept": "application/json"
        },
        method: "GET",
        dataType: "JSON",
        data: "pie",
        success: function (chart) {
            if (chart.status) {
                PieChart(chart.data);
            }
        }
    });
}
var Income = function () {
    $.ajax({
        url: "./api/",
        headers: {
            "Api": $.cookie("BSK_API"),
            "Key": $.cookie("BSK_KEY"),
            "Accept": "application/json"
        },
        method: "GET",
        dataType: "JSON",
        data: "income",
        success: function (come) {
            if (come.status) {
                BarChart(come.data);
            }
        }
    });
}



function update() {
    Radius();
    $('#day').html(moment().format('dddd'));
    $('#clock').html(moment().format('H:mm:ss'));
    $('#date').html(moment().format('D. MMMM YYYY'));
}
History();
Charts();
Circle();
Income();
Servers();
Traffic();

function Refreh() {
    History();
    Charts();
    Income();
}
setInterval(function () {
    Servers();
}, 3000);
setInterval(update, 1000);
setInterval(Refreh, 10000);