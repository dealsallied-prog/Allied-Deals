<?php
$pageTitle = 'Shipping Policy - Allied Deals';
require_once __DIR__ . '/includes/header.php';
?>

<div class="min-h-screen bg-gray-50 py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold mb-8">Shipping & Returns Policy</h1>
        
        <div class="prose max-w-none bg-white p-8 rounded-lg space-y-6">
            <h2 class="text-2xl font-bold">Shipping Information</h2>
            <ul class="list-disc pl-6 space-y-2">
                <li><strong>Free Shipping:</strong> On all orders above ₹<?php echo FREE_SHIPPING_THRESHOLD; ?></li>
                <li><strong>Standard Shipping:</strong> ₹<?php echo SHIPPING_COST; ?> for orders below ₹<?php echo FREE_SHIPPING_THRESHOLD; ?></li>
                <li><strong>Delivery Time:</strong> 5-7 business days</li>
                <li><strong>Coverage:</strong> We ship across India</li>
            </ul>

            <h2 class="text-2xl font-bold mt-8">Return Policy</h2>
            <p>We want you to be completely satisfied with your purchase. If you're not happy, we offer a 7-day return policy.</p>
            
            <h3 class="text-xl font-bold mt-6">Return Conditions:</h3>
            <ul class="list-disc pl-6 space-y-2">
                <li>Products must be unused and in original packaging</li>
                <li>Return request must be made within 7 days of delivery</li>
                <li>Original tags and labels must be intact</li>
                <li>Return shipping costs are borne by the customer</li>
            </ul>

            <h2 class="text-2xl font-bold mt-8">Refund Process</h2>
            <p>Once we receive your returned item, we will inspect it and process your refund within 5-7 business days. The refund will be credited to your original payment method.</p>

            <div class="bg-purple-50 p-6 rounded-lg mt-8">
                <h3 class="text-xl font-bold mb-2">Need Help?</h3>
                <p>For any shipping or returns queries, contact us at: shipping@alliedeals.com</p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
