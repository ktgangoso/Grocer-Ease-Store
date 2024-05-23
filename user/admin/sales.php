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
            $query = "SELECT * FROM `order` WHERE DATE(order_date) = '$currentDate'";
            break;
        case 'week':
            $weekStart = date("Y-m-d", strtotime('monday this week'));
            $query = "SELECT * FROM `order` WHERE order_date >= '$weekStart'";
            break;
        case 'month':
            $monthStart = date("Y-m-01");
            $query = "SELECT * FROM `order` WHERE order_date >= '$monthStart'";
            break;
        default:
            $query = "SELECT * FROM `order`";
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
        $totalIncome += $order['total_price'];
    }

    return $totalIncome;
}

// total income sales report
 
// daily income
// Get the current day, month, and year
$currentDay = date('d');
$currentMonth = date('m');
$currentYear = date('Y');

// Initialize total income for delivered orders for daily, monthly, and weekly calculations
$totalDeliveredIncomeDaily = 0;

// Daily calculation
$select_products_daily = mysqli_query($conn, "SELECT * FROM `order` WHERE DAY(order_date) = $currentDay AND MONTH(order_date) = $currentMonth AND YEAR(order_date) = $currentYear");

while ($row = mysqli_fetch_assoc($select_products_daily)) {
    // Display the orders in the table if needed, or remove this part if not necessary
    // ...

    // Check if the order has the status "Delivered"
    if ($row['updates'] === 'Delivered') {
        // Add the income to the total for delivered orders
        $totalDeliveredIncomeDaily += $row['total_price'];
    }
}

// end of daily income


// weekly income
$currentWeek = date('W');

$totalDeliveredIncomeWeekly = 0;

$select_products_weekly = mysqli_query($conn, "SELECT * FROM `order` WHERE YEARWEEK(order_date) = YEARWEEK(NOW())");

while ($row = mysqli_fetch_assoc($select_products_weekly)) {
    // Display the orders in the table if needed, or remove this part if not necessary
    // ...

    // Check if the order has the status "Delivered"
    if ($row['updates'] === 'Delivered') {
        // Add the income to the total for delivered orders
        $totalDeliveredIncomeWeekly += $row['total_price'];
    }
}

// end of weekly income

// monthly income 
// Get the current month and year
$currentMonth = date('m');
$currentYear = date('Y');

// Initialize total income for delivered orders
$totalDeliveredIncomeMonthly = 0;

// Modify the query to fetch orders from the current month and year
$select_products = mysqli_query($conn, "SELECT * FROM `order` WHERE MONTH(order_date) = $currentMonth AND YEAR(order_date) = $currentYear");

while ($row = mysqli_fetch_assoc($select_products)) {
    // Display the orders in the table
    // ...

    // Check if the order has the status "Delivered"
    if ($row['updates'] === 'Delivered') {
        // Add the income to the total for delivered orders
        $totalDeliveredIncomeMonthly += $row['total_price'];
    }
}

// Now $totalDeliveredIncomeMonthly contains the total income for delivered orders in the current month
// end of monthly income



// total income 
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
?>

<!-- <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        h2 {
            color: #333;
            border-bottom: 2px solid #ccc;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 8px;
        }

        li:last-child {
            font-weight: bold;
        }

        li::before {
            content: '\2022'; 
            color: #333;
            display: inline-block;
            width: 1em;
            margin-left: -1em;
        }
    </style> -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
 

<?php include 'adminnavbar.php'; ?>
<!-- <style>
        .total-income-section {
        background-color: #fff;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 5px;
        margin-top: 20px;
    }

    .total-income-section table {
        width: 100%;
    }

    .total-income-section th, .total-income-section td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .total-income-section tfoot {
        font-weight: bold;
    }

    @media print {
        .no-print {
            display: none;
        }
    }
</style> -->
<div class="container mt-5">
        <div class="row">

        
