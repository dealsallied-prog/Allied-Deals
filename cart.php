<?php
$pageTitle = 'Shopping Cart - Allied Deals';
require_once __DIR__ . '/includes/header.php';

$cartDetails = getCartDetails();
$subtotal = calculateCartTotal();
$shipping = calculateShipping($subtotal);
$tax = calculateTax($subtotal);
$total = $subtotal + $shipping + $tax;
?>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold mb-8">Shopping Cart</h1>

        <?php if (empty($cartDetails)): ?>
            <div class="bg-white rounded-lg p-12 text-center">
                <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h2 class="text-2xl font-bold mb-4">Your cart is empty</h2>
                <a href="shop.php" class="inline-block px-6 py-3 bg-primary-500 text-white rounded-lg hover:bg-primary-600">
                    Continue Shopping
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2 space-y-4">
                    <?php foreach ($cartDetails as $item): ?>
                        <?php 
                            $product = $item['product'];
                            $variant = $item['variant'];
                            $qty = $item['quantity'];
                            $firstImage = !empty($product['images']) ? $product['images'][0] : 'https://via.placeholder.com/200';
                        ?>
                        <div class="bg-white rounded-lg p-6 flex flex-col sm:flex-row gap-4">
                            <img src="<?php echo $firstImage; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-32 h-32 object-cover rounded">
                            
                            <div class="flex-1">
                                <h3 class="font-semibold text-lg mb-2"><?php echo htmlspecialchars($product['name']); ?></h3>
                                <p class="text-sm text-gray-500 mb-2">
                                    <?php if ($variant['size']): ?>Size: <?php echo $variant['size']; ?> • <?php endif; ?>
                                    <?php if ($variant['color']): ?>Color: <?php echo $variant['color']; ?><?php endif; ?>
                                </p>
                                <p class="text-lg font-semibold text-primary-500"><?php echo formatPrice($variant['price']); ?></p>
                                
                                <div class="flex items-center space-x-4 mt-4">
                                    <div class="flex items-center space-x-2">
                                        <button onclick="updateQty(<?php echo $product['id']; ?>, <?php echo $variant['id']; ?>, <?php echo $qty - 1; ?>)" class="px-3 py-1 border rounded hover:bg-gray-100">-</button>
                                        <span class="w-12 text-center"><?php echo $qty; ?></span>
                                        <button onclick="updateQty(<?php echo $product['id']; ?>, <?php echo $variant['id']; ?>, <?php echo $qty + 1; ?>)" class="px-3 py-1 border rounded hover:bg-gray-100" <?php echo $qty >= $variant['stock'] ? 'disabled' : ''; ?>>+</button>
                                    </div>
                                    <button onclick="removeItem(<?php echo $product['id']; ?>, <?php echo $variant['id']; ?>)" class="text-red-600 hover:text-red-700">
                                        Remove
                                    </button>
                                </div>
                            </div>
                            
                            <div class="text-right">
                                <p class="text-lg font-bold"><?php echo formatPrice($variant['price'] * $qty); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg p-6 sticky top-24">
                        <h2 class="text-xl font-bold mb-6">Order Summary</h2>
                        
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between">
                                <span>Subtotal</span>
                                <span><?php echo formatPrice($subtotal); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span>Shipping</span>
                                <span><?php echo $shipping === 0 ? 'FREE' : formatPrice($shipping); ?></span>
                            </div>
                            <?php if ($shipping === 0): ?>
                                <p class="text-sm text-green-600">✓ Free shipping applied!</p>
                            <?php else: ?>
                                <p class="text-sm text-gray-500">Free shipping on orders above <?php echo formatPrice(FREE_SHIPPING_THRESHOLD); ?></p>
                            <?php endif; ?>
                            <div class="flex justify-between">
                                <span>Tax (18%)</span>
                                <span><?php echo formatPrice($tax); ?></span>
                            </div>
                            <div class="border-t pt-3 flex justify-between text-lg font-bold">
                                <span>Total</span>
                                <span class="text-primary-500"><?php echo formatPrice($total); ?></span>
                            </div>
                        </div>
                        
                        <a href="checkout.php" class="block w-full px-6 py-3 bg-primary-500 text-white text-center font-semibold rounded-lg hover:bg-primary-600">
                            Proceed to Checkout
                        </a>
                        <a href="shop.php" class="block w-full mt-3 px-6 py-3 border-2 border-gray-300 text-center font-semibold rounded-lg hover:bg-gray-50">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function updateQty(productId, variantId, quantity) {
    if (quantity < 1) return;
    
    const formData = new FormData();
    formData.append('action', 'update');
    formData.append('product_id', productId);
    formData.append('variant_id', variantId);
    formData.append('quantity', quantity);
    
    fetch('cart_api.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        }
    });
}

function removeItem(productId, variantId) {
    if (!confirm('Remove this item from cart?')) return;
    
    const formData = new FormData();
    formData.append('action', 'remove');
    formData.append('product_id', productId);
    formData.append('variant_id', variantId);
    
    fetch('cart_api.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        }
    });
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
