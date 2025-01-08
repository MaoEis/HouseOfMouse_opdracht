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
      if (response.message) {
        showSuccessModal();
      } else {
        alert("Failed to add product to cart");
      }
    },
    error: function () {
      alert("Failed to add product to cart");
    },
  });
});

function showSuccessModal() {
  const successModal = document.getElementById("successModal");
  successModal.style.display = "flex";

  const closeModalButton = document.getElementById("closeModal");
  closeModalButton.addEventListener("click", function () {
    successModal.style.display = "none";
  });
}
