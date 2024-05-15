
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include("./views/head.php") ?>
    <link rel="stylesheet" href="./css/product.css">
    <title>WolfStudioz - Product</title>
</head>

<body>
    <?php include("./views/header.php") ?>
    <?php
        // get id from parameters (?id=number)
        $productId = isset($_GET["id"]) && $_GET["id"] ? $_GET["id"] : null;
        
        $servername = "localhost";
        $username = "root";
        $password = "usbw";
        $database = "gip2";

        $connect = new mysqli($servername, $username, $password, $database);

        if ($connect->connect_error) {
            die("Connectie mislukt: " . $connect->connect_error);
        }
        
        // put id in mysql
        $sql = "SELECT p.product_id AS product_id, name, price, description, size, quantity
                FROM products p 
                JOIN sizes s ON p.product_id = s.product_id 
                WHERE p.product_id = " . $_GET['id']; 
                
        $result = $connect->query($sql);
        $products = $result->fetch_all();
        // get first result
        $product = $products[0];
    ?>
    <main>
        <div class="product-images">
            <img src="./images/products/<?php echo $product[0]; ?>_1.jpg" alt="plant">
        </div>
        <div class="product-details">
            <h1 class="product-title"><?php echo $product[1] ?></h1>
            <span class="product-price">€<?php echo $product[2] ?></span>
            <p><?php echo $product[3] ?></p>
            <select name="sizes" id="product-sizes">
                <option value="big">BIG</option>
            </select>
            <button>Add To Cart</button>
        </div>
    </main>
    <script>
        let searchParams = new URLSearchParams(window.location.search);

        let priceInput = document.querySelector(".filters .price-range input")
        priceInput.addEventListener("input", (event) => document.querySelector(".filters .price-range span").innerText = `${event.target.value}€`);
        priceInput.addEventListener("change", (event) => changeSearchParams("price", event.target.value));
        
        let sizeSelect = document.getElementById("size");
        let selectedSize = sizeSelect.querySelector(`option[name=${searchParams.get("size") || "all"}]`);
        if(selectedSize) {
            selectedSize.selected = true;
        } else changeSearchParams("size", "all");
        sizeSelect.addEventListener("change", (event) => changeSearchParams("size", event.target.value))
        let categorySelect = document.getElementById("category");
        let selectedCategory = categorySelect.querySelector(`option[name=${searchParams.get("category") || "all"}]`);
        if(selectedCategory) {
            selectedCategory.selected = true;
        } else changeSearchParams("category", "all");
        categorySelect.addEventListener("change", (event) => changeSearchParams("category", event.target.value))
        
        function changeSearchParams(parameter, value) {
            searchParams.set(parameter, value);
            window.location.search = searchParams.toString();
        }
    </script>
    <?php include("./views/footer.php") ?>
</body>
</html>