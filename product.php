<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';

if (!isset($_GET['id'])) {
    header('Location: shop.php');
    exit;
}

require_once __DIR__ . '/models/Product.php';
$productModel = new Product();
$product = $productModel->getById($_GET['id']);

if (!$product) {
    header('Location: shop.php');
    exit;
}

$pageTitle = $product['name'] . ' - AllieDEals';
require_once __DIR__ . '/includes/header.php';

$uniqueSizes = array_unique(array_filter(array_column($product['variants'], 'size')));
$uniqueColors = array_unique(array_filter(array_column($product['variants'], 'color')));
?>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Images -->
            <div>
                <div id="mainImage" class="relative aspect-square bg-white rounded-xl overflow-hidden mb-4">
                    <img src="<?php echo !empty($product['images']) ? $product['images'][0] : 'https://via.placeholder.com/600'; ?>" 
                         alt="<?php echo htmlspecialchars($product['name']); ?>"
                         class="w-full h-full object-contain">
                </div>
                <div class="grid grid-cols-4 gap-4">
                    <?php foreach ($product['images'] as $index => $image): ?>
                        <button onclick="changeImage('<?php echo $image; ?>')" class="relative aspect-square bg-white rounded-lg overflow-hidden border-2 hover:border-primary-500">
                            <img src="<?php echo $image; ?>" alt="Image <?php echo $index + 1; ?>" class="w-full h-full object-contain">
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Product Info -->
            <div class="space-y-6">
                <?php if ($product['brand']): ?>
                    <p class="text-gray-500 uppercase tracking-wide"><?php echo htmlspecialchars($product['brand']); ?></p>
                <?php endif; ?>
                
                <h1 class="text-3xl md:text-4xl font-bold"><?php echo htmlspecialchars($product['name']); ?></h1>

                <?php if ($product['rating'] > 0): ?>
                    <div class="flex items-center space-x-2">
                        <span class="text-yellow-500">⭐ <?php echo number_format($product['rating'], 1); ?></span>
                        <span class="text-gray-500">(<?php echo $product['review_count']; ?> reviews)</span>
                    </div>
                <?php endif; ?>

                <div class="text-3xl font-bold text-primary-500" id="variantPrice">
                    <?php echo formatPrice($product['base_price']); ?>
                </div>

                <div class="prose max-w-none">
                    <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                </div>

                <!-- Size Selection (for T-shirts) -->
                <?php if (!empty($uniqueSizes) && $product['category'] === 'tshirts'): ?>
                    <div>
                        <h3 class="font-semibold mb-3">Select Size</h3>
                        <div class="flex flex-wrap gap-2">
                            <?php foreach ($uniqueSizes as $size): ?>
                                <button onclick="selectSize('<?php echo $size; ?>')" 
                                    class="size-btn px-6 py-3 border-2 rounded-lg font-medium hover:border-primary-500 transition-colors" 
                                    data-size="<?php echo $size; ?>">
                                    <?php echo $size; ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Color Selection (for T-shirts) -->
                <?php if (!empty($uniqueColors) && $product['category'] === 'tshirts'): ?>
                    <div>
                        <h3 class="font-semibold mb-3">Select Color</h3>
                        <div class="flex flex-wrap gap-2">
                            <?php foreach ($uniqueColors as $color): ?>
                                <button onclick="selectColor('<?php echo $color; ?>')" 
                                    class="color-btn px-6 py-3 border-2 rounded-lg font-medium hover:border-primary-500 transition-colors"
                                    data-color="<?php echo $color; ?>">
                                    <?php echo $color; ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Stock Status -->
                <div id="stockStatus" class="text-sm"></div>

                <!-- Quantity -->
                <div>
                    <h3 class="font-semibold mb-3">Quantity</h3>
                    <div class="flex items-center space-x-4">
                        <button onclick="changeQuantity(-1)" class="px-4 py-2 border rounded-lg hover:bg-gray-50">-</button>
                        <span id="quantity" class="text-xl font-medium w-12 text-center">1</span>
                        <button onclick="changeQuantity(1)" class="px-4 py-2 border rounded-lg hover:bg-gray-50">+</button>
                    </div>
                </div>

                <!-- Add to Cart -->
                <button onclick="addToCart()" id="addToCartBtn" class="w-full px-8 py-4 bg-primary-500 text-white font-semibold rounded-lg hover:bg-primary-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    Add to Cart
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const variants = <?php echo json_encode($product['variants']); ?>;
const productId = <?php echo $product['id']; ?>;
let selectedSize = null;
let selectedColor = null;
let selectedVariant = null;
let quantity = 1;

// Auto-select first variant for gadgets
<?php if ($product['category'] === 'gadgets' && !empty($product['variants'])): ?>
    selectedVariant = variants[0];
    updateStockStatus();
<?php endif; ?>

function selectSize(size) {
    selectedSize = size;
    document.querySelectorAll('.size-btn').forEach(btn => {
        btn.classList.remove('bg-primary-500', 'text-white', 'border-primary-500');
        if (btn.dataset.size === size) {
            btn.classList.add('bg-primary-500', 'text-white', 'border-primary-500');
        }
    });
    findMatchingVariant();
}

function selectColor(color) {
    selectedColor = color;
    document.querySelectorAll('.color-btn').forEach(btn => {
        btn.classList.remove('bg-primary-500', 'text-white', 'border-primary-500');
        if (btn.dataset.color === color) {
            btn.classList.add('bg-primary-500', 'text-white', 'border-primary-500');
        }
    });
    findMatchingVariant();
}

function findMatchingVariant() {
    selectedVariant = variants.find(v => 
        (!selectedSize || v.size === selectedSize) &&
        (!selectedColor || v.color === selectedColor)
    ) || null;
    
    if (selectedVariant) {
        document.getElementById('variantPrice').textContent = '₹' + parseFloat(selectedVariant.price).toFixed(2);
        updateStockStatus();
    }
}

function updateStockStatus() {
    const statusEl = document.getElementById('stockStatus');
    const btnEl = document.getElementById('addToCartBtn');
    
    if (selectedVariant) {
        if (selectedVariant.stock > 0) {
            statusEl.innerHTML = `<span class="text-green-600 font-medium">✓ In Stock (${selectedVariant.stock} available)</span>`;
            btnEl.disabled = false;
        } else {
            statusEl.innerHTML = '<span class="text-red-600 font-medium">✗ Out of Stock</span>';
            btnEl.disabled = true;
        }
    }
}

function changeQuantity(delta) {
    const newQty = quantity + delta;
    const maxStock = selectedVariant ? selectedVariant.stock : 99;
    
    if (newQty >= 1 && newQty <= maxStock) {
        quantity = newQty;
        document.getElementById('quantity').textContent = quantity;
    }
}

function changeImage(src) {
    document.querySelector('#mainImage img').src = src;
}

function addToCart() {
    if (!selectedVariant) {
        alert('Please select a variant');
        return;
    }
    
    const formData = new FormData();
    formData.append('action', 'add');
    formData.append('product_id', productId);
    formData.append('variant_id', selectedVariant.id);
    formData.append('quantity', quantity);
    
    fetch('cart_api.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Added to cart!');
            window.location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
