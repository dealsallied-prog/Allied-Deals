-- Add MRP (Maximum Retail Price) to products
ALTER TABLE products ADD COLUMN IF NOT EXISTS mrp DECIMAL(10,2) DEFAULT 0 AFTER base_price;

-- Add MRP to product variants
ALTER TABLE product_variants ADD COLUMN IF NOT EXISTS mrp DECIMAL(10,2) DEFAULT 0 AFTER price;

-- Update existing products to have MRP same as price (can be changed later)
UPDATE products SET mrp = base_price WHERE mrp = 0;
UPDATE product_variants SET mrp = price WHERE mrp = 0;
