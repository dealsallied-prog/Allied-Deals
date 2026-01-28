<?php
$pageTitle = 'Allied Deals - Premium T-Shirts & Digital Gadgets | Home';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/models/Product.php';

$productModel = new Product();
$featuredProducts = $productModel->getFeatured(8);
?>

<!-- Hero Carousel -->
<div class="relative h-[500px] md:h-[600px] bg-gray-900">
    <div class="swiper heroSwiper h-full">
        <div class="swiper-wrapper">
            <!-- Slide 1 - T-Shirts -->
            <div class="swiper-slide">
                <div class="relative h-full" style="background-image: url('https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=1200'); background-size: cover; background-position: center;">
                    <div class="absolute inset-0 bg-gradient-to-r from-primary-900/90 to-purple-900/90"></div>
                    <div class="relative h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center">
                        <div class="max-w-2xl text-white space-y-6">
                            <p class="text-lg md:text-xl font-medium">Express Your Style</p>
                            <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold">Premium T-Shirts Collection</h1>
                            <p class="text-lg md:text-xl">Discover our exclusive range of trendy t-shirts for every occasion</p>
                            <a href="shop.php?category=tshirts" class="inline-block px-8 py-4 bg-white text-gray-900 font-semibold rounded-lg hover:bg-gray-100 transition-all">
                                Shop T-Shirts
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2 - Gadgets -->
            <div class="swiper-slide">
                <div class="relative h-full" style="background-image: url('https://images.unsplash.com/photo-1468495244123-6c6c332eeece?w=1200'); background-size: cover; background-position: center;">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-900/90 to-purple-900/80"></div>
                    <div class="relative h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center">
                        <div class="max-w-2xl text-white space-y-6">
                            <p class="text-lg md:text-xl font-medium">Technology at Your Fingertips</p>
                            <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold">Latest Digital Gadgets</h1>
                            <p class="text-lg md:text-xl">Cutting-edge gadgets to enhance your digital lifestyle</p>
                            <a href="shop.php?category=gadgets" class="inline-block px-8 py-4 bg-white text-gray-900 font-semibold rounded-lg hover:bg-gray-100 transition-all">
                                Shop Gadgets
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 3 - Deals -->
            <div class="swiper-slide">
                <div class="relative h-full" style="background-image: url('https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=1200'); background-size: cover; background-position: center;">
                    <div class="absolute inset-0 bg-gradient-to-r from-orange-900/90 to-red-900/90"></div>
                    <div class="relative h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center">
                        <div class="max-w-2xl text-white space-y-6">
                            <p class="text-lg md:text-xl font-medium">Up to 50% Off</p>
                            <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold">Limited Time Offers</h1>
                            <p class="text-lg md:text-xl">Exclusive deals on selected products. Hurry, while stocks last!</p>
                            <a href="shop.php?featured=1" class="inline-block px-8 py-4 bg-white text-gray-900 font-semibold rounded-lg hover:bg-gray-100 transition-all">
                                Shop Deals
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 4 - New Arrivals -->
            <div class="swiper-slide">
                <div class="relative h-full" style="background-image: url('https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1200'); background-size: cover; background-position: center;">
                    <div class="absolute inset-0 bg-gradient-to-r from-green-900/90 to-teal-900/90"></div>
                    <div class="relative h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center">
                        <div class="max-w-2xl text-white space-y-6">
                            <p class="text-lg md:text-xl font-medium">Fresh & Trendy</p>
                            <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold">New Arrivals</h1>
                            <p class="text-lg md:text-xl">Check out our latest collection of stylish products just for you</p>
                            <a href="shop.php?sort=newest" class="inline-block px-8 py-4 bg-white text-gray-900 font-semibold rounded-lg hover:bg-gray-100 transition-all">
                                Explore New Arrivals
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 5 - Best Sellers -->
            <div class="swiper-slide">
                <div class="relative h-full" style="background-image: url('https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=1200'); background-size: cover; background-position: center;">
                    <div class="absolute inset-0 bg-gradient-to-r from-indigo-900/90 to-pink-900/90"></div>
                    <div class="relative h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center">
                        <div class="max-w-2xl text-white space-y-6">
                            <p class="text-lg md:text-xl font-medium">Customer Favorites</p>
                            <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold">Best Selling Items</h1>
                            <p class="text-lg md:text-xl">Discover what everyone's buying - top-rated products loved by thousands</p>
                            <a href="shop.php?sort=popular" class="inline-block px-8 py-4 bg-white text-gray-900 font-semibold rounded-lg hover:bg-gray-100 transition-all">
                                Shop Best Sellers
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 6 - Summer Collection -->
            <div class="swiper-slide">
                <div class="relative h-full" style="background-image: url('https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=1200'); background-size: cover; background-position: center;">
                    <div class="absolute inset-0 bg-gradient-to-r from-yellow-900/90 to-orange-900/90"></div>
                    <div class="relative h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center">
                        <div class="max-w-2xl text-white space-y-6">
                            <p class="text-lg md:text-xl font-medium">Season's Hottest</p>
                            <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold">Summer Collection 2026</h1>
                            <p class="text-lg md:text-xl">Beat the heat with our vibrant summer essentials and cool gadgets</p>
                            <a href="shop.php" class="inline-block px-8 py-4 bg-white text-gray-900 font-semibold rounded-lg hover:bg-gray-100 transition-all">
                                Shop Summer Styles
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
</div>

