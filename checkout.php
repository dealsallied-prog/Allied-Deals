<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/payment_helpers.php';
require_once __DIR__ . '/models/Order.php';
require_once __DIR__ . '/models/Product.php';

// Check if user is logged in
if (!isLoggedIn()) {
    setFlash('error', 'Please login to proceed with checkout');
    header('Location: login.php');
    exit;
}

$cartDetails = getCartDetails();
if (empty($cartDetails)) {
    header('Location: cart.php');
    exit;
}

$subtotal = calculateCartTotal();
$shipping = calculateShipping($subtotal);
$tax = calculateTax($subtotal);
$total = $subtotal + $shipping + $tax;

// Handle form submission - Create Razorpay Order
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_order'])) {
    header('Content-Type: application/json');
    
    // Check if demo mode is enabled from database
    if (isDemoMode()) {
        // Demo mode - bypass Razorpay and create fake order
        echo json_encode([
            'success' => true,
            'order_id' => 'demo_order_' . time(),
            'amount' => $total * 100,
            'currency' => 'INR',
            'demo' => true
        ]);
        exit;
    }
    
    // Real Razorpay integration - get credentials from database
    $razorpayKeyId = getRazorpayKeyId();
    $razorpayKeySecret = getRazorpayKeySecret();
    
    if (empty($razorpayKeyId) || empty($razorpayKeySecret)) {
        echo json_encode([
            'success' => false,
            'message' => 'Payment gateway not configured. Please contact support.'
        ]);
        exit;
    }
    
    // Real Razorpay integration
    $orderData = [
        'amount' => $total * 100, // Amount in paise
        'currency' => 'INR',
        'receipt' => 'AD' . time(),
    ];
    
    $ch = curl_init('https://api.razorpay.com/v1/orders');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($orderData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
    ]);
    curl_setopt($ch, CURLOPT_USERPWD, $razorpayKeyId . ':' . $razorpayKeySecret);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        $razorpayOrder = json_decode($response, true);
        echo json_encode([
            'success' => true,
            'order_id' => $razorpayOrder['id'],
            'amount' => $razorpayOrder['amount'],
            'currency' => $razorpayOrder['currency']
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to create Razorpay order. Response: ' . $response
        ]);
    }
    exit;
}

// Handle payment verification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_payment'])) {
    header('Content-Type: application/json');
    
    // Check if demo mode
    if (defined('DEMO_MODE') && DEMO_MODE === true) {
        // Demo mode - skip Razorpay verification
        $razorpayOrderId = $_POST['razorpay_order_id'] ?? 'demo_order_' . time();
        $razorpayPaymentId = 'demo_payment_' . time();
        $razorpaySignature = 'demo_signature_' . time();
    } else {
        // Real Razorpay verification
        $razorpayOrderId = $_POST['razorpay_order_id'];
        $razorpayPaymentId = $_POST['razorpay_payment_id'];
        $razorpaySignature = $_POST['razorpay_signature'];
        
        $generatedSignature = hash_hmac('sha256', $razorpayOrderId . '|' . $razorpayPaymentId, RAZORPAY_KEY_SECRET);
        
        if ($generatedSignature !== $razorpaySignature) {
            echo json_encode([
                'success' => false,
                'message' => 'Payment verification failed'
            ]);
            exit;
        }
    }
    
    // Payment verified (or demo mode), create order in database
    $orderModel = new Order();
    $productModel = new Product();
    
    $orderItems = [];
    foreach ($cartDetails as $item) {
        $orderItems[] = [
            'product_id' => $item['product']['id'],
            'product_name' => $item['product']['name'],
            'variant_id' => $item['variant']['id'],
            'variant_details' => [
                'size' => $item['variant']['size'],
                'color' => $item['variant']['color'],
                'sku' => $item['variant']['sku']
            ],
            'quantity' => $item['quantity'],
            'price' => $item['variant']['price'],
            'image' => !empty($item['product']['images']) ? $item['product']['images'][0] : null
        ];
        
        // Update stock
        $productModel->updateStock($item['variant']['id'], $item['quantity']);
    }
    
    $shippingAddress = [
        'full_name' => $_POST['full_name'],
        'phone' => $_POST['phone'],
        'street' => $_POST['street'],
        'city' => $_POST['city'],
        'state' => $_POST['state'],
        'pincode' => $_POST['pincode'],
        'country' => $_POST['country'] ?? 'India'
    ];
    
    $orderId = $orderModel->create([
        'user_id' => $_SESSION['user_id'],
        'items' => $orderItems,
        'subtotal' => $subtotal,
        'shipping' => $shipping,
        'tax' => $tax,
        'total_amount' => $total,
        'shipping_address' => $shippingAddress,
        'payment_details' => [
            'razorpay_order_id' => $razorpayOrderId,
            'razorpay_payment_id' => $razorpayPaymentId,
            'razorpay_signature' => $razorpaySignature
        ],
        'payment_status' => 'completed',
        'order_status' => 'confirmed'
    ]);
    
    // Clear cart
    clearCart();
    
    echo json_encode([
        'success' => true,
        'order_id' => $orderId
    ]);
    exit;
}

$pageTitle = 'Checkout - Allied Deals';
require_once __DIR__ . '/includes/header.php';
?>

