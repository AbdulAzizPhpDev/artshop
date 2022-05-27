var canvas = document.getElementById("canvas");


// Apply multiply blend when drawing datasets
var multiply = {
    beforeDatasetsDraw: function (chart, options, el) {
        chart.ctx.globalCompositeOperation = 'multiply';
    },
    afterDatasetsDraw: function (chart, options) {
        chart.ctx.globalCompositeOperation = 'source-over';
    },
};

// Gradient color - this week

if (canvas) {
    var gradientThisWeek = canvas.getContext('2d').createLinearGradient(0, 0, 0, 150);
    gradientThisWeek.addColorStop(0, '#2ca39f');
    gradientThisWeek.addColorStop(1, '#2ca39f');

// Gradient color - previous week
    var gradientPrevWeek = canvas.getContext('2d').createLinearGradient(0, 0, 0, 150);
    gradientPrevWeek.addColorStop(0, '#29e5df');
    gradientPrevWeek.addColorStop(1, '#29e5df');

}

var config = {
    type: 'line',
    data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
        datasets: [
            {
                label: 'This week',
                data: [24, 18, 16, 18, 24, 36, 28],
                backgroundColor: gradientThisWeek,
                borderColor: 'transparent',
                pointBackgroundColor: '#FFFFFF',
                pointBorderColor: '#FFFFFF',
                lineTension: 0.40,
            },
            {
                label: 'Previous week',
                data: [20, 22, 30, 22, 18, 22, 30],
                backgroundColor: gradientPrevWeek,
                borderColor: 'transparent',
                pointBackgroundColor: '#FFFFFF',
                pointBorderColor: '#FFFFFF',
                lineTension: 0.40,
            }
        ]
    },
    options: {
        elements: {
            point: {
                radius: 0,
                hitRadius: 5,
                hoverRadius: 5
            }
        },
        legend: {
            display: false,
        },
        scales: {
            xAxes: [{
                display: false,
            }],
            yAxes: [{
                display: true,
                ticks: {
                    beginAtZero: true,
                },
            }]
        }
    },
    plugins: [multiply],
};

window.chart = new Chart(canvas, config);
