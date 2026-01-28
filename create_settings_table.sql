-- Create settings table for dynamic configuration
CREATE TABLE IF NOT EXISTS site_settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_group VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default Razorpay settings
INSERT INTO site_settings (setting_key, setting_value, setting_group) VALUES
('razorpay_key_id', '', 'payment'),
('razorpay_key_secret', '', 'payment'),
('razorpay_enabled', '0', 'payment'),
('demo_mode', '1', 'payment')
ON DUPLICATE KEY UPDATE setting_key=setting_key;
