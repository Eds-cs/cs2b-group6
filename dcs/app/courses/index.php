<?php
require_once '../classes/course.class.php';

$courseObj = new Course();
$teacherObj = new Course(); // Create an instance of the Teacher class
$keyword = '';
$array = $courseObj->showCourses($keyword);  // Fetch all courses
$teachers = $teacherObj->showTeachers(); // Fetch all teachers
$record = '';

$course_id = $course_name = $yr_level = $teacher_id = $assigned_professor = '';
$course_idErr = $course_nameErr = $yr_levelErr = $teacher_idErr = '';

// Handle GET request for fetching course details

if (($_SERVER['REQUEST_METHOD'] == 'GET') && isset($_GET['id'])) {
    $course_id = $_GET['id']; // Updated variable
    $record = $courseObj->fetchCourse($course_id);

    if ($record) {
        $course_id = $record['course_id'];
        $course_name = $record['course_name'];
        $yr_level = $record['yr_level'];
        $teacher_id = $record['teacher_id'];
    } else {
        echo 'Course does not exist';
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get course_id and assigned_professor from the POST data
    $course_id = clean_input($_POST['course_id']);
    $teacher_id = clean_input($_POST['assigned_professor']);

    // Validate inputs
    if (empty($course_id)) {
        $course_idErr = 'Course ID is required.';
    }
    if (empty($teacher_id)) {
        $teacher_idErr = 'Assigned professor is required.';
    }

    // If no errors, update the course
    if (empty($course_idErr) && empty($teacher_idErr)) {
        $updated = $courseObj->editCourse($teacher_id, $course_id); // Call your update function

        if ($updated) {
            echo 'Successfully Updated Professor. click courses to refresh';
            
            exit;
        } else {
            echo 'Failed to update professor.';
            exit;
        }
    }
}


?>

<style>
    .error {
        color: red;
    }
</style>
<div class="">
    <div class="">
        <h1>Courses</h1><br>
        <table class="table">
            <thead class="table-success" id="tablehead">
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Year level</th>
                    <th scope="col">Adviser</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($array as $arr) { ?>
                    <tr>
                        <td><?= $arr['course_name'] ?></td>
                        <td><?= $arr['yr_level'] ?></td>
                        <td><?= $arr['assigned_professor'] ?></td>
                        <td><button class="btn btn-outline-success edit-btn" data-course-id="<?= $arr['course_id'] ?>"
                                data-professor-id="<?= $arr['teacher_id'] ?>">Edit</button></td>

                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Assigned Professor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST">
                    <input type="hidden" id="course_id" name="course_id" required>
                    <div class="mb-3">
                        <label for="assigned_professor" class="form-label">Assigned Professor</label>
                        <select class="form-select" id="assigned_professor" name="assigned_professor" required>
                            <option value="" disabled selected>Select Professor</option>
                            <?php foreach ($teachers as $teacher) { ?>
                                <option value="<?= $teacher['teacher_id'] ?>"><?= $teacher['assigned_professor'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success" name="save changes" value="save changes">Save
                        Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

<script>
    // When the Edit button is clicked, populate the modal
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function () {
            const courseId = this.getAttribute('data-course-id');
            const professorId = this.getAttribute('data-professor-id');

            // Set the values of course_id and assigned_professor in the modal
            document.getElementById('course_id').value = courseId;
            document.getElementById('assigned_professor').value = professorId;

            // Show the modal
            var myModal = new bootstrap.Modal(document.getElementById('editModal'));
            myModal.show();
        });
    });
</script>