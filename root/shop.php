
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include("./views/head.php") ?>
    <link rel="stylesheet" href="./css/shop.css">
    <title>WolfStudioz - Shop</title>
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
        
        $sql = "SELECT p.product_id as product_id, name, price, description, COUNT( * ) AS sizes_count, SUM( s.quantity ) AS total_quantity 
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
    <main>
        <label for="search">
            <input placeholder="search for a product..." value="<?php echo $filters["search"] ?>" type="search" id="search">
            <button><img src="../images/search.svg" alt=""></button>
        </label>
        <div class="filters">
            <label for="category">
                <span>category:</span>
                <select name="category" id="category">
                    <option disabled>select the category</option>
                    <option name="all" value="" selected>all categories</option>
                    <option name="skirts" value="skirts">skirts</option>
                    <option name="dress" value="dress">dress</option>
                    <option name="top" value="top">top</option>
                    <option name="shoes" value="shoes">shoes</option>
                    <option name="pants" value="pants">pants</option>
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
                <a href="product.php?id=<?php echo $product[0]; ?>" class="product">
                    <img src="./images/products/<?php echo $product[0]; ?>_1.jpg" alt="plant">
                    <div>
                        <span class="product-title"><?php echo $product[1] ?></span>
                        <span class="product-price">€<?php echo $product[2] ?></span>
                        <button>Add To Cart</button>
                    </div>
                </a>
            <?php
                }
            ?>
        </div>
    </main>
    <script>
        let searchParams = new URLSearchParams(window.location.search);

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