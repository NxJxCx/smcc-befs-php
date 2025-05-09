<?php

authenticated_page("reviewer");

// Sanitize input
$stud_id = htmlspecialchars($_REQUEST['stud_id'], ENT_QUOTES, 'UTF-8');

// Fetch student details using prepared statement
$stmt = conn()->prepare("SELECT
    students.profile_image AS student_profile_image,
    students.lrn_num AS lrn_num,
    students.fname AS fname,
    students.lname AS lname,
    students.gender AS gender,
    students.username AS username,
    students.status AS status,
    students.complete_address AS complete_address,
    year_level.description AS yr_desc,
    course.description AS c_desc,
    section.description AS sec_desc,
    students.about AS about
FROM students
JOIN year_level ON students.year_level_id = year_level.id
JOIN course ON students.course_id = course.id
JOIN section ON students.section_id = section.id
WHERE students.id = ?");
$stmt->bind_param("i", $stud_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_array()) {
    $lrn_num = $row['lrn_num'];
    $sfname = $row['fname'];
    $slname = $row['lname'];
    $gender = $row['gender'];
    $username = $row['username'];
    $status = $row['status'];
    $complete_address = $row['complete_address'];
    $yr_desc = $row['yr_desc'];
    $c_desc = $row['c_desc'];
    $sec_desc = $row['sec_desc'];
    $about = $row['about'];
    $student_profile_image = $row['student_profile_image'];
} else {
    die("Error: " . mysqli_error(conn()->get_conn()));
}

// Fetch subject count using prepared statement
$stmt = conn()->prepare("SELECT COUNT(ss.subjects_id) AS sub_count
    FROM students_subjects AS ss
    WHERE ss.students_id = ?");
$stmt->bind_param("i", $stud_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_array()) {
    $sub_count = $row['sub_count'];
} else {
    die("Error: " . mysqli_error(conn()->get_conn()));
}

$user_id = user_id();

// Fetch dean/user details using prepared statement
$stmt = conn()->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_array()) {
    $fname = ucfirst(strtolower($row['fname']));
    $lname = ucfirst(strtolower($row['lname']));
    $type = ucfirst(strtolower($row['type']));
    $dean_profile_image = !empty($row['profile_image']) ? $row['profile_image'] : '../assets/img/profile-img2.jpg';
}

admin_html_head("Student Profile", [
    // [ "type" => "style", "href" => "assets/vendor/simple-datatables/style.css" ],
    [ "type" => "style", "href" => "assets/css/style.css" ],
]); // html head

?>

<body>
    <?php
    require_once get_reviewer_header();
    require_once get_reviewer_sidebar();
    ?>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Student's Profile</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="reviewer_home">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="reviewer_students">List of Student</a></li>
                    <li class="breadcrumb-item">Student Profile</li>
                </ol>
            </nav>
        </div>

        <section class="section profile">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body pt-3">
                            <ul class="nav nav-tabs nav-tabs-bordered">
                                <li class="nav-item">
                                    <button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#student-results">Results</button>
                                </li>
                            </ul>

                            <div class="tab-content pt-2">
                                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                                    <form>
                                        </br>
                                        <img src='<?= !empty($student_profile_image) ? base_url() . "/{$student_profile_image}" : base_url() . "/assets/img/profile-img2.jpg"; ?>' alt='Profile Image' class='rounded-circle' width='100'>
                                        </br>

                                        <h5 class="card-title">About</h5>
                                        <?php
                                        if (empty($about)) {
                                            echo "<p class='small fst-italic'>Nothing to Show...</p>";
                                        } else {
                                            echo "<p class='small fst-italic'>{$about}</p>";
                                        }
                                        ?>
                                        <h5 class="card-title">Profile Details</h5>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-4 label ">ID Number</div>
                                            <div class="col-lg-9 col-md-8"><?= $lrn_num ? "<b>{$lrn_num}</b>" : "<span class='badge bg-danger'>Not Yet Assign</span>"; ?></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-4 label ">Full Name</div>
                                            <div class="col-lg-9 col-md-8"><?= "$sfname $slname"; ?></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-4 label ">Gender</div>
                                            <div class="col-lg-9 col-md-8"><?= $gender; ?></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-4 label">Username</div>
                                            <div class="col-lg-9 col-md-8"><?= $username; ?></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-4 label">Password</div>
                                            <div class="col-lg-9 col-md-8">*****</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-4 label ">Complete Address</div>
                                            <div class="col-lg-9 col-md-8"><?= $complete_address ?: "<span class='badge bg-danger'>None</span>"; ?></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-4 label ">Year Level</div>
                                            <div class="col-lg-9 col-md-8"><?= $yr_desc ?: "<span class='badge bg-danger'>Not Yet Assign</span>"; ?></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-4 label ">Course</div>
                                            <div class="col-lg-9 col-md-8"><?= $c_desc ?: "<span class='badge bg-danger'>Not Yet Assign</span>"; ?></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-4 label ">Section</div>
                                            <div class="col-lg-9 col-md-8"><?= $sec_desc ?: "<span class='badge bg-danger'>Not Yet Assign</span>"; ?></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-4 label ">Subject Counts</div>
                                            <div class="col-lg-9 col-md-8"><?= $sub_count > 0 ? $sub_count : "<span class='badge bg-danger'>Empty</span>"; ?></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-4 label ">Status</div>
                                            <div class="col-lg-9 col-md-8"><?= $status == "For Approval" ? "<span class='badge bg-danger'>For Approval</span>" : "<span class='badge bg-success'>{$status}</span>"; ?></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-4 label">Average Score</div>
                                            <?php
                                            $stmt = conn()->prepare("SELECT SUM(ss.average) AS sum_average FROM student_score as ss WHERE ss.stud_id = ?");
                                            $stmt->bind_param("i", $stud_id);
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            if ($row = $result->fetch_array()) {
                                                $sum_average = $row['sum_average'];
                                                echo "<div class='col-lg-9 col-md-8'>" . ($sum_average ? number_format($sum_average, 2) . " %" : "<span class='badge bg-danger'>Empty</span>") . "</div>";
                                            }
                                            ?>
                                        </div>
                                    </form>
                                </div>

                                <!-- Results Tab -->
                                <div class="tab-pane fade" id="student-results">
                                    <!-- Preboard 1 -->
                                    <section class="section">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h5 class="card-title">PREBOARD 1</h5>

                                                        <?php
                                                        // Query to calculate the total average score for PREBOARD 1
                                                        $stmt = conn()->prepare("
                                                            SELECT AVG(ss.average) AS total_average
                                                            FROM student_score as ss
                                                            WHERE ss.stud_id = ? 
                                                                AND ss.level = 'PREBOARD1'
                                                        ");
                                                        $stmt->bind_param("i", $stud_id);
                                                        $stmt->execute();
                                                        $result = $stmt->get_result();
                                                        $row = $result->fetch_array();
                                                        $total_average = $row['total_average'];

                                                        // Display total average score if available
                                                        if ($total_average !== null) {
                                                            echo "<p><strong>Total Average Score: </strong>" . number_format($total_average, 2) . " %</p>";
                                                        } else {
                                                            echo "<p><strong>Total Average Score: </strong>Not available</p>";
                                                        }
                                                        ?>

                                                        <!-- Table with stripped rows -->
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Code No.</th>
                                                                    <th>Description</th>
                                                                    <th>Status</th>
                                                                    <th>Score</th>
                                                                    <th>Average</th>
                                                                    <th>Percentile</th>
                                                                    <th>Reviewer</th>
                                                                    <th>Dean</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $stmt1 = conn()->prepare("
                                                                    SELECT 
                                                                        sj.code AS code,
                                                                        sj.description AS description,
                                                                        MAX(ssj.status) AS status,
                                                                        MAX(ssc.score) AS score,
                                                                        MAX(ssc.total_items) AS items,
                                                                        MAX(ssc.average) AS avg_score,
                                                                        MAX(sp.percent) AS percent,
                                                                        MAX(ssc.remarks) AS remarks,
                                                                        MAX(ssc.remarks2) AS remarks2
                                                                    FROM 
                                                                        student_score AS ssc 
                                                                    JOIN 
                                                                        students_subjects AS ssj 
                                                                        ON ssj.subjects_id = ssc.sub_id
                                                                        AND ssj.students_id = ssc.stud_id
                                                                    JOIN 
                                                                        subjects AS sj 
                                                                        ON ssj.subjects_id = sj.id
                                                                    LEFT JOIN 
                                                                        subject_percent AS sp 
                                                                        ON sp.sub_id = sj.id
                                                                    WHERE 
                                                                        ssc.stud_id = ? 
                                                                        AND ssc.level = 'PREBOARD1'
                                                                    GROUP BY 
                                                                        sj.code, sj.description;
                                                                ");
                                                                $stmt1->bind_param("i", $stud_id);
                                                                $stmt1->execute();
                                                                $result1 = $stmt1->get_result();

                                                                while ($row = $result1->fetch_array()) {
                                                                    $code = $row['code'];
                                                                    $description = $row['description'];
                                                                    $status = $row['status'];
                                                                    $score = $row['score'];
                                                                    $items = $row['items'];
                                                                    $avg_score = $row['avg_score'];
                                                                    $percent = $row['percent'];
                                                                    $remarks = $row['remarks'];
                                                                    $remarks2 = $row['remarks2'];
                                                                    $formatted_avg_score = number_format($avg_score, 2); // Format individual subject average
                                                                ?>
                                                                    <tr>
                                                                        <td><?= $code; ?></td>
                                                                        <td><?= $description; ?></td>
                                                                        <td><?= $status; ?></td>
                                                                        <td><?= "$score  / $items"; ?></td>
                                                                        <td><?= $formatted_avg_score; ?> %</td>
                                                                        <td><?= $percent; ?>%</td>
                                                                        <td style="max-width: 200px; overflow-x: auto;"><?= htmlspecialchars($remarks ?: ""); ?></td>
                                                                        <td style="max-width: 200px; overflow-x: auto;"><?= htmlspecialchars($remarks2 ?: ""); ?></td>
                                                                    </tr>
                                                                <?php
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>

                                    <!-- Preboard 2 -->
                                    <section class="section">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h5 class="card-title">PREBOARD 2</h5>
                                                        <!-- Table with stripped rows -->
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Code No.</th>
                                                                    <th>Description</th>
                                                                    <th>Status</th>
                                                                    <th>Score</th>
                                                                    <th>Average</th>
                                                                    <th>Percentile</th>
                                                                    <th>Reviewer</th>
                                                                    <th>Dean</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $stmt2 = conn()->prepare("
                                                                    SELECT 
                                                                        sj.code AS code,
                                                                        sj.description AS description,
                                                                        MAX(ssj.status) AS status,
                                                                        MAX(ssc.score) AS score,
                                                                        MAX(ssc.total_items) AS items,
                                                                        MAX(ssc.average) AS avg_score,
                                                                        MAX(sp.percent) AS percent,
                                                                        MAX(ssc.remarks) AS remarks,
                                                                        MAX(ssc.remarks2) AS remarks2
                                                                    FROM 
                                                                        student_score AS ssc 
                                                                    JOIN 
                                                                        students_subjects AS ssj 
                                                                        ON ssj.students_id = ssc.stud_id
                                                                        AND ssj.subjects_id = ssc.sub_id
                                                                    JOIN 
                                                                        subjects AS sj 
                                                                        ON ssj.subjects_id = sj.id
                                                                    LEFT JOIN 
                                                                        subject_percent AS sp  
                                                                        ON sp.sub_id = sj.id
                                                                    WHERE 
                                                                        ssc.stud_id = ? 
                                                                        AND ssc.level = 'PREBOARD2'
                                                                        AND ssj.level = 'PREBOARD2'
                                                                    GROUP BY 
                                                                        sj.code, sj.description;
                                                                ");

                                                                $stmt2->bind_param("i", $stud_id);
                                                                $stmt2->execute();
                                                                $result2 = $stmt2->get_result();

                                                                while ($row = $result2->fetch_array()) {
                                                                    $code = $row['code'];
                                                                    $description = $row['description'];
                                                                    $status = $row['status'];
                                                                    $score = $row['score'];
                                                                    $items = $row['items'];
                                                                    $avg_score = $row['avg_score'];
                                                                    $percent = $row['percent'];
                                                                    $remarks = $row['remarks'];
                                                                    $remarks2 = $row['remarks2'];
                                                                    $formatted_sum_average = number_format($avg_score, 2);
                                                                ?>
                                                                    <tr>
                                                                        <td><?= $code; ?></td>
                                                                        <td><?= $description; ?></td>
                                                                        <td><?= $status; ?></td>
                                                                        <td><?= "$score  / $items" ?></td>
                                                                        <td><?= $formatted_sum_average; ?> %</td>
                                                                        <td><?= $percent; ?>%</td>
                                                                        <td style="max-width: 200px; overflow-x: auto;"><?= htmlspecialchars($remarks ?: ""); ?></td>
                                                                        <td style="max-width: 200px; overflow-x: auto;"><?= htmlspecialchars($remarks2 ?: ""); ?></td>
                                                                    </tr>
                                                                <?php
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <?php admin_html_body_end([
        // ["type" => "script", "src" => "assets/vendor/simple-datatables/simple-datatables.js"],
        ["type" => "script", "src" => "assets/js/main.js"],
    ]); ?>

</body>

</html>