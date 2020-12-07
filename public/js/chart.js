$(function() {
    canvas = document.getElementById('myChart').getContext('2d');
    chartDays = JSON.parse(chartDays);
    chartPositiveMarks = JSON.parse(chartPositiveMarks);
    chartNegativeMarks = JSON.parse(chartNegativeMarks);
    chartTasksCount = JSON.parse(chartTasksCount);
    createChart();
});

$('#chart_choice_chart_type').on('change', (e) => {
    let select = $('#chart_choice_chart_type');
    updateChart(select.val());
});

function createChart() {
    chart = new Chart(canvas, {
        type: 'line',
        data: {
            labels: chartDays,
            datasets: [{
                label: '# of items',
                data: chartPositiveMarks,
                backgroundColor: [
                    'rgba(0, 0, 0, 0)',
                ],
                borderColor: [
                    'rgba(0, 123, 255, 1)',
                ],
                pointBorderColor: [
                    'rgba(0, 123, 255, 1)',
                    'rgba(0, 123, 255, 1)',
                    'rgba(0, 123, 255, 1)',
                    'rgba(0, 123, 255, 1)',
                    'rgba(0, 123, 255, 1)',
                ],
                pointBackgroundColor: [
                    'rgba(0, 123, 255, 1)',
                    'rgba(0, 123, 255, 1)',
                    'rgba(0, 123, 255, 1)',
                    'rgba(0, 123, 255, 1)',
                    'rgba(0, 123, 255, 1)',
                ],
                borderWidth: 2,
                lineTension: 0,
            }]
        }
    });
};

function updateChart(id) {
    let params = [];

    switch(id) {
        case '0':
            params = chartNegativeMarks;
            break;
        case '1':
            params = chartPositiveMarks;
            break;
        case '2':
            params = chartTasksCount;
            break;
    }

    chart.data.datasets[0].data = params;
    chart.update();
};