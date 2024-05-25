
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
        <div class="product-images" data-src="./images/products/<?php echo $product[0]; ?>_<i>.jpg">
        </div>
        <div class="product-details">
            <h1 class="product-title"><?php echo $product[1] ?></h1>
            <span class="product-price">€<?php echo $product[2] ?></span>
            <p><?php echo $product[3] ?></p>
            <select name="sizes" id="product-sizes">
                <?php foreach( $products as $size ) { ?>
                    <option 
                        <?php if($product[4] === $size[4]) { ?>
                            selected="true"    
                        <?php } ?> 
                    name="<?php echo $size[4];?>" value="<?php echo $size[5];?>"><?php echo $size[4];?> - <?php echo $size[5] ? $size[5] : "none"; ?> available </option>
                <?php } ?>
            </select>
            <span class="no-stock">OUT OF STOCK!</span>
            <button>Add To Cart</button>
        </div>
    </main>
    <script>
        let select = document.getElementById("product-sizes");
        let selectedSize = document.querySelector("#product-sizes option[selected=true]");
        let button = document.querySelector(".product-details button");
        // if the quantity of the selected size is less than one disabled the button and enable the OUT OF STOCK text
        noStock(parseInt(selectedSize.value) < 1);
        select.addEventListener("change", (event) => noStock(parseInt(event.target.value) < 1));
        function noStock(bool) {
            button.disabled = bool;
            document.querySelector(".no-stock").classList[bool ? "add" : "remove"]("active");
        }
        let imageContainer = document.querySelector(".product-images");
        recursiveProductImage(imageContainer.dataset.src, 1);
        async function recursiveProductImage(url, i) {
            let img = await checkImage(url.replace("<i>", i));
            if(!img) return;
            imageContainer.append(img);
            recursiveProductImage(url, ++i);
        }
        async function checkImage(imageSrc) {
            return new Promise((resolve, reject) => {
                let img = new Image();
                img.onload = () => resolve(img);
                img.src = imageSrc;
            });
        }
    </script>
    <?php include("./views/footer.php") ?>
</body>
</html>