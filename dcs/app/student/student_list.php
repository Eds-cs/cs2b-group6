<?php


$keyword = '';
$student = new Student();
$students = $student->getAllStudents($keyword);

$studentId = 0;
if (isset($_GET["student_id"])) {
    $studentId = (int) clean_input($_GET["student_id"]);
}
?>

<?php if ($studentId != 0) { ?>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <a href="/dcs/app">
                    < Back</a>
            </li>
        </ol>
    </nav>
    <?php
    include "student_profile.php";
} else { ?>
    <table class="table" id="Student-table">
        <thead class="table-success">
            <h3>Organization Confirmation</h3>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Course</th>
                <th scope="col">Year</th>
                <th scope="col">Overall Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($students as $student) {
                ?>
                <form method="POST" action="?student_id=<?= $student['student_id'] ?>">
                    <tr>
                        <td><?= "{$student['last_name']}, {$student['first_name']} {$student['middle_initial']}" ?></td>
                        <td><?= $student['course_name'] ?></td>
                        <td><?= $student['yr_level'] ?></td>
                        <td><?= $student['overall_status'] ?></td>
                        <td>
                            <button type="submit" class="btn btn-success">View</button>
                        </td>
                    </tr>
                </form>
            <?php } ?>
        </tbody>
    </table>
<?php } ?>