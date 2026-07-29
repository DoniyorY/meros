
document.addEventListener('DOMContentLoaded', function () {
    const chartElement = document.querySelector('#column_chart_datalabel');

    if (!chartElement) {
        return;
    }

    if (typeof ApexCharts === 'undefined') {
        console.error('ApexCharts is not loaded.');

        return;
    }

    const endpoint = "/admin/site/visit-chart";

    const periodLabelElement = document.querySelector(
        '#visitChartPeriodLabel'
    );

    const filterElements = document.querySelectorAll(
        '.visit-chart-filter'
    );

    const primaryColor = getComputedStyle(document.documentElement)
        .getPropertyValue('--vz-primary')
        .trim() || '#405189';

    let currentPeriod = 'current_week';
    let requestController = null;

    const chartOptions = {
        series: [],

        chart: {
            type: 'bar',
            height: 275,
            toolbar: {
                show: false
            },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 450
            },
            fontFamily: 'inherit'
        },

        colors: [
            primaryColor
        ],

        plotOptions: {
            bar: {
                borderRadius: 4,
                columnWidth: '55%',
                dataLabels: {
                    position: 'top'
                }
            }
        },

        dataLabels: {
            enabled: true,
            formatter: function (value) {
                return value > 0
                    ? Number(value).toLocaleString()
                    : '';
            },
            offsetY: -20,
            style: {
                fontSize: '12px',
                fontWeight: 600
            }
        },

        xaxis: {
            categories: [],
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false
            },
            labels: {
                rotate: 0,
                trim: true
            }
        },

        yaxis: {
            min: 0,
            forceNiceScale: true,
            labels: {
                formatter: function (value) {
                    return Math.round(value).toLocaleString();
                }
            }
        },

        grid: {
            strokeDashArray: 3
        },

        tooltip: {
            y: {
                formatter: function (value) {
                    return Number(value).toLocaleString() + ' visits';
                }
            }
        },

        noData: {
            text: 'Loading visits...'
        }
    };

    const chart = new ApexCharts(chartElement, chartOptions);

    chart.render();

    function setLoading(isLoading) {
        chartElement.style.opacity = isLoading ? '0.55' : '1';
        chartElement.style.transition = 'opacity 0.2s ease';

        filterElements.forEach(function (element) {
            element.classList.toggle('disabled', isLoading);
        });
    }

    function setActiveFilter(period) {
        filterElements.forEach(function (element) {
            element.classList.toggle(
                'active',
                element.dataset.period === period
            );
        });
    }

    async function loadVisitChart(period) {
        if (requestController) {
            requestController.abort();
        }

        requestController = new AbortController();

        currentPeriod = period;

        setLoading(true);

        const url = new URL(endpoint, window.location.origin);

        url.searchParams.set('period', period);

        try {
            const response = await fetch(url.toString(), {
                method: 'GET',
                credentials: 'same-origin',
                signal: requestController.signal,
                headers: {
                    'Accept': 'application/json',

                    // Благодаря этому visitTracker не посчитает
                    // загрузку чарта отдельным посещением.
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error(
                    'HTTP error: ' + response.status
                );
            }

            const result = await response.json();

            const isDenseChart = result.categories.length > 14;

            await chart.updateOptions({
                xaxis: {
                    categories: result.categories,
                    labels: {
                        rotate: isDenseChart ? -45 : 0,
                        trim: true
                    }
                },

                dataLabels: {
                    enabled: !isDenseChart
                },

                plotOptions: {
                    bar: {
                        columnWidth: isDenseChart ? '70%' : '55%',
                        borderRadius: isDenseChart ? 2 : 4,
                        dataLabels: {
                            position: 'top'
                        }
                    }
                },

                noData: {
                    text: 'No visits for this period'
                }
            }, false, true);

            await chart.updateSeries(result.series, true);

            if (periodLabelElement) {
                periodLabelElement.textContent = result.periodLabel;
            }

            setActiveFilter(period);
        } catch (error) {
            if (error.name === 'AbortError') {
                return;
            }

            console.error('Visit chart loading error:', error);

            await chart.updateSeries([], true);

            await chart.updateOptions({
                noData: {
                    text: 'Failed to load visit statistics'
                }
            });
        } finally {
            if (period === currentPeriod) {
                setLoading(false);
            }
        }
    }

    filterElements.forEach(function (element) {
        element.addEventListener('click', function (event) {
            event.preventDefault();

            const period = this.dataset.period;

            if (!period || period === currentPeriod) {
                return;
            }

            loadVisitChart(period);
        });
    });

    loadVisitChart(currentPeriod);
});
