<?php
include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Order Details</title>
    <link rel="stylesheet" href="order_details.css">
</head>
<body>
    <div class="container"><a href="index.php"><< Home page</a> </div>
    <h1>Manage Order Details</h1>
    <h2>Add Order Detail</h2>
    <form action="order_details.php" method="post">
        <input type="hidden" name="action" value="add">
        <label for="order_id">Order ID:</label>
        <input type="number" id="order_id" name="order_id" required>
        <label for="product_id">Product ID:</label>
        <input type="number" id="product_id" name="product_id" required>
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required>
        <label for="price">Price:</label>
        <input type="number" step="0.01" id="price" name="price" required>
        <input type="submit" value="Add Order Detail">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'add') {
        $order_id = $_POST['order_id'];
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        $price = $_POST['price'];

        $sql = "INSERT INTO OrderDetails (OrderID, ProductID, Quantity, Price)
                VALUES ('$order_id', '$product_id', '$quantity', '$price')";
        
        if ($conn->query($sql) === TRUE) {
            echo "Order detail added successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'update') {
        $order_id = $_POST['order_id'];
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        $price = $_POST['price'];

        $sql = "UPDATE OrderDetails SET Quantity='$quantity', Price='$price' WHERE OrderID='$order_id' AND ProductID='$product_id'";
        
        if ($conn->query($sql) === TRUE) {
            echo "Order detail updated successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'delete') {
        $order_id = $_POST['order_id'];
        $product_id = $_POST['product_id'];

        $sql = "DELETE FROM OrderDetails WHERE OrderID='$order_id' AND ProductID='$product_id'";
        
        if ($conn->query($sql) === TRUE) {
            echo "Order detail deleted successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    ?>

    <div class="order-list-header">
        <h2>Order Details List</h2>
        <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Search by Order ID...">
    </div>
    <table id="order-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Product ID</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM OrderDetails";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row["OrderID"]. "</td>
                            <td>" . $row["ProductID"]. "</td>
                            <td>" . $row["Quantity"]. "</td>
                            <td>" . $row["Price"]. "</td>
                            <td class='actions'>
                                <form action='order_details.php' method='post'>
                                    <input type='hidden' name='action' value='delete'>
                                    <input type='hidden' name='order_id' value='" . $row["OrderID"] . "'>
                                    <input type='hidden' name='product_id' value='" . $row["ProductID"] . "'>
                                    <input type='submit' value='Delete'>
                                </form>
                                <form action='order_details.php' method='post'>
                                    <input type='hidden' name='action' value='update_form'>
                                    <input type='hidden' name='order_id' value='" . $row["OrderID"] . "'>
                                    <input type='hidden' name='product_id' value='" . $row["ProductID"] . "'>
                                    <input type='hidden' name='quantity' value='" . $row["Quantity"] . "'>
                                    <input type='hidden' name='price' value='" . $row["Price"] . "'>
                                    <input type='submit' value='Update'>
                                </form>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No order details found</td></tr>";
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
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        $price = $_POST['price'];
    ?>
        <h2>Update Order Detail</h2>
        <form action="order_details.php" method="post">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" value="<?php echo $quantity; ?>" required>
            <label for="price">Price:</label>
            <input type="number" step="0.01" id="price" name="price" value="<?php echo $price; ?>" required>
            <input type="submit" value="Update Order Detail">
        </form>
    <?php
    }
    ?>
</body>
</html>