<!-- daily income -->
    <?php
    // Assuming $daily_orders is an array of daily orders, and $daily_income is the total daily income

    // Pagination settings
    $recordsPerPage = 10;
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $startFrom = ($page - 1) * $recordsPerPage;

    // Get all delivered orders
    $deliveredOrders = array_filter($daily_orders, function ($order) {
        return $order['updates'] == 'Delivered';
    });

    // Sort the delivered orders by date in descending order (latest first)
    usort($deliveredOrders, function ($a, $b) {
        return strtotime($b['order_date']) - strtotime($a['order_date']);
    });

    // Calculate total delivered income
    $totalDeliveredIncomeDaily = array_sum(array_column($deliveredOrders, 'total_price'));

    // Get the orders for the current page
    $currentDeliveredOrders = array_slice($deliveredOrders, $startFrom, $recordsPerPage);

    ?>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body daily-income-section">
                <h2 class="card-title">Daily Income</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $counter = ($page - 1) * $recordsPerPage + 1;
                        foreach ($currentDeliveredOrders as $order) : ?>
                            <tr>
                                <td><?= $counter++ ?></td>
                                <td><?= $order['order_date'] ?></td>
                                <td>₱<?= $order['total_price'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">Total Delivered Income:</td>
                            <td><?= number_format($totalDeliveredIncomeDaily, 2) ?></td>
                        </tr>
                    </tfoot>
                </table>

            </div>
        </div>

            <!-- Print Button -->
            <button class="btn btn-primary" onclick="printDailyIncome()">Print Daily Income</button>
            <br>

            <!-- Download Button -->
            <button class="btn btn-primary" onclick="downloadDailyIncome()">Download Daily Income</button>
            <br>

            <!-- Bootstrap Pagination -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= ceil(count($deliveredOrders) / $recordsPerPage); $i++) : ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>

    </div>
<!-- end of daily income -->





<!-- weekly income -->
    <?php
    // Assuming $weekly_orders is an array of weekly orders, and $weekly_income is the total weekly income

    // Pagination settings
    $recordsPerPage = 10;
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $startFrom = ($page - 1) * $recordsPerPage;

    // Get all delivered orders for the week
    $deliveredWeeklyOrders = array_filter($weekly_orders, function ($order) {
        return $order['updates'] == 'Delivered';
    });

    // Sort the orders by date in descending order (latest first)
    usort($deliveredWeeklyOrders, function ($a, $b) {
        return strtotime($b['order_date']) - strtotime($a['order_date']);
    });

    // Calculate total delivered weekly income
    $totalDeliveredIncomeWeekly = array_sum(array_column($deliveredWeeklyOrders, 'total_price'));

    // Get the orders for the current page
    $currentDeliveredWeeklyOrders = array_slice($deliveredWeeklyOrders, $startFrom, $recordsPerPage);
    ?>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body weekly-income-section">
                <h2 class="card-title">Weekly Income</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $counter = ($page - 1) * $recordsPerPage + 1;
                        foreach ($currentDeliveredWeeklyOrders as $order) : ?>
                            <tr>
                                <td><?= $counter++ ?></td>
                                <td><?= $order['order_date'] ?></td>
                                <td>₱<?= $order['total_price'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">Total Delivered Income:</td>
                            <td>₱<?= number_format($totalDeliveredIncomeWeekly, 2) ?></td>
                        </tr>
                    </tfoot>
                </table>

            </div>
        </div>

            <!-- Print Button -->
            <button class="btn btn-primary" onclick="printWeeklyIncome()">Print Weekly Income</button>
            <br>

            <!-- Download Button -->
            <button class="btn btn-primary" onclick="downloadWeeklyIncome()">Download Weekly Income</button>
            <br>

            <!-- Bootstrap Pagination -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= ceil(count($deliveredWeeklyOrders) / $recordsPerPage); $i++) : ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>


    </div>
<!-- end on weekly income -->





