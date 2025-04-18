<?php

authenticated_page("admin");


if (isset($_POST['add_faculty'])) {
    $fname = conn()->sanitize($_POST['fname']);
    $lname = conn()->sanitize($_POST['lname']);
    $course = conn()->sanitize($_POST['course']);
    $username = conn()->sanitize($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Set the timezone to Asia/Manila
    date_default_timezone_set("Asia/Manila");
    $dt = date("Y-m-d") . " " . date("H:i:s");

    if ($password == $confirm_password) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        // Insert user data into the 'users' table
        $query = "INSERT INTO users (username, password, type, status, fname, lname, date_created, logged_in) 
                  VALUES ('$username', '$password', 'REVIEWER', 'Active', '$fname', '$lname', '$dt', 'NO')";
        if (conn()->query($query)) {
            // Get the newly inserted user ID
            $query = "SELECT * FROM users WHERE fname = '$fname' AND lname = '$lname'";
            $result = conn()->query($query);
            if ($row = mysqli_fetch_array($result)) {
                $f_id = $row['id'];

                // Get the current school year
                $query = "SELECT * FROM school_year WHERE status = 'Current Set'";
                $result = conn()->query($query);
                if ($row = mysqli_fetch_array($result)) {
                    $school_year_id = $row['id'];

                    // Insert the faculty course and school year relation
                    $query = "INSERT INTO faculty_course_school_year (user_id, course_id, school_year_id) 
                              VALUES ('$f_id', '$course', '$school_year_id')";
                    if (conn()->query($query)) {
                        echo "<script type='text/javascript'>alert('Reviewer Successfully Saved!'); 
                        document.location='admin_faculty'</script>";
                    } else {
                        echo "Error: " . $query . "<br>" . mysqli_error(conn()->get_conn());
                    }
                } else {
                    echo "Error: Could not find current school year.<br>" . mysqli_error(conn()->get_conn());
                }
            } else {
                echo "Error: Faculty not found after insertion.<br>" . mysqli_error(conn()->get_conn());
            }
        } else {
            echo "Error: " . $query . "<br>" . mysqli_error(conn()->get_conn());
        }
    } else {
        // Passwords do not match
        echo "<script type='text/javascript'>alert('Password does not match!'); 
        document.location='admin_faculty'</script>";
    }
}

$query = conn()->query("SELECT * FROM users WHERE id = '" . user_id() . "'") or die(mysqli_error(conn()->get_conn()));
if ($row = mysqli_fetch_array($query)) {
    $fname = $row['fname'];
    $lname = $row['lname'];
    $type = $row['type'];
    $fname = ucfirst(strtolower($fname));
    $lname = ucfirst(strtolower($lname));
    $type = ucfirst(strtolower($type));
}

admin_html_head("Reviewer", [
    [ "type" => "style", "href" => "assets/vendor/remixicon/remixicon.css" ],
    [ "type" => "style", "href" => "assets/vendor/simple-datatables/style.css" ],
    [ "type" => "style", "href" => "assets/css/style.css" ],
]); // html head

?>

<body>
    <?php require_once get_admin_header(); ?>
    <?php require_once get_admin_sidebar(); ?>

    <main id="main" class="main">
        <div class="pagetitle">
            <div align="right">
                <button class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#addReviewerForm">Add Reviewer Account</button>
            </div>
            <h1>List of Reviewer</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="admin_home">Dashboard</a></li>
                    <li class="breadcrumb-item">Reviewer</li>
                </ol>
            </nav>
        </div>

        <div class="collapse" id="addReviewerForm">
            <section class="section">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Registration Form</h5>

                                <?php
                                if (isset($_SESSION['error'])) {
                                    echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
                                    unset($_SESSION['error']);
                                }
                                ?>

                                <form method="POST" enctype="multipart/form-data" class="row g-3 user needs-validation" novalidate>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 col-form-label">First Name</label>
                                        <div class="col-sm-10">
                                            <input name="fname" type="text" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputEmail" class="col-sm-2 col-form-label">Last Name</label>
                                        <div class="col-sm-10">
                                            <input name="lname" type="text" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Course to be Handled</label>
                                        <div class="col-sm-10">
                                            <select name="course" class="form-select">
                                                <?php
                                                $query = conn()->query("SELECT * FROM course WHERE status = 'Active' ORDER BY description ASC");
                                                while ($row = mysqli_fetch_array($query)) {
                                                    $id = $row['id'];
                                                    $description = $row['description'];
                                                ?>
                                                    <option value="<?php echo $id; ?>"><?php echo $description; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputNumber" class="col-sm-2 col-form-label">Username</label>
                                        <div class="col-sm-10">
                                            <input name="username" type="text" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputNumber" class="col-sm-2 col-form-label">Password</label>
                                        <div class="col-sm-10">
                                            <input name="password" class="form-control" type="password" id="formFile" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputDate" class="col-sm-2 col-form-label">Confirm Password</label>
                                        <div class="col-sm-10">
                                            <input name="confirm_password" class="form-control" type="password" id="formFile" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div align="right">
                                            <button name="add_faculty" type="submit" class="btn btn-primary">Save Record</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Reviewer</h5>
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>ID No.</th>
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
                                    <?php
                                    $query = conn()->query("SELECT DISTINCT 
                        users.id AS id, 
                        users.lname AS lname, 
                        users.fname AS fname, 
                        course.description AS c_desc, 
                        school_year.description AS sy_desc, 
                        users.date_created AS date_created,
                        users.status AS status 
                        FROM 
                        users, course, school_year, faculty_course_school_year 
                        WHERE 
                        faculty_course_school_year.user_id = users.id 
                        AND faculty_course_school_year.course_id = course.id 
                        AND faculty_course_school_year.school_year_id = school_year.id 
                        AND users.type = 'REVIEWER' 
                        AND users.status = 'Active'");

                                    $counter = 1;

                                    while ($row = mysqli_fetch_array($query)) {
                                        $id = $row['id'];
                                        $lname = $row['lname'];
                                        $fname = $row['fname'];
                                        $c_desc = $row['c_desc'];
                                        $sy_desc = $row['sy_desc'];
                                        $date_created = $row['date_created'];
                                        $status = $row['status'];
                                    ?>
                                        <tr>
                                            <td><?php echo $counter++; ?></td>
                                            <td><?php echo $id; ?></td>
                                            <td><?php echo $lname; ?></td>
                                            <td><?php echo $fname; ?></td>
                                            <td><?php echo $c_desc; ?></td>
                                            <td><?php echo $sy_desc; ?></td>
                                            <td><?php echo $date_created; ?></td>
                                            <td><?php echo $status; ?></td>
                                            <td><a href="admin_faculty_update?f_id=<?php echo $id; ?>"><i class="ri-edit-box-fill"></i></a> / <a href="admin_faculty_remove?f_id=<?php echo $id; ?>"><i class="ri-delete-bin-5-fill"></i></a></td>
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
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <?php admin_html_body_end([
      ["type" => "script", "src" => "assets/vendor/simple-datatables/simple-datatables.js"],
      ["type" => "script", "src" => "assets/js/main.js"],
    ]); ?>

</body>

</html>