var Charts = function () {

    return {
        //main function to initiate the module

        init: function () {

            Metronic.addResizeHandler(function () {
                 Charts.initPieCharts(); 
            });
            
        },

        initCharts: function () {

            if (!jQuery.plot) {
                return;
            }

            var data = [];
            var totalPoints = 250;

            // random data generator for plot charts

            function getRandomData() {
                if (data.length > 0) data = data.slice(1);
                // do a random walk
                while (data.length < totalPoints) {
                    var prev = data.length > 0 ? data[data.length - 1] : 50;
                    var y = prev + Math.random() * 10 - 5;
                    if (y < 0) y = 0;
                    if (y > 100) y = 100;
                    data.push(y);
                }
                // zip the generated y values with the x values
                var res = [];
                for (var i = 0; i < data.length; ++i) res.push([i, data[i]])
                return res;
            }

            //Basic Chart



            //Interactive Chart

            function chart2() {
                function randValue() {
                    return (Math.floor(Math.random() * (1 + 40 - 20))) + 20;
                }
               
                var visitors = [

                    [1, 16 + randValue()],
                    [2, 17 + randValue()],
                    [3, 18 + randValue()],
                    [4, 19 + randValue()],
                    [5, 20 + randValue()],
                    [6, 21 + randValue()],
                    [7, 14 + randValue()],
                    [8, 24 + randValue()],
                    [9, 25 + randValue()],
                    [10, 26 + randValue()],
                    [11, 27 + randValue()],
                    [12, 31 + randValue()]
                ];

                var plot = $.plot($("#chart_2"), [{
                            data: visitors,
                            label: "Unique Visits",
                            lines: {
                                lineWidth: 1,
                            },
                            shadowSize: 0

                        }
                    ], {
                        series: {
                            lines: {
                                show: true,
                                lineWidth: 2,
                                fill: true,
                                fillColor: {
                                    colors: [{
                                            opacity: 0.05
                                        }, {
                                            opacity: 0.01
                                        }
                                    ]
                                }
                            },
                            points: {
                                show: true,
                                radius: 3,
                                lineWidth: 1
                            },
                            shadowSize: 2
                        },
                        grid: {
                            hoverable: true,
                            clickable: true,
                            tickColor: "#eee",
                            borderColor: "#eee",
                            borderWidth: 1
                        },
                        colors: ["#d12610", "#37b7f3", "#52e136"],
                        xaxis: {
                            ticks: 11,
                            tickDecimals: 0,
                            tickColor: "#eee",
                        },
                        yaxis: {
                            ticks: 11,
                            tickDecimals: 0,
                            tickColor: "#eee",
                        }
                    });


                function showTooltip(x, y, contents) {
                    $('<div id="tooltip">' + contents + '</div>').css({
                        position: 'absolute',
                        display: 'none',
                        top: y + 5,
                        left: x + 15,
                        border: '1px solid #333',
                        padding: '4px',
                        color: '#fff',
                        'border-radius': '3px',
                        'background-color': '#333',
                        opacity: 0.80
                    }).appendTo("body").fadeIn(200);
                }

                var previousPoint = null;
                $("#chart_2").bind("plothover", function (event, pos, item) {
                    $("#x").text(pos.x.toFixed(2));
                    $("#y").text(pos.y.toFixed(2));

                    if (item) {
                        if (previousPoint != item.dataIndex) {
                            previousPoint = item.dataIndex;

                            $("#tooltip").remove();
                            var x = item.datapoint[0].toFixed(2),
                                y = item.datapoint[1].toFixed(2);

                            showTooltip(item.pageX, item.pageY, item.series.label + " of " + x + " = " + y);
                        }
                    } else {
                        $("#tooltip").remove();
                        previousPoint = null;
                    }
                });

                var previousPoint = null;
                
            }

            //Tracking Curves

            chart2();

        },


        
    };

}();