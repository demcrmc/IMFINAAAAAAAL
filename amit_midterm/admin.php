<?php
require_once 'connection.php';

$productCRUD = new ProductCRUD();
$categoryCRUD = new CategoryCRUD();
$products = $productCRUD->readProducts();
$categories = $categoryCRUD->readCategories();
$orderCRUD = new OrderCRUD();
$orders = $orderCRUD->readOrders();
if ($_SESSION['user_type'] != 'admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $action = $_POST['action'];

    if ($action === 'complete') {
        $orderCRUD->updateOrderStatus($order_id, 'completed');
    } elseif ($action === 'delete') {
        $orderCRUD->deleteOrder($order_id);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // File upload handling
    $image_url = null;
    if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['image_url']['tmp_name'];
        $file_name = basename($_FILES['image_url']['name']);
        $target_path = "assets/products/" . $file_name;

        if (move_uploaded_file($file_tmp, $target_path)) {
            $image_url = $target_path;
        }
    }

    if (isset($_POST['action']) && $_POST['action'] == 'create') {
        $productCRUD->createProduct(
            $_POST['product_name'],
            $_POST['description'],
            $_POST['price'],
            $_POST['category'],
            $_POST['stock_quantity'],
            $image_url
        );
    } elseif (isset($_POST['action']) && $_POST['action'] == 'update') {
        $productCRUD->updateProduct(
            $_POST['product_id'],
            $_POST['product_name'],
            $_POST['description'],
            $_POST['price'],
            $_POST['category'],
            $_POST['stock_quantity'],
            $image_url
        );
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $productCRUD->deleteProduct($_POST['product_id']);
    }

    if (isset($_POST['action']) && $_POST['action'] == 'create_category') {
        $categoryCRUD->createCategory($_POST['category_name']);
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete_category') {
        $categoryCRUD->deleteCategory($_POST['category_id']);
    } elseif (isset($_POST['action']) && $_POST['action'] == 'update_category') {
        $categoryCRUD->updateCategory($_POST['category_id'], $_POST['category_name']);
    }

    header("Location: admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Admin Panel</title>
</head>

<body class="bg-gray-100 font-sans text-gray-900">
    <div class="container mx-auto p-6">
        <!-- Admin Header -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-semibold text-gray-800">Admin - Manage Products and Categories</h1>
            <a href="logout.php" class="text-gray-600 hover:text-gray-800 text-lg flex items-center">
                Logout <i class="fa-solid fa-right-from-bracket ml-2"></i>
            </a>
        </div>

        <!-- Category Form -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <form action="admin.php" method="POST" class="flex gap-4 items-center">
                <input type="text" name="category_name" placeholder="Category Name" class="w-full p-3 border border-gray-300 rounded-lg" required>
                <button type="submit" name="action" value="create_category" class="bg-gray-600 text-white py-3 px-6 rounded-lg hover:bg-gray-700">Add Category</button>
            </form>
        </div>

        <!-- Categories Table -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <table class="min-w-full table-auto">
                <thead class="text-gray-700 bg-gray-200">
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Category Name</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category): ?>
                    <tr class="border-b">
                        <td class="px-4 py-2"><?= $category->category_id; ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($category->category_name); ?></td>
                        <td class="px-4 py-2">
                            <form action="admin.php" method="POST" style="display:inline;">
                                <input type="hidden" name="category_id" value="<?= $category->category_id; ?>">
                                <button type="submit" name="action" value="delete_category" class="text-red-600 hover:text-red-800">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Product Form -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <form action="admin.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="product_id">
                <input type="text" name="product_name" placeholder="Product Name" class="w-full p-3 border border-gray-300 rounded-lg" required>
                <textarea name="description" placeholder="Description" class="w-full p-3 border border-gray-300 rounded-lg" required></textarea>
                <input type="number" step="0.01" name="price" placeholder="Price" class="w-full p-3 border border-gray-300 rounded-lg" required>
                <select name="category" class="w-full p-3 border border-gray-300 rounded-lg" required>
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $category): ?>
                    <option value="<?= $category->category_id; ?>"><?= htmlspecialchars($category->category_name); ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="number" name="stock_quantity" placeholder="Stock Quantity" class="w-full p-3 border border-gray-300 rounded-lg" required>
                <input type="file" name="image_url" accept="image/*" class="w-full p-3 border border-gray-300 rounded-lg">
                <button type="submit" name="action" value="create" class="bg-gray-600 text-white py-3 px-6 rounded-lg hover:bg-gray-700">Add Product</button>
            </form>
        </div>

        <!-- Products Table -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <table class="min-w-full table-auto">
                <thead class="text-gray-700 bg-gray-200">
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Image</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Description</th>
                        <th class="px-4 py-2">Price</th>
                        <th class="px-4 py-2">Category</th>
                        <th class="px-4 py-2">Stock</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                    <tr class="border-b">
                        <td class="px-4 py-2"><?= $product->product_id; ?></td>
                        <td class="px-4 py-2"><img src="<?= $product->image_url; ?>" alt="Product Image" class="w-16 h-16 object-cover rounded-lg"></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($product->product_name); ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($product->description); ?></td>
                        <td class="px-4 py-2"><?= $product->price; ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($product->category); ?></td>
                        <td class="px-4 py-2"><?= $product->stock_quantity; ?></td>
                        <td class="px-4 py-2">
                            <form action="admin.php" method="POST" style="display:inline;">
                                <input type="hidden" name="product_id" value="<?= $product->product_id; ?>">
                                <button type="submit" name="action" value="delete" class="text-red-600 hover:text-red-800">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Manage Orders -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Manage Orders</h2>
            <table class="min-w-full table-auto">
                <thead class="text-gray-700 bg-gray-200">
                    <tr>
                        <th class="px-4 py-2">Order ID</th>
                        <th class="px-4 py-2">User Name</th>
                        <th class="px-4 py-2">Total Amount</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Payment Method</th>
                        <th class="px-4 py-2">Shipping Address</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $orders = $orderCRUD->readOrders();
                    foreach ($orders as $order):
                        if ($order->user_name !== null && $order->status == 'pending'):
                    ?>
                    <tr class="border-b">
                        <td class="px-4 py-2"><?= htmlspecialchars($order->order_id) ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($order->user_name) ?></td>
                        <td class="px-4 py-2"><?= number_format($order->total_amount, 2) ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars(ucfirst($order->status)) ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($order->payment_method) ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($order->shipping_address) ?></td>
                        <td class="px-4 py-2">
                            <form action="" method="POST" style="display:inline-block;">
                                <input type="hidden" name="order_id" value="<?= htmlspecialchars($order->order_id) ?>">
                                <button type="submit" name="action" value="complete" class="bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700">Complete</button>
                            </form>
                            <form action="" method="POST" style="display:inline-block;">
                                <input type="hidden" name="order_id" value="<?= htmlspecialchars($order->order_id) ?>">
                                <button type="submit" name="action" value="delete" class="text-red-600 hover:text-red-800 py-2 px-4">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
