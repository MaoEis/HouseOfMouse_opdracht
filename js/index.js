$(".indexAddBtn").click(function () {
  var productId = $(this).data("product-id");
  var productName = $(this).data("product-name");

  $.ajax({
    url: "assets/add_to_cart.php",
    method: "POST",
    data: {
      product_id: productId,
      product_name: productName,
    },
    success: function (response) {
      alert(response.message);
    },
    error: function () {
      alert("Failed to add product to cart");
    },
  });
});
