<?php
// Kết nối CSDL
include_once('connection/connection.php');
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
// if (isset($_SESSION['user_id'])) {
//     echo '<a href="logout.php">Logout</a>';
// } else {
//     echo '<a href="login.php">Login</a>';
// }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gấu Bông Shop</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="index.php">Gấu Bông Shop</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Trang Chủ <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Sản Phẩm</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="#">Giỏ Hàng</a>
                    </li> -->
                    <li class="nav-item">
                    <?php if (isset($_SESSION['user_id'])) : ?>
                        <a class="nav-link" href="logout.php">Đăng Xuất</a>
                    <?php else : ?>
                        <a class="nav-link" href="#">Đăng Nhập</a>
                    <?php endif; ?>
                    </li>
                    <li class="nav-item">
                        <a href="cart.php" class="nav-link">Xem Giỏ Hàng</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <section class="container mt-4">
        <!-- Thêm ô nhập liệu và nút tìm kiếm -->
        <div class="search-container">
            <form method="get" action="">
                <input type="text" placeholder="Tìm kiếm sản phẩm" name="search">
                <button type="submit">Tìm kiếm</button>
            </form>
        </div>

        <h2>Danh Sách Sản Phẩm</h2>

        <div id="productCarousel" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <?php
                $sql = "SELECT * FROM products";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $products = $result->fetch_all(MYSQLI_ASSOC);
                    $productsChunked = array_chunk($products, 3);

                    // Tạo indicators
                    for ($i = 0; $i < count($productsChunked); $i++) {
                ?>
                        <li data-target="#productCarousel" data-slide-to="<?php echo $i; ?>"
                            <?php echo $i === 0 ? 'class="active"' : ''; ?>></li>
                <?php
                    }
                }
                ?>
            </ol>
            <div class="carousel-inner">
                <?php
                // Xử lý tìm kiếm
                if (isset($_GET['search']) && !empty($_GET['search'])) {
                    $searchTerm = $_GET['search'];
                    $sql = "SELECT * FROM products WHERE name LIKE '%$searchTerm%' OR description LIKE '%$searchTerm%'";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        $products = $result->fetch_all(MYSQLI_ASSOC);
                        $productsChunked = array_chunk($products, 3);

                        $firstItem = true;
                        foreach ($productsChunked as $chunk) {
                            $activeClass = $firstItem ? 'active' : '';
                ?>
                            <div class="carousel-item <?php echo $activeClass; ?>">
                                <div class="row">
                                    <?php
                                    foreach ($chunk as $row) {
                                    ?>
                                        <div class="col-md-4">
                                            <div class="card">
                                                <a href="product_detail.php?product_id=<?php echo $row['id']; ?>">
                                                    <img src="<?php echo $row['image']; ?>" class="card-img-top"
                                                        alt="<?php echo $row['name']; ?>">
                                                </a>
                                                <div class="card-body">
                                                    <h5 class="card-title"><?php echo $row['name']; ?></h5>
                                                    <p class="card-text"><?php echo $row['description']; ?></p>
                                                    <p class="card-text">$<?php echo $row['price']; ?></p>
                                                    <form method="post" action="cart.php">
                                                        <input type="hidden" name="add_to_cart" value="1">
                                                        <input type="hidden" name="product_id"
                                                            value="<?php echo $row['id']; ?>">
                                                        <button type="submit" class="btn btn-primary">Thêm vào giỏ hàng</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                <?php
                            $firstItem = false;
                        }
                    } else {
                        echo "<p>Không tìm thấy sản phẩm nào.</p>";
                    }
                } else {
                    // Hiển thị tất cả sản phẩm khi không có yêu cầu tìm kiếm
                    $firstItem = true;
                    foreach ($productsChunked as $chunk) {
                        $activeClass = $firstItem ? 'active' : '';
                ?>
                        <div class="carousel-item <?php echo $activeClass; ?>">
                            <div class="row">
                                <?php
                                foreach ($chunk as $row) {
                                ?>
                                    <div class="col-md-4">
                                        <div class="card">
                                            <a href="product_detail.php?product_id=<?php echo $row['id']; ?>">
                                                <img src="<?php echo $row['image']; ?>" class="card-img-top"
                                                    alt="<?php echo $row['name']; ?>">
                                            </a>
                                            <div class="card-body">
                                                <h5 class="card-title"><?php echo $row['name']; ?></h5>
                                                <p class="card-text"><?php echo $row['description']; ?></p>
                                                <p class="card-text">$<?php echo $row['price']; ?></p>
                                                <form method="post" action="cart.php">
                                                    <input type="hidden" name="add_to_cart" value="1">
                                                    <input type="hidden" name="product_id"
                                                        value="<?php echo $row['id']; ?>">
                                                    <button type="submit" class="btn btn-primary">Thêm vào giỏ hàng</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                <?php
                        $firstItem = false;
                    }
                }
                ?>
            </div>
        </div>


        <h2>Danh Sách Sản Phẩm Cao Cấp</h2>
    
    <div id="productCarousel_2" class="carousel slide" data-ride="carousel">
      <ol class="carousel-indicators">
        <?php
        $sql_2 = "SELECT * FROM products where type = 'Cao Cấp'";
        $result_2 = $conn->query($sql_2);

        if ($result_2->num_rows > 0) {
          $products_2 = $result_2->fetch_all(MYSQLI_ASSOC);
          $productsChunked_2 = array_chunk($products_2, 3);

          // Tạo indicators
          for ($i = 0; $i < count($productsChunked_2); $i++) {
        ?>
            <li data-target="#productCarousel_2" data-slide-to="<?php echo $i; ?>" <?php echo $i === 0 ? 'class="active"' : ''; ?>></li>
        <?php
          }
        }
        ?>
      </ol>
      <div class="carousel-inner">
        <?php
        $firstItem_2 = true;
        foreach ($productsChunked_2 as $chunk) {
          $activeClass_2 = $firstItem_2 ? 'active' : '';
        ?>
          <div class="carousel-item <?php echo $activeClass_2; ?>">
            <div class="row">
              <?php
              foreach ($chunk as $row) {
              ?>
                <div class="col-md-4">
                  <div class="card">
                    <a href="product_detail.php?product_id=<?php echo $row['id']; ?>">
                      <img src="<?php echo $row['image']; ?>" class="card-img-top" alt="<?php echo $row['name']; ?>">
                    </a>
                    <div class="card-body">
                      <h5 class="card-title"><?php echo $row['name']; ?></h5>
                      <p class="card-text"><?php echo $row['description']; ?></p>
                      <p class="card-text">$<?php echo $row['price']; ?></p>
                      <form method="post" action="cart.php">
                        <input type="hidden" name="add_to_cart" value="1">
                        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="btn btn-primary">Thêm vào giỏ hàng</button>
                    </form>
                    </div>
                  </div>
                </div>
              <?php
              }
              ?>
            </div>
          </div>
        <?php
          $firstItem_2 = false;
        }
        ?>
      </div>
      
    </div>
    <!-- <a class="carousel-control-prev" href="#productCarousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="carousel-control-next" href="#productCarousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a> -->
      <h2>Danh Sách Sản Phẩm Trung Cấp</h2>
    
    <div id="productCarousel_3" class="carousel slide" data-ride="carousel">
      <ol class="carousel-indicators">
        <?php
        $sql_3 = "SELECT * FROM products where type = 'Trung Cấp'";
        $result_3 = $conn->query($sql_3);

        if ($result_3->num_rows > 0) {
          $products_3 = $result_3->fetch_all(MYSQLI_ASSOC);
          $productsChunked_3 = array_chunk($products_3, 3);

          // Tạo indicators
          for ($i = 0; $i < count($productsChunked_3); $i++) {
        ?>
            <li data-target="#productCarousel_3" data-slide-to="<?php echo $i; ?>" <?php echo $i === 0 ? 'class="active"' : ''; ?>></li>
        <?php
          }
        }
        ?>
      </ol>
      <div class="carousel-inner">
        <?php
        $firstItem_3 = true;
        foreach ($productsChunked_3 as $chunk) {
          $activeClass_3 = $firstItem_3 ? 'active' : '';
        ?>
          <div class="carousel-item <?php echo $activeClass_3; ?>">
            <div class="row">
              <?php
              foreach ($chunk as $row) {
              ?>
                <div class="col-md-4">
                  <div class="card">
                    <a href="product_detail.php?product_id=<?php echo $row['id']; ?>">
                      <img src="<?php echo $row['image']; ?>" class="card-img-top" alt="<?php echo $row['name']; ?>">
                    </a>
                    <div class="card-body">
                      <h5 class="card-title"><?php echo $row['name']; ?></h5>
                      <p class="card-text"><?php echo $row['description']; ?></p>
                      <p class="card-text">$<?php echo $row['price']; ?></p>
                      <form method="post" action="cart.php">
                        <input type="hidden" name="add_to_cart" value="1">
                        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="btn btn-primary">Thêm vào giỏ hàng</button>
                    </form>
                    </div>
                  </div>
                </div>
              <?php
              }
              ?>
            </div>
          </div>
        <?php
          $firstItem_3 = false;
        }
        ?>
      </div>
      
    </div>
    <h2>Danh Sách Sản Phẩm VIP</h2>
    
    <div id="productCarousel_4" class="carousel slide" data-ride="carousel">
      <ol class="carousel-indicators">
        <?php
        $sql_4 = "SELECT * FROM products where type = 'VIP'";
        $result_4 = $conn->query($sql_4);

        if ($result_4->num_rows > 0) {
          $products_4 = $result_4->fetch_all(MYSQLI_ASSOC);
          $productsChunked_4 = array_chunk($products_4, 3);

          // Tạo indicators
          for ($i = 0; $i < count($productsChunked_4); $i++) {
        ?>
            <li data-target="#productCarousel_4" data-slide-to="<?php echo $i; ?>" <?php echo $i === 0 ? 'class="active"' : ''; ?>></li>
        <?php
          }
        }
        ?>
      </ol>
      <div class="carousel-inner">
        <?php
        $firstItem_4 = true;
        foreach ($productsChunked_4 as $chunk) {
          $activeClass_3 = $firstItem_4 ? 'active' : '';
        ?>
          <div class="carousel-item <?php echo $activeClass_3; ?>">
            <div class="row">
              <?php
              foreach ($chunk as $row) {
              ?>
                <div class="col-md-4">
                  <div class="card">
                    <a href="product_detail.php?product_id=<?php echo $row['id']; ?>">
                      <img src="<?php echo $row['image']; ?>" class="card-img-top" alt="<?php echo $row['name']; ?>">
                    </a>
                    <div class="card-body">
                      <h5 class="card-title"><?php echo $row['name']; ?></h5>
                      <p class="card-text"><?php echo $row['description']; ?></p>
                      <p class="card-text">$<?php echo $row['price']; ?></p>
                      <form method="post" action="cart.php">
                        <input type="hidden" name="add_to_cart" value="1">
                        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="btn btn-primary">Thêm vào giỏ hàng</button>
                    </form>
                    </div>
                  </div>
                </div>
              <?php
              }
              ?>
            </div>
          </div>
        <?php
          $firstItem_4 = false;
        }
        ?>
      </div>
      
    </div>
    </section>

    <?php include('footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>
