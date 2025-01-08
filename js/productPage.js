document.addEventListener("DOMContentLoaded", function () {
  const reviewForm = document.querySelector(".reviewForm");

  if (reviewForm) {
    reviewForm.addEventListener("submit", function (e) {
      e.preventDefault(); // Prevent the form from submitting normally

      let formData = new FormData(this);

      fetch("assets/submit_review.php", {
        // Make sure this path is correct
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          const reviewMessage = document.getElementById("reviewMessage");
          if (data.success) {
            // If review is successfully added, show the success message
            reviewMessage.innerHTML = "<p>Review submitted successfully.</p>";

            // Dynamically update reviews section with the new HTML
            document.querySelector(".reviews").innerHTML = data.reviewsHtml;
            document.getElementById("comment").value = "";
            const stars = document.querySelectorAll(
              '.star-rating input[type="radio"]'
            );
            stars.forEach((star) => (star.checked = false));
          } else {
            reviewMessage.innerHTML =
              "<p>Failed to submit review. Please try again.</p>";
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          document.getElementById("reviewMessage").innerHTML =
            "<p>Something went wrong. Please try again.</p>";
        });
    });
  }
});
