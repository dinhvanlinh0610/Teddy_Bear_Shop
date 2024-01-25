<?php
include_once('connection/connection.php');

session_start();

// Define variables and initialize with empty values
$username = $password = "";
$error = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate username and password
    if (empty(trim($username)) || empty(trim($password))) {
        $error = "Please enter both username and password.";
    } else {
        $sql = "SELECT * FROM users WHERE username = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $param_username);
            $param_username = $username;
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    if (password_verify($password, $user['password'])) {
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['role'] = $user['role'];
                        header("Location: index.php");
                        exit();
                    } else {
                        $error = "Incorrect password.";
                    }
                } else {
                    $error = "Username not found.";
                }
            } else {
                $error = "Oops! Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gấu Bông Shop</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        .small-form {
            /* background-color: #f8f1f1; */
            max-width: 600px; /* Đặt giá trị tùy chỉnh cho kích thước chiều ngang */
            margin: auto; /* Căn giữa form */
        }
        .footer {
            background-color: #f8f1f1;
            padding-top: 30px;
            padding-bottom: 30px;
        }

        .footer-heading {
            color: #333;
        }

        .subscribe-form {
            margin-top: 20px;
        }
        .footer-heading {
            color: #333;
            font-size: 18px;
            font-weight: 700;
        }

        .list-unstyled li a {
            font-size: 14px;
            color: #555;
            text-decoration: none;
            transition: color 0.3s;
        }

        .list-unstyled li a:hover {
            color: #ff4081;
        }
        .ftco-footer-social {
            display: inline-flex;
            align-items: center;
            list-style: none;
        }
        .ftco-footer-social a {
            font-size: 24px;
            color: #333;
            margin-right: 15px;
            transition: color 0.3s;
        }

        .ftco-footer-social a:hover {
            color: #ff4081;
        }

        .subscribe-form {
            margin-top: 20px;
        }

        .submit {
            background-color: #ff4081;
            color: #fff;
            cursor: pointer;
        }

        .submit:hover {
            background-color: #e6005c;
        }

        .copyright {
            color: #777;
            font-size: 14px;
        }

        .list-unstyled a {
            color: #777;
            font-size: 14px;
            text-decoration: none;
            transition: color 0.3s;
        }

        .list-unstyled a:hover {
            color: #ff4081;
        }
    </style>
</head>

<body>

<?php include('header.php'); ?>

<section class="container mt-4">
    <div class="card small-form">
        <div class="card-body">
            <h2 class="text-center mb-4">Đăng Nhập</h2>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-outline mb-4">
                    <label>Tên Đăng Nhập </label>
                    <input type="text" id="form2Example1" class="form-control" name="username" value="<?php echo $username; ?>" />
                </div>

                <div class="form-outline mb-4">
                    <label>Mật Khẩu</label>
                    <input type="password" id="form2Example2" class="form-control" name="password" />
                </div>

                <div class="row mb-4">
                    <div class="col d-flex justify-content-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="form2Example31" checked />
                            <label class="form-check-label" for="form2Example31"> Remember me </label>
                        </div>
                    </div>

                    <div class="col">
                        <a href="#!">Quên Mật Khẩu?</a>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block mb-4">Sign in</button>

                <div class="text-center">
                    <p>Chưa có tài khoản? <a href="register.php">Đăng Ký</a></p>
                    <p>hoặc đăng nhập với:</p>
                    <button type="button" class="btn btn-link btn-floating mx-1">
                        <i class="fab fa-facebook-f"></i>
                    </button>

                    <button type="button" class="btn btn-link btn-floating mx-1">
                        <i class="fab fa-google"></i>
                    </button>

                    <button type="button" class="btn btn-link btn-floating mx-1">
                        <i class="fab fa-twitter"></i>
                    </button>

                    <button type="button" class="btn btn-link btn-floating mx-1">
                        <i class="fab fa-github"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>


<?php include('footer.php'); ?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Your script includes here -->
</body>

</html>
