(() => {
  // scripts/complete-task.js
  document.querySelectorAll(".vikunja-task").forEach((task) => {
    task.addEventListener("change", function() {
      if (this.checked) {
        fetch("/api/complete-task/", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify({
            id: task.dataset.id,
            frequency: Number(task.dataset.frequency)
          })
        }).then((response) => {
          if (!response.ok) {
            throw new Error("Fetch failed - Invalid data");
          }
        });
      }
    });
  });
})();
