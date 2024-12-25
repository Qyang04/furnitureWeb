<?php
require_once 'connection.php';

// Check if a specific productID is provided
$productID = $_GET['productID'] ?? null;

if ($productID) {
    $sql = $_db->prepare("
        SELECT 
            pv.variationID,
            p.productName,
            p.productDesc,
            pv.price,
            pv.details
        FROM 
            ProductVariation pv
        INNER JOIN 
            Product p ON pv.productID = p.productID
        WHERE 
            p.productID = :productID
    ");
    $sql->execute(['productID' => $productID]);
    $variations = $sql->fetchAll(PDO::FETCH_ASSOC);

    // Return variations as JSON
    header('Content-Type: application/json');
    echo json_encode($variations);
    exit;
}


// Fetch all products along with their categories for the main listing
$products = $_db->query("
    SELECT 
        p.productID,
        p.productName,
        p.productDesc,
        c.categoryName,
        MIN(pv.price) AS minPrice,
        MAX(pv.price) AS maxPrice
    FROM 
        Product p
    INNER JOIN 
        ProductVariation pv ON p.productID = pv.productID
    INNER JOIN 
        Subcategory s ON p.subcategoryID = s.subcategoryID
    INNER JOIN 
        Category c ON s.categoryID = c.categoryID
    GROUP BY 
        p.productID, p.productName, p.productDesc, c.categoryName
    ORDER BY 
        c.categoryName;
")->fetchAll(PDO::FETCH_ASSOC);
?>

<body>
    <main>
    <div class="container">

    <?php
        $currentCategory = null;

        foreach($products as $row){
            // If the category changes, display the category name
            if ($currentCategory !== $row['categoryName']) {
                if ($currentCategory !== null) {
                    // Close the previous category's product group
                    echo '</div>';
                }
                // Update the current category and start a new group
                $currentCategory = $row['categoryName'];
                echo "<h2 class='category-header'>{$currentCategory}</h2>";
                echo '<div class="product-group">';
            }
    ?>
            <div class="card">
                <div class="image">
                    <img src="/image/test.png" alt=""> 
                </div>
                <div class="caption">
                    <p class="product_name"><?php echo $row["productName"]; ?></p>
                    <p class="price">
                        RM<?php echo number_format($row["minPrice"], 2); ?>
                        - 
                        RM<?php echo number_format($row["maxPrice"], 2); ?>
                    </p>
                </div>
                <button class="add" onclick="showPopup('<?php echo $row['productID']; ?>')">Add to cart</button>

                <!-- Popup Modal(hide by default) -->
                <div id="popup" class="popup">
                    <div class="popup-content">
                        <h2 id="product-name"></h2>
                        <p id="product-description"></p>
                        <div id="product-details"></div>

                        <!-- Quantity Input -->
                        <label for="quantity-input">Quantity:</label>
                        <input type="number" id="quantity-input" min="1" value="1" />

                        <!-- Price Display -->
                        <p id="product-price">Price: RM 0.00</p>
                        <button class="add" onclick="submitCart()">Add to Cart</button>
                        <button id="close-popup">Close</button>
                    </div>
                </div>
            </div>
    <?php
        }
        // Close the last category's product group
        echo '</div>';
    ?>
    </div>
    </main>

    <script src="js/addToCart.js"></script>
</body>
</html>


