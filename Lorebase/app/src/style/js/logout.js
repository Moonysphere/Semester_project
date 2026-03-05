
      // Logout functionality
      const logoutBtn = document.getElementById("logoutBtn");
      if (logoutBtn) {
        logoutBtn.addEventListener("click", async function () {
          try {
            const response = await fetch("/logout", {
              method: "POST",
              headers: {
                Accept: "application/json",
              },
            });

            const data = await response.json();

            if (data.success) {
              window.location.href = "/login";
            }
          } catch (error) {
            // Fallback: redirection directe
            window.location.href = "/logout";
          }
        });
      }
   