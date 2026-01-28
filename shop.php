<?php
$pageTitle = 'Shop - Allied Deals';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/models/Product.php';

$productModel = new Product();

// Get filters from URL
$filters = [
    'category' => $_GET['category'] ?? '',
    'brand' => $_GET['brand'] ?? '',
    'min_price' => $_GET['min_price'] ?? '',
    'max_price' => $_GET['max_price'] ?? '',
    'size' => $_GET['size'] ?? '',
    'featured' => $_GET['featured'] ?? '',
    'sort' => $_GET['sort'] ?? '-created_at',
    'page' => $_GET['page'] ?? 1,
];

$products = $productModel->getAll($filters);
$total = $productModel->getCount($filters);
$totalPages = ceil($total / PRODUCTS_PER_PAGE);
$brands = $productModel->getBrands();
$sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
?>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold mb-2">Shop All Products</h1>
                <p class="text-gray-600"><?php echo $total; ?> products found</p>
            </div>
            <button id="filterToggle" class="md:hidden px-4 py-2 bg-white border rounded-lg">
                Filters
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Sidebar Filters -->
            <aside id="filterSidebar" class="hidden md:block bg-white p-6 rounded-lg h-fit sticky top-24">
                <h2 class="text-xl font-bold mb-6">Filters</h2>

                <!-- Category -->
                <div class="mb-6">
                    <h3 class="font-semibold mb-3">Category</h3>
                    <div class="space-y-2">
                        <?php foreach (['', 'tshirts', 'gadgets'] as $cat): ?>
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="radio" name="category" value="<?php echo $cat; ?>" <?php echo $filters['category'] === $cat ? 'checked' : ''; ?> onchange="updateFilter('category', this.value)" class="text-primary-500">
                                <span><?php echo $cat === '' ? 'All' : ucfirst($cat); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Price Range -->
                <div class="mb-6">
                    <h3 class="font-semibold mb-3">Price Range</h3>
                    <input type="number" id="minPrice" placeholder="Min" value="<?php echo $filters['min_price']; ?>" class="w-full px-3 py-2 border rounded mb-2">
                    <input type="number" id="maxPrice" placeholder="Max" value="<?php echo $filters['max_price']; ?>" class="w-full px-3 py-2 border rounded mb-2">
                    <button onclick="applyPriceFilter()" class="w-full px-4 py-2 bg-primary-500 text-white rounded hover:bg-primary-600">Apply</button>
                </div>

                <!-- Size -->
                <?php if ($filters['category'] === 'tshirts'): ?>
                <div class="mb-6">
                    <h3 class="font-semibold mb-3">Size</h3>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($sizes as $size): ?>
                            <button onclick="updateFilter('size', '<?php echo $filters['size'] === $size ? '' : $size; ?>')" 
                                class="px-4 py-2 border rounded <?php echo $filters['size'] === $size ? 'bg-primary-500 text-white' : 'bg-white'; ?>">
                                <?php echo $size; ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Sort -->
                <div class="mb-6">
                    <h3 class="font-semibold mb-3">Sort By</h3>
                    <select onchange="updateFilter('sort', this.value)" class="w-full px-3 py-2 border rounded">
                        <option value="-created_at" <?php echo $filters['sort'] === '-created_at' ? 'selected' : ''; ?>>Newest First</option>
                        <option value="price_asc" <?php echo $filters['sort'] === 'price_asc' ? 'selected' : ''; ?>>Price: Low to High</option>
                        <option value="price_desc" <?php echo $filters['sort'] === 'price_desc' ? 'selected' : ''; ?>>Price: High to Low</option>
                        <option value="rating" <?php echo $filters['sort'] === 'rating' ? 'selected' : ''; ?>>Highest Rated</option>
                    </select>
                </div>

                <!-- Clear Filters -->
                <button onclick="window.location.href='shop.php'" class="w-full px-4 py-2 border rounded hover:bg-gray-50">
                    Clear All Filters
                </button>
            </aside>

            <!-- Products Grid -->
            <main class="md:col-span-3">
                <?php if (empty($products)): ?>
                    <div class="text-center py-12 bg-white rounded-lg">
                        <p class="text-xl text-gray-500 mb-4">No products found</p>
                        <a href="shop.php" class="inline-block px-6 py-3 bg-primary-500 text-white rounded-lg hover:bg-primary-600">Clear Filters</a>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($products as $product): ?>
                            <?php $firstImage = !empty($product['images']) ? $product['images'][0] : 'https://via.placeholder.com/400'; ?>
                            <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                                <a href="product.php?id=<?php echo $product['id']; ?>">
                                    <div class="relative aspect-square overflow-hidden">
                                        <img src="<?php echo $firstImage; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                                        <?php if ($product['featured']): ?>
                                            <span class="absolute top-3 left-3 bg-primary-500 text-white text-xs font-semibold px-3 py-1 rounded-full">Featured</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="p-4">
                                        <h3 class="text-lg font-semibold mb-2"><?php echo htmlspecialchars($product['name']); ?></h3>
                                        <?php if ($product['brand']): ?>
                                            <p class="text-sm text-gray-500 mb-2"><?php echo htmlspecialchars($product['brand']); ?></p>
                                        <?php endif; ?>
                                        <p class="text-sm text-gray-600 mb-3 line-clamp-2"><?php echo htmlspecialchars(substr($product['description'], 0, 80)); ?>...</p>
                                        
                                        <div class="flex items-center justify-between">
                                            <div class="flex flex-col">
                                                <?php if ($product['mrp'] && $product['mrp'] > $product['base_price']): ?>
                                                    <?php $discount = round((($product['mrp'] - $product['base_price']) / $product['mrp']) * 100); ?>
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-xl font-bold text-primary-500">₹<?php echo number_format($product['base_price'], 2); ?></span>
                                                        <span class="text-sm text-gray-500 line-through">₹<?php echo number_format($product['mrp'], 2); ?></span>
                                                    </div>
                                                    <span class="text-xs text-green-600 font-semibold"><?php echo $discount; ?>% OFF</span>
                                                <?php else: ?>
                                                    <span class="text-xl font-bold text-primary-500">₹<?php echo number_format($product['base_price'], 2); ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <?php if ($product['in_stock_variants'] > 0): ?>
                                                <a href="product.php?id=<?php echo $product['id']; ?>" 
                                                   class="px-4 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition-colors text-sm">
                                                    View Details
                                                </a>
                                            <?php else: ?>
                                                <span class="text-sm text-red-600">Out of Stock</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                        <div class="flex justify-center mt-8 space-x-2">
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <button onclick="updateFilter('page', '<?php echo $i; ?>')" 
                                    class="px-4 py-2 rounded <?php echo $filters['page'] == $i ? 'bg-primary-500 text-white' : 'bg-white hover:bg-gray-100'; ?>">
                                    <?php echo $i; ?>
                                </button>
                            <?php endfor; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </main>
        </div>
    </div>
</div>

<script>
// Filter toggle for mobile
document.getElementById('filterToggle')?.addEventListener('click', function() {
    document.getElementById('filterSidebar').classList.toggle('hidden');
});

function updateFilter(key, value) {
    const url = new URL(window.location);
    if (value === '' || value === null) {
        url.searchParams.delete(key);
    } else {
        url.searchParams.set(key, value);
    }
    // Reset page when changing filters
    if (key !== 'page') {
        url.searchParams.delete('page');
    }
    window.location.href = url.toString();
}

function applyPriceFilter() {
    const minPrice = document.getElementById('minPrice').value;
    const maxPrice = document.getElementById('maxPrice').value;
    const url = new URL(window.location);
    
    if (minPrice) url.searchParams.set('min_price', minPrice);
    else url.searchParams.delete('min_price');
    
    if (maxPrice) url.searchParams.set('max_price', maxPrice);
    else url.searchParams.delete('max_price');
    
    url.searchParams.delete('page');
    window.location.href = url.toString();
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
