<?php

// Fetch the student's profile and related details from the database
$query = conn()->query("
    SELECT
        students.profile_image AS profile_image,
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
        students.about AS about,
        students.level AS level
    FROM 
        students
    INNER JOIN year_level ON students.year_level_id = year_level.id
    INNER JOIN course ON students.course_id = course.id
    INNER JOIN section ON students.section_id = section.id
    WHERE 
        students.id = '" . user_id() . "'
") or die("Error fetching data: " . mysqli_error(conn()->get_conn()));

// Check if the query returned any result
if ($row = mysqli_fetch_array($query)) {
    // Assign variables from the query result
    $lrn_num = $row['lrn_num'];
    $fname = $row['fname'];
    $lname = $row['lname'];
    $gender = $row['gender'];
    $username = $row['username'];
    $status = $row['status'];
    $complete_address = $row['complete_address'];
    $yr_desc = $row['yr_desc'];
    $c_desc = $row['c_desc'];
    $sec_desc = $row['sec_desc'];
    $about = $row['about'];
    $level = $row['level'];
    $profile_image = !empty($row['profile_image']) ? external_storage_api_url() . "/files/" . $row['profile_image'] : base_url() . "/assets/img/profile-img2.jpg";
} else {
    // Handle case where student is not found
    echo "<script>alert('Student not found.');</script>";
    echo "<script>document.location='students_list';</script>"; // Redirect to a list or another appropriate page
    exit;
}

$query=conn()->query("SELECT count(subjects_id) as sub_count
FROM 
students_subjects
WHERE 
students_id = '" . user_id() . "' and level = '$level'
")or die(mysqli_error(conn()->get_conn()));

if($row=mysqli_fetch_array($query))
{
  $sub_count = $row['sub_count'];
}
else
{
  echo "Error: " . $query . "<br>" . mysqli_error(conn()->get_conn());
}

$query=conn()->query("select * from students where id = '" . user_id() . "'")or die(mysqli_error(conn()->get_conn()));
if($row=mysqli_fetch_array($query))
{
  $fname=$row['fname'];
  $lname=$row['lname'];
  $fname = ucfirst(strtolower($fname));
  $lname = ucfirst(strtolower($lname));
}

admin_html_head("Profile", [
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
        <span class="d-none d-lg-block">SMCC-BEFS</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <div class="search-bar">
    </div><!-- End Search Bar -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Search Icon-->
        <li class="nav-item dropdown pe-3">
    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
        <img src="<?php echo $profile_image; ?>" alt="Profile Image" class="rounded-circle">
        <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $lname; ?></span>
    </a><!-- End Profile Image Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?php echo $fname." ".$lname; ?></h6>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="students_home_sc">
                <i class="bi bi-box-arrow-right"></i>
                <span>Back</span>
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
      <h1>Profile</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="students_home_sc">Home Page</a></li>
          <li class="breadcrumb-item">My Profile</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
      <div class="row">
  

        <div class="col-xl-12">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">

                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                </li>

              </ul>
              <div class="tab-content pt-2">           
                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                <form>
                  <div class="col-xl-4">
                  </br>
                  <img src='<?php echo $profile_image; ?>' alt='Profile Image' class='rounded-circle' width='100'>
                  </br>
                  </div>
                  <h5 class="card-title">About</h5>
                  <?php
                  if (empty($about)){
                  ?>
                    <p class="small fst-italic">Nothing to Show...
                    <?php
                  }
                  else{
                  ?>

                    <p class="small fst-italic"><?php echo $about; ?>
                    <?php
                  }
                  ?>
                
                  <h5 class="card-title">Profile Details</h5>
                  <?php
                  if (empty($lrn_num)){
                  ?>
                    <div class="row">
                    <div class="col-lg-3 col-md-4 label ">ID Numberr</div>
                    <div class="col-lg-9 col-md-8"><span class="badge bg-danger">Not Yet Assign</span></div>
                    </div>
                    <?php
                  }
                  else{
                  ?>

                    <div class="row">
                    <div class="col-lg-3 col-md-4 label ">ID Number</div>
                    <div class="col-lg-9 col-md-8"><b><?php echo $lrn_num; ?></b></div>
                    </div>
                    <?php
                  }
                  ?>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Full Name</div>
                    <div class="col-lg-9 col-md-8"><?php echo $fname." ".$lname; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Gender</div>
                    <div class="col-lg-9 col-md-8"><?php echo $gender; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Username</div>
                    <div class="col-lg-9 col-md-8"><?php echo $username; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Password</div>
                    <div class="col-lg-9 col-md-8">**********</div>
                  </div>
                  <?php
                  if (empty($complete_address)){
                  ?>
                    <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Complete Address</div>
                    <div class="col-lg-9 col-md-8"><span class="badge bg-danger">None</span></div>
                    </div>
                    <?php
                  }
                  else{
                  ?>

                    <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Complete Address</div>
                    <div class="col-lg-9 col-md-8"><?php echo $complete_address; ?></div>
                    </div>
                    <?php
                  }
                  ?>
                  <?php
                  if (empty($yr_desc)){
                  ?>
                    <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Year Level</div>
                    <div class="col-lg-9 col-md-8"><span class="badge bg-danger">Not Yet Assign</span></div>
                    </div>
                    <?php
                  }
                  else{
                  ?>

                    <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Year Level</div>
                    <div class="col-lg-9 col-md-8"><?php echo $yr_desc; ?></div>
                    </div>
                    <?php
                  }
                  ?>
                  <?php
                  if (empty($c_desc)){
                  ?>
                    <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Course</div>
                    <div class="col-lg-9 col-md-8"><span class="badge bg-danger">Not Yet Assign</span></div>
                    </div>
                    <?php
                  }
                  else{
                  ?>

                    <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Course</div>
                    <div class="col-lg-9 col-md-8"><?php echo $c_desc; ?></div>
                    </div>
                    <?php
                  }
                  ?>
                  <?php
                  if (empty($sec_desc)){
                  ?>
                    <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Section</div>
                    <div class="col-lg-9 col-md-8"><span class="badge bg-danger">Not Yet Assign</span></div>
                    </div>
                    <?php
                  }
                  else{
                  ?>

                    <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Section</div>
                    <div class="col-lg-9 col-md-8"><?php echo $sec_desc; ?></div>
                    </div>
                    <?php
                  }
                  ?>

                  <?php
                  if ($sub_count==0){
                  ?>
                    <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Subject Counts</div>
                    <div class="col-lg-9 col-md-8"><span class="badge bg-danger">Empty</span></div>
                    </div>
                    <?php
                  }
                  else{
                  ?>

                    <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Subject Counts</div>
                    <div class="col-lg-9 col-md-8"><?php echo $sub_count; ?></div>
                    </div>
                    <?php
                  }
                  ?>

                  <?php
                  if ($status=="For Approval"){
                  ?>
                    <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Status</div>
                    <div class="col-lg-9 col-md-8"><span class="badge bg-danger">For Approval</span></div>
                    </div>
                    <?php
                  }
                  else{
                  ?>

                    <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Status</div>
                    <div class="col-lg-9 col-md-8"><span class="badge bg-success"><?php echo $status; ?></span></div>
                    </div>
                    <?php
                  }
                  ?>

                    <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Examination Level</div>
                    <div class="col-lg-9 col-md-8"><span class="badge bg-info"><?php echo $level; ?></span></div>
                    </div>


                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Average Score</div>
                    <?php 
                        // $query = conn()->query("SELECT SUM(average)/COUNT(average) AS sum_average FROM student_score WHERE stud_id = '" . user_id() . "'") or die(mysqli_error());
                        if($level === 'PREBOARD1'){
                              $query = conn()->query("SELECT SUM(average) AS sum_average FROM student_score WHERE stud_id = '" . user_id() . "' and level ='$level'") or die(mysqli_error(conn()->get_conn()));
                              if ($row = mysqli_fetch_array($query)) {
                                  $sum_average = $row['sum_average'];
                                  if ($sum_average == "") {
                          ?>
                                      <div class="col-lg-9 col-md-8"><span class="badge bg-danger">Empty</span></div>
                          <?php
                                  } else {
                                      // Format the sum_average to 2 decimal places
                                      $formatted_sum_average = number_format($sum_average, 2);
                          ?>
                                      <div class="col-lg-9 col-md-8"><?php echo $formatted_sum_average; ?> %</div>
                          <?php
                                  }
                              }
                          ?>
                      <?php
                        }
                        else{
                          $query = conn()->query("SELECT SUM(average) AS sum_average FROM student_score WHERE stud_id = '" . user_id() . "' and level ='$level'") or die(mysqli_error(conn()->get_conn()));
                              if ($row = mysqli_fetch_array($query)) {
                                  $sum_average = $row['sum_average'];
                                  if ($sum_average == "") {
                          ?>
                                      <div class="col-lg-9 col-md-8"><span class="badge bg-danger">Empty</span></div>
                          <?php
                                  } else {
                                      // Format the sum_average to 2 decimal places
                                      $formatted_sum_average = number_format($sum_average, 2);
                          ?>
                                      <div class="col-lg-9 col-md-8"><?php echo $formatted_sum_average; ?> %</div>
                          <?php
                                  }
                              }
                          ?>
                          <?php
                        }
                        
                        ?>

                             
                  </div>
                  <div class="row mb-12">
                    <a href="students_subjects" type="submit" class="btn btn-primary">View Taken Exam</a>
                  </div>
                  </form>
              </div>
              
                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                  <!-- Profile Edit Form -->
                  <form action="./students_profile_update_sc" method="POST" enctype="multipart/form-data" class="row g-3 user needs-validation" novalidate>
                    
                  <div class="row mb-3">
                     <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                        <div class="col-md-8 col-lg-9">
                            <img src='<?php echo $profile_image; ?>' alt='Profile Image' class='rounded-circle' width='100'>
                            <div class="pt-2">
                                <!-- Upload Image Button -->
                                <input type="file" name="profile_image" id="profileImage" accept="image/*" class="btn btn-primary btn-sm" title="Upload new profile image" />
                                <!-- Remove Image Button
                                <a href="#" class="btn btn-danger btn-sm" title="Remove my profile image" id="removeImageBtn"><i class="bi bi-trash"></i></a> -->
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                      <label for="about" class="col-md-4 col-lg-3 col-form-label">About</label>
                      <div class="col-md-8 col-lg-9">
                        <textarea name="about" class="form-control" id="about" style="height: 100px"><?= $about ?? "" ?></textarea>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Job" class="col-md-4 col-lg-3 col-form-label">Complete Address</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="address" type="text" class="form-control" id="Job" value="<?= $complete_address ?? "" ?>">
                      </div>
                    </div>

                    <div class="text-center">
                      <button name="update_profile" type="submit" class="btn btn-primary">Update Profile</button>
                    </div>
                  </form><!-- End Profile Edit Form -->

                </div>

                <div class="tab-pane fade pt-3" id="profile-change-password">
                  <!-- Change Password Form -->
                  <form action="./students_profile_change_password_sc" method="POST" enctype="multipart/form-data" class="row g-3 user needs-validation" novalidate>

                    <div class="row mb-3">
                      <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="current_password" type="password" class="form-control" id="currentPassword" required>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="new_password" type="password" class="form-control" id="newPassword" required>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="renew_password" type="password" class="form-control" id="renewPassword" required>
                      </div>
                    </div>

                    <div class="text-center">
                      <button name="change_password" type="submit" class="btn btn-primary">Change Password</button>
                    </div>
                  </form><!-- End Change Password Form -->

                </div>

              </div><!-- End Bordered Tabs -->

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