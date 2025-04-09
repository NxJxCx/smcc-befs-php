<?php

authenticated_page("reviewer");

$school_year = conn()->sanitize($_GET['school_year'] ?? ''); // Get the selected school year, if any

// Base query to get students assigned to the courses under the faculty member for the specific school year
$sql = "
    SELECT students.id AS stud_id, students.lrn_num, students.fname, students.lname, 
           students.gender, students.date_registered, course.description AS course, 
           section.description AS section, students.course_id AS c_id 
    FROM students 
    INNER JOIN course ON students.course_id = course.id 
    INNER JOIN section ON students.section_id = section.id
    INNER JOIN faculty_course_school_year ON students.course_id = faculty_course_school_year.course_id 
    WHERE students.status = 'Inactive' AND faculty_course_school_year.user_id = ?";

// Add school year filter if provided
if (!empty($school_year)) {
    $sql .= " AND students.school_year_id = ?";
}

$sql .= " ORDER BY students.lname ASC";

// Prepare the SQL query
$stmt = conn()->prepare($sql);

$user_id = user_id();
// Bind parameters
if (!empty($school_year)) {
    $stmt->bind_param("ii", $user_id, $school_year); // Bind user_id and school_year_id
} else {
    $stmt->bind_param("i", $user_id); // Bind only user_id
}

// Execute and fetch results
$stmt->execute();
$result = $stmt->get_result();
$counter = 1; // Initialize counter outside the loop

$query = conn()->query("SELECT * FROM users WHERE id = '" . user_id() . "'") or die(mysqli_error(conn()->get_conn()));
if($row = mysqli_fetch_array($query)) {
    $fname = $row['fname'];
    $lname = $row['lname'];
    $type = $row['type'];
    $fname = ucfirst(strtolower($fname));
    $lname = ucfirst(strtolower($lname));
    $type = ucfirst(strtolower($type));
}

admin_html_head("Inactive Students", [
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
  <!-- End Sidebar -->

  <main id="main" class="main">

    <div class="pagetitle">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h1>List of Inactive Students</h1>
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="reviewer_home">Dashboard</a>
              </li>
              <li class="breadcrumb-item">Inactive Students</li>
            </ol>
          </nav>
        </div>

        <div>
          <form method="GET">
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($_GET['user_id'] ?? ''); ?>">

            <div class="d-flex align-items-center justify-content-end">
              <label for="school_year_filter" class="card-title me-2">School Year:</label>
              <select class="form-select" style="width: 200px;" name="school_year" id="school_year_filter" onchange="this.form.submit()">
                <option value="" selected>All</option>
                <?php
                  
                  // Fetch all available school years
                  $sy_query = conn()->query("SELECT id, description FROM school_year ORDER BY description ASC");
                  while ($sy_row = mysqli_fetch_array($sy_query)) {
                    $selected = isset($_GET['school_year']) && $_GET['school_year'] == $sy_row['id'] ? 'selected' : '';
                    echo "<option value='{$sy_row['id']}' $selected>{$sy_row['description']}</option>";
                  }
                ?>
              </select>
            </div>
          </form>
        </div>

      </div>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Students</h5>
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>ID No.</th>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Gender</th>
                    <th>Course</th>
                    <th>Section</th>
                    <th>Date Registered</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                  while ($row = $result->fetch_assoc()) {
                ?>
                  <tr>
                    <td><?php echo $counter++; ?></td>
                    <td><?= htmlspecialchars($row['lrn_num']) ?></td>
                    <td><?= htmlspecialchars($row['lname']) ?></td>
                    <td><?= htmlspecialchars($row['fname']) ?></td>
                    <td><?= htmlspecialchars($row['gender']) ?></td>
                    <td><?= htmlspecialchars($row['course']) ?></td>
                    <td><?= htmlspecialchars($row['section']) ?></td>
                    <td><?= htmlspecialchars($row['date_registered']) ?></td>
                  </tr>
                <?php
                  }
                  $stmt->close();
                ?>
                </tbody>
              </table>
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

</body>
</html>
