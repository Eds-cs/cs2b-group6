document.querySelectorAll('.deleteBtn').forEach((button) => {
    button.addEventListener('click', function() {
      // Get organization name and ID
      const orgName = this.dataset.name;
      const orgId = this.dataset.id;
  
      // Set the organization name in the modal
      document.getElementById('orgNameToDelete').textContent = orgName;
  
      // Open the delete confirmation modal
      const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
      deleteModal.show();
  
      // Handle the delete organization button click
      document.getElementById('deleteOrgBtn').onclick = function() {
        // Send request to delete the entire organization
        fetch(`/dcs/app/organizations/deleteorg.php?id=${orgId}&action=deleteOrg`, {
          method: 'GET',
        })
        .then(response => response.text())
        .then(data => {
          console.log(data); // Log the response for debugging
          if (data === 'success') {
            window.location.reload();
          } else {
            alert(`Error: ${data}`); // Show error message from server
          }
        })
        .catch(error => {
          console.error("Error:", error);
          alert("An unexpected error occurred.");
        });
  
        // Close the modal
        deleteModal.hide();
      };
  
      // Handle the delete fees button click
      document.getElementById('deleteFeesBtn').onclick = function() {
        // Send request to delete only the fees
        fetch(`/dcs/app/organizations/deleteFees.php?id=${orgId}`, {
          method: 'GET',
        })
        .then(response => response.text())
        .then(data => {
          console.log(data); // Log the response for debugging
          if (data === 'success') {
            window.location.reload();
          } else {
            alert(`Error: ${data}`); // Show error message from server
          }
        })
        .catch(error => {
          console.error("Error:", error);
          alert("An unexpected error occurred.");
        });
  
        // Close the modal
        deleteModal.hide();
      };
    });
  });
  