<?php 

authenticated_page("dean");
$sub_id = conn()->sanitize($_REQUEST['sub_id']);

$subject_query = conn()->query("SELECT description FROM subjects WHERE id = '$sub_id'") or die(mysqli_error(conn()->get_conn()));
$subject_description = "Subject Not Found"; // Default value if query fails
if ($subject_row = mysqli_fetch_assoc($subject_query)) {
    $subject_description = $subject_row['description'];
}

admin_html_head("Students", [
    [ "type" => "style", "href" => "assets/vendor/simple-datatables/style.css" ],
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
  <!-- Header -->
  <?php require_once get_dean_header(); ?>
  <!-- Sidebar -->
  <?php require_once get_dean_sidebar(); ?>

  <main id="main" class="main">
    <div class="pagetitle">
      <h1>List of Students</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="dean_home_page">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="dean/dean_subjects">Subjects</a></li>
          <li class="breadcrumb-item">List of Students</li>
        </ol>
      </nav>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 style="font-family: 'Poppins', sans-serif; color: #012970;"><?php echo $subject_description; ?></h5>

              <!-- Tabs for Preboard1 and Preboard2 -->
              <ul class="nav nav-tabs" id="studentTabs" role="tablist">
                <li class="nav-item" role="presentation">
                  <a class="nav-link active" id="preboard1-tab" data-bs-toggle="tab" href="#preboard1" role="tab" aria-controls="preboard1" aria-selected="true">Preboard 1</a>
                </li>
                <li class="nav-item" role="presentation">
                  <a class="nav-link" id="preboard2-tab" data-bs-toggle="tab" href="#preboard2" role="tab" aria-controls="preboard2" aria-selected="false">Preboard 2</a>
                </li>
              </ul>

              <!-- Tab content -->
              <div class="tab-content" id="studentTabsContent">
                <!-- Preboard1 Tab -->
                <div class="tab-pane fade show active" id="preboard1" role="tabpanel" aria-labelledby="preboard1-tab">
                  <table class="table datatable">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>ID No.</th>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Gender</th>
                        <th>Section</th>
                        <th>Score</th>
                        <th>Average</th>
                        <th>Remarks</th>
                      </tr>
                    </thead>
                    <tbody>
<?php
$query = conn()->query("
    SELECT 
        students.lname AS slname,
        students.fname AS sfname,
        students.lrn_num AS sid,
        students.gender AS gender,
        section.description AS section,
        student_score.level AS level,
        student_score.score AS score,
        student_score.total_items AS items,
        student_score.average AS average,
        student_score.remarks2 AS remarks2
    FROM students
    JOIN student_score ON student_score.stud_id = students.id
    INNER JOIN section ON students.section_id = section.id
    WHERE student_score.sub_id = '$sub_id' AND student_score.level = 'PREBOARD1'
    ORDER BY students.lname ASC
") or die(mysqli_error(conn()->get_conn()));

$counter = 1;
while ($row = mysqli_fetch_array($query)) {
    $sid = $row['sid'];
    $slname = $row['slname'];
    $sfname = $row['sfname'];
    $gender = $row['gender'];
    $section = $row['section'];
    $score = $row['score'] . " / " . $row['items'];
    $average = number_format($row['average'], 2);
    $remarks2 = $row['remarks2'];

    echo "<tr>";
    echo "<td>{$counter}</td>";
    echo "<td>{$sid}</td>";
    echo "<td>{$slname}</td>";
    echo "<td>{$sfname}</td>";
    echo "<td>{$gender}</td>";
    echo "<td>{$section}</td>";
    echo "<td>{$score}</td>";
    echo "<td>{$average}</td>";
    echo "<td>
            <form method='POST' action='./dean_update_remarks'>
                <input type='hidden' name='sid' value='{$sid}'>
                <input type='hidden' name='sub_id' value='{$sub_id}'>
                <input type='hidden' name='user_id' value='{user_id()}'>
                <input type='hidden' name='level' value='PREBOARD1'>
                <input type='hidden' name='tab' value='preboard1'> 
                <textarea name='remarks' class='form-control' rows='2'>" . htmlspecialchars($remarks2 ?: "") . "</textarea>
                <button type='submit' class='btn btn-primary btn-sm mt-2'>Save</button>
            </form>
          </td>";
    echo "</tr>";

    $counter++;
}
?>
                    </tbody>
                  </table>
                </div>

                <!-- Preboard2 Tab -->
                <div class="tab-pane fade" id="preboard2" role="tabpanel" aria-labelledby="preboard2-tab">
                  <table class="table datatable">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>ID No.</th>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Gender</th>
                        <th>Section</th>
                        <th>Score</th>
                        <th>Average</th>
                        <th>Remarks</th>
                      </tr>
                    </thead>
                    <tbody>
<?php
$query = conn()->query("
    SELECT 
        students.lname AS slname,
        students.fname AS sfname,
        students.lrn_num AS sid,
        students.gender AS gender,
        section.description AS section,
        student_score.level AS level,
        student_score.score AS score,
        student_score.total_items AS items,
        student_score.average AS average,
        student_score.remarks2 AS remarks2
    FROM students
    JOIN student_score ON student_score.stud_id = students.id
    INNER JOIN section ON students.section_id = section.id
    WHERE student_score.sub_id = '$sub_id' AND student_score.level = 'PREBOARD2'
    ORDER BY students.lname ASC
") or die(mysqli_error(conn()->get_conn()));

$counter = 1;
while ($row = mysqli_fetch_array($query)) {
    $sid = $row['sid'];
    $slname = $row['slname'];
    $sfname = $row['sfname'];
    $gender = $row['gender'];
    $section = $row['section'];
    $score = $row['score'] . " / " . $row['items'];
    $average = number_format($row['average'], 2);
    $remarks2 = $row['remarks2'];

    echo "<tr>";
    echo "<td>{$counter}</td>";
    echo "<td>{$sid}</td>";
    echo "<td>{$slname}</td>";
    echo "<td>{$sfname}</td>";
    echo "<td>{$gender}</td>";
    echo "<td>{$section}</td>";
    echo "<td>{$score}</td>";
    echo "<td>{$average}</td>";
    echo "<td>
            <form method='POST' action='./dean_update_remarks'>
                <input type='hidden' name='sid' value='{$sid}'>
                <input type='hidden' name='sub_id' value='{$sub_id}'>
                <input type='hidden' name='user_id' value='{user_id()}'>
                <input type='hidden' name='level' value='PREBOARD2'>
                <input type='hidden' name='tab' value='preboard2'> 
                <textarea name='remarks' class='form-control' rows='2'>" . htmlspecialchars($remarks2 ?: "") . "</textarea>
                <button type='submit' class='btn btn-primary btn-sm mt-2'>Save</button>
            </form>
          </td>";
    echo "</tr>";

    $counter++;
}
?>
                    </tbody>
                  </table>
                </div>

              </div>

            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <?php require_once get_footer(); ?>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <?php admin_html_body_end([
      ["type" => "script", "src" => "assets/vendor/simple-datatables/simple-datatables.js"],
      ["type" => "script", "src" => "assets/js/main.js"],        
  ]); ?>

  <script>
    // Get URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const activeTab = urlParams.get('tab');

    // Set active tab based on the "tab" parameter
    if (activeTab) {
      const tabs = document.querySelectorAll('#studentTabs .nav-link');
      tabs.forEach(tab => tab.classList.remove('active'));

      const tabContent = document.querySelectorAll('.tab-pane');
      tabContent.forEach(content => content.classList.remove('show', 'active'));

      const activeTabLink = document.querySelector(`#${activeTab}-tab`);
      const activeTabPane = document.querySelector(`#${activeTab}`);

      if (activeTabLink && activeTabPane) {
        activeTabLink.classList.add('active');
        activeTabPane.classList.add('show', 'active');
      }
    }
  </script>
</body>
</html>