<!-- Razorpay Checkout Script -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold mb-8">Checkout</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Shipping Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg p-6">
                    <h2 class="text-xl font-bold mb-6">Shipping Address</h2>
                    
                    <form id="checkoutForm" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Full Name *</label>
                            <input type="text" name="full_name" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Phone *</label>
                            <input type="tel" name="phone" required pattern="[0-9]{10}" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="10-digit mobile number">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Street Address *</label>
                            <input type="text" name="street" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">City *</label>
                                <input type="text" name="city" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">State *</label>
                                <input type="text" name="state" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Pincode *</label>
                                <input type="text" name="pincode" required pattern="[0-9]{6}" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="6-digit pincode">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Country</label>
                                <input type="text" name="country" value="India" readonly class="w-full px-4 py-3 border rounded-lg bg-gray-50">
                            </div>
                        </div>

                        <button type="submit" id="payBtn" class="w-full px-6 py-4 bg-primary-500 text-white font-semibold rounded-lg hover:bg-primary-600 transition-colors mt-6">
                            Pay <?php echo formatPrice($total); ?>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg p-6 sticky top-24">
                    <h2 class="text-xl font-bold mb-6">Order Summary</h2>

                    <div class="space-y-4 mb-6">
                        <?php foreach ($cartDetails as $item): ?>
                            <?php $firstImage = !empty($item['product']['images']) ? $item['product']['images'][0] : 'https://via.placeholder.com/100'; ?>
                            <div class="flex space-x-3">
                                <img src="<?php echo $firstImage; ?>" alt="<?php echo htmlspecialchars($item['product']['name']); ?>" class="w-16 h-16 object-cover rounded">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium truncate"><?php echo htmlspecialchars($item['product']['name']); ?></p>
                                    <p class="text-xs text-gray-500">
                                        <?php if ($item['variant']['size']): ?>Size: <?php echo $item['variant']['size']; ?> • <?php endif; ?>
                                        Qty: <?php echo $item['quantity']; ?>
                                    </p>
                                    <p class="text-sm font-semibold"><?php echo formatPrice($item['variant']['price'] * $item['quantity']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="border-t pt-4 space-y-2">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span><?php echo formatPrice($subtotal); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Shipping</span>
                            <span><?php echo $shipping === 0 ? 'FREE' : formatPrice($shipping); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Tax (18%)</span>
                            <span><?php echo formatPrice($tax); ?></span>
                        </div>
                        <div class="flex justify-between text-lg font-bold border-t pt-2">
                            <span>Total</span>
                            <span class="text-primary-500"><?php echo formatPrice($total); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('checkoutForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('create_order', '1');
    
    try {
        // Create Razorpay order (or demo order)
        const response = await fetch('checkout.php', {
            method: 'POST',
            body: formData
        });
        
        const orderData = await response.json();
        console.log('Order response:', orderData);
        
        if (!orderData.success) {
            alert('Error creating order: ' + orderData.message);
            return;
        }
        
        // Check if this is demo mode
        if (orderData.demo) {
            alert('Processing in Demo Mode...');
            
            // Demo mode - skip Razorpay and directly verify
            const verifyData = new FormData(document.getElementById('checkoutForm'));
            verifyData.append('verify_payment', '1');
            verifyData.append('razorpay_order_id', orderData.order_id);
            
            const verifyResponse = await fetch('checkout.php', {
                method: 'POST',
                body: verifyData
            });
            
            const result = await verifyResponse.json();
            console.log('Verification result:', result);
            
            if (result.success) {
                alert('Order placed successfully! (Demo Mode - No actual payment)');
                window.location.href = 'index.php';
            } else {
                alert('Order creation failed: ' + (result.message || 'Unknown error'));
            }
            return;
        }
        
        // Real Razorpay mode - Check if Razorpay is loaded
        if (typeof Razorpay === 'undefined') {
            alert('Payment gateway not loaded. Please refresh the page.');
            return;
        }
        
        // Initialize Razorpay checkout
        const options = {
            key: '<?php echo getRazorpayKeyId(); ?>',
            amount: orderData.amount,
            currency: orderData.currency,
            name: '<?php echo SITE_NAME; ?>',
            description: 'E-commerce Purchase',
            order_id: orderData.order_id,
            handler: async function(response) {
                // Verify payment
                const verifyData = new FormData(document.getElementById('checkoutForm'));
                verifyData.append('verify_payment', '1');
                verifyData.append('razorpay_order_id', response.razorpay_order_id);
                verifyData.append('razorpay_payment_id', response.razorpay_payment_id);
                verifyData.append('razorpay_signature', response.razorpay_signature);
                
                const verifyResponse = await fetch('checkout.php', {
                    method: 'POST',
                    body: verifyData
                });
                
                const result = await verifyResponse.json();
                
                if (result.success) {
                    alert('Order placed successfully!');
                    window.location.href = 'index.php';
                } else {
                    alert('Payment verification failed');
                }
            },
            prefill: {
                name: formData.get('full_name'),
                contact: formData.get('phone')
            },
            theme: {
                color: '#0096FF'
            }
        };
        
        const rzp = new Razorpay(options);
        rzp.on('payment.failed', function(response) {
            alert('Payment failed. Please try again.');
        });
        rzp.open();
        
    } catch (error) {
        console.error('Checkout error:', error);
        alert('An error occurred: ' + error.message);
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
