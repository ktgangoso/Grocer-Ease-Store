<?php
require 'config.php';
if (!empty($_SESSION["user_id"])) {
    $id = $_SESSION["user_id"];
    $result = mysqli_query($conn, "SELECT * FROM acc WHERE user_id = $id");
    $row = mysqli_fetch_assoc($result);

    // Get orders for the current day
    $daily_orders = getOrdersByPeriod('day');

    // Get orders for the current week
    $weekly_orders = getOrdersByPeriod('week');

    // Get orders for the current month
    $monthly_orders = getOrdersByPeriod('month');

    // Get all orders for total income
    $total_orders = getAllOrders();

    // Calculate total income for each period
    $daily_income = calculateTotalIncome($daily_orders);
    $weekly_income = calculateTotalIncome($weekly_orders);
    $monthly_income = calculateTotalIncome($monthly_orders);
    $total_income = calculateTotalIncome($total_orders);
} else {
    header("Location: adminlogin.php");
}

function getOrdersByPeriod($period)
{
    global $conn;

    $currentDate = date("Y-m-d");

    switch ($period) {
        case 'day':
            $query = "SELECT * FROM `order` WHERE DATE(order_date) = '$currentDate' AND updates = 'Delivered'";
            break;
        case 'week':
            $weekStart = date("Y-m-d", strtotime('monday this week'));
            $query = "SELECT * FROM `order` WHERE order_date >= '$weekStart' AND updates = 'Delivered'";
            break;
        case 'month':
            $monthStart = date("Y-m-01");
            $query = "SELECT * FROM `order` WHERE order_date >= '$monthStart' AND updates = 'Delivered'";
            break;
        default:
            $query = "SELECT * FROM `order` WHERE updates = 'Delivered'";
            break;
    }

    $result = mysqli_query($conn, $query);

    $orders = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $orders[] = $row;
    }

    return $orders;
}


function getAllOrders()
{
    global $conn;

    $query = "SELECT * FROM `order`";
    $result = mysqli_query($conn, $query);

    $orders = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $orders[] = $row;
    }

    return $orders;
}

function calculateTotalIncome($orders)
{
    $totalIncome = 0;

    foreach ($orders as $order) {
        // Check if the order is canceled
        if ($order['cancellation_reason'] !== null) {
            // Handle canceled order (if needed)
        } else {
            $totalIncome += $order['total_price'];
        }
    }

    return $totalIncome;
}


// Fetch the customer orders
$select_products = mysqli_query($conn, "SELECT * FROM `order`");

// Initialize total income for delivered orders
$totalDeliveredIncome = 0;

while ($row = mysqli_fetch_assoc($select_products)) {
    // Display the orders in the table

    // ...

    // Check if the order has the status "Delivered"
    if ($row['updates'] === 'Delivered') {
        // Add the income to the total for delivered orders
        $totalDeliveredIncome += $row['total_price'];
    }
}


// logo dynamic

// Fetch the current site name from the database
$resultSiteName = $conn->query("SELECT site_name FROM content WHERE id = 1");

if ($resultSiteName->num_rows > 0) {
    $rowSiteName = $resultSiteName->fetch_assoc();
    $currentSiteName = $rowSiteName['site_name'];
} else {
    $currentSiteName = "Grocer Ease";
}


// icon header dynamic

// Fetch the current icon code from the database
$resultIcon = $conn->query("SELECT icon_code FROM content WHERE id = 1");

if ($resultIcon->num_rows > 0) {
    $rowIcon = $resultIcon->fetch_assoc();
    $currentIconCode = $rowIcon['icon_code'];
} else {
    $currentIconCode = '<i class="ri-shopping-cart-2-fill"></i>'; // Default icon code
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Admin</title>
    <link rel="stylesheet" href="admincss.css">
    <!-- Add this line to include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<style>
    
    /* dashboard admin content style */
    .container {
            max-width: 1200px;
            margin: auto;
        }

        /* Header Styles */
        .header {
            background-color: #3B3B3B;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        .header a {
            margin: 0;
            font-size: 2rem;
            text-decoration: none;
            color: #333;
            font-weight: 700;
        }

        .header i {
            margin-right: 10px;
        }

        .header span {
            font-weight: bold;
        }


        /* Content Styles */
        .content {
            background: #f8f9fa;
            padding: 20px;
            min-height: 80vh;
        }

        .cards {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin-top: 20px;
        }

        .card {
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.3s;
            margin: 10px;
            width: 250px;
            height: 180px;
            background: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: space-around;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.5), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        }

        .box {
            text-align: center;
            padding: 20px;
        }

        .icon-case img {
            width: 80px;
            height: 80px;
        }

        h3 {
            color: #333;
            font-size: 18px;
            margin-bottom: 10px;
        }

        h1 {
            color: #007bff;
            font-size: 36px;
            margin: 0;
        }

        a {
            text-decoration: none;
        }


        /* Responsive styles using Bootstrap */
        @media (max-width: 767px) {
        .container .content {
            position: static;
            margin-top: 0;
        }

        .container .content .cards {
            flex-direction: column;
            align-items: center;
        }

        .card {
        width: 350px;
        margin-left: .5rem;
        margin-top: 4rem;
        }
    }

    /* graph css */


    /* .chart-container {
        height: 300px;
        width: 50%;
    } */

            /* Graph Styles */
            .chart-container {
      background-color: #ffffff;
      border: 1px solid #dee2e6;
      border-radius: 8px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.1), 0 6px 20px 0 rgba(0, 0, 0, 0.1);
    }

    canvas {
      width: 100%;
      height: auto;
    }
    
    .container-fluid{
        margin-top: 5rem;
    }

        /* graph responsive */
