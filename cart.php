<?php
require 'base.php';

$_title = 'Cart';
include 'top.php';
?>

<?php
    require_once 'connection.php';

    // Fetch products along with their categories
    $sql = $_db->query("
        SELECT 
            p.productName, 
            p.price, 
            c.categoryName
        FROM 
            Product p
        INNER JOIN 
            Subcategory s ON p.subcategoryID = s.subcategoryID
        INNER JOIN 
            Category c ON s.categoryID = c.categoryID
        ORDER BY c.categoryName
    ")->fetchAll(PDO::FETCH_ASSOC);
?>







<?php
include 'bottom.php';