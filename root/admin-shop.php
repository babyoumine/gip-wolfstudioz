<!DOCTYPE html>
<html lang="en">
<head>
  	<?php include("./views/head.php") ?>
    <link rel="stylesheet" href="./css/admin-shop.css">
  	<title>WolfStudioz - Admin</title>
</head>

<body>
	<?php include("./views/header.php") ?>
    <?php
        $filters["category"] = isset($_GET["category"]) && $_GET["category"] && $_GET["category"] !== "all" ? $_GET["category"] : null;
        $filters["size"] = isset($_GET["size"]) && $_GET["size"] && $_GET["size"] !== "all" ? $_GET["size"] : null;
        $filters["search"] = isset($_GET["search"]) && $_GET["search"] ? $_GET["search"] : "";
        $orderDescending = isset($_GET["order"]) && $_GET["order"] == "h2l" ? true : false;
        $minprice = isset($_GET["minprice"]) && $_GET["minprice"] > 0 ? floatval($_GET["minprice"]) : null;
        $maxprice = isset($_GET["maxprice"]) && $_GET["maxprice"] > 0 ? floatval($_GET["maxprice"]) : null;
   
        $servername = "localhost";
        $username = "root";
        $password = "usbw";
        $database = "gip2";

        $connect = new mysqli($servername, $username, $password, $database);

        if ($connect->connect_error) {
            die("Connectie mislukt: " . $connect->connect_error);
        }
        // COUNT( * ) AS sizes_count, SUM( s.quantity )
        $sql = "SELECT p.product_id as product_id, name, price, description, category
                FROM products p
                JOIN sizes s ON p.product_id = s.product_id 
                WHERE ";
        $categoryQuery = where($filters, 'category');
        $sizeQuery = where($filters, 'size');
        $minPriceQuery = (isset($minprice)) ? " price >= {$minprice} AND " : " price >= 0 AND ";
        $maxPriceQuery = (isset($maxprice)) ? " price <= {$maxprice} AND " : " price >= 0 AND ";
        $searchQuery = " ( name LIKE '%{$filters['search']}%' OR description LIKE '%{$filters['search']}%' ) ";
        $orderQuery = $orderDescending ? "DESC" : "ASC"; 
        $sql = implode('', array($sql, $categoryQuery, $sizeQuery, $minPriceQuery, $maxPriceQuery, $searchQuery, " GROUP BY p.product_id ORDER BY price {$orderQuery}"));
        // echo $sql;
        $result = $connect->query($sql);
        
        $products = $result->fetch_all();
        function where($object, $key) {
            if(isset($object[$key])) {
                return " {$key} = '{$object[$key]}' AND ";
            } else return "";
        }
    ?>

    <form action="admin-shop-post.php?insert=true" method="post" class="product" id="insert-product">
        <div>
            <h1>Create product:</h1>
            <label for="product-title">
                <span>name</span>
                <input type="text" id="product-title" placeholder="product name" name="name" value="">
            </label>
            <label for="product-price">
                <span>price (€)</span>
                <input type="number" min="1" max="500" step="0.01" id="product-price" placeholder="price" name="price" value="1">
            </label>
            <label for="product-description">
                <span>description</span>
                <textarea id="product-description" placeholder="product description" name="description"></textarea>
            </label>
            <label for="product-category">
                <span>category</span>
                <select name="category" id="product-category">
                    <option selected disabled>select the category</option>
                    <option name="sweaters" value="sweaters">sweaters</option>
                    <option name="skirts" value="skirts">skirts</option>
                    <option name="shirts" value="shirts">shirts</option>
                    <option name="jeans" value="jeans">jeans</option>
                </select>
            </label>
            <hr>
            <h1>Product size</h1>

            <label for="product-size">
                <span>size</span>
                <select name="size" id="product-size">
                    <option disabled>select the size</option>
                    <option name="small" value="small">small</option>
                    <option name="medium" value="medium">medium</option>
                    <option name="large" value="large">large</option>
                </select>
            </label>
            <label for="product-quantity">
                <span>quantity</span>
                <input type="number" min="0" max="100" step="1" id="product-quantity" placeholder="choose the quantity" name="quantity" value="0">
            </label>
            <div class="product-buttons">
                <button name="save" type="submit">create</button>
                <button name="cancel" type="reset">cancel</button>
            </div>
        </div>
    </form>
    
    <main>
        
        <label for="search">
            <input placeholder="search for a product..." value="<?php echo $filters["search"] ?>" type="search" id="search">
            <button><img src="../images/search.svg"></button>
        </label>
        <div class="filters">
            <label for="max-price">
                <span>add product:</span>
                <button name="create"><img src="../images/plus.svg"></button>
            </label>
            <label for="category">
                <span>category:</span>
                <select name="category" id="category">
                    <option disabled>select the category</option>
                    <option name="all" value="" selected>all categories</option>
                    <option name="sweaters" value="sweaters">sweaters</option>
                    <option name="skirts" value="skirts">skirts</option>
                    <option name="shirts" value="shirts">shirts</option>
                    <option name="jeans" value="jeans">jeans</option>
                </select>
            </label>
            <label for="size">
                <span>size:</span>    
                <select name="size" id="size">
                    <option disabled>select the size</option>
                    <option name="all" value="" selected >all sizes</option>
                    <option name="small" value="small">small</option>
                    <option name="medium" value="medium">medium</option>
                    <option name="large" value="large">large</option>
                </select>
            </label>
            <label for="order">
                <span>order:</span>
                <select name="order" id="order">
                    <option disabled>select the order</option>
                    <option name="l2h" value="l2h">price: low to high</option>
                    <option name="h2l" value="h2l">price: high to low</option>
                </select>
            </label>
            <!-- <label for="min-price">
                <span>minimum price:</span>
                <div class="price-range">
                        <input id="min-price" type="range" min=1 max=500 value="<?php echo $minprice ? $minprice : "0" ?>">
                        <span><?php echo $minprice ? $minprice : "0" ?>€</span>
                </div>
            </label> -->
            <label for="max-price">
                <span>maximum price:</span>
                <div class="price-range">
                    <input id="max-price" type="range" min=1 max=500 value="<?php echo $maxprice ? $maxprice : "500" ?>">
                    <span><?php echo $maxprice ? $maxprice : "500" ?>€</span>
                </div>
            </label>
        </div>
        <div class="products">
            
            <?php 
                foreach($products as &$product) {
            ?>
                <form action="admin-shop-post.php?insert=false&id=<?php echo $product[0]; ?>" method="post" class="product">
                    <img src="./images/products/<?php echo $product[0]; ?>_1.jpg">
                    <div>
                        <label for="">
                            <span>product id: <?php echo $product[0]; ?></span>
                        </label>
                        <label for="product-title">
                            <span>name</span>
                            <input type="text" id="product-title" name="name" value="<?php echo $product[1] ?>">
                        </label>
                        <label for="product-price">
                            <span>price (€)</span>
                            <input type="number" step="0.01" id="product-price" name="price" value="<?php echo $product[2] ?>">
                        </label>
                        <label for="product-description">
                            <span>description</span>
                            <textarea id="product-description" name="description"><?php echo $product[3] ?></textarea>
                        </label>
                        <label for="product-category">
                            <span>category</span>
                            <select name="category" id="category">
                                <option selected disabled>select the category</option>
                                <option name="sweaters" value="sweaters">sweaters</option>
                                <option name="skirts" value="skirts">skirts</option>
                                <option name="shirts" value="shirts">shirts</option>
                                <option name="jeans" value="jeans">jeans</option>
                            </select>
                        </label>
                        <div class="product-buttons">
                            <button name="save" type="submit">save</button>
                            <button name="edit-sizes" type="button">edit sizes</button>
                            <button name="delete" type="button"><img src="../images/delete.svg"></button>
                        </div>
                    </div>
                </form>
            <?php
                }
            ?>
        </div>
    </main>
    <script>
        let searchParams = new URLSearchParams(window.location.search);

        let createButton = document.querySelector("button[name=create]");
        let insertProduct = document.getElementById("insert-product");
        createButton.addEventListener("click", () => insertProduct.classList.add("active"));
        insertProduct.addEventListener("reset", () => insertProduct.classList.remove("active"));
        
        let searchInput = document.getElementById("search");
        let searchButton = document.querySelector("label[for=search] button");
        searchButton.addEventListener("click", () => {
            changeSearchParams("search", searchInput.value);
        });

        let maxPriceInput = document.getElementById("max-price")
        maxPriceInput.addEventListener("input", (event) => {
            // if(parseInt(minPriceInput.value) > parseInt(event.target.value)) return event.target.value = parseInt(minPriceInput.value) + 1;
            document.querySelector('.filters label[for="max-price"] div span').innerText = `${event.target.value}€`
        });
        maxPriceInput.addEventListener("change", (event) => changeSearchParams("maxprice", event.target.value));

        // let minPriceInput = document.getElementById("min-price")
        // minPriceInput.addEventListener("input", (event) => {
        //     if(parseInt(maxPriceInput.value) < parseInt(event.target.value)) return event.target.value = parseInt(maxPriceInput.value) - 1;
        //     document.querySelector('.filters label[for="min-price"] div span').innerText = `${event.target.value}€`
        // });
        // minPriceInput.addEventListener("change", (event) => changeSearchParams("minprice", event.target.value));
        
        setDefaults("size", "all");
        setDefaults("category", "all");
        setDefaults("order", "l2h");
        
        function setDefaults(name, defaultValue) {
            let select = document.getElementById(name);
            let selected = select.querySelector(`option[name=${searchParams.get(name) || defaultValue}]`);
            if(selected) {
                selected.selected = true;
            } else changeSearchParams(name, defaultValue);
            select.addEventListener("change", (event) => changeSearchParams(name, event.target.value))
        }
        
        function changeSearchParams(parameter, value) {
            searchParams.set(parameter, value);
            window.location.search = searchParams.toString();
        }
    </script>

	<?php include("./views/footer.php") ?>
</body>
</html>