<!-- monthly income -->
    <?php
    // Assuming $monthly_orders is an array of monthly orders, and $monthly_income is the total monthly income

    // Pagination settings
    $recordsPerPage = 10;
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $startFrom = ($page - 1) * $recordsPerPage;

    // Get all delivered orders for the month
    $deliveredMonthlyOrders = array_filter($monthly_orders, function ($order) {
        return $order['updates'] == 'Delivered';
    });

    // Sort the delivered orders by date in descending order (latest first)
    usort($deliveredMonthlyOrders, function ($a, $b) {
        return strtotime($b['order_date']) - strtotime($a['order_date']);
    });

    // Calculate total delivered monthly income
    $totalDeliveredIncomeMonthly = array_sum(array_column($deliveredMonthlyOrders, 'total_price'));

    // Get the orders for the current page
    $currentDeliveredMonthlyOrders = array_slice($deliveredMonthlyOrders, $startFrom, $recordsPerPage);
    ?>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body monthly-income-section">
                <h2 class="card-title">Monthly Income</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $counter = ($page - 1) * $recordsPerPage + 1;
                        foreach ($currentDeliveredMonthlyOrders as $order) : ?>
                            <tr>
                                <td><?= $counter++ ?></td>
                                <td><?= $order['order_date'] ?></td>
                                <td>₱<?= $order['total_price'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">Total Delivered Income:</td>
                            <td><?= number_format($totalDeliveredIncomeMonthly, 2) ?></td>
                        </tr>
                    </tfoot>
                </table>


            </div>
        </div>
            <!-- Print Button -->
            <button class="btn btn-primary" onclick="printMonthlyIncome()">Print Monthly Income</button>
            <br>

            <!-- Download Button -->
            <button class="btn btn-primary" onclick="downloadMonthlyIncome()">Download Monthly Income</button>
            <br>

                <!-- Bootstrap Pagination -->
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= ceil(count($deliveredMonthlyOrders) / $recordsPerPage); $i++) : ?>
                            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>

    </div>
<!-- end of monthly income -->






<!-- total income  -->
    <?php
    // Assuming $total_orders is an array of all orders, and $total_income is the total income

    // Pagination settings
    $recordsPerPage = 10;
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $startFrom = ($page - 1) * $recordsPerPage;

    // Get all delivered orders
    $deliveredOrders = array_filter($total_orders, function ($order) {
        return $order['updates'] == 'Delivered';
    });

    // Sort the delivered orders by date in descending order (latest first)
    usort($deliveredOrders, function ($a, $b) {
        return strtotime($b['order_date']) - strtotime($a['order_date']);
    });

    // Calculate total delivered income
    $totalDeliveredIncome = array_sum(array_column($deliveredOrders, 'total_price'));

    // Get the orders for the current page
    $currentDeliveredOrders = array_slice($deliveredOrders, $startFrom, $recordsPerPage);
    ?>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body total-income-section">
                <h2 class="card-title">Total Income</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $counter = ($page - 1) * $recordsPerPage + 1;
                        foreach ($currentDeliveredOrders as $order) : ?>
                            <tr>
                                <td><?= $counter++ ?></td>
                                <td><?= $order['order_date'] ?></td>
                                <td>₱<?= $order['total_price'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">Total Delivered Income:</td>
                            <td>₱<?= number_format($totalDeliveredIncome, 2) ?></td>
                        </tr>
                    </tfoot>
                </table>



            </div>
        </div>
                <!-- Print Button -->
                <button class="btn btn-primary" onclick="printTotalIncome()">Print Total Income</button>
                <br>

                <!-- Download Button -->
                <button class="btn btn-primary" onclick="downloadTotalIncome()">Download Total Income</button>
                <br>


                <!-- Bootstrap Pagination -->
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= ceil(count($deliveredOrders) / $recordsPerPage); $i++) : ?>
                            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
    </div>
<!-- end of total income -->






        </div>
    </div>


   <!-- ... (Existing code) -->

<script>
// total income print
    function printTotalIncome() {
        // Open a new window for printing
        var printWindow = window.open('', '_blank');

        // Write the HTML content to the new window
        printWindow.document.write('<html><head><title>Total Income Report</title>');
        printWindow.document.write('<link rel="stylesheet" type="text/css" href="path/to/your/styles.css"></head><body>');

        // Append the content of the "Total Income" section to the new window
        var totalIncomeSection = document.querySelector('.total-income-section');
        printWindow.document.write(totalIncomeSection.outerHTML);

        // Close the HTML content and trigger the print
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }
// end of total income print

