
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include("./views/head.php") ?>
    <link rel="stylesheet" href="./css/shop.css">
    <title>WolfStudioz - Home</title>
</head>

<body>
    <?php include("./views/header.php") ?>
    <?php
        $filters["category"] = isset($_GET["category"]) ? $_GET["category"] : null;
        $filters["size"] = isset($_GET["size"]) ? $_GET["size"] : null;
        $price = isset($_GET["price"]) ? floatval($_GET["price"]) : null;

        $servername = "localhost";
        $username = "root";
        $password = "usbw";
        $database = "gip2";

        $connect = new mysqli($servername, $username, $password, $database);

        if ($connect->connect_error) {
            die("Connectie mislukt: " . $connect->connect_error);
        }

        $sql = "SELECT product_id, name, price, description FROM products WHERE ";
        $categoryQuery = where($filters, 'category');
        $sizeQuery = where($filters, 'size');
        $priceQuery = (isset($price)) ? " price <= {$price} " : " price >= 0;";
        $sql = implode('', array($sql, $categoryQuery, $sizeQuery, $priceQuery));
        echo $sql;

        $result = $connect->query($sql);
        
        $products = $result->fetch_all();
        function where($object, $key) {
            if(isset($object[$key])) {
                return " {$key} = '{$object[$key]}' AND ";
            } else return "";
        }
    ?>
    <main>
        <h1><?php 
                echo $filters["category"];
            ?>
        </h1>
        <div class="producten">
            <?php 
                foreach($products as &$product) {
            ?>
                <div class="product">
                    <img src="./Images/Hoodie1.jpg" alt="plant">
                    <div>
                        <span class="product-title"><?php echo $product[1] ?></span>
                        <!-- <p><?php echo $product[3] ?></p> description not necessary -->
                        <span class="product-price">€<?php echo $product[2] ?></span>
                        <button>Add To Cart</button>
                    </div>
                </div>
            <?php
                }
            ?>
        </div>
    </main>
    <script>
        function filterProducts(keyword) {
            var products = document.querySelectorAll('.producten');
            products.forEach(function(product) {
                var description = product.querySelector('p').innerText.toLowerCase();
                if (description.includes(keyword.toLowerCase())) {
                    product.style.display = 'block';
                } else {
                    product.style.display = 'none';
                }
            });
        }
    </script>
    <?php include("./views/footer.php") ?>
</body>
</html>