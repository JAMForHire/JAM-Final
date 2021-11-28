<?php
session_start();
include_once('config.php');

try {
  $db = new PDO('mysql:host=localhost;dbname=JAM', $db_username, $db_password);
}
catch(PDOException $e) {
  echo "Failed to connect to JAM database: " . $e->getMessage() . "<br>";
  exit();
}

$nav_login_style = "\"display: block;\"";
$nav_profile_style = "\"display: none;\"";
$pfp_src = "Resources/assets/profile.png";

if(!empty($_SESSION['filepath'])) {
  $pfp_src = $_SESSION['filepath'];
}

if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 1) {
  $nav_login_style = "\"display: none;\"";
  $nav_profile_style = "\"display: block;\"";
}
else {
  $nav_login_style = "\"display: block;\"";
  $nav_profile_style = "\"display: none;\"";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./styles/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
    crossorigin="anonymous" defer></script>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
    integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
    crossorigin="anonymous" defer></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"
    integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh"
    crossorigin="anonymous" defer></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"
    integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ"
    crossorigin="anonymous" defer></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" defer></script>
  <script src="./scripts/generate_model.js" defer></script>
  <script src="./scripts/chevron.js" defer></script>
  <script src="./scripts/profile.js" defer></script>
  <title>JAM Dashboard</title>
</head>

<body>
  <!-- Modal -->
  <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <!-- Main Modal Content -->
      <div class="modal-content">
        <div class="modal-header border-0">
          <!-- Heading -->
          <h5 class="modal-title" id="modalTitle">Create JAM Jar</h5>
        </div>
        <!-- Body text -->
        <div class="modal-body">
          <h1 class="text-center mb-4">Opportunity Title</h1>
          <form class="">
            <!-- Company Input -->
            <div class="input-group mb-4">
              <div class="input-group-prepend">
                <span class="input-group-text">Company:</span>
              </div>
              <input type="text" id="company" class="form-control" placeholder="Enter company name" required>
            </div>
            <!-- Due Date Input -->
            <div class="input-group mb-4">
              <div class="input-group-prepend">
                <span class="input-group-text">Due Date:</span>
              </div>
              <input type="text" id="date" class="form-control" placeholder="mm/dd/yy" required>
            </div>
            <!-- Notes Input -->
            <div class="input-group mb-4">
              <div class="input-group-prepend">
                <span class="input-group-text pr-5">Notes:</span>
              </div>
              <textarea id="notes" rows="5" class="form-control" placeholder="Enter notes"></textarea>
            </div>
            <!-- Application Link Input -->
            <div class="input-group mb-4">
              <div class="input-group-prepend">
                <span class="input-group-text">Link:</span>
              </div>
              <input type="text" id="app_link" class="form-control" placeholder="Application Link" required>
            </div>
            <!-- Progress Selection -->
            <div class="input-group mb-4">
              <div class="input-group-append">
                <label class="input-group-text" for="progress">Progress:</label>
              </div>
              <select id="progress"
                class="form-control custom-select text-left border-secondary border border-1 rounded text-secondary"
                required>
                <option selected value="">Choose...</option>
                <option value="1">Not Started</option>
                <option value="2">In Progress</option>
                <option value="3">Completed</option>
              </select>
            </div>
          </form>
        </div>
        <!-- Buttons for closing -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal">Confirm</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Navbar -->
  <nav>
    <a class="active" href="./index.php">Home</a>
    <div class="right">
      <!-- Links -->
      <a href="./dashboard.php">Dashboard</a>
      <a href="./login.php" style=<?php echo $nav_login_style; ?>>Login</a>
      <button id="profile-icon-button" onclick="toggleProfile()" style=<?php echo $nav_profile_style; ?>>
        <img class="profile-icon" src=<?php echo $pfp_src ?> alt="User Profile Picture"/>
      </button>
    </div>
  </nav>

  <!-- Profile section -->
  <div id="profile-menu" style="display: none;">
    <div id="profile-overview">
      <img class="profile-icon" src=<?php echo $pfp_src ?> alt="User Profile Picture"/>
      <p><?php echo $_SESSION['username'] ?>'s Profile</p>
    </div>
    <div>
      <?php
        if(!empty($_SESSION['fname']) || !empty($_SESSION['lname'])) {
          echo "<p>Name: " . $_SESSION['fname'] . " " . $_SESSION['lname'] . "</p>";
        }
        if($_SESSION['type'] == 1) echo "<p>User Type: Premium</p>";
        else echo "<p>User Type: Standard</p>";
        if($_SESSION['dob'] != "0000-00-00") echo "<p>Date of Birth: " . $_SESSION['dob'] . "</p>";
      ?>
      <form id="logout-form" method="post" action="logout.php">
        <input type="submit" name="logout" id="logout" value="Logout"/>
      </form>
    </div>
  </div>

  <!-- Main content -->
  <div class="d-flex h-90">
    <div class="d-flex flex-column align-items-center flex-shrink-0 p-3 bg-light" style="width: 230px;">
      <img src=<?php echo $pfp_src ?> class="mt-4 mb-3 pfp" />
      <?php
        if(isset($_SESSION['logged_in'])) {
          if(!empty($_SESSION['fname']) || !empty($_SESSION['lname'])) {
            echo "<p>" . $_SESSION['fname'] . " " . $_SESSION['lname'] . "</p>";
          }
          else echo "<p>" . $_SESSION['username'] . "</p>";
        }
        else echo "<p style=\"text-align: center\">Log in to access this feature of JAM!</p>";
      ?>
    </div>
    <div class="d-flex flex-column p-3">
    <div class="mt-4 d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
      <!-- Opportunities Section -->
      <h1 class="h2">Opportunities</h1>
      <img class="icon" src="#" id="chevron" alt="chevron" />
      <div class="d-flex justify-content-end w-100 h-25">
        <button type="button" class="bg-transparent border-0" data-toggle="modal" data-target="#modal"
          onclick="gen_modal('0')">
          <img class="icon" src="./Resources/assets/add.svg" alt="add" />
        </button>
      </div>
    </div>

    <!-- Jars -->
    <div class="d-flex gap-5 flex-column flex-lg-row">
      <!-- Jar -->
      <div id="jar_1" class="jar-50" data-toggle="modal" data-target="#modal" onclick="gen_modal('jar_1')">
        Company 1
      </div>
      <!-- Jar -->
      <div id="jar_2" class="jar-100" data-toggle="modal" data-target="#modal" onclick="gen_modal('jar_2')">
        Company 2
      </div>
      <!-- Jar -->
      <div id="jar_3" class="jar-100" data-toggle="modal" data-target="#modal" onclick="gen_modal('jar_3')">
        Company 3
      </div>
    </div>
  </div>
  </div>

  <!-- Footer -->
  <footer>
    <p class="copyright text-white">© JAM 2021</p>
  </footer>
</body>

</html>