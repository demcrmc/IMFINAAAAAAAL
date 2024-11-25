<?php
require_once 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone_number = $_POST['phone_number'];
    $address_line1 = $_POST['address_line1'];
    $address_line2 = $_POST['address_line2'];
    $city = $_POST['city'];

    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        $userCRUD = new UserCRUD();
        try {
            $userCRUD->createUser($email, $username, $password, $first_name, $last_name, $phone_number, $address_line1, $address_line2, $city);
            $success_message = "Registration successful! You can now log in.";
        } catch (Exception $e) {
            $error_message = "An error occurred. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Register</title>
</head>

<body class="bg-gray-100 text-gray-800">

    <!-- Header (optional) -->
    <header>
        <nav class="bg-gray-800 text-white p-4">
            <div class="flex justify-between items-center">
                <div class="text-2xl font-semibold">Josuan Shop</div>
                <ul class="flex space-x-6">
                    <a href="index.php" class="hover:text-gray-300">Products</a>
                    <a href="cart.php" class="hover:text-gray-300">Cart</a>
                </ul>
                <div class="cart">
                    <a href="logout.php" class="flex items-center space-x-2 hover:text-gray-300">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </nav>
    </header>

    <!-- Register Container -->
    <div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-lg mt-10">
        <h2 class="text-3xl font-semibold text-gray-900 text-center mb-6">Create an Account</h2>

        <!-- Display Messages -->
        <?php if (!empty($error_message)) : ?>
            <p class="text-red-600 text-center mb-4"><?= htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
        <?php if (!empty($success_message)) : ?>
            <p class="text-green-600 text-center mb-4"><?= htmlspecialchars($success_message); ?></p>
        <?php endif; ?>

        <!-- Registration Form -->
        <form action="register.php" method="POST">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <!-- Personal Information Fields -->
                    <div>
                        <label for="email" class="block text-gray-700">Email:</label>
                        <input type="email" id="email" name="email" required class="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-500">
                    </div>
                    <div>
                        <label for="username" class="block text-gray-700">Username:</label>
                        <input type="text" id="username" name="username" required class="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-500">
                    </div>
                    <div>
                        <label for="password" class="block text-gray-700">Password:</label>
                        <input type="password" id="password" name="password" required class="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-500">
                    </div>
                    <div>
                        <label for="confirm_password" class="block text-gray-700">Confirm Password:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required class="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-500">
                    </div>
                </div>
                <div class="space-y-4">
                    <!-- Address and Contact Information Fields -->
                    <div>
                        <label for="first_name" class="block text-gray-700">First Name:</label>
                        <input type="text" id="first_name" name="first_name" required class="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-500">
                    </div>
                    <div>
                        <label for="last_name" class="block text-gray-700">Last Name:</label>
                        <input type="text" id="last_name" name="last_name" required class="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-500">
                    </div>
                    <div>
                        <label for="phone_number" class="block text-gray-700">Phone Number:</label>
                        <input type="text" id="phone_number" name="phone_number" required class="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-500">
                    </div>
                    <div>
                        <label for="address_line1" class="block text-gray-700">Address Line 1:</label>
                        <input type="text" id="address_line1" name="address_line1" required class="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-500">
                    </div>
                    <div>
                        <label for="address_line2" class="block text-gray-700">Address Line 2:</label>
                        <input type="text" id="address_line2" name="address_line2" class="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-500">
                    </div>
                    <div>
                        <label for="city" class="block text-gray-700">City:</label>
                        <input type="text" id="city" name="city" required class="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-500">
                    </div>

                    <!-- Register Button -->
                    <div class="flex justify-center mt-6">
                        <button type="submit" class="bg-gray-800 text-white px-6 py-3 rounded-md hover:bg-gray-700 transition">Register</button>
                    </div>
                </div>
            </div>
        </form>

        <div class="text-center mt-4">
            <p class="text-gray-600">Already have an account? <a href="login.php" class="text-blue-500 hover:text-blue-700">Login here</a>.</p>
        </div>
    </div>

</body>

</html>
