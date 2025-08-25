  // Check if wishlist count exists in local storage
  var wishlistCount = localStorage.getItem('wishlistCount');
  if (wishlistCount) {
      $('.wishlist-item-count').text(wishlistCount); // Update count in the UI
  }

  // Function to update wishlist count in local storage
  function updateWishlistCount(count) {
      $('.wishlist-item-count').text(count); // Update count in the UI
      localStorage.setItem('wishlistCount', count); // Update count in local storage
  }

  // Function to submit wishlist form
  function submitWishlistForm(event) {
      event.preventDefault();
      document.getElementById('wishlistForm').submit();
  }

  // Attach event listener to wishlist link
  function addToWishlist(productId) {
    var routeUrl = $('.links-details').data('route');
    var $messageDiv = $('.add-to-wishlist-message');

    // Perform AJAX request to add product to wishlist
    $.ajax({
        url: routeUrl,
        type: 'POST',
        data: {productId: productId},
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.exists) {
                // Product is already in the wishlist
                $messageDiv.text('Product is already in wishlist').show();
                setTimeout(function() {
                    $messageDiv.hide();
                }, 3000); // Hide after 3 seconds
            } else {
                // Product is successfully added to wishlist
                updateWishlistCount(response.wishlistCount);
                $messageDiv.text('Product added to wishlist').show();
                setTimeout(function() {
                    $messageDiv.hide();
                }, 3000); // Hide after 3 seconds
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}

function addToCart(productId) {
    var routeUrl = $('.add-card').data('route');
    var login = $('.add-card').data('login');
    var register = $('.add-card').data('register');

    // Send AJAX request to add product to cart
    $.ajax({
        url: routeUrl, // Endpoint to handle adding product to cart
        type: 'POST',
        data: { productId: productId },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            // Update the mini cart with the latest cart data
            updateMiniCart(response);

            // Display success message
            showSuccessMessage();
        },
        error: function(xhr, status, error) {
            if (xhr.status === 401) {
                // Construct the message with login and register links
                var message = `Please <a href="${login}">login</a> or <a href="${register}">register</a> to add items to your cart.`;
                // Display the message within a div
                $('#error-message-cart').html(message).css('display', 'block');

                // Set a timeout to hide the error message after 3 seconds (3000 milliseconds)
                setTimeout(function() {
                    $('#error-message-cart').hide();
                }, 3000);
            } else {
                console.error(error);
            }
        }
    });
}

function showSuccessMessage() {
    // Create and display success message div
    var successMessage = $('<div>')
        .addClass('success-message')
        .text('Product successfully added to cart!');

    // Append the success message to the body
    $('body').append(successMessage);

    // Apply styles to the success message
    successMessage.css({
        'position': 'fixed',
        'top': '50%',
        'right': '50%',
        'padding': '10px',
        'background-color': '#4CAF50',
        'color': 'white',
        'border-radius': '5px',
        'box-shadow': '0 2px 5px rgba(0, 0, 0, 0.2)',
        'z-index': '9999'
    });

    // Automatically hide the success message after a few seconds
    setTimeout(function() {
        successMessage.fadeOut('slow', function() {
            $(this).remove(); // Remove the success message from DOM
        });
    }, 3000); // 3000 milliseconds (3 seconds) timeout before hiding
}
$(document).ready(function() { 
    // Fetch cart data from the server on page load
    $.ajax({
        url: $('.add-card').data('fetch'), 
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            
            updateMiniCart(response); // Update the mini cart with fetched data
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
});

function updateMiniCart(cartData) {
    var productList = $('.minicart-product-list');
    var subtotal = $('.minicart-total span');
    var toptotal = $('.count-price')
    var itemCount = $('.cart-item-count');
    // Check if cartData.items is defined and is an array
    if (cartData.items && Array.isArray(cartData.items)) {
        // Iterate through cart items and append them to the product list
        cartData.items.forEach(function (item) {
            var productHTML = `
                <li>
                    <div class="minicart-product-image">
                        <img src="${item.image}" alt="${item.name}" />
                    </div>
                    <div class="minicart-product-details">
                        <h6><a href="#">${item.name}</a></h6>
                        <p>${item.price}</p>
                        <button class="close" onclick="removeFromCart(${item.id})">Remove</button>
                    </div>
                </li>`;
            productList.append(productHTML);
        });
    } else {
        // Handle the case when cartData.items is not valid
        console.error('Cart items data is invalid or missing:', cartData.items);
        // You can optionally display a message or handle the error gracefully
    }

    // Update subtotal and item count if cartData contains valid values
    if (cartData.subtotal !== undefined && cartData.cartCount !== undefined) {
        subtotal.text(cartData.subtotal.toFixed(2));
        itemCount.text(cartData.cartCount);
        toptotal.html(cartData.subtotal.toFixed(2));
    } else {
        // Handle the case when subtotal or cartCount is missing
        console.error('Subtotal or cart count data is missing or invalid:', cartData.subtotal, cartData.cartCount);
        // You can optionally display a message or handle the error gracefully
    }
}

function removeFromCart(productId) {
    // Send AJAX request to remove product from cart
    $.ajax({
        url: `/${currentLocale}/remove-from-cart/${productId}`,
        type: 'POST',
        data: { productId: productId },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            updateMiniCart(response);
            localStorage.setItem('cartData', JSON.stringify(response)); // Update local storage with new cart data
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}

  
    
    
    








