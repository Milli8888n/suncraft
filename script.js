document.addEventListener('DOMContentLoaded', function() {
    // Product Thumbnails
    const thumbnails = document.querySelectorAll('.product-thumbnail');
    const mainImage = document.getElementById('main-product-image');
    
    if (thumbnails.length > 0 && mainImage) {
        thumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', function() {
                // Remove active class from all thumbnails
                thumbnails.forEach(t => t.classList.remove('active'));
                
                // Add active class to clicked thumbnail
                this.classList.add('active');
                
                // Update main image with smooth transition
                mainImage.style.opacity = '0';
                setTimeout(() => {
                    const imageUrl = this.getAttribute('data-image');
                    mainImage.src = imageUrl;
                    mainImage.style.opacity = '1';
                }, 300);
            });
        });
    }
    
    // Quantity Selectors
    const decreaseButtons = document.querySelectorAll('#decrease-quantity, .quantity-selector .btn:first-child');
    const increaseButtons = document.querySelectorAll('#increase-quantity, .quantity-selector .btn:last-child');
    const quantityInputs = document.querySelectorAll('#quantity, .quantity-selector input');
    
    if (decreaseButtons.length > 0 && increaseButtons.length > 0 && quantityInputs.length > 0) {
        decreaseButtons.forEach((button, index) => {
            button.addEventListener('click', function() {
                const input = quantityInputs[index];
                const currentValue = parseInt(input.value);
                if (currentValue > 1) {
                    input.value = currentValue - 1;
                    updateCartTotal();
                }
            });
        });
        
        increaseButtons.forEach((button, index) => {
            button.addEventListener('click', function() {
                const input = quantityInputs[index];
                const currentValue = parseInt(input.value);
                input.value = currentValue + 1;
                updateCartTotal();
            });
        });
        
        quantityInputs.forEach(input => {
            input.addEventListener('change', function() {
                if (parseInt(this.value) < 1 || isNaN(parseInt(this.value))) {
                    this.value = 1;
                }
                updateCartTotal();
            });
        });
    }
    
    // Size and Frame Options
    const sizeOptions = document.querySelectorAll('input[name="size"]');
    const frameOptions = document.querySelectorAll('input[name="frame"]');
    
    if (sizeOptions.length > 0) {
        sizeOptions.forEach(option => {
            option.addEventListener('change', function() {
                document.querySelectorAll('.size-option').forEach(label => {
                    label.classList.remove('active');
                });
                this.nextElementSibling.classList.add('active');
                updateProductPrice();
            });
        });
    }
    
    if (frameOptions.length > 0) {
        frameOptions.forEach(option => {
            option.addEventListener('change', function() {
                document.querySelectorAll('input[name="frame"] + .size-option').forEach(label => {
                    label.classList.remove('active');
                });
                this.nextElementSibling.classList.add('active');
                updateProductPrice();
            });
        });
    }
    
    // Update Product Price based on options
    function updateProductPrice() {
        const basePrice = 4500000; // Base price in VND
        const selectedSize = document.querySelector('input[name="size"]:checked');
        const selectedFrame = document.querySelector('input[name="frame"]:checked');
        const priceElement = document.querySelector('.product-price');
        
        if (priceElement && selectedSize && selectedFrame) {
            let finalPrice = basePrice;
            
            // Adjust price based on size
            if (selectedSize.value === '40x60') {
                finalPrice = basePrice * 0.8; // 20% smaller, 20% cheaper
            } else if (selectedSize.value === '80x120') {
                finalPrice = basePrice * 1.5; // 50% larger, 50% more expensive
            }
            
            // Adjust price based on frame
            if (selectedFrame.value === 'wood') {
                finalPrice += 500000; // Add frame cost
            }
            
            // Format price
            const formattedPrice = new Intl.NumberFormat('vi-VN').format(finalPrice) + '₫';
            priceElement.textContent = formattedPrice;
        }
    }
    
    // Update Cart Total
    function updateCartTotal() {
        const quantityInputs = document.querySelectorAll('.quantity-selector input');
        const itemPrices = document.querySelectorAll('.product-price');
        const subtotalElement = document.querySelector('.cart-summary .d-flex:first-child span:last-child');
        const totalElement = document.querySelector('.cart-summary .d-flex:nth-child(3) span:last-child');
        
        if (quantityInputs.length > 0 && itemPrices.length > 0 && subtotalElement && totalElement) {
            let subtotal = 0;
            
            quantityInputs.forEach((input, index) => {
                const quantity = parseInt(input.value);
                const priceText = itemPrices[index].textContent;
                const price = parseInt(priceText.replace(/\D/g, ''));
                subtotal += quantity * price;
            });
            
            const shipping = subtotal > 0 ? 50000 : 0;
            const total = subtotal + shipping;
            
            // Format prices
            subtotalElement.textContent = new Intl.NumberFormat('vi-VN').format(subtotal) + '₫';
            totalElement.textContent = new Intl.NumberFormat('vi-VN').format(total) + '₫';
        }
    }
    
    // Clear Cart
    const clearCartButton = document.getElementById('clearCart');
    const cartItems = document.querySelector('.col-lg-8');
    const emptyCart = document.getElementById('emptyCart');
    
    if (clearCartButton && cartItems && emptyCart) {
        clearCartButton.addEventListener('click', function() {
            // Add fade-out animation
            cartItems.style.opacity = '0';
            setTimeout(() => {
                cartItems.style.display = 'none';
                emptyCart.classList.remove('d-none');
                // Add fade-in animation
                emptyCart.style.opacity = '0';
                setTimeout(() => {
                    emptyCart.style.opacity = '1';
                }, 50);
            }, 300);
        });
    }
    
    // Add to Wishlist
    const wishlistButtons = document.querySelectorAll('.btn-wishlist');
    
    if (wishlistButtons.length > 0) {
        wishlistButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const icon = this.querySelector('i');
                
                if (icon.classList.contains('bi-heart')) {
                    icon.classList.remove('bi-heart');
                    icon.classList.add('bi-heart-fill');
                    icon.style.color = '#900202';
                    
                    // Show notification
                    showNotification('Đã thêm vào danh sách yêu thích');
                } else {
                    icon.classList.remove('bi-heart-fill');
                    icon.classList.add('bi-heart');
                    icon.style.color = '';
                    
                    // Show notification
                    showNotification('Đã xóa khỏi danh sách yêu thích');
                }
            });
        });
    }
    
    // Product Tabs
    const productTabs = document.querySelectorAll('#productTab button');
    
    if (productTabs.length > 0) {
        productTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                productTabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });
    }
    
    // Add to Cart Animation
    const addToCartButtons = document.querySelectorAll('.btn-primary:not(form .btn-primary)');
    
    if (addToCartButtons.length > 0) {
        addToCartButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Change button text temporarily
                const originalText = this.textContent;
                this.innerHTML = '<i class="bi bi-check2"></i> Đã thêm';
                this.classList.add('added');
                
                // Show notification
                showNotification('Đã thêm sản phẩm vào giỏ hàng');
                
                // Reset button after delay
                setTimeout(() => {
                    this.textContent = originalText;
                    this.classList.remove('added');
                }, 2000);
            });
        });
    }
    
    // Notification System
    function showNotification(message) {
        // Create notification element if it doesn't exist
        let notification = document.querySelector('.custom-notification');
        
        if (!notification) {
            notification = document.createElement('div');
            notification.className = 'custom-notification';
            document.body.appendChild(notification);
            
            // Add styles
            notification.style.position = 'fixed';
            notification.style.bottom = '20px';
            notification.style.right = '20px';
            notification.style.backgroundColor = '#900202';
            notification.style.color = 'white';
            notification.style.padding = '12px 20px';
            notification.style.borderRadius = '4px';
            notification.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
            notification.style.zIndex = '9999';
            notification.style.opacity = '0';
            notification.style.transform = 'translateY(20px)';
            notification.style.transition = 'all 0.3s ease';
        }
        
        // Set message and show notification
        notification.textContent = message;
        setTimeout(() => {
            notification.style.opacity = '1';
            notification.style.transform = 'translateY(0)';
        }, 100);
        
        // Hide notification after delay
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateY(20px)';
        }, 3000);
    }
    
    // Initialize any functions that need to run on page load
    if (document.querySelector('.product-price')) {
        updateProductPrice();
    }
    
    if (document.querySelector('.cart-summary')) {
        updateCartTotal();
    }
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                window.scrollTo({
                    top: target.offsetTop - 100,
                    behavior: 'smooth'
                });
            }
        });
    });
});