<?php
require_once 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$productCRUD = new ProductCRUD();
$cartCRUD = new CartCRUD();
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['product_id'])) {
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        if (isset($_POST['action']) && $_POST['action'] === 'add_to_cart') {
            $cartCRUD->addToCart($user_id, $_POST['product_id'], $quantity);
            header("Location: index.php");
            exit;
        } elseif (isset($_POST['action']) && $_POST['action'] === 'buy_now') {
            $cartCRUD->buyNow($user_id, $_POST['product_id'], $quantity);
            header("Location: confirmation.php");
            exit;
        }
    }
}

$products = $productCRUD->readProducts();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Hulom Midterm - Products</title>
</head>

<body class="bg-gray-100 text-gray-800">

    <!-- Header -->
    <header>
        <nav class="bg-gray-800 text-white p-4 flex justify-between items-center">
            <div class="text-2xl font-semibold">Josuan Shop</div>
            <ul class="flex space-x-6">
                <li><a href="index.php" class="hover:text-gray-300">Products</a></li>
                <li><a href="cart.php" class="hover:text-gray-300">Cart</a></li>
            </ul>
            <div class="cart">
                <a href="logout.php" class="flex items-center space-x-2 hover:text-gray-300">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Logout</span>
                </a>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="py-8 px-4">
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($products as $product): ?>
                <div class="product-card bg-white p-4 rounded-lg shadow-lg transition-transform transform hover:scale-105">
                    <img src="<?= htmlspecialchars($product->image_url); ?>"
                        alt="<?= htmlspecialchars($product->product_name); ?>" class="w-full h-64 object-cover rounded-lg mb-4">
                    <div class="product-info">
                        <h3 class="text-xl font-semibold text-gray-900"><?= htmlspecialchars($product->product_name); ?></h3>
                        <p class="text-gray-700 text-lg mt-2">$<?= number_format($product->price, 2); ?></p>
                        <div class="product-actions mt-4 space-y-2">
                            <!-- Add to Cart Form -->
                            <form action="index.php" method="POST" class="flex items-center space-x-2">
                                <input type="hidden" name="product_id" value="<?= $product->product_id; ?>">
                                <input type="number" name="quantity" value="1" min="1" class="w-16 p-2 border border-gray-300 rounded-md" required>
                                <button type="submit" name="action" value="add_to_cart" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition">Add to Cart</button>
                            </form>
                            <!-- Buy Now Form -->
                            <form action="index.php" method="POST" class="flex items-center space-x-2">
                                <input type="hidden" name="product_id" value="<?= $product->product_id; ?>">
                                <input type="number" name="quantity" value="1" min="1" class="w-16 p-2 border border-gray-300 rounded-md" required>
                                <button type="submit" name="action" value="buy_now" class="bg-gray-900 text-white px-4 py-2 rounded-md hover:bg-gray-800 transition">Buy Now</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>
    </main>

</body>

</html>
