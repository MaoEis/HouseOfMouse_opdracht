$(document).ready(function () {
  function showCustomModal(message) {
    $("#modalMessage").text(message);
    $("#customModal").fadeIn();

    // Close modal when clicking the close button
    $("#closeModal").click(function () {
      $("#customModal").fadeOut();
    });

    // Close modal when clicking outside the modal content
    $(window).click(function (event) {
      if ($(event.target).is("#customModal")) {
        $("#customModal").fadeOut();
      }
    });
  }

  let productIdToDelete = null;

  function showConfirmModal(callback) {
    $("#confirmModal").fadeIn();

    // Handle Yes button
    $("#confirmYes")
      .off()
      .click(function () {
        $("#confirmModal").fadeOut();
        if (callback) callback(true);
      });

    // Handle No button
    $("#confirmNo")
      .off()
      .click(function () {
        $("#confirmModal").fadeOut();
        if (callback) callback(false);
      });

    // Close modal when clicking outside the modal content
    $(window).click(function (event) {
      if ($(event.target).is("#confirmModal")) {
        $("#confirmModal").fadeOut();
        if (callback) callback(false);
      }
    });
  }

  $(".adminDel").click(function () {
    productIdToDelete = $(this).data("product-id");
    console.log("Delete button clicked, product ID:", productIdToDelete);

    showConfirmModal(function (isConfirmed) {
      if (isConfirmed) {
        $.ajax({
          url: "adminIndex.php",
          type: "POST",
          data: { id: productIdToDelete, action: "delete" },
          success: function (response) {
            console.log("AJAX request successful, response:", response);
            if (response.trim() === "success") {
              showCustomModal("Product deleted successfully.");
              $('[data-product-id="' + productIdToDelete + '"]')
                .closest(".collectionItem")
                .remove();
            } else {
              showCustomModal(
                "Failed to delete the product. Please try again."
              );
            }
          },
          error: function (xhr, status, error) {
            console.error(
              "AJAX request failed:",
              xhr.responseText,
              status,
              error
            );
            showCustomModal("An error occurred. Please try again later.");
          },
        });
      } else {
        console.log("User canceled the deletion.");
      }
    });
  });
});
