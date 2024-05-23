     // Wait for the document to load
     document.addEventListener("DOMContentLoaded", function () {
        // Find the message container
        const messageContainer = document.getElementById("message-container");
  
        // Check if the container is found
        if (messageContainer) {
            // Set a timeout to hide the message container after 3 seconds
            setTimeout(function () {
                messageContainer.style.display = "none";
            }, 3000); // 3000 milliseconds (3 seconds)
        }
    });
    
    
    function decreaseQuantity() {
          var quantityInput = document.getElementById('product_quantity');
          var currentQuantity = parseInt(quantityInput.value);
          if (currentQuantity > 0) {
              quantityInput.value = currentQuantity - 1;
          }
      }
  
      function increaseQuantity() {
          var quantityInput = document.getElementById('product_quantity');
          var currentQuantity = parseInt(quantityInput.value);
          quantityInput.value = currentQuantity + 1;
      }