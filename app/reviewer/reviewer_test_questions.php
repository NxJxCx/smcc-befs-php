<?php

authenticated_page("reviewer");

$s_id = conn()->sanitize($_REQUEST['s_id']);

$query=conn()->query("select * from subjects where id = '$s_id'")or die(mysqli_error(conn()->get_conn()));
if($row=mysqli_fetch_array($query))
{
$sub_desc = $row['description'];
}

$query=conn()->query("select * from users where id = '" . user_id() . "'")or die(mysqli_error(conn()->get_conn()));
if($row=mysqli_fetch_array($query)) {
  $fname=$row['fname'];
  $lname=$row['lname'];
  $type=$row['type'];
  $fname = ucfirst(strtolower($fname));
  $lname = ucfirst(strtolower($lname));
  $type = ucfirst(strtolower($type));
}

admin_html_head("Manage Test Questions", [
    // [ "type" => "style", "href" => "assets/vendor/simple-datatables/style.css" ],
    [ "type" => "style", "href" => "assets/css/style.css" ],
]); // html head

?>

<body>
  <!-- ======= Header ======= -->
 <?php 
 require_once get_reviewer_header();
 ?> 
  <!-- End Header -->
  <!-- ======= Sidebar ======= -->
 <?php 
 require_once get_reviewer_sidebar();
 ?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Manage Test Questions</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="reviewer_home">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="reviewer_subjects">Subjects</a></li>
          <li class="breadcrumb-item">Manage Test Questions</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
      <div class="row">
      

        <div class="col-xl-12">

          <div class="card">
            
            <div class="card-body pt-3">
            <h1><b><?php echo $sub_desc; ?></b></h1>
            </br>
            </br>
            
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">

              <li class="nav-item">
                <button class="nav-link <?php echo (!isset($_GET['active_tab']) || $_GET['active_tab'] == 'Prelim') ? 'active' : ''; ?>" 
                        data-bs-toggle="tab" 
                        data-bs-target="#Prelim">
                  Pre-Board 1
                </button>
              </li>
              <li class="nav-item">
                <button class="nav-link <?php echo (isset($_GET['active_tab']) && $_GET['active_tab'] == 'Preboard') ? 'active' : ''; ?>" 
                        data-bs-toggle="tab" 
                        data-bs-target="#Preboard">
                  Pre-Board 2
                </button>
              </li>
              </ul>
              <div class="tab-content pt-2">
              <div class="tab-pane fade show <?php echo (!isset($_GET['active_tab']) || $_GET['active_tab'] == 'Prelim') ? 'active' : ''; ?>" id="Prelim">
              </br>
                <!-- Profile Edit Form -->
                <form action="./add_questions_preboard1_sc?s_id=<?php echo $s_id; ?>&active_tab=Prelim" 
                method="POST" enctype="multipart/form-data" class="row g-3 user needs-validation" novalidate>
                <div class="row mb-3">
                  <label for="question" class="col-md-4 col-lg-3 col-form-label">Questions</label>
                  <div class="col-md-8 col-lg-9">
                    <textarea name="question" class="form-control" id="question" rows="4" required></textarea>
                  </div>
                </div>

                    <div class="row mb-3">
                      <label for="option1" class="col-md-4 col-lg-3 col-form-label"><input name="option" type="radio" id="option1" value="Option 1" style="display: none;"> Option 1</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="answer1" type="text" class="form-control" id="answer1" placeholder="Enter option 1 answer" required>
                        
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="option2" class="col-md-4 col-lg-3 col-form-label"><input name="option" type="radio" id="option2" value="Option 2" style="display: none;"> Option 2</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="answer2" type="text" class="form-control" id="answer2" placeholder="Enter option 2 answer" required>
                        
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="option3" class="col-md-4 col-lg-3 col-form-label"><input name="option" type="radio" id="option3" value="Option 3" style="display: none;"> Option 3</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="answer3" type="text" class="form-control" id="answer3" placeholder="Enter option 3 answer" required>
                        
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="option4" class="col-md-4 col-lg-3 col-form-label"><input name="option" type="radio" id="option4" value="Option 4" style="display: none;"> Option 4</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="answer4" type="text" class="form-control" id="answer4" placeholder="Enter option 4 answer" required>
                        
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="finalAnswer" class="col-md-4 col-lg-3 col-form-label">Answer</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="final_answer" type="text" class="form-control" id="finalAnswer" readonly>
                      </div>
                    </div>

                    <script>
                      // Function to show the radio button when the text input has content
                      document.getElementById('answer1').addEventListener('input', function() {
                        if (this.value.trim() !== "") {
                          document.getElementById('option1').style.display = 'inline';  // Show radio button for Option 1
                        } else {
                          document.getElementById('option1').style.display = 'none';   // Hide radio button for Option 1
                        }
                      });

                      document.getElementById('answer2').addEventListener('input', function() {
                        if (this.value.trim() !== "") {
                          document.getElementById('option2').style.display = 'inline';  // Show radio button for Option 2
                        } else {
                          document.getElementById('option2').style.display = 'none';   // Hide radio button for Option 2
                        }
                      });

                      document.getElementById('answer3').addEventListener('input', function() {
                        if (this.value.trim() !== "") {
                          document.getElementById('option3').style.display = 'inline';  // Show radio button for Option 3
                        } else {
                          document.getElementById('option3').style.display = 'none';   // Hide radio button for Option 3
                        }
                      });

                      document.getElementById('answer4').addEventListener('input', function() {
                        if (this.value.trim() !== "") {
                          document.getElementById('option4').style.display = 'inline';  // Show radio button for Option 4
                        } else {
                          document.getElementById('option4').style.display = 'none';   // Hide radio button for Option 4
                        }
                      });

                      // Function to update the final answer field when a radio button is selected
                      document.getElementById('option1').addEventListener('change', function() {
                        if (this.checked) {
                          document.getElementById('finalAnswer').value = document.getElementById('answer1').value;
                        }
                      });

                      document.getElementById('option2').addEventListener('change', function() {
                        if (this.checked) {
                          document.getElementById('finalAnswer').value = document.getElementById('answer2').value;
                        }
                      });

                      document.getElementById('option3').addEventListener('change', function() {
                        if (this.checked) {
                          document.getElementById('finalAnswer').value = document.getElementById('answer3').value;
                        }
                      });

                      document.getElementById('option4').addEventListener('change', function() {
                        if (this.checked) {
                          document.getElementById('finalAnswer').value = document.getElementById('answer4').value;
                        }
                      });
                    </script>




                    <div align="right">
                      <button name="add_prelim_question" type="submit" class="btn btn-primary">Add Question</button>
                    </div>
                    </form>
                    <?php
  // Count the number of questions for Pre-Board 1
  $count_query = conn()->query("SELECT COUNT(*) AS total_questions FROM question_answer WHERE subject_id = '$s_id' AND faculty_id = '" . user_id() . "' AND level='PREBOARD1'") or die(mysqli_error(conn()->get_conn()));
  $count_row = mysqli_fetch_assoc($count_query);
  $total_questions = $count_row['total_questions'];
