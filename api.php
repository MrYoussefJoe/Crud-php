<?php

ob_start();
session_start();

include 'db.php';
include 'func.php';

header('Content-Type: application/json');

if (!isset($_POST['action'])) {
    sendResponse(false, 'Invalid Request');
    exit;
}

$action = $_POST['action'];

switch ($action) {
    case 'signin':
        handleSignin($conn);
        break;
    case 'register':
        handleRegister($conn);
        break;
    case 'add':
        handleAddProduct($conn);
        break;
    case 'showAll':
        handleShowAllProducts($conn);
        break;
    case 'update':
        handleUpdateProduct($conn);
        break;
    case 'destroyById':
        handleDestroyProduct($conn);
        break;
    default:
        sendResponse(false, 'Invalid Action');
}

function sendResponse($success, $message, $data = null)
{
    echo json_encode(['success' => $success, 'message' => $message, 'data' => $data]);
}

function handleSignin($conn)
{
    $email = noEnj($_POST['email'] ?? '');
    $password = noEnj($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        sendResponse(false, 'Email and Password are required');
        return;
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        sendResponse(false, 'User not found');
        return;
    }

    $user = $result->fetch_assoc();
    if (!password_verify($password, $user['password'])) {
        sendResponse(false, 'Invalid credentials');
        return;
    }

    $_SESSION['userId'] = $user['id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['full_name'] = $user['full_name'];

    sendResponse(true, 'Login successful', [
        'user_id' => $user['id'],
        'email' => $user['email'],
        'full_name' => $user['full_name'],
        'username' => $user['username'],
        'created_at' => $user['created_at'],
    ]);
}

function handleRegister($conn)
{
    $username = noEnj($_POST['username'] ?? '');
    $email = noEnj($_POST['email'] ?? '');
    $password = noEnj($_POST['password'] ?? '');
    $confirmPassword = noEnj($_POST['confirmPassword'] ?? '');
    $fullName = noEnj($_POST['fullName'] ?? '');

    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword) || empty($fullName)) {
        sendResponse(false, 'All fields are required');
        return;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        sendResponse(false, 'Invalid email format');
        return;
    }

    if ($password !== $confirmPassword) {
        sendResponse(false, 'Passwords do not match');
        return;
    }

    if (strlen($password) < 6) {
        sendResponse(false, 'Password must be at least 6 characters');
        return;
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        sendResponse(false, 'Email or username already exists');
        return;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $hashedPassword, $fullName);

    if ($stmt->execute()) {
        sendResponse(true, 'Registration successful', ['redirect' => 'signin']);
    } else {
        sendResponse(false, 'Failed to register user');
    }
}

function handleAddProduct($conn)
{
    $name = noEnj($_POST['name'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $description = noEnj($_POST['description'] ?? null);
    $quantity = intval($_POST['quantity'] ?? 0);
    $rating = floatval($_POST['rating'] ?? 0);

    if (empty($name) || $price <= 0 || $quantity < 0 || $rating < 0 || $rating > 5) {
        sendResponse(false, 'Invalid product details');
        return;
    }

    if (empty($_FILES['image']['tmp_name'])) {
        sendResponse(false, 'Image is required');
        return;
    }

    $imageName = time() . '_' . basename($_FILES['image']['name']);
    $uploadPath = 'uploads/' . $imageName;
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
        sendResponse(false, 'Failed to upload image');
        return;
    }

    $stmt = $conn->prepare("INSERT INTO products (name, price, description, quantity, rating, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdsiss", $name, $price, $description, $quantity, $rating, $imageName);

    if ($stmt->execute()) {
        sendResponse(true, 'Product added successfully');
    } else {
        sendResponse(false, 'Failed to add product');
    }
}

function handleShowAllProducts($conn)
{
    $sql = "SELECT id, name, price, image FROM products WHERE is_deleted != 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $products = $result->fetch_all(MYSQLI_ASSOC);
        sendResponse(true, 'Products retrieved successfully', $products);
    } else {
        sendResponse(false, 'No products found');
    }
}

function handleUpdateProduct($conn)
{
    $id = intval($_POST['id'] ?? 0);
    $name = noEnj($_POST['name'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $description = noEnj($_POST['description'] ?? null);

    if ($id <= 0 || empty($name) || $price <= 0) {
        sendResponse(false, 'Invalid product details');
        return;
    }

    $imageName = null;
    if (!empty($_FILES['image']['tmp_name'])) {
        $imageName = time() . '_' . basename($_FILES['image']['name']);
        $uploadPath = 'uploads/' . $imageName;
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
            sendResponse(false, 'Failed to upload image');
            return;
        }
    }

    $query = "UPDATE products SET name = ?, price = ?, description = ?";
    $params = [$name, $price, $description];
    $types = "sds";

    if ($imageName) {
        $query .= ", image = ?";
        $params[] = $imageName;
        $types .= "s";
    }

    $query .= " WHERE id = ?";
    $params[] = $id;
    $types .= "i";

    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        sendResponse(true, 'Product updated successfully');
    } else {
        sendResponse(false, 'Failed to update product');
    }
}

function handleDestroyProduct($conn)
{
    $id = intval($_POST['id'] ?? 0);

    if ($id <= 0) {
        sendResponse(false, 'Invalid product ID');
        return;
    }

    $stmt = $conn->prepare("UPDATE products SET is_deleted = 1 WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        sendResponse(true, 'Product deleted successfully');
    } else {
        sendResponse(false, 'Failed to delete product');
    }
}

