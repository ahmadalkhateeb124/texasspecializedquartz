/**
 * admin/dashboard.js — renders the weekly orders bar chart + availability donut.
 * Reads data from two hidden <div>s in the DOM.
 */
(function () {
    if (typeof ApexCharts === 'undefined') return;

    const ordersNode = document.getElementById('chartOrders');
    const ordersRaw  = JSON.parse(document.getElementById('ordersChartData')?.dataset.raw || '[]');

    if (ordersNode) {
        const last7 = [];
        for (let i = 6; i >= 0; i--) {
            const d = new Date();
            d.setDate(d.getDate() - i);
            last7.push(d.toISOString().split('T')[0]);
        }
        const labels = last7.map(d => new Date(d).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
        const counts = last7.map(d => {
            const f = ordersRaw.find(r => r.d === d);
            return f ? parseInt(f.n) : 0;
        });

        new ApexCharts(ordersNode, {
            series: [{ name: 'Orders', data: counts }],
            chart: { type: 'bar', height: 260, toolbar: { show: false }, fontFamily: 'Inter,sans-serif' },
            colors: ['#2563eb'],
            plotOptions: { bar: { borderRadius: 6, columnWidth: '50%' } },
            dataLabels: { enabled: false },
            xaxis: {
                categories: labels,
                axisBorder: { show: false }, axisTicks: { show: false },
                labels: { style: { colors: '#64748b', fontSize: '12px' } },
            },
            yaxis: {
                labels: { style: { colors: '#64748b', fontSize: '12px' } },
                tickAmount: 4, min: 0,
            },
            grid: { borderColor: '#e2e8f0', strokeDashArray: 4, padding: { left: 0, right: 0 } },
            tooltip: { y: { formatter: v => v + ' order' + (v !== 1 ? 's' : '') } },
            fill: {
                type: 'gradient',
                gradient: { shade: 'light', type: 'vertical', gradientToColors: ['#60a5fa'], stops: [0, 100] },
            },
        }).render();
    }

    const availNode = document.getElementById('chartAvail');
    const availRaw  = JSON.parse(document.getElementById('availChartData')?.dataset.raw || '[]');

    if (availNode && availRaw.length > 0) {
        const labelMap = {
            'Available in store': 'Available',
            'Reserved': 'Reserved',
            'Sold Out': 'Sold Out',
        };
        new ApexCharts(availNode, {
            series: availRaw.map(r => parseInt(r.n)),
            labels: availRaw.map(r => labelMap[r.availability] || r.availability),
            chart: { type: 'donut', height: 260, fontFamily: 'Inter,sans-serif' },
            colors: ['#10b981', '#f59e0b', '#ef4444'],
            plotOptions: {
                pie: {
                    donut: {
                        size: '62%',
                        labels: {
                            show: true,
                            total: { show: true, label: 'Products', fontSize: '12px', color: '#64748b' },
                        },
                    },
                },
            },
            dataLabels: { enabled: true, formatter: (_, opts) => opts.w.config.series[opts.seriesIndex] },
            legend: { position: 'bottom', fontSize: '12px' },
            stroke: { width: 2 },
        }).render();
    }
})();
