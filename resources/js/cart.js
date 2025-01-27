document.addEventListener('alpine:init', () => {
    Alpine.data('cart', (initialCount) => ({
        cartOpen: false,
        cartItems: [],
        loading: false,
        cartCount: initialCount,
        closeTimeout: null,
        cartItemsRoute: '',
        cartRemoveRouteTemplate: '',

        init() {
            this.cartItemsRoute = document.querySelector('meta[name="cart-items-route"]').content;
            this.cartRemoveRouteTemplate = document.querySelector('meta[name="cart-remove-route"]').content;

            window.addEventListener('cart-updated', (event) => {
                this.cartCount = event.detail.cartCount;
                this.loadCart();
            });
        },

        async loadCart() {
            this.loading = true;
            try {
                const response = await fetch(this.cartItemsRoute);
                const data = await response.json();
                this.cartItems = data.items;
                this.cartCount = data.totalQuantity;
            } catch (error) {
                console.error('Cart load failed:', error);
            } finally {
                this.loading = false;
            }
        },

        openCart() {
            clearTimeout(this.closeTimeout);
            if (!this.cartOpen) this.loadCart();
            this.cartOpen = true;
        },

        closeCart() {
            this.closeTimeout = setTimeout(() => {
                this.cartOpen = false;
            }, 300);
        },

        cancelClose() {
            clearTimeout(this.closeTimeout);
        },

        async removeItem(itemId) {
            try {
                const url = this.cartRemoveRouteTemplate.replace('CART_ITEM_ID', itemId);
                const response = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error(response.statusText);

                const data = await response.json();
                this.cartItems = this.cartItems.filter(i => i.id !== itemId);
                this.cartCount = data.cart_count;

                window.dispatchEvent(new CustomEvent('cart-updated', {
                    detail: {
                        message: data.message,
                        cartCount: data.cart_count
                    }
                }));
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to remove item: ' + error.message);
            }
        },
        
        async addToCart(artworkId, quantity = 1) {
            try {
                const response = await fetch(`/cart/add/${artworkId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ quantity })
                });

                if (!response.ok) throw new Error(response.statusText);

                const data = await response.json();

                // Dispatch the same event used in removeItem
                window.dispatchEvent(new CustomEvent('cart-updated', {
                    detail: {
                        message: data.message,
                        cartCount: data.cart_count
                    }
                }));

                this.loadCart(); // Reload cart items
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to add item: ' + error.message);
            }
        }
    }));

});