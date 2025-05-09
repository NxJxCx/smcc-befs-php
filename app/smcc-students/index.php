<?php

authenticated_page("student");

$stud_id = user_id();

$query = conn()->query("select * from students where id = '$stud_id'") or die(mysqli_error(conn()->get_conn()));

while ($row = mysqli_fetch_array($query)) {
    $fname = $row['fname'];
    $lname = $row['lname'];
}

student_html_head('Home', [
    [ "type" => "style", "href" => "smcc-students/lib/animate/animate.min.css" ],
    [ "type" => "style", "href" => "smcc-students/lib/owlcarousel/assets/owl.carousel.min.css" ],
    [ "type" => "style", "href" => "smcc-students/css/style.css" ],
]);
?>

<body>
    <?php student_nav("exam_subject_list", "Take Exam Now"); ?>

    <!-- Carousel Start -->
    <div class="container-fluid p-0 mb-5">
        <div class="owl-carousel header-carousel position-relative">
            <div class="owl-carousel-item position-relative">
                <img class="img-fluid" src="<?= base_url() ?>/smcc-students/img/smccnasipit_cover.jpeg" alt="" style="width: 100%; height: 900px;">
                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center" style="background: rgba(24, 29, 56, .7);">
                    <div class="container">
                        <div class="row justify-content-start">
                            <div class="col-sm-10 col-lg-8">
                                <h1 class="display-3 text-white animated slideInDown"><?php echo "Hi! " . $fname . " " . $lname; ?> </h1>
                                </br>
                                </br>
                                </br>
                                <h5 class="text-primary text-uppercase mb-3 animated slideInDown">Saint Michael College of Caraga</h5>
                                <h1 class="text-primary text-uppercase text-white mb-3 animated slideInDown">Vision</h1>
                                <p class="fs-5 text-white mb-4 pb-2">Saint Michael College of Caraga Envisions to be a University by 2035 and Upholds Spiritual Formation and Excellence in Teaching, Service, and Research.</p>

                                <a href="exam_subject_list" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">Take Exam Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="owl-carousel-item position-relative">
                <img class="img-fluid" src="<?= base_url() ?>/smcc-students/img/smcc-staffs.jpg" alt="" style="width: 100%; height: 900px;">
                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center" style="background: rgba(24, 29, 56, .7);">
                    <div class="container">
                        <div class="row justify-content-start">
                            <div class="col-sm-10 col-lg-8">
                                <h1 class="display-3 text-white animated slideInDown"><?php echo "Hi! " . $fname . " " . $lname; ?></h1>
                                </br>
                                </br>
                                </br>
                                <h5 class="text-primary text-uppercase mb-3 animated slideInDown">Saint Michael College of Caraga</h5>
                                <h1 class="text-primary text-uppercase text-white mb-3 animated slideInDown">Mission</h1>
                                <p class="fs-5 text-white mb-4 pb-2">- SMCC shall provide spiritual formation and learning culture that will ensure the students with excellent and rewarding learning experience that transform lives, abound spirituality, develop skills and prepare future leaders. <br><br>
                                    - SMCC shall engage in dynamic, innovative, and interdisciplinary researches that are publishable to advance and achieve institutional initiatives. <br><br>
                                    - SMCC shall commit to serve the diverse and local communities in fostering innovations through service-learning that enhances reciprocal community partnerships for spiritual and social development.</p>

                                <a href="exam_subject_list" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">Take Exam Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="owl-carousel-item position-relative">
                <img class="img-fluid" src="<?= base_url() ?>/smcc-students/img/smcc.jpg" alt="" style="width: 100%; height: 900px;">
                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center" style="background: rgba(24, 29, 56, .7);">
                    <div class="container">
                        <div class="row justify-content-start">
                            <div class="col-sm-10 col-lg-8">
                                <h1 class="display-3 text-white animated slideInDown"><?php echo "Hi! " . $fname . " " . $lname; ?></h1>
                                </br>
                                </br>
                                </br>
                                <h5 class="text-primary text-uppercase mb-3 animated slideInDown">Saint Michael College of Caraga</h5>
                                <h1 class="text-primary text-uppercase text-white mb-3 animated slideInDown">Goal</h1>
                                <p class="fs-5 text-white mb-4 pb-2">Uphold Culture of Excellence in the Areas of Spiritual Formation, Instruction, Research, and Extension, thus Produce Graduates that are Globally Competent, Spiritually Embodied, and Socially Responsible.</p>

                                <a href="exam_subject_list" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">Take Exam Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Carousel End -->


    <!-- Service Start -->
    <div class="container-xxl py-5">
        <h1 class="section-title bg-white text-start text-primary pe-3">Michaelinian Identity</h1>
        <br>
        <h5>Secured by Saint Michael the Archangel’s Sword of Bravery and Victory, nourished by the faithful
            acceptance and practice of the Christian teachings and guidance of the Catholic Church, animated by the
            Blessed Virgin Mary’s maternal devotion and intercession, guided by the gospel values, and empowered
            by Christ’s life and examples – the Michaelinians of today and tomorrow are persons who are:</h5>
        <br>
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="service-item text-center pt-3">
                        <div class="p-4">
                            <i class="fa fa-3x fa-hands-helping text-primary mb-4"></i>
                            <h5 class="mb-3">Socially Responsible</h5>
                            <p>for the respect, care, love and development of God’s creations as such at
                                all times demonstrate and live out their social responsibilities;
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="service-item text-center pt-3">
                        <div class="p-4">
                            <i class="fa fa-3x fa-cross text-primary mb-4"></i>
                            <h5 class="mb-3">Missionaries of Christian Values </h5>
                            <p>in possessing a faith that is dynamic to imbibe and to
                                proclaim and promote the Christian values, hence, sharing in the mission of Christ and of the
                                Catholic church to make all people members of one sheepfold of God;
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="service-item text-center pt-3">
                        <div class="p-4">
                            <i class="fa fa-3x fa-user-tie text-primary mb-4"></i>
                            <h5 class="mb-3">Committed Individuals and/or Leaders</h5>
                            <p>as equated to the faithful commitment of Jesus to His
                                Father, thus, upholding unconditional commitment to value-filled life and actions of love and
                                mercy;
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.7s">
                    <div class="service-item text-center pt-3">
                        <div class="p-4">
                            <i class="fa fa-3x fa-lightbulb text-primary mb-4"></i>
                            <h5 class="mb-3">Competent in their Chosen Fields of Endeavor</h5>
                            <p>by being aware, curious, and interested in
                                learning about the world and how it works in order to possess the ability to innovate and
                                ensure success.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Service End -->


    <!-- About Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s" style="min-height: 400px;">
                    <div class="position-relative h-100">
                        <img class="img-fluid position-absolute w-100 h-100" src="<?= base_url() ?>/smcc-students/img/2019-01-10.jpg" alt="" style="object-fit: cover;">
                    </div>
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.3s">
                    <h1 class="section-title bg-white text-start text-primary pe-3">General Objectives</h1>
                    <p class="mb-4">-To integrate positive and evangelical values in all areas and design Christian formation programs that are effective and responsive to the psychospiritual needs of the students, pupils, parents, and personnel. <br><br>-To continuously enhance the curriculum and upgrade teachers’ professional, emotional, spiritual growth, and quality of instruction.

                        <br><br>-To continue upgrading facilities and services for the satisfaction of the clientele.

                        <br><br>-To intensify the curriculum-based and institutional researches that are dynamic, innovative, and interdisciplinary

                        <br><br>-To implement programs that help educate, motivate, and inspire to assume an active role and become socially responsible stewards of God’s creation.

                        <br><br>-To provide the best student services catering physical, mental, emotional, spiritual, socio-cultural needs of the students.

                        <br><br>-To establish harmonious linkages with the Alumni, PTA, LGU, and other stakeholders to gain support for the school development plans.

                        <br><br>-To work for Accreditation by any recognized accrediting agency.
                    </p>

                </div>
            </div>
        </div>
    </div>
    <!-- About End -->

    <!-- Testimonial Start -->
    <div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="text-center">
                <h6 class="section-title bg-white text-center text-primary px-3">Testimonial</h6>
                <h1 class="mb-5">Our Students Say!</h1>
            </div>
            <div class="owl-carousel testimonial-carousel position-relative">

                <?php
                
                $query = conn()->query("select * from students") or die(mysqli_error(conn()->get_conn()));
                while ($row = mysqli_fetch_array($query)) {
                    $fname = $row['fname'];
                    $about = $row['about'];
                    if ($about == "") {
                        $about = "Nothing to say!";
                    } else {
                        $about = $row['about'];
                    }
                ?>
                    <div class="testimonial-item text-center">
                        <h5 class="mb-0"><?php echo $fname; ?></h5>
                        <p>Student</p>
                        <div class="testimonial-text bg-light text-center p-4">
                            <p class="mb-0"><?php echo $about; ?></p>
                        </div>
                    </div>

                <?php
                }
                ?>
            </div>
        </div>
    </div>
    <!-- Testimonial End -->


    <!-- Footer Start -->
    <?php
    require_once get_student_footer();
    ?>
    <!-- Footer End -->


    
    <?php student_html_body_end([
        [ "type" => "script", "src" => "/smcc-students/lib/owlcarousel/owl.carousel.min.js" ],
        [ "type" => "script", "src" => "/smcc-students/js/main.js" ],
    ]); ?>

</body>

</html>