</style>

    <body>
    <?php include 'adminnavbar.php'; ?>
    
        <div class="container">
    <div class="header">
        <!-- <h1 class=""><i class="ri-shopping-cart-2-fill "></i> <span>Grocer Ease Store</span></h1> -->
        <!-- <a class="display-4" href="admin.php"><img src="../image/kcj.png" alt="" height="50px" width="250px"></a> -->
        <a class="display-4" href="admin.php"><?php echo $currentIconCode; ?></i> <?php echo $currentSiteName; ?></a>
        <!-- <h1>Welcome, <?php echo $row["fname"]; ?></h1> -->
    </div>
            


            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                        <div class="chart-container">
                            <canvas id="incomeChart"></canvas>
                        </div>
                        </div>
                        
                        <div class="col-md-6">
                        <div class="chart-container">
                            <canvas id="orderChart"></canvas>
                        </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                        <div class="chart-container">
                            <canvas id="dailyIncomeChart"></canvas>
                        </div>
                        </div>

                        <div class="col-md-4">
                        <div class="chart-container">
                            <canvas id="weeklyIncomeChart"></canvas>
                        </div>
                        </div>

                        <div class="col-md-4">
                        <div class="chart-container">
                            <canvas id="monthlyIncomeChart"></canvas>
                        </div>
                        </div>
                    </div>
                </div>


                <div class="cards">

                <a href="adminuser.php" style="text-decoration: none;">
                    <div class="card">
                        <div class="box">
                                <?php
                                $select_rows = mysqli_query($conn, "SELECT * FROM `acc`") or die ('query failed');
                                $row_count = mysqli_num_rows($select_rows);
                                ?>
                            <div class="icon-case">
                                <img src="image/user.png" alt="">
                            </div>
                            <h3>Users</h3>
                            <h1><?php echo $row_count; ?></h1>
                        </div>
                    </div>
                    </a>

                    <a href="adminproduct.php" style="text-decoration: none;">
                    <div class="card">
                        <div class="box">
                            <?php
                            $select_rows = mysqli_query($conn, "SELECT * FROM `product`") or die ('query failed');
                            $row_count = mysqli_num_rows($select_rows);
                            ?>
                        <div class="icon-case">
                            <img src="image/product.png" alt="">
                        </div>
                            <h3>Product</h3>
                            <h1><?php echo $row_count; ?></h1>
                        </div>
                        
                    </div>
                    </a>

                    <a href="orderlist.php" style="text-decoration: none;" >
                    <div class="card">
                        <div class="box">
                        <?php
                            $select_rows = mysqli_query($conn, "SELECT * FROM `order`") or die ('query failed');
                            $row_count = mysqli_num_rows($select_rows);
                        ?>
                        <div class="icon-case">
                            <img src="image/order.png" alt="">
                        </div>
                            <h3>Order List</h3>
                            <h1><?php echo $row_count; ?></h1>
                        </div>
                    </div>
                    </a>

                    <a href="orderlist.php" style="text-decoration: none;">
    <div class="card">
        <div class="box">
            <?php
                $select_rows = mysqli_query($conn, "SELECT cancellation_reason FROM `order` WHERE cancellation_reason IS NOT NULL") or die ('query failed');
                $row_count = mysqli_num_rows($select_rows);
            ?>
            <div class="icon-case">
                <img src="image/order.png" alt="">
            </div>
            <h4>Cancelled Orders</h4>
            <h1><?php echo $row_count; ?></h1>
        </div>
    </div>
