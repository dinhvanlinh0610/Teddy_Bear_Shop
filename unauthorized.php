<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unauthorized Access</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <link rel="stylesheet" href="styles.css"> <!-- Add your custom styles if needed -->
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="index.php">Gấu Bông Shop</a>
            <!-- Add your navigation links here -->
        </nav>
    </header>

    <section class="container mt-4">
        <div class="alert alert-danger" role="alert">
            <h4 class="alert-heading">Unauthorized Access</h4>
            <p>You do not have permission to access this page.</p>
            <hr>
            <p class="mb-0">Please contact the administrator if you believe this is an error.</p>
        </div>
    </section>

    <footer class="mt-5 text-center">
        <p>&copy; <?php echo date("Y"); ?> Your Website Name</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Add your additional scripts here if needed -->
</body>

</html>
