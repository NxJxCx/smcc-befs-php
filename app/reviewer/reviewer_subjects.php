<?php 

authenticated_page("reviewer");


// Fetch the "Current Set" school year
$current_school_year_query = conn()->query("SELECT id, description FROM school_year WHERE status = 'Current Set'") or die(mysqli_error(conn()->get_conn()));
$current_school_year = mysqli_fetch_assoc($current_school_year_query);
$current_school_year_id = $current_school_year['id'] ?? null;

// Determine selected school year (default to "Current Set")
$selected_school_year = conn()->sanitize($_GET['school_year'] ?? $current_school_year_id);

$query = conn()->query("SELECT * FROM users WHERE id = '" . user_id() . "'") or die(mysqli_error(conn()->get_conn()));
if ($row = mysqli_fetch_array($query)) {
    $fname = ucfirst(strtolower($row['fname']));
    $lname = ucfirst(strtolower($row['lname']));
    $type = ucfirst(strtolower($row['type']));
}

admin_html_head("Subjects", [
  [ "type" => "style", "href" => "assets/vendor/simple-datatables/style.css" ],
  [ "type" => "style", "href" => "assets/css/style.css" ],
]); // html head

?>
<body>
  <!-- ======= Header ======= -->
  <?php require_once get_reviewer_header(); ?> 
  <!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <?php require_once get_reviewer_sidebar(); ?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>List of Subjects</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="reviewer_home">Dashboard</a></li>
          <li class="breadcrumb-item">Subjects</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    <div>
        <!-- School Year Filter -->
        <form method="GET">
          <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($_GET['user_id'] ?? ''); ?>">
          <div class="d-flex align-items-center justify-content-end">
            <label for="school_year_filter" class="card-title me-2">School Year:</label>
            <select class="form-select" style="width: 200px;" name="school_year" id="school_year_filter" onchange="this.form.submit()">
              <option value="" selected>All</option>
              <?php
               // Fetch all available school years
          $sy_query = conn()->query("SELECT id, description FROM school_year ORDER BY description ASC");
          while ($sy_row = mysqli_fetch_assoc($sy_query)) {
            $selected = ($sy_row['id'] == $selected_school_year) ? 'selected' : '';
            echo "<option value='{$sy_row['id']}' $selected>{$sy_row['description']}</option>";
                }
              ?>
            </select>
          </div>
        </form>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Lists</h5>
                
              <!-- Table with stripped rows -->
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>Code No.</th>
                    <th>Description</th>
                    <th>Year Level</th>
                    <th>Course</th>
                    <th>Date Entry</th>
                    <th>Exam (%)</th>
                    <th>Timer (mins)</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                <?php
               // Build the query with the school year filter
              $school_year_filter = $selected_school_year ? "AND subjects.school_year_id = '$selected_school_year'" : '';
                $query = conn()->query("
                SELECT DISTINCT
                    subjects.id AS s_id, 
                    subjects.code AS s_code, 
                    subjects.description AS s_desc, 
                    year_level.description AS y_desc, 
                    course.description AS c_desc, 
                    course.id AS c_id,
                    school_year.description AS sy_desc, 
                    subjects.date_entry AS s_date, 
                    subjects.status AS s_status,
                    subject_percent.percent AS percent,
                    subjects_timer.timer AS timer
                FROM 
                    subjects
                JOIN year_level ON subjects.year_level_id = year_level.id
                JOIN course ON subjects.course_id = course.id
                JOIN school_year ON subjects.school_year_id = school_year.id
                JOIN subject_percent ON subjects.id = subject_percent.sub_id
                LEFT JOIN subjects_timer ON subjects.id = subjects_timer.subjects_id
                JOIN faculty_subjects ON subjects.id = faculty_subjects.subjects_id
                WHERE 
                    subjects.status = 'Active' AND
                    faculty_subjects.faculty_id = '" . user_id() . "'
                    $school_year_filter
              ") or die(mysqli_error(conn()->get_conn()));              
                
                while ($row = mysqli_fetch_array($query)) {
                    $s_id = $row['s_id'];
                    $s_code = $row['s_code'];
                    $s_desc = $row['s_desc'];
                    $y_desc = $row['y_desc'];
                    $c_desc = $row['c_desc'];
                    $c_id = $row['c_id'];
                    $sy_desc = $row['sy_desc'];
                    $s_date = $row['s_date'];
                    $formatted_date = date('Y-m-d', strtotime($s_date));
                    $percent = $row['percent'];
                    $timer = $row['timer'] ?? 'N/A';
                ?>
                  <tr>
                      <td><?php echo $s_code; ?></td>
                      <td><?php echo $s_desc; ?></td>
                      <td><?php echo $y_desc; ?></td>
                      <td><?php echo $c_desc; ?></td>
                      <td><?php echo $formatted_date; ?></td>
                      <td><?php echo $percent; ?>%</td>
                      <td><?php echo $timer; ?></td>
                      <td class="d-flex justify-content-between align-items-center">
                        <div>
                          <a href="reviewer_test_questions?s_id=<?php echo $s_id; ?>" class="text-decoration-none">
                              Manage Test Questions
                          </a> /
                          <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#timerModal">
                              Set Timer
                          </a>
                        </div>
                        <div>
                          <a href="reviewer_students_view?sub_id=<?php echo $s_id; ?>" class="btn btn-primary btn-sm">
                              View
                          </a>
                        </div>
                      </td>
                  </tr>
                  <?php } ?>
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
   <?php require_once get_footer(); ?>
  <!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <?php admin_html_body_end([
      ["type" => "script", "src" => "assets/vendor/simple-datatables/simple-datatables.js"],
      ["type" => "script", "src" => "assets/js/main.js"],
  ]); ?>

  <!-- Modal -->
<div class="modal fade" id="timerModal" tabindex="-1" aria-labelledby="timerModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="timerModalLabel">Set Timer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Form to set the timer -->
        <form action="./reviewer_update_timer_sc?s_id=<?php echo $s_id; ?>" method="POST" enctype="multipart/form-data" class="row g-3 user needs-validation" novalidate>
          <div class="row mb-3">
            <label for="inputText" class="col-sm-2 col-form-label">Minutes:</label>
            <div class="col-sm-10">
              <input name="timer" type="number" class="form-control" required>
            </div>
          </div>

          <div class="row mb-3">
            <div align="right">
              <button name="set_timer" type="submit" class="btn btn-primary">Set Timer</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

</body>

</html>
