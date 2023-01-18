<?php
  require_once 'includes/dbc-inc.php';
  require_once 'includes/functions-inc.php';
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Icon library -->
    <link rel="stylesheet" href="fontawesome-6.1.1\css\all.css">
    <!-- Bootstrap CSS -->
    <link href="bootstrap_css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Style CSS -->
    <link rel="stylesheet" href="style.css?<?php echo time(); ?>">

    <script type="text/javascript">
      function scrollDown() {
        document.getElementById("about_content").scrollIntoView(); // JUMP TO DIV "BOTTOM".
      }
    </script>

    <title>About</title>
  </head>
  <body>

    <?php
      include_once 'header.php';
    ?>

    <div class="container-fluid about-container">
      <div class="row about-intro d-flex justify-content-center pt-5">
        <div class="col-md-6 d-flex flex-column order-last order-md-first mt-4">
          <span class="about-title m-0 text-center text-sm-start">UNIVERSITY OF MAKATI<span class="about-title-secondary m-0"> E-LIBRARY</span></span>
          <p class="text-dark">Our digital library contains a collection of fifteen million print and electronic volumes in more than a dozen libraries and locations.
          Our resources for teaching and research range from thousands of licensed e-resources.</p>
          <button type="submit" name="button" onclick="scrollDown()" class="btn about-btn d-none d-md-block">READ MORE</button>
        </div>
        <div class="col-md-5">
          <img src="img/tablet.png" alt="" class="img-fluid tablet-img">
        </div>
      </div>

      <div class="row d-flex justify-content-center" id="about_content">
        <h1 class="text-center my-5">ABOUT</h1>
        <div class="col-md-8">
          <figure class="figure">
            <img src="img/umak.jpg" class="figure-img img-fluid rounded" alt="...">
            <figcaption class="figure-caption">The University of Makati (UMak) is a public, locally funded university of the local government of Makati. It is envisioned as the primary instrument where university education and industry training programs interface to mold Makati and non-Makati youth into productive citizens and IT-enabled professionals who are exposed to cutting-edge technology in their areas of specialization. UMak is the final stage of Makati City's integrated primary level to tertiary level educational system that enables its less privileged citizens to compete for job opportunities in various businesses and industries.</figcaption>
          </figure>
        </div>
      </div>

      <div class="row d-flex justify-content-center">
        <h1 class="text-center my-5">Community Values</h1>
        <div class="col-md-8">
          <figure class="figure">
            <img src="img/umak.png" class="figure-img img-fluid rounded" alt="...">
            <figcaption class="figure-caption">The library’s community values serve as a reminder of our principles. Underpinning these values is a commitment to the free and respectful interchange of ideas.
              We intend for the following values to serve as guiding principles for everything we do.</figcaption>
          </figure>
        </div>
      </div>
      <div class="row d-flex justify-content-center">
        <div class="col-md-8">
          <p><span>Integrity</span> We honor our commitments and hold ourselves and each other accountable for them.</p>
          <p><span>Respect</span> We treat people equally well regardless of position or status. We honor individual differences and continually strive to create an environment that is diverse and inclusive, in which each person is valued.</p>
          <p><span>Civility</span> We encourage open communication. We think before we speak, listen carefully, respond respectfully, and acknowledge that everyone’s ideas are worthy of consideration.</p>
        </div>
      </div>

      <div class="row d-flex justify-content-center mb-sm-5">
        <h1 class="text-center my-5">Meet the Empowered Youth Members</h1>
        <div class="col-md-8">
          <div class="row justify-content-evenly">
            <div class="card col-lg-5 mb-3 mb-lg-0">
                <img src="img/dev_david.jpg" class="card-img-top" alt="...">
                <div class="card-body d-flex">
                  <a href="https://www.facebook.com/davidrafael.sumawang.3">
                    <h5 class="card-title">David Rafael Sumawang</h5>
                    <p class="card-text"><small class="text-muted">Developer/Tester</small></p>
                  </a>
                </div>
            </div>
            <div class="card col-lg-5 mb-3 mb-lg-0">
              <img src="img/dev_lawrence.jpg" class="card-img-top" alt="...">
              <div class="card-body">
                <a href="https://www.facebook.com/kuyaguard19">
                  <h5 class="card-title">Lawrence Amores</h5>
                  <p class="card-text"><small class="text-muted">UI Designer</small></p>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row d-flex justify-content-center mb-sm-5">
        <div class="col-md-8">
          <div class="row justify-content-evenly">
            <div class="card col-lg-5 mb-3 mb-lg-0">
              <img src="img/dev_gino.jpg" class="card-img-top" alt="...">
              <div class="card-body">
                <a href="https://www.facebook.com/ginomgsn/">
                  <h5 class="card-title">Gino Ben Magsino</h5>
                  <p class="card-text"><small class="text-muted">Lead Developer/Database Manager</small></p>
                </a>
              </div>
            </div>
            <div class="card col-lg-5 mb-3 mb-lg-0">
              <img src="img/valfrid.jpg" class="card-img-top" alt="...">
              <div class="card-body">
                <a href="https://www.facebook.com/realvalfridgalinato">
                  <h5 class="card-title">Valfrid Galinato</h5>
                  <p class="card-text"><small class="text-muted">Project Manager</small></p>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- BACK TO TOP-->
      <a href="#" class="back-to-top">
        <i class="fa-solid fa-circle-chevron-up"></i>
      </a>

    </div>

    <?php
      include_once 'footer.php';
    ?>

    <script src="bootstrap_js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">
      const toTop = document.querySelector(".back-to-top");
      window.addEventListener("scroll", () => {
        if (window.pageYOffset > 100) {
          toTop.classList.add("active");
        }
        else {
          toTop.classList.remove("active");
        }
      })
    </script>
  </body>

</html>
