<?php
include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Service Requests</title>
    <link rel="stylesheet" href="service_request.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container"><a href="index.php"><< Home page</a> </div>
    <h1>Manage Service Requests</h1>
    <h2>Add Service Request</h2>
    <form action="service_request.php" method="post">
        <input type="hidden" name="action" value="add">
        <label for="customer_id">Customer ID:</label>
        <input type="number" id="customer_id" name="customer_id" required>
        <label for="product_id">Product ID:</label>
        <input type="number" id="product_id" name="product_id" required>
        <label for="issue_description">Issue Description:</label>
        <textarea id="issue_description" name="issue_description" required></textarea>
        <label for="request_date">Request Date:</label>
        <input type="date" id="request_date" name="request_date" required>
        <label for="resolution_date">Resolution Date:</label>
        <input type="date" id="resolution_date" name="resolution_date">
        <label for="status">Status:</label>
        <select id="status" name="status">
            <option value="Open">Open</option>
            <option value="In Progress">In Progress</option>
            <option value="Closed">Closed</option>
        </select>
        <input type="submit" value="Add Service Request">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'add') {
        $customer_id = $_POST['customer_id'];
        $product_id = $_POST['product_id'];
        $issue_description = $_POST['issue_description'];
        $request_date = $_POST['request_date'];
        $resolution_date = $_POST['resolution_date'] ? $_POST['resolution_date'] : 'NULL';
        $status = $_POST['status'];

        $sql = "INSERT INTO ServiceRequests (CustomerID, ProductID, IssueDescription, RequestDate, ResolutionDate, Status)
                VALUES ('$customer_id', '$product_id', '$issue_description', '$request_date', 
                " . ($resolution_date == 'NULL' ? "NULL" : "'$resolution_date'") . ", '$status')";
        
        if ($conn->query($sql) === TRUE) {
            echo "New service request added successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'update') {
        $request_id = $_POST['request_id'];
        $customer_id = $_POST['customer_id'];
        $product_id = $_POST['product_id'];
        $issue_description = $_POST['issue_description'];
        $request_date = $_POST['request_date'];
        $resolution_date = $_POST['resolution_date'] ? $_POST['resolution_date'] : 'NULL';
        $status = $_POST['status'];

        $sql = "UPDATE ServiceRequests SET CustomerID='$customer_id', ProductID='$product_id', IssueDescription='$issue_description', RequestDate='$request_date', ResolutionDate=" . ($resolution_date == 'NULL' ? "NULL" : "'$resolution_date'") . ", Status='$status' WHERE RequestID='$request_id'";
        
        if ($conn->query($sql) === TRUE) {
            echo "Service request updated successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'delete') {
        $request_id = $_POST['request_id'];

        $sql = "DELETE FROM ServiceRequests WHERE RequestID='$request_id'";
        
        if ($conn->query($sql) === TRUE) {
            echo "Service request deleted successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    ?>

    <div class="service-request-list-header">
        <h2>Service Request List</h2>
            <input type="text" id="searchInput" placeholder="Search by Customer ID...">
    </div>
    <table id="service-request-table">
        <thead>
            <tr>
                <th>Request ID</th>
                <th>Customer ID</th>
                <th>Product ID</th>
                <th>Issue Description</th>
                <th>Request Date</th>
                <th>Resolution Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM ServiceRequests";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row["RequestID"]. "</td>
                            <td>" . $row["CustomerID"]. "</td>
                            <td>" . $row["ProductID"]. "</td>
                            <td>" . $row["IssueDescription"]. "</td>
                            <td>" . $row["RequestDate"]. "</td>
                            <td>" . $row["ResolutionDate"]. "</td>
                            <td>" . $row["Status"]. "</td>
                            <td class='actions'>
                                <form action='service_request.php' method='post'>
                                    <input type='hidden' name='action' value='delete'>
                                    <input type='hidden' name='request_id' value='" . $row["RequestID"] . "'>
                                    <input type='submit' value='Delete'>
                                </form>
                                <form action='service_request.php' method='post'>
                                    <input type='hidden' name='action' value='update_form'>
                                    <input type='hidden' name='request_id' value='" . $row["RequestID"] . "'>
                                    <input type='hidden' name='customer_id' value='" . $row["CustomerID"] . "'>
                                    <input type='hidden' name='product_id' value='" . $row["ProductID"] . "'>
                                    <input type='hidden' name='issue_description' value='" . $row["IssueDescription"] . "'>
                                    <input type='hidden' name='request_date' value='" . $row["RequestDate"] . "'>
                                    <input type='hidden' name='resolution_date' value='" . $row["ResolutionDate"] . "'>
                                    <input type='hidden' name='status' value='" . $row["Status"] . "'>
                                    <input type='submit' value='Update'>
                                </form>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No service requests found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <script>
        $(document).ready(function(){
            $("#searchInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#service-request-table tbody tr").filter(function() {
                    $(this).toggle($(this).find("td:eq(1)").text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'update_form') {
        $request_id = $_POST['request_id'];
        $customer_id = $_POST['customer_id'];
        $product_id = $_POST['product_id'];
        $issue_description = $_POST['issue_description'];
        $request_date = $_POST['request_date'];
        $resolution_date = $_POST['resolution_date'];
        $status = $_POST['status'];
    ?>
        <h2>Update Service Request</h2>
        <form action="service_request.php" method="post">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="request_id" value="<?php echo $request_id; ?>">
            <label for="customer_id">Customer ID:</label>
            <input type="number" id="customer_id" name="customer_id" value="<?php echo $customer_id; ?>" required>
            <label for="product_id">Product ID:</label>
            <input type="number" id="product_id" name="product_id" value="<?php echo $product_id; ?>" required>
            <label for="issue_description">Issue Description:</label>
            <textarea id="issue_description" name="issue_description" required><?php echo $issue_description; ?></textarea>
            <label for="request_date">Request Date:</label>
            <input type="date" id="request_date" name="request_date" value="<?php echo $request_date; ?>" required>
            <label for="resolution_date">Resolution Date:</label>
            <input type="date" id="resolution_date" name="resolution_date" value="<?php echo $resolution_date; ?>">
            <label for="status">Status:</label>
            <select id="status" name="status">
                <option value="Open" <?php if ($status == 'Open') echo 'selected'; ?>>Open</option>
                <option value="In Progress" <?php if ($status == 'In Progress') echo 'selected'; ?>>In Progress</option>
                <option value="Closed" <?php if ($status == 'Closed') echo 'selected'; ?>>Closed</option>
            </select>
            <input type="submit" value="Update Service Request">
        </form>
    <?php
    }
    ?>
</body>
</html>
