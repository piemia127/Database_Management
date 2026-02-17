<?php
include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products</title>
    <link rel="stylesheet" href="product.css">
</head>
<body>
    <div class="container"><a href="index.php"><< Home page</a> </div>
    <h1>Manage Products</h1>
    <h2>Add Products</h2>
    <form action="product.php" method="post">
        <input type="hidden" name="action" value="add">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>
        <label for="price">Price:</label>
        <input type="number" step="0.01" id="price" name="price" required>
        <label for="stock_quantity">Stock Quantity:</label>
        <input type="number" id="stock_quantity" name="stock_quantity" required>
        <label for="category_id">Category ID:</label>
        <input type="number" id="category_id" name="category_id" required>
        <input type="submit" value="Add Product">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $action = $_POST['action'];
        if ($action == 'add') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $stock_quantity = $_POST['stock_quantity'];
            $category_id = $_POST['category_id'];

            $sql = "INSERT INTO Products (Name, Description, Price, StockQuantity, CategoryID)
                    VALUES ('$name', '$description', '$price', '$stock_quantity', '$category_id')";
            
            if ($conn->query($sql) === TRUE) {
                echo "New product added successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } elseif ($action == 'update') {
            $product_id = $_POST['product_id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $stock_quantity = $_POST['stock_quantity'];
            $category_id = $_POST['category_id'];

            $sql = "UPDATE Products SET Name='$name', Description='$description', Price='$price', StockQuantity='$stock_quantity', CategoryID='$category_id' WHERE ProductID='$product_id'";
            
            if ($conn->query($sql) === TRUE) {
                echo "Product updated successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } elseif ($action == 'delete') {
            $product_id = $_POST['product_id'];

            $sql = "DELETE FROM Products WHERE ProductID='$product_id'";
            
            if ($conn->query($sql) === TRUE) {
                echo "Product deleted successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
    ?>

    <div class="product-list-header">
        <h2>Product List</h2>
        <input type="text" id="categorySearchInput" placeholder="Search by Category ID">
        <input type="text" id="nameSearchInput" placeholder="Search by Name">
    </div>

    <table>
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Stock Quantity</th>
                <th>Category ID</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="productTable">
            <?php
            $sql = "SELECT * FROM Products";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row["ProductID"]. "</td>
                            <td>" . $row["Name"]. "</td>
                            <td>" . $row["Description"]. "</td>
                            <td>" . $row["Price"]. "</td>
                            <td>" . $row["StockQuantity"]. "</td>
                            <td>" . $row["CategoryID"]. "</td>
                            <td class='actions'>
                                <form action='product.php' method='post'>
                                    <input type='hidden' name='action' value='delete'>
                                    <input type='hidden' name='product_id' value='" . $row["ProductID"] . "'>
                                    <input type='submit' value='Delete'>
                                </form>
                                <form action='product.php' method='post'>
                                    <input type='hidden' name='action' value='update_form'>
                                    <input type='hidden' name='product_id' value='" . $row["ProductID"] . "'>
                                    <input type='hidden' name='name' value='" . $row["Name"] . "'>
                                    <input type='hidden' name='description' value='" . $row["Description"] . "'>
                                    <input type='hidden' name='price' value='" . $row["Price"] . "'>
                                    <input type='hidden' name='stock_quantity' value='" . $row["StockQuantity"] . "'>
                                    <input type='hidden' name='category_id' value='" . $row["CategoryID"] . "'>
                                    <input type='submit' value='Update'>
                                </form>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No products found</td></tr>";
            }
            $conn->close();
            ?>
        </tbody>
    </table>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'update_form') {
        $product_id = $_POST['product_id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $stock_quantity = $_POST['stock_quantity'];
        $category_id = $_POST['category_id'];
    ?>
        <h2>Update Product</h2>
        <form action="product.php" method="post">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $name; ?>" required>
            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?php echo $description; ?></textarea>
            <label for="price">Price:</label>
            <input type="number" step="0.01" id="price" name="price" value="<?php echo $price; ?>" required>
            <label for="stock_quantity">Stock Quantity:</label>
            <input type="number" id="stock_quantity" name="stock_quantity" value="<?php echo $stock_quantity; ?>" required>
            <label for="category_id">Category ID:</label>
            <input type="number" id="category_id" name="category_id" value="<?php echo $category_id; ?>" required>
            <input type="submit" value="Update Product">
        </form>
    <?php
    }
    ?>

    <script>
    function filterTable() {
        var categoryInput, nameInput, filterCategory, filterName, table, tr, td, i, txtValue;
        categoryInput = document.getElementById("categorySearchInput");
        nameInput = document.getElementById("nameSearchInput");
        filterCategory = categoryInput.value.toUpperCase();
        filterName = nameInput.value.toUpperCase();
        table = document.querySelector("table");
        tr = table.getElementsByTagName("tr");

        for (i = 1; i < tr.length; i++) {
            tdCategory = tr[i].getElementsByTagName("td")[5];
            tdName = tr[i].getElementsByTagName("td")[1];
            if (tdCategory && tdName) {
                txtValueCategory = tdCategory.textContent || tdCategory.innerText;
                txtValueName = tdName.textContent || tdName.innerText;
                if (txtValueCategory.toUpperCase().indexOf(filterCategory) > -1 && txtValueName.toUpperCase().indexOf(filterName) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }       
        }
    }

    document.getElementById("categorySearchInput").addEventListener("keyup", filterTable);
    document.getElementById("nameSearchInput").addEventListener("keyup", filterTable);
    </script>
</body>
</html>
