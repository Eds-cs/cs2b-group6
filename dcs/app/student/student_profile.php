<?php
$role = Role::getInstance();
$application = Application::getInstance();
$organization = new Organization();
$application->setPageTitle("Student Profile");

$studentId = 0;
// student profile being viewed by other elevated users
if (isset($_GET['student_id']) && $application->userInRole(['system_admin', 'organization_officer', 'adviser', 'dean','student_affairs'])) {
    $studentId = $_GET['student_id'];
} else {
    if (!empty($application->getStudentId()) && $application->userInRole(['student'])) {
        $studentId = $application->getStudentId();
    } else {
        header('Location: ./login/index.php');
        exit();
    }
}

$disabled = $application->userInRole(['system_admin', 'student']) ? '' : 'disabled';

$studentClass = new Student();
$studentProfile = $studentClass->getStudentProfile($studentId);
$studentOrgFees = $studentClass->getStudentOrgFees($studentId);
$organizations = $organization->getAllOrgs();

function getFeesByOrg($orgId)
{
    global $studentOrgFees;
    return array_filter($studentOrgFees, fn($fee) => $fee['organization_id'] == $orgId);
}
?>
<div class="accordion accordion-flush"
     id="accordionFlushExample">
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#flush-collapseOne"
                    aria-expanded="false"
                    aria-controls="flush-collapseOne">
                <h4>Membership</h4>
            </button>
        </h2>
        <div id="flush-collapseOne"
             class="accordion-collapse collapse <?= $application->userInRole(['organization_officer']) ? 'show' : '' ?>"
             data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">
                <div class="row g-3">
                    <div class="col">
                        <label for="lastName"
                               class="form-label">Last Name</label>
                        <input type="text"
                               class="form-control form-control-md"
                               id="lastName"
                               value="<?= $studentProfile['last_name'] ?>"
                               <?= $disabled ?>>
                    </div>
                    <div class="col">
                        <label for="firstName"
                               class="form-label">First Name</label>
                        <input type="text"
                               class="form-control form-control-md"
                               id="firstName"
                               value="<?= $studentProfile['first_name'] ?>"
                               <?= $disabled ?>>
                    </div>
                    <div class="col">
                        <label for="initial"
                               class="form-label">Initial</label>
                        <input type="text"
                               class="form-control form-control-md"
                               id="initial"
                               value="<?= $studentProfile['middle_initial'] ?>"
                               <?= $disabled ?>>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col">
                        <label for="studentNo"
                               class="form-label">Student No: <span class="fw-bold">
                                <?= $studentProfile['student_number'] ?>
                            </span></label>
                    </div>
                    <div class="col">
                        <label for="course"
                               class="form-label">Course: <span class="fw-bold"> <?= $studentProfile['course_name'] ?> -
                                <?= $studentProfile['yr_level'] ?> </span></label>
                    </div>
                </div>
                <div class="row">
                    <div class="col"></div>
                    <div class="col">Amount</div>
                    <div class="col">Paid Amount</div>
                    <div class="col">Balance</div>
                    <div class="col"></div>
                </div>
                <?php foreach ($organizations as $org) {
                    $totalPaid = 0;
                    $balance = 0;
                    ?>
                    <div class="row g-3">
                        <div class="col span-4 fw-bold"><?= $org['organization_name'] ?></div>
                    </div>
                    <hr>
                    <?php
                    foreach (getFeesByOrg($org['organization_id']) as $studentOrgFee) {
                        $feeBalance = $studentOrgFee['fee_amount'] - $studentOrgFee['total_paid'];
                        $balance += $feeBalance;
                        $totalPaid += $studentOrgFee['total_paid'];

                        ?>
                        <div class="row g-3">
                            <div class="col">
                                <?= $studentOrgFee['fee_name'] ?>
                            </div>
                            <div class="col"><?= $studentOrgFee['fee_amount'] ?></div>
                            <div class="col"><?= $studentOrgFee['total_paid'] ?></div>
                            <div class="col"><?= $feeBalance ?></div>
                            <div class="col"><?php if ($application->userInRole(['organization_officer'])) {
                                if ($feeBalance > 0) { ?>
                                        <a class="btn btn-link"
                                           data-bs-orgFeeId="<?= $studentOrgFee['organization_fee_id'] ?>"
                                           data-bs-receivedById="<?= $application->getAccountId() ?>"
                                           data-bs-studentId="<?= $studentId ?>"
                                           data-bs-toggle="modal"
                                           data-bs-target="#paymentModal">Receive Payment</a>
                                    <?php }
                            } ?>
                            </div>
                        </div>
                    <?php } ?>
                    <hr>
                    <div class="row g-3">
                        <div class="col ">Total</div>
                        <div class="col fw-bold"><?= $org['total_fee'] ?></div>
                        <div class="col fw-bold"><?= $totalPaid ?></div>
                        <div class="col fw-bold"><?= $balance ?></div>
                        <div class="col">
                            <?php if ($balance <= 0 && $studentOrgFee['is_cleared']) {
                                echo 'Cleared';
                            } else if ($application->userInRole(['organization_officer']) && $balance <= 0) { ?>
                                    <a class="btn btn-link"
                                       onclick="clearStudent(<?= $studentId ?>,<?= $application->getAccountId() ?>,<?= $application->userInRole(['organization_officer'])?>, <?= $org['organization_id'] ?>)">Clear
                                        Student</a>
                                <?php
                            } else {
                                echo 'Pending';
                            }
                            ?>

                        </div>
                    </div>
                    <br />
                <?php } ?>
                <div class="modal fade"
                     id="paymentModal"
                     tabindex="-1"
                     aria-labelledby="paymentLabel"
                     aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5"
                                    id="paymentLabel">Payment</h1>
                                <button type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <label for="amount">Enter amount</label>
                                <input type="decimal"
                                       name="amount"
                                       id="amount">
                            </div>
                            <div class="modal-footer">
                                <button type="button"
                                        id="mayPayment"
                                        class="btn btn-primary"
                                        onclick="submitPayment()">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#flush-collapseTwo"
                    aria-expanded="false"
                    aria-controls="flush-collapseTwo">
                <h4>Clearance</h4>
            </button>
        </h2>
        <div id="flush-collapseTwo"
             class="accordion-collapse collapse <?= $application->userInRole(['adviser', 'student_affair', 'dean']) ? 'show' : '' ?>"
             data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">
                <?php
                include 'clearance.php';
                ?>
            </div>
        </div>
    </div>
