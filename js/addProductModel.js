document.addEventListener("DOMContentLoaded", function () {
  if (window.productAdded) {
    // Show success modal
    const successModal = document.getElementById("successModalAdd");
    successModal.style.display = "block";

    // Button actions
    document
      .getElementById("seeProductBtnAdd")
      .addEventListener("click", function () {
        // Redirect to the product page with the product ID
        window.location.href = `productPage.php?id=${window.productId}`;
      });

    document
      .getElementById("addAnotherBtnAdd")
      .addEventListener("click", function () {
        // Reload the page for adding another product
        window.location.reload();
      });

    // Close modal when clicking outside of it
    window.addEventListener("click", function (event) {
      if (event.target === successModal) {
        successModal.style.display = "none";
      }
    });
  }
});
