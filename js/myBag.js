document.querySelectorAll(".indexAddBtn").forEach((button) => {
  button.addEventListener("click", function (event) {
    event.preventDefault();

    // Get the product ID and product name
    const productId = this.getAttribute("data-product-id");
    const productName = this.getAttribute("data-product-name");

    // Add the product to the cart (make an AJAX request)
    alert("Product added to cart: " + productName); // Show product name instead of ID

    // Create FormData object and append product ID and quantity
    const formData = new FormData();
    formData.append("productId", productId);
    formData.append("quantity", 1); // Assuming a quantity of 1 for now

    // Send data using AJAX
    fetch("assets/add_to_cart.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        const contentType = response.headers.get("Content-Type");
        if (contentType && contentType.includes("application/json")) {
          return response.json();
        } else {
          throw new Error("Expected JSON response, but got " + contentType);
        }
      })
      .then((data) => {
        console.log("Response from server:", data);
        // Handle the response from the server, e.g., update cart UI
      })
      .catch((error) => {
        console.error("Error adding to cart:", error);
      });
  });
});
