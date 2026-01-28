<?php
$pageTitle = 'Contact Us - Allied Deals';
require_once __DIR__ . '/includes/header.php';

$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Simple contact form (you can add email sending later)
    $success = 'Thank you for contacting us! We\'ll get back to you soon.';
}
?>

<div class="min-h-screen bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold text-center mb-12">Contact Us</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Contact Info -->
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <svg class="w-12 h-12 text-primary-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="font-bold mb-2">Email</h3>
                    <p class="text-gray-600"><a href="mailto:Support@alliedeals.com" class="text-primary-500 hover:underline">Support@alliedeals.com</a></p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <svg class="w-12 h-12 text-primary-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    <h3 class="font-bold mb-2">Phone</h3>
                    <p class="text-gray-600"><a href="tel:+917012598880" class="text-primary-500 hover:underline">7012598880</a></p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <svg class="w-12 h-12 text-primary-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <h3 class="font-bold mb-2">Address</h3>
                    <p class="text-gray-600">
                        RKP MANDIRAM KOTTAMVILLAROAD,<br>
                        VATTIYOORKAVU PO,<br>
                        THIRUVANANTHAPURAM - 695013
                    </p>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-2">
                <div class="bg-white p-8 rounded-lg shadow">
                    <h2 class="text-2xl font-bold mb-6">Send us a Message</h2>
                    
                    <?php if ($success): ?>
                        <div class="bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded mb-6">
                            <?php echo $success; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Name</label>
                            <input type="text" name="name" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Email</label>
                            <input type="email" name="email" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Subject</label>
                            <input type="text" name="subject" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Message</label>
                            <textarea name="message" required rows="5" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent resize-none"></textarea>
                        </div>

                        <button type="submit" class="w-full px-6 py-3 bg-primary-500 text-white font-semibold rounded-lg hover:bg-primary-600 transition-colors">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