?>
<h2>List of Questions (<?php echo $total_questions; ?>)</h2>

                    </br>
                    <div class="row mb-3">
                    <table class="table">
                      <thead>
                        <tr>
                          <th>No.</th>
                          <th>Question</th>
                          <th>Answer</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          
                          $query = conn()->query("SELECT * FROM question_answer WHERE subject_id = '$s_id' AND faculty_id = '" . user_id() . "' AND level='PREBOARD1'") or die(mysqli_error(conn()->get_conn()));
                          
                          $no = 1; // Initialize row counter
                          while ($row = mysqli_fetch_array($query)) {
                            $qid = $row['id'];
                            $question = $row['question'];
                            $answer = $row['answer'];
                        ?>
                        <tr>
                          <td><?php echo $no++; ?></td> <!-- Increment the counter for each row -->
                          <td><?php echo htmlspecialchars($question); ?></td> <!-- Use htmlspecialchars for safety -->
                          <td><?php echo htmlspecialchars($answer); ?></td> <!-- Use htmlspecialchars for safety -->
                          <td><a href="remove_questions_sc?qid=<?php echo $qid;?>&s_id=<?php echo $s_id;?>">Remove</a></td>
                        </tr>
                        <?php 
                          } 
                        ?>
                      </tbody>
                    </table>
                    </div>
                </div>
                <div class="tab-pane fade show <?php echo (isset($_GET['active_tab']) && $_GET['active_tab'] == 'Preboard') ? 'active' : ''; ?>" id="Preboard">
                </br>
                <!-- Profile Edit Form -->
                <form action="./add_questions_preboard2_sc?s_id=<?php echo $s_id; ?>&active_tab=Preboard" 
                method="POST" enctype="multipart/form-data" class="row g-3 user needs-validation" novalidate>
                    <div class="row mb-3">
                      <label for="question" class="col-md-4 col-lg-3 col-form-label">Questions</label>
                      <div class="col-md-8 col-lg-9">
                        <textarea name="question" class="form-control" id="question" rows="4" required></textarea>
                      </div>
                    </div>
                    <div class="row mb-3">
                      <label for="option11" class="col-md-4 col-lg-3 col-form-label"><input name="option" type="radio" id="option11" value="Option 1" style="display: none;"> Option 1</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="answer11" type="text" class="form-control" id="answer11" placeholder="Enter option 1 answer" required>
                        
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="option22" class="col-md-4 col-lg-3 col-form-label"><input name="option" type="radio" id="option22" value="Option 2" style="display: none;"> Option 2</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="answer22" type="text" class="form-control" id="answer22" placeholder="Enter option 2 answer" required>
                        
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="option33" class="col-md-4 col-lg-3 col-form-label"><input name="option" type="radio" id="option33" value="Option 3" style="display: none;"> Option 3</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="answer33" type="text" class="form-control" id="answer33" placeholder="Enter option 3 answer" required>
                        
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="option44" class="col-md-4 col-lg-3 col-form-label"><input name="option" type="radio" id="option44" value="Option 4" style="display: none;"> Option 4</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="answer44" type="text" class="form-control" id="answer44" placeholder="Enter option 4 answer" required>
                        
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="finalAnswer1" class="col-md-4 col-lg-3 col-form-label">Answer</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="final_answer1" type="text" class="form-control" id="finalAnswer1" readonly>
                      </div>
                    </div>

                    <script>
                      // Function to show the radio button when the text input has content
                      document.getElementById('answer11').addEventListener('input', function() {
                        if (this.value.trim() !== "") {
                          document.getElementById('option11').style.display = 'inline';  // Show radio button for Option 1
                        } else {
                          document.getElementById('option11').style.display = 'none';   // Hide radio button for Option 1
                        }
                      });

                      document.getElementById('answer22').addEventListener('input', function() {
                        if (this.value.trim() !== "") {
                          document.getElementById('option22').style.display = 'inline';  // Show radio button for Option 2
                        } else {
                          document.getElementById('option22').style.display = 'none';   // Hide radio button for Option 2
                        }
                      });

                      document.getElementById('answer33').addEventListener('input', function() {
                        if (this.value.trim() !== "") {
                          document.getElementById('option33').style.display = 'inline';  // Show radio button for Option 3
                        } else {
                          document.getElementById('option33').style.display = 'none';   // Hide radio button for Option 3
                        }
                      });

                      document.getElementById('answer44').addEventListener('input', function() {
                        if (this.value.trim() !== "") {
                          document.getElementById('option44').style.display = 'inline';  // Show radio button for Option 4
                        } else {
                          document.getElementById('option44').style.display = 'none';   // Hide radio button for Option 4
                        }
                      });

                      // Function to update the final answer field when a radio button is selected
                      document.getElementById('option11').addEventListener('change', function() {
                        if (this.checked) {
                          document.getElementById('finalAnswer1').value = document.getElementById('answer11').value;
                        }
                      });

                      document.getElementById('option22').addEventListener('change', function() {
                        if (this.checked) {
                          document.getElementById('finalAnswer1').value = document.getElementById('answer22').value;
                        }
                      });

                      document.getElementById('option33').addEventListener('change', function() {
                        if (this.checked) {
                          document.getElementById('finalAnswer1').value = document.getElementById('answer33').value;
                        }
                      });

                      document.getElementById('option44').addEventListener('change', function() {
                        if (this.checked) {
                          document.getElementById('finalAnswer1').value = document.getElementById('answer44').value;
                        }
                      });
                    </script>




                    <div align="right">
                      <button name="add_prelim_question1" type="submit" class="btn btn-primary">Add Question</button>
                    </div>
                    </form>
                    <?php
                      // Count the number of questions for Pre-Board 1
                      $count_query = conn()->query("SELECT COUNT(*) AS total_questions FROM question_answer WHERE subject_id = '$s_id' AND faculty_id = '" . user_id() . "' AND level='PREBOARD2'") or die(mysqli_error(conn()->get_conn()));
                      $count_row = mysqli_fetch_assoc($count_query);
                      $total_questions = $count_row['total_questions'];
                    ?>
                    <h2>List of Questions (<?php echo $total_questions; ?>)</h2>

                    </br>
                    <div class="row mb-3">
                    <table class="table">
                      <thead>
                        <tr>
                          <th>No.</th>
                          <th>Question</th>
                          <th>Answer</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          
                          $query = conn()->query("SELECT * FROM question_answer WHERE subject_id = '$s_id' AND faculty_id = '" . user_id() . "' AND level = 'PREBOARD2'") or die(mysqli_error(conn()->get_conn()));
                          
                          $no = 1; // Initialize row counter
                          while ($row = mysqli_fetch_array($query)) {
                            $qid = $row['id'];
                            $question = $row['question'];
                            $answer = $row['answer'];
                        ?>
                        <tr>
                          <td><?php echo $no++; ?></td> <!-- Increment the counter for each row -->
                          <td><?php echo htmlspecialchars($question); ?></td> <!-- Use htmlspecialchars for safety -->
                          <td><?php echo htmlspecialchars($answer); ?></td> <!-- Use htmlspecialchars for safety -->
                          <td>
                              <a href="remove_questions_sc?qid=<?php echo $qid; ?>&s_id=<?php echo $s_id; ?>&active_tab=Preboard">Remove</a>
                          </td>
                        </tr>
                        <?php 
                          } 
                        ?>
                      </tbody>
                    </table>
                    </div>
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