</a>

                <!-- daily income -->
                <a href="sales.php" style="text-decoration: none;">
                    <div class="card">
                        <div class="box">
                            <div class="icon-case">
                                <img src="image/sales.png" alt="">
                            </div>
                            <h3>Daily Income</h3>
                            <h1>₱<?= number_format($daily_income, 2) ?></h1>
                        </div>
                    </div>
                </a>

                <a href="sales.php" style="text-decoration: none;">
                    <div class="card">
                        <div class="box">
                            <div class="icon-case">
                                <img src="image/sales.png" alt="">
                            </div>
                            <h3>Weekly Income</h3>
                            <h1>₱<?= number_format($weekly_income, 2) ?></h1>
                        </div>
                    </div>
                </a>

                <a href="sales.php" style="text-decoration: none;">
                    <div class="card">
                        <div class="box">
                            <div class="icon-case">
                                <img src="image/sales.png" alt="">
                            </div>
                            <h3>Monthly Income</h3>
                            <h1>₱<?= number_format($monthly_income, 2) ?></h1>
                        </div>
                    </div>
                </a>


                <a href="sales.php" style="text-decoration: none;">
                    <div class="card">
                        <div class="box">
                            <div class="icon-case">
                                <img src="image/sales.png" alt="">
                            </div>
                            <h3>Total Sales</h3>
                            <h1>₱<?= number_format($totalDeliveredIncome, 2) ?></h1>
                            <!-- <h1>₱<?= number_format($total_income, 2) ?></h1> -->
                            <p></p>
                        </div>
                    </div>
                </a>
                

                </div>
            </div>


            
        </div>



        <script>
// total income chart
    // Get the canvas element
    document.addEventListener('DOMContentLoaded', function () {
    var ctx = document.getElementById('incomeChart').getContext('2d');

    // Prepare data
    var incomeData = {
        labels: ['Daily', 'Weekly', 'Monthly', 'Total'],
        datasets: [{
            label: 'Income',
            backgroundColor: ['rgba(255, 99, 132, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(255, 206, 86, 0.5)', 'rgba(75, 192, 192, 0.5)'],
            borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)'],
            borderWidth: 1,
            data: [<?= $daily_income ?>, <?= $weekly_income ?>, <?= $monthly_income ?>, <?= $total_income ?>],
        }]
    };

    // Create a bar chart
    var incomeChart = new Chart(ctx, {
        type: 'bar',
        data: incomeData,
        options: {
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'right',
                    labels: {
                        boxWidth: 12,
                        padding: 10,
                        generateLabels: function (chart) {
                            var labels = chart.data.labels;
                            var datasets = chart.data.datasets;

                            return labels.map(function (label, index) {
                                var value = datasets[0].data[index];
                                return {
                                    text: `${label}: ₱${value}`,
                                    fillStyle: datasets[0].backgroundColor[index]
                                };
                            });
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Total Income Chart'
                }
            }
        }
    });
});


// order graph

    // Prepare data for the order graph
    var orderData = {
        labels: ['Daily', 'Weekly', 'Monthly', 'Total'],
        datasets: [{
            label: 'Orders',
            backgroundColor: ['rgba(255, 99, 132, 0.5)', 'rgba(255, 205, 86, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(201, 203, 207, 0.5)'],
            borderColor: ['rgba(255, 99, 132, 1)', 'rgba(255, 205, 86, 1)', 'rgba(54, 162, 235, 1)', 'rgba(201, 203, 207, 1)'],
            borderWidth: 2,
            data: [<?= count($daily_orders) ?>, <?= count($weekly_orders) ?>, <?= count($monthly_orders) ?>, <?= count($total_orders) ?>],
        }]
    };

    // Create a bar chart for the order graph
    var orderChart = new Chart(document.getElementById('orderChart').getContext('2d'), {
        type: 'bar',
        data: orderData,
        options: {
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'right',
                    labels: {
                        boxWidth: 12,
                        padding: 10,
                        generateLabels: function (chart) {
                            var labels = chart.data.labels;
                            var datasets = chart.data.datasets;

                            return labels.map(function (label, index) {
                                var value = datasets[0].data[index];
                                return {
                                    text: `${label}: ${value} orders`,
                                    fillStyle: datasets[0].backgroundColor[index]
                                };
                            });
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Order Statistics Chart'
                }
            }
        }
});