</div>
<div class="modal fade"
     id="successPrompt"
     tabindex="-1"
     aria-labelledby="successPromptLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5"
                    id="successPromptLabel">Success!</h1>
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                        onclick="refresh()"></button>
            </div>
            <div class="modal-body">
                Your payment has been posted.
            </div>
        </div>
    </div>
</div>
<script>
    var orgFeeId = 0;
    var studentId = 0;
    var receivedById = 0;
    const successPrompt = $('#successPrompt');

    function submitPayment() {
        $.post('student/make-payment.php', JSON.stringify({
            orgFeeId,
            studentId,
            receivedById,
            amount: $('#amount').val()
        }), function (response) {
            if (response) {
                $('#paymentModal').modal('hide');
                successPrompt.find('.modal-body').text('Your payment has been posted.');
                successPrompt.modal('show');
                $('#amount').val('');
            }
        }, 'json');
    }
    function refresh() {
        window.location.reload();
    }
    function clearStudent(studentId, approverId, roleId, orgId) {
        $.post('student/clear-student.php', JSON.stringify({
            studentId,
            approverId,
            orgId,
            roleId
        }), function (response) {
            if (response) {
                successPrompt.find('.modal-body').text(`Your sign-off has been recorded. Student is cleared!`);
                successPrompt.modal('show');
            }
        }, 'json');
    }
    const paymentModal = $('#paymentModal');
    if (paymentModal) {
        paymentModal.on('show.bs.modal', event => {
            // Button that triggered the modal
            const button = event.relatedTarget;
            // Extract info from data-bs-* attributes
            orgFeeId = button.getAttribute('data-bs-orgFeeId');
            studentId = button.getAttribute('data-bs-studentId');
            receivedById = button.getAttribute('data-bs-receivedById');
        })
    }
</script>