@props(['answers'])

<div id="line-chart"></div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const answers = @json($answers);

        const options = {
            chart: {
                height: "100%",
                maxWidth: "100%",
                type: "line",
                fontFamily: "Inter, sans-serif",
                dropShadow: {
                    enabled: false,
                },
                toolbar: {
                    show: false,
                },
            },
            tooltip: {
                enabled: true,
                x: {
                    show: false,
                },
            },
            dataLabels: {
                enabled: false,
            },
            stroke: {
                width: 4,
                curve: 'smooth'
            },
            grid: {
                show: true,
                strokeDashArray: 4,
                padding: {
                    left: 2,
                    right: 2,
                    top: -26
                },
            },
            series: [{
                    name: "Sangat Baik",
                    data: Object.values(answers.daily).map(day => day[3].total),
                    color: "#1C64F2",
                },
                {
                    name: "Baik",
                    data: Object.values(answers.daily).map(day => day[2].total),
                    color: "#16BDCA",
                },
                {
                    name: "Kurang Baik",
                    data: Object.values(answers.daily).map(day => day[1].total),
                    color: "#FDBA8C",
                },
                {
                    name: "Tidak Baik",
                    data: Object.values(answers.daily).map(day => day[0].total),
                    color: "#F05252",
                },
            ],
            legend: {
                show: true
            },
            xaxis: {
                categories: Object.keys(answers.daily),
                labels: {
                    show: true,
                    style: {
                        fontFamily: "Inter, sans-serif",
                        cssClass: 'text-xs font-normal fill-gray-500 dark:fill-gray-400'
                    }
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
            },
            yaxis: {
                show: true,
                labels: {
                    style: {
                        fontFamily: "Inter, sans-serif",
                        cssClass: 'text-xs font-normal fill-gray-500 dark:fill-gray-400'
                    }
                }
            },
        }

        if (document.getElementById("line-chart") && typeof ApexCharts !== 'undefined') {
            const chart = new ApexCharts(document.getElementById("line-chart"), options);
            chart.render();
        }
    });
</script>
