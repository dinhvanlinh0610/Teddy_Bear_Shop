<style>
    header {
    position: fixed;
    top: 0;
    width: 100%;
    color: #fff; /* Màu chữ của header */
    z-index: 1000; /* Đảm bảo header ở trên cùng của các phần tử khác */
    height: 100px;
 
}
body {
  margin-top: 100px ;
 /* position: relative; */
 /* padding:10px; */
}

</style>
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
                        <a class="nav-link" href="index.php">Trang Chủ <span class="sr-only">(current)</span></a>
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
                    <?php if (isset($_SESSION['user_id'])) : ?>
                        <a href="cart.php" class="nav-link">Xem Giỏ Hàng</a>
                        <!-- <a class="nav-link" href="logout.php">Đăng Xuất</a> -->
                    <?php else : ?>
                        <!-- <a href="cart.php" class="nav-link">Xem Giỏ Hàng</a> -->
                    <?php endif; ?>
                    </li>
                </ul>
            </div>
        </nav>
    </header>