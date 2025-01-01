let deleteButtons = document.querySelectorAll(".deleteBtn");

deleteButtons.forEach((button) => {
  button.addEventListener("click", function (e) {
    e.preventDefault();

    let account = this.dataset.name;
    let account_id = this.dataset.id;

    let response = confirm(
      "Are you sure you want to delete the account:" + account + "?"
    );

    if (response) {
      fetch("/dcs/app/accounts/deleteAccount.php?id=" + account_id, {
        method: "GET",
      })
        .then((response) => response.text())
        .then((data) => {
          if (data === "success") {
            window.location.reload();
          }else{
            alert("Cannot delete due to professor is assigned to a course. please reassign a professor to that course before deleting this account.")
          }
        });
    }
  });
});