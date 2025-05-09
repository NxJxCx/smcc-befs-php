<?php

authenticated_page("dean");

// Get the selected school year from the form submission
$selected_school_year = conn()->sanitize(isset($_GET['school_year']) ? $_GET['school_year'] : '');

// Build the query to fetch reviewers based on the dean's course
$sql = "
    SELECT DISTINCT 
        users.id AS f_id, 
        users.lname AS lname, 
        users.fname AS fname, 
        course.description AS c_desc, 
        school_year.description AS sy_desc, 
        faculty_course_school_year.school_year_id AS school_year_id,
        faculty_course_school_year.course_id AS c_id,
        users.date_created AS date_created,
        users.`status` AS `status` 
    FROM 
        users
    INNER JOIN faculty_course_school_year 
        ON faculty_course_school_year.user_id = users.id
    INNER JOIN course 
        ON faculty_course_school_year.course_id = course.id
    INNER JOIN school_year 
        ON faculty_course_school_year.school_year_id = school_year.id
    INNER JOIN dean_course 
        ON faculty_course_school_year.course_id = dean_course.course_id
    WHERE 
        users.type = 'REVIEWER' 
        AND users.`status` = 'Active' 
        AND dean_course.user_id = '" . user_id() . "'";

// Apply the school year filter if selected
if (!empty($selected_school_year)) {
    $sql .= " AND faculty_course_school_year.school_year_id = '" . $selected_school_year . "'";
}

// Add ordering
$sql .= " ORDER BY users.lname ASC";

// Execute the query
$result = conn()->query($sql) or die("Query Error: " . mysqli_error(conn()->get_conn()));

// Initialize counter
$counter = 1;

admin_html_head("Reviewers", [
    [ "type" => "style", "href" => "assets/css/style.css" ],
]); // html head

?>

<body>
  <?php 
    $query = conn()->query("SELECT * FROM users WHERE id = '" . user_id() . "'") or die(mysqli_error(conn()->get_conn()));
    if ($row = mysqli_fetch_array($query)) {
        $fname = ucfirst(strtolower($row['fname']));
        $lname = ucfirst(strtolower($row['lname']));
        $type = ucfirst(strtolower($row['type']));
    }
  ?>
  
  <?php require_once get_dean_header(); ?>
  <?php require_once get_dean_sidebar(); ?>

  <main id="main" class="main">
    <div class="pagetitle">
      <h1>List of Reviewers</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="dean_home_page">Dashboard</a></li>
          <li class="breadcrumb-item">Reviewer</li>
        </ol>
      </nav>
    </div>

    <section class="section">
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
      
      <div class="row mt-4">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Reviewers</h5>
              <table class="table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Course Handled</th>
                    <th>School Year</th>
                    <th>Date Registered</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while ($row = mysqli_fetch_array($result)) { ?>
                    <tr>
                      <td><?php echo $counter++; ?></td>
                      <td><?php echo ucfirst($row['lname']); ?></td>
                      <td><?php echo ucfirst($row['fname']); ?></td>
                      <td><?php echo $row['c_desc']; ?></td>
                      <td><?php echo $row['sy_desc']; ?></td>
                      <td><?php echo $row['date_created']; ?></td>
                      <td><?php echo $row['status']; ?></td>
                      <td>
                        <a href="dean_reviewer_assign_subjects?faculty_id=<?php echo $row['f_id']; ?>&school_year=<?php echo $row['school_year_id']; ?>&course_id=<?php echo $row['c_id']; ?>" class="btn btn-primary">Assign</a>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <?php require_once get_footer(); ?>

  <?php admin_html_body_end([
      ["type" => "script", "src" => "assets/js/main.js"],
  ]); ?>

</body>
</html>
