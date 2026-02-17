<?php
include 'db.php';

// Fetch customers for the dropdown
$customer_sql = "SELECT CustomerID, Name FROM Customers";
$customer_result = $conn->query($customer_sql);

$customers = [];
if ($customer_result->num_rows > 0) {
    while($row = $customer_result->fetch_assoc()) {
        $customers[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Orders</title>
    <link rel="stylesheet" href="order.css">
</head>
<body>
    <div class="container"><a href="index.php"><< Home page</a> </div>
    <h1>Manage Orders</h1>
    <h2>Add Orders</h2>
    <form action="order.php" method="post">
        <input type="hidden" name="action" value="add">
        <label for="order_id">Order ID:</label>
        <input type="number" id="order_id" name="order_id" required>
        <label for="customer_id">Customer ID:</label>
        <input type="number" id="customer_id" name="customer_id" required>
        <label for="order_date">Order Date:</label>
        <input type="date" id="order_date" name="order_date" required>
        <label for="total_amount">Total Amount:</label>
        <input type="number" step="0.01" id="total_amount" name="total_amount" required>
        <label for="payment_status">Payment Status:</label>
        <select id="payment_status" name="payment_status">
            <option value="Pending">Pending</option>
            <option value="Completed">Completed</option>
            <option value="Failed">Failed</option>
        </select>
        <label for="delivery_status">Delivery Status:</label>
        <select id="delivery_status" name="delivery_status">
            <option value="Pending">Pending</option>
            <option value="Shipped">Shipped</option>
            <option value="Delivered">Delivered</option>
        </select>
        <input type="submit" value="Add Order">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'add') {
        $order_id = $_POST['order_id'];
        $customer_id = $_POST['customer_id'];
        $order_date = $_POST['order_date'];
        $total_amount = $_POST['total_amount'];
        $payment_status = $_POST['payment_status'];
        $delivery_status = $_POST['delivery_status'];

        $sql = "INSERT INTO SalesOrders (OrderID, CustomerID, OrderDate, TotalAmount, PaymentStatus, DeliveryStatus)
                VALUES ('$order_id', '$customer_id', '$order_date', '$total_amount', '$payment_status', '$delivery_status')";
        
        if ($conn->query($sql) === TRUE) {
            echo "New order added successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'update') {
        $order_id = $_POST['order_id'];
        $customer_id = $_POST['customer_id'];
        $order_date = $_POST['order_date'];
        $total_amount = $_POST['total_amount'];
        $payment_status = $_POST['payment_status'];
        $delivery_status = $_POST['delivery_status'];

        $sql = "UPDATE SalesOrders SET CustomerID='$customer_id', OrderDate='$order_date', TotalAmount='$total_amount', PaymentStatus='$payment_status', DeliveryStatus='$delivery_status' WHERE OrderID='$order_id'";
        
        if ($conn->query($sql) === TRUE) {
            echo "Order updated successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'delete') {
        $order_id = $_POST['order_id'];

        $sql = "DELETE FROM SalesOrders WHERE OrderID='$order_id'";
        
        if ($conn->query($sql) === TRUE) {
            echo "Order deleted successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    ?>

    <div class="order-list-header">
        <h2>Order List</h2>     
        <input type="text" id="searchInput" placeholder="Search by Order ID...">  
    </div>
    <table id="order-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer ID</th>
                <th>Order Date</th>
                <th>Total Amount</th>
                <th>Payment Status</th>
                <th>Delivery Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM SalesOrders";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row["OrderID"]. "</td>
                            <td>" . $row["CustomerID"]. "</td>
                            <td>" . $row["OrderDate"]. "</td>
                            <td>" . $row["TotalAmount"]. "</td>
                            <td>" . $row["PaymentStatus"]. "</td>
                            <td>" . $row["DeliveryStatus"]. "</td>
                            <td class='actions'>
                                <form action='order.php' method='post'>
                                    <input type='hidden' name='action' value='delete'>
                                    <input type='hidden' name='order_id' value='" . $row["OrderID"] . "'>
                                    <input type='submit' value='Delete'>
                                </form>
                                <form action='order.php' method='post'>
                                    <input type='hidden' name='action' value='update_form'>
                                    <input type='hidden' name='order_id' value='" . $row["OrderID"] . "'>
                                    <input type='hidden' name='customer_id' value='" . $row["CustomerID"] . "'>
                                    <input type='hidden' name='order_date' value='" . $row["OrderDate"] . "'>
                                    <input type='hidden' name='total_amount' value='" . $row["TotalAmount"] . "'>
                                    <input type='hidden' name='payment_status' value='" . $row["PaymentStatus"] . "'>
                                    <input type='hidden' name='delivery_status' value='" . $row["DeliveryStatus"] . "'>
                                    <input type='submit' value='Update'>
                                </form>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No orders found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            var value = this.value.toLowerCase();
            var rows = document.querySelectorAll('#order-table tbody tr');

            rows.forEach(function(row) {
                var orderID = row.querySelector('td:first-child').textContent.toLowerCase();
                if (orderID.indexOf(value) > -1) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'update_form') {
        $order_id = $_POST['order_id'];
        $customer_id = $_POST['customer_id'];
        $order_date = $_POST['order_date'];
        $total_amount = $_POST['total_amount'];
        $payment_status = $_POST['payment_status'];
        $delivery_status = $_POST['delivery_status'];
    ?>
        <h2>Update Order</h2>
        <form action="order.php" method="post">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
            <label for="customer_id">Customer ID:</label>
            <input type="number" id="customer_id" name="customer_id" value="<?php echo $customer_id; ?>" required>
            <label for="order_date">Order Date:</label>
            <input type="date" id="order_date" name="order_date" value="<?php echo $order_date; ?>" required>
            <label for="total_amount">Total Amount:</label>
            <input type="number" step="0.01" id="total_amount" name="total_amount" value="<?php echo $total_amount; ?>" required>
            <label for="payment_status">Payment Status:</label>
            <select id="payment_status" name="payment_status">
                <option value="Pending" <?php if ($payment_status == 'Pending') echo 'selected'; ?>>Pending</option>
                <option value="Completed" <?php if ($payment_status == 'Completed') echo 'selected'; ?>>Completed</option>
                <option value="Failed" <?php if ($payment_status == 'Failed') echo 'selected'; ?>>Failed</option>
            </select>
            <label for="delivery_status">Delivery Status:</label>
            <select id="delivery_status" name="delivery_status">
                <option value="Pending" <?php if ($delivery_status == 'Pending') echo 'selected'; ?>>Pending</option>
                <option value="Shipped" <?php if ($delivery_status == 'Shipped') echo 'selected'; ?>>Shipped</option>
                <option value="Delivered" <?php if ($delivery_status == 'Delivered') echo 'selected'; ?>>Delivered</option>
            </select>
            <input type="submit" value="Update Order">
        </form>
    <?php
    }
    ?>
</body>
</html>
