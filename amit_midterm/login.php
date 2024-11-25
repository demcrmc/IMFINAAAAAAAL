<?php
require_once 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $conn = new Connection();
    $userCRUD = new UserCRUD();
    $user = $userCRUD->login($email, $password);

    if ($user) {
        $_SESSION['user_id'] = $user->user_id;
        $_SESSION['username'] = $user->username;
        $_SESSION['user_type'] = $user->user_type;
        if ($user->user_type == 'admin') {
            header("Location: admin.php");
        } else {

            header("Location: index.php");
        }
        exit;
    } else {
        $error_message = "Invalid email or password.";
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
    <title>Login</title>
    <style>
        /* Gradient animation */
        @keyframes gradientAnimation {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        .gradient-background {
            background: linear-gradient(45deg, #4c6ef5, #48c78e, #f09, #ff6584);
            background-size: 400% 400%;
            animation: gradientAnimation 15s ease infinite;
        }
    </style>
</head>

<body class="gradient-background h-screen flex items-center justify-center">

    <!-- Login Container -->
    <div class="bg-white p-8 rounded-lg shadow-lg w-full sm:w-96">
        <h2 class="text-2xl font-semibold text-gray-900 mb-6 text-center">Login</h2>

        <!-- Error Message -->
        <?php if (!empty($error_message)) : ?>
        <p class="text-red-600 text-center mb-4"><?= htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <!-- Login Form -->
        <form action="login.php" method="POST">
            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-gray-700">Email:</label>
                    <input type="text" id="email" name="email" required class="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-500">
                </div>
                <div>
                    <label for="password" class="block text-gray-700">Password:</label>
                    <input type="password" id="password" name="password" required class="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-500">
                </div>

                <!-- Login Button -->
                <div class="flex justify-center">
                    <button type="submit" class="w-full bg-gray-800 text-white py-3 rounded-md hover:bg-gray-700 transition">Login</button>
                </div>
            </div>
        </form>

        <!-- Sign-up Link -->
        <p class="text-center text-gray-600 mt-4">Don't have an account? <a href="register.php" class="text-blue-500 hover:text-blue-700">Sign up here</a>.</p>
    </div>

</body>

</html>