<!-- Featured Categories -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Shop by Category</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Explore our diverse range of products</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- T-Shirts Category -->
            <a href="shop.php?category=tshirts" class="group relative h-96 rounded-2xl overflow-hidden hover:scale-105 transition-transform duration-300">
                <img src="https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=800" alt="T-Shirts" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-primary-900/90 to-purple-900/90"></div>
                <div class="absolute inset-0 flex flex-col justify-end p-8 text-white">
                    <h3 class="text-3xl md:text-4xl font-bold mb-2">Men's Fashion</h3>
                    <p class="text-lg mb-4">Trendy T-Shirts & Apparel</p>
                    <div class="inline-flex items-center space-x-2 font-semibold group-hover:translate-x-2 transition-transform">
                        <span>Explore Now</span>
                        <span class="text-2xl">→</span>
                    </div>
                </div>
            </a>

            <!-- Gadgets Category -->
            <a href="shop.php?category=gadgets" class="group relative h-96 rounded-2xl overflow-hidden hover:scale-105 transition-transform duration-300">
                <img src="https://images.unsplash.com/photo-1498049794561-7780e7231661?w=800" alt="Gadgets" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-blue-900/90 to-purple-900/80"></div>
                <div class="absolute inset-0 flex flex-col justify-end p-8 text-white">
                    <h3 class="text-3xl md:text-4xl font-bold mb-2">Tech Gadgets</h3>
                    <p class="text-lg mb-4">Latest Digital Devices</p>
                    <div class="inline-flex items-center space-x-2 font-semibold group-hover:translate-x-2 transition-transform">
                        <span>Explore Now</span>
                        <span class="text-2xl">→</span>
                    </div>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Best Selling Products</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Discover our most popular items loved by customers worldwide</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($featuredProducts as $product): ?>
                <?php 
                    $firstImage = !empty($product['images']) ? $product['images'][0] : 'https://via.placeholder.com/400';
                    $categoryClass = $product['category'] === 'tshirts' ? 'bg-gradient-to-br from-primary-50 to-white hover:shadow-primary-200' : 'bg-gray-800 text-white';
                ?>
                <div class="<?php echo $categoryClass; ?> rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                    <a href="product.php?id=<?php echo $product['id']; ?>" class="block">
                        <div class="relative aspect-square overflow-hidden">
                            <img src="<?php echo $firstImage; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                            <?php if ($product['featured']): ?>
                                <span class="absolute top-3 left-3 bg-primary-500 text-white text-xs font-semibold px-3 py-1 rounded-full">Featured</span>
                            <?php endif; ?>
                            <?php if ($product['trending']): ?>
                                <span class="absolute top-3 right-3 bg-orange-500 text-white text-xs font-semibold px-3 py-1 rounded-full">Trending</span>
                            <?php endif; ?>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold mb-2 hover:text-primary-500 transition-colors truncate"><?php echo htmlspecialchars($product['name']); ?></h3>
                            <?php if ($product['brand']): ?>
                                <p class="text-sm text-gray-500 mb-2"><?php echo htmlspecialchars($product['brand']); ?></p>
                            <?php endif; ?>
                            <div class="flex items-center justify-between">
                                <span class="text-xl font-bold text-primary-500"><?php echo formatPrice($product['base_price']); ?></span>
                                <?php if ($product['rating'] > 0): ?>
                                    <span class="text-sm text-yellow-500">⭐ <?php echo number_format($product['rating'], 1); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-8">
            <a href="shop.php" class="inline-block px-8 py-3 bg-primary-500 text-white font-semibold rounded-lg hover:bg-primary-600 transition-colors">
                View All Products
            </a>
        </div>
    </div>
</section>

<!-- Features -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center p-6">
                <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-3xl">🚚</span>
                </div>
                <h3 class="text-xl font-semibold mb-2">Free Shipping</h3>
                <p class="text-gray-600">On orders above ₹<?php echo FREE_SHIPPING_THRESHOLD; ?></p>
            </div>

            <div class="text-center p-6">
                <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-3xl">🔒</span>
                </div>
                <h3 class="text-xl font-semibold mb-2">Secure Payment</h3>
                <p class="text-gray-600">100% secure transactions with Razorpay</p>
            </div>

            <div class="text-center p-6">
                <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-3xl">↩️</span>
                </div>
                <h3 class="text-xl font-semibold mb-2">Easy Returns</h3>
                <p class="text-gray-600">7-day hassle-free return policy</p>
            </div>
        </div>
    </div>
</section>

<script>
// Initialize Hero Swiper after DOM is fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Small delay to ensure Swiper library is loaded
    setTimeout(function() {
        const heroSwiper = new Swiper('.heroSwiper', {
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    }, 100);
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