// total income download
    function downloadTotalIncome() {
        // Get the content of the "Total Income" section
        var totalIncomeSection = document.querySelector('.total-income-section').outerHTML;

        // Create a Blob containing the HTML content
        var blob = new Blob([totalIncomeSection], { type: 'text/html' });

        // Create a link element to trigger the download
        var link = document.createElement('a');
        link.href = window.URL.createObjectURL(blob);
        link.download = 'total_income_report.html';

        // Append the link to the document and trigger the click event
        document.body.appendChild(link);
        link.click();

        // Remove the link from the document
        document.body.removeChild(link);
    }
// end of total income download


// monthly income print

    function printMonthlyIncome() {
        // Open a new window for printing
        var printWindow = window.open('', '_blank');

        // Write the HTML content to the new window
        printWindow.document.write('<html><head><title>Monthly Income Report</title></head><body>');

        // Append the content of the "Monthly Income" section to the new window
        var monthlyIncomeSection = document.querySelector('.monthly-income-section');
        printWindow.document.write(monthlyIncomeSection.outerHTML);

        // Close the HTML content and trigger the print
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }
// end of monthly income

// monthly income download
    function downloadMonthlyIncome() {
        // Get the content of the "Monthly Income" section
        var monthlyIncomeSection = document.querySelector('.monthly-income-section').outerHTML;

        // Create a Blob containing the HTML content
        var blob = new Blob([monthlyIncomeSection], { type: 'text/html' });

        // Create a link element to trigger the download
        var link = document.createElement('a');
        link.href = window.URL.createObjectURL(blob);
        link.download = 'monthly_income_report.html';

        // Append the link to the document and trigger the click event
        document.body.appendChild(link);
        link.click();

        // Remove the link from the document
        document.body.removeChild(link);
    }
// end monthly income download


// weekly income print
    function printWeeklyIncome() {
        // Open a new window for printing
        var printWindow = window.open('', '_blank');

        // Write the HTML content to the new window
        printWindow.document.write('<html><head><title>Weekly Income Report</title></head><body>');

        // Append the content of the "Weekly Income" section to the new window
        var weeklyIncomeSection = document.querySelector('.weekly-income-section');
        printWindow.document.write(weeklyIncomeSection.outerHTML);

        // Close the HTML content and trigger the print
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }
// end of weekly income

// weekly income download
    function downloadWeeklyIncome() {
        // Get the content of the "Weekly Income" section
        var weeklyIncomeSection = document.querySelector('.weekly-income-section').outerHTML;

        // Create a Blob containing the HTML content
        var blob = new Blob([weeklyIncomeSection], { type: 'text/html' });

        // Create a link element to trigger the download
        var link = document.createElement('a');
        link.href = window.URL.createObjectURL(blob);
        link.download = 'weekly_income_report.html';

        // Append the link to the document and trigger the click event
        document.body.appendChild(link);
        link.click();

        // Remove the link from the document
        document.body.removeChild(link);
    }
// end of weekly income download



// daily income print
    function printDailyIncome() {
        // Open a new window for printing
        var printWindow = window.open('', '_blank');

        // Write the HTML content to the new window
        printWindow.document.write('<html><head><title>Daily Income Report</title></head><body>');

        // Append the content of the "Daily Income" section to the new window
        var dailyIncomeSection = document.querySelector('.daily-income-section');
        printWindow.document.write(dailyIncomeSection.outerHTML);

        // Close the HTML content and trigger the print
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }

// end of daily income

// daily income download
    function downloadDailyIncome() {
        // Get the content of the "Daily Income" section
        var dailyIncomeSection = document.querySelector('.daily-income-section').outerHTML;

        // Create a Blob containing the HTML content
        var blob = new Blob([dailyIncomeSection], { type: 'text/html' });

        // Create a link element to trigger the download
        var link = document.createElement('a');
        link.href = window.URL.createObjectURL(blob);
        link.download = 'daily_income_report.html';

        // Append the link to the document and trigger the click event
        document.body.appendChild(link);
        link.click();

        // Remove the link from the document
        document.body.removeChild(link);
    }
// end of daily income download

</script>

<!-- ... (Remaining code) -->



    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

