<?php
global $studentId;
$application = Application::getInstance();
$role = Role::getInstance();
$student = new Student();
$orgStatuses = $student->getOrgStatusByStudent($studentId);
$facultyClearances = $student->getFacultyClearances($studentId);
$adviser = findFirstByRoles('adviser');
$student_affair = findFirstByRoles('student_affairs');
$dean = findFirstByRoles('dean');

function findFirstByRoles($roles)
{
    global $facultyClearances;
    foreach ($facultyClearances as $facultyClearance) {
        if (str_contains($facultyClearance["role_name"], $roles)) {
            return $facultyClearance;
        }
    }
}
?>
<div class="row">
    <div class="col-5"></div>
    <div class="col-3">Approver</div>
    <div class="col-2">Date Approved</div>
    <div class="col-2">Status</div>
</div>
<div class="row">
    <div class="col">Organizations(s)</div>
</div>
<?php foreach ($orgStatuses as $orgStatus) { ?>
    <div class="row">
        <div class="col-1"></div>
        <div class="col-4"><?= $orgStatus['organization_name'] ?></div>
        <div class="col-3"><?= $orgStatus['approver'] ?></div>
        <div class="col-2"><?= $orgStatus['date_approved'] ?></div>
        <div class="col-2"><?= !empty($orgStatus['date_approved']) ? 'Cleared' : 'Pending' ?></div>
    </div>
    <hr>
<?php } ?>

<div class="row">
    <div class="col-5">Adviser</div>
    <div class="col-3"><?= $adviser['approver'] ?? '' ?></div>
    <div class="col-2"><?= $adviser['date_approved'] ?? '' ?></div>
    <div class="col-2"><?php if (!empty($adviser['date_approved'])) {
        echo 'Cleared';
    } else {
        if ($application->userInRole(['adviser'])) { ?>
                <a class="btn btn-link"
                   onclick="clearStudent(<?= $studentId ?>,<?= $application->getTeacherId() ?>,null, null)">Clear
                    Student</a>
            <?php } else {
            echo 'Pending';
        }
    } ?>
    </div>
</div>

<div class="row">
    <div class="col-5">Student Affairs</div>
    <div class="col-3"><?= $student_affair['approver'] ?? '' ?></div>
    <div class="col-2"><?= $student_affair['date_approved'] ?? '' ?></div>
    <div class="col-2"><?php if (!empty($student_affair['date_approved'])) {
        echo 'Cleared';
    } else {
        if ($application->userInRole(['student_affairs'])) { ?>
                <a class="btn btn-link"
                   onclick="clearStudent(<?= $studentId ?>,<?= $application->getTeacherId() ?>,null, null)">Clear
                    Student</a>
            <?php } else {
            echo 'Pending';
        }
    } ?>
    </div>
</div>

<div class="row">
    <div class="col-5">Dean</div>
    <div class="col-3"><?= $dean['approver'] ?? '' ?></div>
    <div class="col-2"><?= $dean['date_approved'] ?? '' ?></div>
    <div class="col-2"><?php if (!empty($dean['date_approved'])) {
        echo 'Cleared';
    } else {
        if ($application->userInRole(['dean'])) { ?>
                <a class="btn btn-link"
                   onclick="clearStudent(<?= $studentId ?>,<?= $application->getTeacherId() ?>,null, null)">Clear
                    Student</a>
            <?php } else {
            echo 'Pending';
        }
    } ?>
    </div>
</div>