// daily income
document.addEventListener('DOMContentLoaded', function () {
    var ctx = document.getElementById('dailyIncomeChart').getContext('2d');

    // Extract dates, incomes, and canceled flags from PHP arrays
    var dates = <?php echo json_encode(array_column($daily_orders, 'order_date')); ?>;
    var incomes = <?php echo json_encode(array_column($daily_orders, 'total_price')); ?>;
    var canceledFlags = <?php echo json_encode(array_column($daily_orders, 'cancellation_reason')); ?>;

    // Extract hours and minutes from date values
    var timeLabels = dates.map(function (date) {
        var d = new Date(date);
        var hours = ('0' + d.getHours()).slice(-2);
        var minutes = ('0' + d.getMinutes()).slice(-2);
        return hours + ':' + minutes;
    });

    // Create an array to store income values, considering canceled orders
    var incomeValues = incomes.map(function (income, index) {
        // Check if the order is canceled
        if (canceledFlags[index] !== null) {
            // Handle canceled order (if needed)
            return 0; // You can set canceled order income to 0 or any other value
        } else {
            return income;
        }
    });

    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: timeLabels, // Use hours and minutes as x-axis labels
            datasets: [{
                label: 'Daily Income',
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                data: incomeValues
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                x: {
                    type: 'category',
                    labels: timeLabels
                },
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false,
                },
                title: {
                    display: true,
                    text: 'Daily Income Chart (By Hours & Minutes)'
                },
                tooltips: {
                    callbacks: {
                        title: function (tooltipItems) {
                            return tooltipItems[0].label;
                        },
                        label: function (tooltipItem) {
                            return `$${tooltipItem.formattedValue}`;
                        }
                    }
                }
            }
        }
    });
});






// weekly income
document.addEventListener('DOMContentLoaded', function () {
    var ctx = document.getElementById('weeklyIncomeChart').getContext('2d');

    // Extract dates, incomes, and canceled flags from PHP arrays
    var dates = <?php echo json_encode(array_column($weekly_orders, 'order_date')); ?>;
    var incomes = <?php echo json_encode(array_column($weekly_orders, 'total_price')); ?>;
    var canceledFlags = <?php echo json_encode(array_column($weekly_orders, 'cancellation_reason')); ?>;

    // Extract days from date values
    var dayLabels = dates.map(function (date) {
        var d = new Date(date);
        return d.toLocaleDateString('en-US', { weekday: 'short' });
    });

    // Create an array to store income values, considering canceled orders
    var incomeValues = incomes.map(function (income, index) {
        // Check if the order is canceled
        if (canceledFlags[index] !== null) {
            // Handle canceled order (if needed)
            return 0; // You can set canceled order income to 0 or any other value
        } else {
            return income;
        }
    });

    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: dayLabels, // Use days as x-axis labels
            datasets: [{
                label: 'Weekly Income',
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                data: incomeValues
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                x: {
                    type: 'category',
                    labels: dayLabels
                },
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false,
                },
                title: {
                    display: true,
                    text: 'Weekly Income Chart'
                },
                tooltips: {
                    callbacks: {
                        title: function (tooltipItems) {
                            return tooltipItems[0].label;
                        },
                        label: function (tooltipItem) {
                            return `₱${tooltipItem.formattedValue}`;
                        }
                    }
                }
            }
        }
    });
});










// monthly income
document.addEventListener('DOMContentLoaded', function () {
    var ctxMonthly = document.getElementById('monthlyIncomeChart').getContext('2d');

    var monthlyDates = <?php echo json_encode(array_map(function($date) { return date('j', strtotime($date)); }, array_column($monthly_orders, 'order_date'))); ?>;
    var monthlyIncomes = <?php echo json_encode(array_column($monthly_orders, 'total_price')); ?>;

    // Create an array to store income values, considering canceled orders
    var incomeValues = monthlyIncomes.map(function (income) {
        // Check if there are canceled orders for the month
        if (canceledOrdersExist()) {
            // Handle canceled orders (if needed)
            return 0; // You can set monthly income to 0 or any other value
        } else {
            return income;
        }
    });

    var monthlyChart = new Chart(ctxMonthly, {
        type: 'bar',
        data: {
            labels: monthlyDates,
            datasets: [{
                label: 'Monthly Income',
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                data: incomeValues
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                x: {
                    type: 'category',
                    labels: monthlyDates
                },
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Monthly Income Chart (By Day)'
                }
            }
        }
    });

    // Function to check if canceled orders exist for the month
    function canceledOrdersExist() {
        // Implement your logic to check for canceled orders in the monthly data
        // For example, you can check the cancellation_reason for any order in the month
        return false; // Return true if there are canceled orders, false otherwise
    }
});

    </script>

    </body>
</html>