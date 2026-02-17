<?php
include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Customer Interactions</title>
    <link rel="stylesheet" href="interaction.css">
</head>
<body>
    <div class="container"><a href="index.php"><< Home page</a> </div>
    <h1>Manage Customer Interactions</h1>
    <h2>Add Interaction</h2>
    <form action="interaction.php" method="post">
        <input type="hidden" name="action" value="add">
        <label for="customer_id">Customer ID:</label>
        <input type="number" id="customer_id" name="customer_id" required>
        <label for="date">Date:</label>
        <input type="date" id="date" name="date" required>
        <label for="mode">Mode:</label>
        <select id="mode" name="mode">
            <option value="Email">Email</option>
            <option value="Phone">Phone</option>
            <option value="In-Person">In-Person</option>
        </select>
        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>
        <input type="submit" value="Add Interaction">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'add') {
        $customer_id = $_POST['customer_id'];
        $date = $_POST['date'];
        $mode = $_POST['mode'];
        $description = $_POST['description'];

        $sql = "INSERT INTO CustomerInteractions (CustomerID, Date, Mode, Description)
                VALUES ('$customer_id', '$date', '$mode', '$description')";
        
        if ($conn->query($sql) === TRUE) {
            echo "New interaction added successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'update') {
        $interaction_id = $_POST['interaction_id'];
        $customer_id = $_POST['customer_id'];
        $date = $_POST['date'];
        $mode = $_POST['mode'];
        $description = $_POST['description'];

        $sql = "UPDATE CustomerInteractions SET CustomerID='$customer_id', Date='$date', Mode='$mode', Description='$description' WHERE InteractionID='$interaction_id'";
        
        if ($conn->query($sql) === TRUE) {
            echo "Interaction updated successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'delete') {
        $interaction_id = $_POST['interaction_id'];

        $sql = "DELETE FROM CustomerInteractions WHERE InteractionID='$interaction_id'";
        
        if ($conn->query($sql) === TRUE) {
            echo "Interaction deleted successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    ?>

    <div class="interaction-list-header">
        <h2>Customer Interaction List</h2>
        <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Search for Customer ID..">
    </div>

    <table>
        <tr>
            <th>Interaction ID</th>
            <th>Customer ID</th>
            <th>Date</th>
            <th>Mode</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>

        <?php
        $sql = "SELECT * FROM CustomerInteractions";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["InteractionID"]. "</td>
                        <td>" . $row["CustomerID"]. "</td>
                        <td>" . $row["Date"]. "</td>
                        <td>" . $row["Mode"]. "</td>
                        <td>" . $row["Description"]. "</td>
                        <td class='actions'>
                            <form action='interaction.php' method='post'>
                                <input type='hidden' name='action' value='delete'>
                                <input type='hidden' name='interaction_id' value='" . $row["InteractionID"] . "'>
                                <input type='submit' value='Delete'>
                            </form>
                            <form action='interaction.php' method='post'>
                                <input type='hidden' name='action' value='update_form'>
                                <input type='hidden' name='interaction_id' value='" . $row["InteractionID"] . "'>
                                <input type='hidden' name='customer_id' value='" . $row["CustomerID"] . "'>
                                <input type='hidden' name='date' value='" . $row["Date"] . "'>
                                <input type='hidden' name='mode' value='" . $row["Mode"] . "'>
                                <input type='hidden' name='description' value='" . $row["Description"] . "'>
                                <input type='submit' value='Update'>
                            </form>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No interactions found</td></tr>";
        }
        ?>
    </table>

    <script>
    function filterTable() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        table = document.querySelector("table");
        tr = table.getElementsByTagName("tr");

        for (i = 1; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[1]; // 修改這裡為第二列 (Customer ID)
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

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'update_form') {
        $interaction_id = $_POST['interaction_id'];
        $customer_id = $_POST['customer_id'];
        $date = $_POST['date'];
        $mode = $_POST['mode'];
        $description = $_POST['description'];
    ?>
        <h2>Update Interaction</h2>
        <form action="interaction.php" method="post">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="interaction_id" value="<?php echo $interaction_id; ?>">
            <label for="customer_id">Customer ID:</label>
            <input type="number" id="customer_id" name="customer_id" value="<?php echo $customer_id; ?>" required>
            <label for="date">Date:</label>
            <input type="date" id="date" name="date" value="<?php echo $date; ?>" required>
            <label for="mode">Mode:</label>
            <select id="mode" name="mode">
                <option value="Email" <?php if ($mode == 'Email') echo 'selected'; ?>>Email</option>
                <option value="Phone" <?php if ($mode == 'Phone') echo 'selected'; ?>>Phone</option>
                <option value="In-Person" <?php if ($mode == 'In-Person') echo 'selected'; ?>>In-Person</option>
            </select>
            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?php echo $description; ?></textarea>
            <input type="submit" value="Update Interaction">
        </form>
    <?php
    }
    ?>
</body>
</html>
