fetch('/subscriptions-data')
    .then(response => response.json())
    .then(data => {
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        let totalSubscription = new Array(12).fill(0);
        let activeSubscription = new Array(12).fill(0);
        let cancelSubscription = new Array(12).fill(0);
        let pendingSubscription = new Array(12).fill(0);
        data.subscriptions.forEach(item => {
            const monthIndex = item.month - 1;
            totalSubscription[monthIndex] += parseInt(item.total);
            activeSubscription[monthIndex] += parseInt(item.active);
            cancelSubscription[monthIndex] += parseInt(item.canceled);
            pendingSubscription[monthIndex] += parseInt(item.pending);
        });
        var options = {
            series: [
                { name: "Total Subscription", type: "line", data: totalSubscription },
                { name: "Active Subscription", type: "area", data: activeSubscription },
                { name: "Cancel Subscription", type: "bar", data: cancelSubscription },
                { name: "Pending Subscription", type: "bar", data: pendingSubscription }
            ],
            chart: {
                height: 350,
                type: 'line',
                toolbar: { show: false }
            },
            stroke: {
                curve: 'smooth',
                width: [2, 2, 1, 1]
            },
            fill: {
                opacity: [1, 0.1, 1, 1]
            },
            markers: {
                size: [4, 4, 4, 4],
                hover: { size: 6 }
            },
            xaxis: {
                categories: months,
                title: {
                    text: 'Months'
                }
            },
            yaxis: {
                title: {
                    text: 'Subscriptions Count'
                }
            },
            colors: ["#6674A1", "#3BC2B0", "#F3846D", "#F9C66F"],
            tooltip: {
                shared: true,
                intersect: false
            }
        };
        var chart = new ApexCharts(document.querySelector("#projects-overview-chart"), options);
        chart.render();
    });