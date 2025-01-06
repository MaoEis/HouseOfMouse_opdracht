document.addEventListener("DOMContentLoaded", () => {
  const passwordForm = document.getElementById("passwordform");
  const error = document.getElementById("error");

  if (!passwordForm) {
    console.error("Password form not found.");
    return;
  }

  passwordForm.addEventListener("submit", (e) => {
    e.preventDefault();

    const currentPassword = document.getElementById("current_password").value;
    const newPassword = document.getElementById("new_password").value;
    const confirmPassword = document.getElementById("confirm_password").value;

    const passwordRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}$/;

    if (!passwordRegex.test(newPassword)) {
      error.textContent =
        "Password must contain at least 8 characters, a number, an uppercase letter, and a symbol.";
      error.style.display = "block";
      return;
    }

    if (newPassword !== confirmPassword) {
      error.textContent = "Passwords do not match.";
      error.style.display = "block";
      return;
    }

    const formData = new FormData(passwordForm);

    fetch("../assets/change_pw.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((result) => {
        if (result.status === "success") {
          alert("Password changed successfully!");
          location.reload();
        } else {
          error.textContent = result.message;
          error.style.display = "block";
        }
      })
      .catch((err) => {
        error.textContent = "An error occurred. Please try again later.";
        error.style.display = "block";
        console.error("Error:", err);
      });
  });
});
