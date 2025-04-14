<?php

$user_id = user_id();

$query = conn()->query("select
students.profile_image as profile_image,
students.lrn_num as lrn_num,
students.fname as fname,
students.lname as lname,
students.username as username,
students.status as status,
students.complete_address as complete_address,
students.level as level,
year_level.description as yr_desc,
course.description as c_desc,
section.description as sec_desc,
students.about as about
from 
students,
year_level,
course,
section
where 
students.year_level_id = year_level.id and 
students.course_id = course.id and
students.section_id = section.id and
students.id = '" . $user_id . "'
") or die(mysqli_error(conn()->get_conn()));
if ($row = mysqli_fetch_array($query)) {
  $lrn_num = $row['lrn_num'];
  $fname = $row['fname'];
  $lname = $row['lname'];
  $username = $row['username'];
  $status = $row['status'];
  $complete_address = $row['complete_address'];
  $yr_desc = $row['yr_desc'];
  $c_desc = $row['c_desc'];
  $sec_desc = $row['sec_desc'];
  $about = $row['about'];
  $level = $row['level'];
  $profile_image = base_url() . "/" . $row['profile_image'];

  // Set a default profile image if none is provided
  if (empty($profile_image)) {
    $profile_image = base_url() . "/assets/img/profile-img2.jpg";
  }
} else {
  echo "Error: " . $query . "<br>" . mysqli_error(conn()->get_conn());
}

$query = conn()->query("select count(subjects_id) as sub_count
from 
students_subjects
where 
students_id = '" . $user_id . "'
") or die(mysqli_error(conn()->get_conn()));
if ($row = mysqli_fetch_array($query)) {
  $sub_count = $row['sub_count'];
} else {
  echo "Error: " . $query . "<br>" . mysqli_error(conn()->get_conn());
}

$query = conn()->query("select * from students where id = '" . $user_id . "'") or die(mysqli_error(conn()->get_conn()));
if ($row = mysqli_fetch_array($query)) {
  $fname = $row['fname'];
  $lname = $row['lname'];
  $fname = ucfirst(strtolower($fname));
  $lname = ucfirst(strtolower($lname));
}

admin_html_head("Subjects", [
  // [ "type" => "style", "href" => "assets/vendor/simple-datatables/style.css" ],
  [ "type" => "style", "href" => "assets/css/style.css" ],
]); // html head

?>

<body class="toggle-sidebar">
  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="students_home_sc" class="logo d-flex align-items-center">
        <img src="<?= base_url() ?>/images/android-icon-192x192.png" alt="">
        <span class="d-none d-lg-block">SMCC - BEFS</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Search Icon-->
        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="<?php echo $profile_image; ?>" alt="Profile Image" class="rounded-circle" width="35" height="35" style="margin-right: 10px;">
            <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $lname; ?></span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?php echo $fname . " " . $lname; ?></h6>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="students_home_sc">
                <i class="bi bi-box-arrow-right"></i>
                <span>Back</span>
              </a>
              </a>
              <a href="<?= base_url() ?>/log_out_sc" class="dropdown-item"><i class="bi bi-box-arrow-right"></i>
                Log Out
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <?php
  require_once get_student_sidebar();
  ?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">

      <h1>List of Subjects</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="students_profile">Back to Profile</a></li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">PREBOARD 1</h5>

              <?php
              // Query to calculate the sum of the average scores for all subjects in PREBOARD 1
              $stmt = conn()->prepare("
    SELECT SUM(average) AS total_average
    FROM student_score
    WHERE stud_id = ? 
        AND level = 'PREBOARD1'
");

              // Execute the query with the student ID
              $stmt->bind_param("i", $user_id);
              $stmt->execute();
              $result = $stmt->get_result();
              $row = $result->fetch_array();

              // Fetch the total average from the result
              $total_average = $row['total_average'];

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
                  
                  $query = conn()->query("
                    SELECT 
    subjects.code AS code,
    subjects.description AS description,
    MAX(students_subjects.status) AS status, -- Use MAX or GROUP BY to avoid duplicates
    MAX(student_score.score) AS score,
    MAX(student_score.total_items) AS items,
    MAX(student_score.average) AS avg_score,
    MAX(subject_percent.percent) AS percent,
    MAX(student_score.remarks) AS remarks,
    MAX(student_score.remarks2) AS remarks2
FROM 
    student_score
JOIN 
    students_subjects 
    ON students_subjects.students_id = student_score.stud_id
    AND students_subjects.subjects_id = student_score.sub_id -- Ensure specific matching
JOIN 
    subjects 
    ON students_subjects.subjects_id = subjects.id
LEFT JOIN 
    subject_percent 
    ON subject_percent.sub_id = subjects.id
WHERE 
    student_score.stud_id = '" . $user_id . "' 
    AND student_score.level = 'PREBOARD1'
    AND students_subjects.level = 'PREBOARD1'
GROUP BY 
    subjects.code, subjects.description;
                ") or die(mysqli_error(conn()->get_conn()));

                  while ($row = mysqli_fetch_array($query)) {
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
                      <td><?php echo $code; ?></td>
                      <td><?php echo $description; ?></td>
                      <td><?php echo $status; ?></td>
                      <td><?php echo $score . " / " . $items; ?></td>
                      <td><?php echo $formatted_sum_average; ?> %</td>
                      <td><?php echo $percent; ?>%</td>
                      <td style="max-width: 200px; overflow-x: auto;"><?php echo htmlspecialchars($remarks); ?></td>
                      <td style="max-width: 200px; overflow-x: auto;"><?php echo htmlspecialchars($remarks2); ?></td>
                    </tr>
                  <?php
                  }
                  ?>
                </tbody>
              </table>
              <!-- End Table with stripped rows -->

            </div>
          </div>

        </div>
      </div>
    </section>
    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">PREBOARD 2</h5>

              <?php
              // Query to calculate the sum of the average scores for all subjects in PREBOARD 1
              $stmt = conn()->prepare("
    SELECT SUM(average) AS total_average
    FROM student_score
    WHERE stud_id = ? 
        AND level = 'PREBOARD2'
");

              // Execute the query with the student ID
              $stmt->bind_param("i", $user_id);
              $stmt->execute();
              $result = $stmt->get_result();
              $row = $result->fetch_array();

              // Fetch the total average from the result
              $total_average = $row['total_average'];

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
                  
                  $query = conn()->query("
                   SELECT 
    subjects.code AS code,
    subjects.description AS description,
    MAX(students_subjects.status) AS status, -- Use MAX or GROUP BY to avoid duplicates
    MAX(student_score.score) AS score,
    MAX(student_score.total_items) AS items,
    MAX(student_score.average) AS avg_score,
    MAX(subject_percent.percent) AS percent,
    MAX(student_score.remarks) AS remarks,
    MAX(student_score.remarks2) AS remarks2
FROM 
    student_score
JOIN 
    students_subjects 
    ON students_subjects.students_id = student_score.stud_id
    AND students_subjects.subjects_id = student_score.sub_id -- Ensure specific matching
JOIN 
    subjects 
    ON students_subjects.subjects_id = subjects.id
LEFT JOIN 
    subject_percent 
    ON subject_percent.sub_id = subjects.id
WHERE 
    student_score.stud_id = '" . $user_id . "' 
    AND student_score.level = 'PREBOARD2'
    AND students_subjects.level = 'PREBOARD2'
GROUP BY 
    subjects.code, subjects.description;

                ") or die(mysqli_error(conn()->get_conn()));

                  while ($row = mysqli_fetch_array($query)) {
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
                      <td><?php echo $code; ?></td>
                      <td><?php echo $description; ?></td>
                      <td><?php echo $status; ?></td>
                      <td><?php echo $score . " / " . $items; ?></td>
                      <td><?php echo $formatted_sum_average; ?> %</td>
                      <td><?php echo $percent; ?>%</td>
                      <td style="max-width: 200px; overflow-x: auto;"><?php echo htmlspecialchars($remarks); ?></td>
                      <td style="max-width: 200px; overflow-x: auto;"><?php echo htmlspecialchars($remarks2); ?></td>
                    </tr>
                  <?php
                  }
                  ?>
                </tbody>
              </table>
              <!-- End Table with stripped rows -->

            </div>
          </div>

        </div>
      </div>
    </section>


  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php
  require_once get_footer();
  ?>
  <!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <?php admin_html_body_end([
      // ["type" => "script", "src" => "assets/vendor/simple-datatables/simple-datatables.js"],
      ["type" => "script", "src" => "assets/js/main.js"],
  ]); ?>

</body>

</html>