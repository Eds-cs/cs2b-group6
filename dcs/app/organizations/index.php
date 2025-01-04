
<?php
require_once '../classes/organization.class.php';
$orgObj = new Organization();
$organizations = $orgObj->OrgDetails(); // Assuming this fetches a list of organizations

$organization_name = $fee_name = $fee_amount = $old_fee = $date_created = $date_updated = $new_fee_amount = $old_fee_amount = $fee_id = '';
$organization_nameErr = '';

//adding organization
if (($_SERVER['REQUEST_METHOD'] == "POST") && isset($_POST['add_org'])) {
  $organization_name = clean_input($_POST['organization_name']);

  //validation
  if (empty($organization_name)) {
    $organization_nameErr = "Input a valid Organization name";
  }

  if (empty($organization_nameErr)) {
    $orgObj->organization_name = $organization_name;

    if ($orgObj->addOrg()) {
      $_SESSION['success'] = 'Organization added successfully!';
      exit;
    } else {
      $_SESSION['error'] = 'Failed to add organization. Please try again.';
    }
  }
}

// adding fees for organizations
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['assign_fee'])) {
  $organization_id = $_POST['organization_id'];
  $fee_name = clean_input($_POST['fee_name']);
  $fee_amount = clean_input($_POST['fee_amount']);

  // Validation
  if (empty($organization_id)) {
    $_SESSION['error'] = 'Please select an organization.';
  } elseif (empty($fee_name)) {
    $_SESSION['error'] = 'Fee name cannot be empty.';
  } elseif ($fee_amount <= 0) {
    $_SESSION['error'] = 'Fee amount must be greater than 0.';
  } else {
    $orgObj->fee_name = $fee_name;
    $orgObj->fee_amount = $fee_amount;

    if ($orgObj->addFees($organization_id)) {
      $_SESSION['success'] = 'Fee assigned successfully!';
      exit;
    } else {
      $_SESSION['error'] = 'Failed to assign fee. Please try again.';
    }
  }
}

if (isset($_POST['save_changes'])) {
  // Assuming you have a database connection `$db`
  $organization_name = $_POST['organization_name']; // The updated organization name

  // Loop through the fee data
  foreach ($_POST['fee_name'] as $fee_id => $fee_name) {
    if (isset($_POST['fee_amount'][$fee_id]) && isset($_POST['old_fee'][$fee_id])) {
      $old_fee_amount = $_POST['old_fee'][$fee_id];
      $new_fee_amount = $_POST['fee_amount'][$fee_id];



      // Call the update function
      $result = $orgObj->updateFee($fee_id, $organization_name, $fee_name, $new_fee_amount, $old_fee_amount);

      if ($result) {
        // Success, store the updated fee ID
        $_SESSION['success'] = 'Organization Information updated successfully!';

      } else {
        // Failure, handle the error
        $_SESSION['error'] = "Failed to update fee for fee ID $fee_id.";
        echo "Error updating fee ID: $fee_id\n"; // Debug error message
      }
    } else {
      // Handle missing fee data
      $_SESSION['error'] = "Missing data for fee ID $fee_id.";
      echo "Missing data for fee ID: $fee_id\n"; // Debug missing data
      exit;
    }
  }

}




?>

<?php if (isset($_SESSION['success'])): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= $_SESSION['success'];
    unset($_SESSION['success']); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php elseif (isset($_SESSION['error'])): ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?= $_SESSION['error'];
    unset($_SESSION['error']); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif;

?>


<style>
  .error {
    color: red;
  }
