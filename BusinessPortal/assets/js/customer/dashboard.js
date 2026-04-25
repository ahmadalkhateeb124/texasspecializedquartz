/**
 * customer/dashboard.js — renders the 30-day activity chart.
 * Reads data from <div id="chartData" data-raw='[...]'>
 */
(function () {
    const chartNode = document.getElementById('activityChart');
    if (!chartNode || typeof ApexCharts === 'undefined') return;

    const dataEl = document.getElementById('chartData');
    const raw = dataEl ? JSON.parse(dataEl.dataset.raw || '[]') : [];

    const days = [], counts = [];
    for (let i = 29; i >= 0; i--) {
        const d = new Date();
        d.setDate(d.getDate() - i);
        const key = d.toISOString().split('T')[0];
        days.push(d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
        const f = raw.find(r => r.d === key);
        counts.push(f ? parseInt(f.n) : 0);
    }

    new ApexCharts(chartNode, {
        series: [{ name: 'Orders', data: counts }],
        chart: {
            type: 'area',
            height: 220,
            toolbar: { show: false },
            fontFamily: 'Inter,sans-serif',
        },
        colors: ['#0f766e'],
        fill: {
            type: 'gradient',
            gradient: { shadeIntensity: 1, opacityFrom: .3, opacityTo: .02, stops: [0, 100] },
        },
        stroke: { curve: 'smooth', width: 2.5 },
        dataLabels: { enabled: false },
        xaxis: {
            categories: days,
            tickAmount: 6,
            labels: { style: { colors: '#64748b', fontSize: '11px' } },
            axisBorder: { show: false },
            axisTicks: { show: false },
        },
        yaxis: {
            min: 0,
            tickAmount: 3,
            labels: { style: { colors: '#64748b', fontSize: '11px' } },
        },
        grid: {
            borderColor: '#e2e8f0',
            strokeDashArray: 4,
            padding: { left: 0, right: 0 },
        },
        tooltip: {
            y: { formatter: v => v + ' order' + (v !== 1 ? 's' : '') },
        },
    }).render();
})();
