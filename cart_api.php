<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'add':
        $productId = intval($_POST['product_id'] ?? 0);
        $variantId = intval($_POST['variant_id'] ?? 0);
        $quantity = intval($_POST['quantity'] ?? 1);
        
        if ($productId && $variantId && $quantity > 0) {
            addToCart($productId, $variantId, $quantity);
            echo json_encode(['success' => true, 'cart_count' => getCartCount()]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
        }
        break;
        
    case 'update':
        $productId = intval($_POST['product_id'] ?? 0);
        $variantId = intval($_POST['variant_id'] ?? 0);
        $quantity = intval($_POST['quantity'] ?? 1);
        
        updateCartQuantity($productId, $variantId, $quantity);
        echo json_encode(['success' => true, 'cart_count' => getCartCount()]);
        break;
        
    case 'remove':
        $productId = intval($_POST['product_id'] ?? 0);
        $variantId = intval($_POST['variant_id'] ?? 0);
        
        removeFromCart($productId, $variantId);
        echo json_encode(['success' => true, 'cart_count' => getCartCount()]);
        break;
        
    case 'clear':
        clearCart();
        echo json_encode(['success' => true]);
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>