</style>
<div class="">
  <div class="">
    <h1>Organizations</h1><br>
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addOrganizationModal"> Add
      Organization</button>
    <button class="btn btn-success btn-add-fee" data-id="<?= $org['organization_id'] ?>">Add Fee</button>
    <br>
    <br>
    <table class="table">
      <thead class="table-success" id="tablehead">
        <tr>
          <th scope="col">Organization</th>
          <th scope="col">Fees</th>
          <th scope="col">Total Amount</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <?php

      $array = $orgObj->OrgDetails();

      foreach ($array as $orgId => $org) {
        // Get the organization ID directly from the organization details
        $orgId = $org['organization_id'];
        $modalId = 'editModal' . $orgId;
        ?>
        <tbody>
          <tr>
            <td><?= $org['organization_name'] ?></td>


            <td>
              <?php
              if (!empty($org['fees'])) {
                foreach ($org['fees'] as $fee) {
                  echo $fee['fee_name'] . ' = ₱' . $fee['fee_amount'] . '<br>';
                }
              } else {
                echo "No fees assigned.";
              }
              ?>
            </td>

            <td>
              ₱ <?= array_sum(array_column($org['fees'], 'fee_amount')) ?>
            </td>

            <td>
              <!-- Use dynamic modal ID for each edit button -->
              <button class="btn btn-outline-success" data-bs-toggle="modal"
                data-bs-target="#<?= $modalId ?>">Edit</button>
              <button class="btn btn-outline-danger deleteBtn" data-id="<?= htmlspecialchars($orgId) ?>"
                data-name="<?= htmlspecialchars($org['organization_name']) ?>">Delete</button>

            </td>
          </tr>
        </tbody>

        <!-- Edit Modal (unique for each organization) -->
        <div class="modal fade" id="<?= $modalId ?>" tabindex="-1" role="dialog" aria-labelledby="<?= $modalId ?>Label"
          aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="<?= $modalId ?>Label">Edit Fees for <?= $org['organization_name'] ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form id="editFeesForm" method="POST">
                  <!-- Organization Name -->
                  <div class="mb-3">
                    <label for="organizationName" class="form-label">Organization Name</label>
                    <input type="text" class="form-control" id="organizationName" name="organization_name"
                      value="<?= $org['organization_name'] ?>">
                  </div>

                  <!-- Fees Section -->
                  <div class="mb-3">
                    <label for="fees" name="fees">Fees:</label><br>
                    <?php
                    if (!empty($org['fees'])) {
                      foreach ($org['fees'] as $fee) {
                        echo $fee['fee_name'] . ": <br>";
                        ?>
                        <input type="hidden" name="old_fee[<?= $fee['organization_fee_id'] ?>]"
                          value="<?= $fee['fee_amount'] ?>">

                        <input type="text" name="fee_name[<?= $fee['organization_fee_id'] ?>]"
                          value="<?= $fee['fee_name'] ?>">
                        <!-- Editable fee amount -->
                        <input type="number" name="fee_amount[<?= $fee['organization_fee_id'] ?>]"
                          value="<?= $fee['fee_amount'] ?>">
                        <br><br>
                        <?php
                      }
                    } else {
                      echo "No fees assigned.";
                    }
                    ?>
                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" name="save_changes">Save changes</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <?php
      } ?>
    </table>

  </div>
</div>



<!-- Add Organization Modal -->
<div class="modal fade" id="addOrganizationModal" tabindex="-1" aria-labelledby="addOrganizationModalLabel"
  aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h5 class="modal-title" id="addOrganizationModalLabel">Add Organization</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body">
        <form method="POST">
          <!-- Organization Name Input -->
          <div class="mb-3">
            <label for="organizationName" class="form-label">Organization Name</label>
            <input type="text" class="form-control" id="organizationName" name="organization_name"
              placeholder="Enter organization name" required>
            <!-- Display validation error -->
            <?php if (!empty($organization_nameErr)): ?>
              <span class="error"><?= $organization_nameErr ?></span>
            <?php endif; ?>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success" name="add_org">Add Organization</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<script>
  document.getElementById('addOrganizationModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('organizationName').value = '';
  });
</script>



<!-- Add Fees Modal -->
<div class="modal fade" id="addFeesModal" tabindex="-1" aria-labelledby="addFeesModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h5 class="modal-title" id="addFeesModalLabel">Assign Fees to Organization</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body">
        <form method="POST">
          <!-- Hidden Input for Organization ID -->
          <input type="hidden" id="organizationId" name="organization_id">

          <!-- Organization Dropdown (Optional for manual selection) -->
          <div class="mb-3">
            <label for="organizationDropdown" class="form-label">Select Organization</label>
            <select class="form-select" id="organizationDropdown" name="organization_id" required>
              <option value="" selected disabled>Choose an organization</option>
              <?php foreach ($orgObj->OrgDetails() as $orgId => $org): ?>
                <option value="<?= $orgId ?>"><?= htmlspecialchars($org['organization_name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Fee Name Input -->
          <div class="mb-3">
            <label for="feeName" class="form-label">Fee Name</label>
            <input type="text" class="form-control" id="feeName" name="fee_name" placeholder="Enter fee name" required>
          </div>

          <!-- Fee Amount Input -->
          <div class="mb-3">
            <label for="feeAmount" class="form-label">Fee Amount</label>
            <input type="number" class="form-control" id="feeAmount" name="fee_amount" placeholder="Enter fee amount"
              required>
          </div>

          <!-- Modal Footer -->
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success" name="assign_fee">Assign Fee</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<script>
  // Open Add Fees Modal with Organization ID
  document.querySelectorAll('.btn-add-fee').forEach(button => {
    button.addEventListener('click', function () {
      const orgId = this.getAttribute('data-id');
      // Set the organization ID in the hidden input
      document.getElementById('organizationId').value = orgId;
      // Show the modal
      const addFeesModal = new bootstrap.Modal(document.getElementById('addFeesModal'));
      addFeesModal.show();
    });
  });

</script>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteConfirmationModalLabel">Delete Organization or Fees</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>What would you like to do with <span id="orgNameToDelete"></span>?</p>
        <button type="button" class="btn btn-danger" id="deleteOrgBtn">Delete Organization</button>
        <button type="button" class="btn btn-warning" id="deleteFeesBtn">Delete Fees Only</button>
      </div>
    </div>
  </div>
</div>

<script src="./organizations/deleteorg.js"></script>