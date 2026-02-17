<?php
include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Customers</title>
    <link rel="stylesheet" href="customer.css">
</head>
<body>
    <div class="container"><a href="index.php"><< Home page</a> </div>
    <h1>Manage Customers</h1>
    <h2>Add New Customer</h2>
    <form action="customer.php" method="post">
        <input type="hidden" name="action" value="add">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <label for="phone">Phone Number:</label>
        <input type="text" id="phone" name="phone">
        <label for="address">Address:</label>
        <input type="text" id="address" name="address">
        <label for="registration_date">Registration Date:</label>
        <input type="date" id="registration_date" name="registration_date">
        <label for="customer_type">Customer Type:</label>
        <select id="customer_type" name="customer_type">
            <option value="Individual">Individual</option>
            <option value="Corporate">Corporate</option>
        </select>
        <input type="submit" value="Add Customer">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $action = $_POST['action'];
        if ($action == 'add') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            $registration_date = $_POST['registration_date'];
            $customer_type = $_POST['customer_type'];

            $sql = "INSERT INTO Customers (Name, Email, PhoneNumber, Address, RegistrationDate, CustomerType)
                    VALUES ('$name', '$email', '$phone', '$address', '$registration_date', '$customer_type')";
            
            if ($conn->query($sql) === TRUE) {
                echo "New customer added successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } elseif ($action == 'update') {
            $customer_id = $_POST['customer_id'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            $registration_date = $_POST['registration_date'];
            $customer_type = $_POST['customer_type'];

            $sql = "UPDATE Customers SET Name='$name', Email='$email', PhoneNumber='$phone', Address='$address', RegistrationDate='$registration_date', CustomerType='$customer_type' WHERE CustomerID='$customer_id'";
            
            if ($conn->query($sql) === TRUE) {
                echo "Customer updated successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } elseif ($action == 'delete') {
            $customer_id = $_POST['customer_id'];

            $sql = "DELETE FROM Customers WHERE CustomerID='$customer_id'";
            
            if ($conn->query($sql) === TRUE) {
                echo "Customer deleted successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
    ?>

    <div class="customer-list-header">
        <h2>Customer List</h2>
        <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Search for Customer Name..">
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Address</th>
            <th>Registration Date</th>
            <th>Customer Type</th>
            <th>Actions</th>
        </tr>

        <?php
        $sql = "SELECT * FROM Customers";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["CustomerID"]. "</td>
                        <td>" . $row["Name"]. "</td>
                        <td>" . $row["Email"]. "</td>
                        <td>" . $row["PhoneNumber"]. "</td>
                        <td>" . $row["Address"]. "</td>
                        <td>" . $row["RegistrationDate"]. "</td>
                        <td>" . $row["CustomerType"]. "</td>
                        <td class='actions'>
                            <form action='customer.php' method='post'>
                                <input type='hidden' name='action' value='delete'>
                                <input type='hidden' name='customer_id' value='" . $row["CustomerID"] . "'>
                                <input type='submit' value='Delete'>
                            </form>
                            <form action='customer.php' method='post'>
                                <input type='hidden' name='action' value='update_form'>
                                <input type='hidden' name='customer_id' value='" . $row["CustomerID"] . "'>
                                <input type='hidden' name='name' value='" . $row["Name"] . "'>
                                <input type='hidden' name='email' value='" . $row["Email"] . "'>
                                <input type='hidden' name='phone' value='" . $row["PhoneNumber"] . "'>
                                <input type='hidden' name='address' value='" . $row["Address"] . "'>
                                <input type='hidden' name='registration_date' value='" . $row["RegistrationDate"] . "'>
                                <input type='hidden' name='customer_type' value='" . $row["CustomerType"] . "'>
                                <input type='submit' value='Update'>
                            </form>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No customers found</td></tr>";
        }
        $conn->close();
        ?>
    </table>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'update_form') {
        $customer_id = $_POST['customer_id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $registration_date = $_POST['registration_date'];
        $customer_type = $_POST['customer_type'];
    ?>
        <h2>Update Customer</h2>
        <form action="customer.php" method="post">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $name; ?>" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
            <label for="phone">Phone Number:</label>
            <input type="text" id="phone" name="phone" value="<?php echo $phone; ?>">
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" value="<?php echo $address; ?>">
            <label for="registration_date">Registration Date:</label>
            <input type="date" id="registration_date" name="registration_date" value="<?php echo $registration_date; ?>">
            <label for="customer_type">Customer Type:</label>
            <select id="customer_type" name="customer_type">
                <option value="Individual" <?php if ($customer_type == 'Individual') echo 'selected'; ?>>Individual</option>
                <option value="Corporate" <?php if ($customer_type == 'Corporate') echo 'selected'; ?>>Corporate</option>
            </select>
            <input type="submit" value="Update Customer">
        </form>
    <?php
    }
    ?>
    <script>
    function filterTable() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        table = document.querySelector("table");
        tr = table.getElementsByTagName("tr");

        for (i = 1; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[1]; // 改成第二欄，即Name欄
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
    </script>
</body>
</html>
