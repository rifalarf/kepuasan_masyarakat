@props(['data'])

@php
    $chartId = 'pie-chart-' . uniqid();
@endphp

<div id="{{ $chartId }}"></div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const data = @json($data);

        const getChartOptions = () => {
            return {
                series: data.series,
                colors: data.colors,
                chart: {
                    height: 420,
                    width: "100%",
                    type: "pie",
                },
                stroke: {
                    colors: ["white"],
                    lineCap: "",
                },
                plotOptions: {
                    pie: {
                        labels: {
                            show: true,
                        },
                        size: "100%",
                        dataLabels: {
                            offset: -25
                        }
                    },
                },
                labels: data.labels,
                dataLabels: {
                    enabled: true,
                    style: {
                        fontFamily: "Inter, sans-serif",
                    },
                },
                legend: {
                    position: "bottom",
                    fontFamily: "Inter, sans-serif",
                },
                yaxis: {
                    labels: {
                        formatter: function(value) {
                            return value + "%"
                        },
                    },
                },
                xaxis: {
                    labels: {
                        formatter: function(value) {
                            return value + "%"
                        },
                    },
                    axisTicks: {
                        show: false,
                    },
                    axisBorder: {
                        show: false,
                    },
                },
            }
        }

        if (document.getElementById("{{ $chartId }}") && typeof ApexCharts !== 'undefined') {
            const chart = new ApexCharts(document.getElementById("{{ $chartId }}"), getChartOptions());
            chart.render();
        }
    });
</script>
