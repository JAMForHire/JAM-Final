<?php

require_once("./components/jar.php");
session_start();
include_once('config.php');

if (!isset($_SESSION['user_id'])) {
  header("location: login.php");
}

$user_id = $_SESSION['user_id'];

try {
  $db = new PDO('mysql:host=localhost;dbname=JAM', $db_username, $db_password);
  $user_id = $_SESSION['user_id'];

  // Check for post request
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['sort'])) {
    // Put into corresponding variables
    $id = stripslashes(trim(htmlspecialchars($_POST['id'])));
    $name = stripslashes(trim(htmlspecialchars($_POST['company'])));
    $date = stripslashes(trim(htmlspecialchars($_POST['date'])));
    $notes = stripslashes(trim(htmlspecialchars($_POST['notes'])));
    $link = stripslashes(trim(htmlspecialchars($_POST['link'])));
    $progress = stripslashes(trim(htmlspecialchars($_POST['progress'])));

    // Check if params are set
    if (isset($_POST['id']) && isset($_POST['company']) && isset($_POST['date']) && isset($_POST['notes']) && isset($_POST['link']) && isset($_POST['progress'])) {
      // If inserting jar
      if (isset($_POST['add'])) {
        $sql = "INSERT INTO jars(user_id, date, company, notes, link, progress) VALUES (:user_id, :date, :name,:notes,:link,:progress)";
        $stmt = $db->prepare($sql);
        $stmt->execute(['user_id' => $user_id, 'date' => $date, 'name' => $name, 'notes' => $notes, 'link' => $link, 'progress' => $progress]);
        $finish = $stmt->fetchAll();
      }

      // Remove jar
      else if (isset($_POST['delete'])) {
        $sql = "DELETE FROM jars WHERE id=:id AND user_id=:user_id";
        $stmt = $db->prepare($sql);
        $stmt->execute(['id' => $id, 'user_id' => $user_id]);
        $finish = $stmt->fetchAll();
      }

      // Adding archived jar to dashboard
      else if (isset($_POST['unarchive'])) {
        $sql = "UPDATE jars SET archived = 0 WHERE id=:id";
        $stmt = $db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $finish = $stmt->fetchAll();
      }

      // Updating jar
      else {
        // Prepare sql statement
        $sql = "UPDATE jars SET company=:name, date=:date, notes=:notes, link=:link, progress=:progress WHERE id=:id AND user_id=:user_id";
        $stmt = $db->prepare($sql);

        // Execute
        $stmt->execute(['id' => $id, 'user_id' => $user_id, 'name' => $name, 'date' => $date, 'notes' => $notes, 'link' => $link, 'progress' => $progress]);
        $finish = $stmt->fetchAll();
      }

      header("Location: archived.php");
      exit();
    }
  }
} catch (PDOException $e) {
  echo "Failed to connect to JAM database: " . $e->getMessage() . "<br>";
  exit();
}
$sortnum='2';
if(isset($_POST["sort"])){
  $sortnum=$_POST["sort"];
}
$jars = get_jars($db, $user_id, $sortnum, 1);
$jar_count = get_num_jars($db, $user_id, 1);

$nav_login_style = "\"display: block;\"";
$nav_profile_style = "\"display: none;\"";
$pfp_src = "Resources/assets/profile.png";

if (!empty($_SESSION['filepath'])) {
  $pfp_src = $_SESSION['filepath'];
}

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 1) {
  $nav_login_style = "\"display: none;\"";
  $nav_profile_style = "\"display: block;\"";
} else {
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
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"
        defer></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"
        defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"
        integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"
        defer></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"
        integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"
        defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" defer></script>
    <script src="./scripts/generate_model.js" defer></script>
    <script src="./scripts/chevron.js" defer></script>
    <script src="./scripts/profile.js" defer></script>
    <script src="./scripts/upgrade.js" defer></script>
    <script src="./scripts/redirect.js" defer></script>
    <title>JAM Archived Opportunities</title>
</head>

<body>
    <!-- Navbar -->
    <nav>
        <a class="active" href="./index.php">Home</a>
        <div class="right">
            <!-- Links -->
            <a href="./dashboard.php">Dashboard</a>
            <a href="./login.php" style=<?php echo $nav_login_style; ?>>Login</a>
            <button id="profile-icon-button" onclick="toggleProfile()" style=<?php echo $nav_profile_style; ?>>
                <img class="profile-icon rounded-circle" src=<?php echo $pfp_src ?> alt="User Profile Picture" />
            </button>
        </div>
    </nav>

    <!-- Profile section -->
    <div id="profile-menu" style="display: none;">
        <div id="profile-overview">
            <img class="profile-icon rounded-circle" src=<?php echo $pfp_src ?> alt="User Profile Picture" />
            <p><?php echo $_SESSION['username'] ?>'s Profile</p>
        </div>
        <div>
            <?php
      if (!empty($_SESSION['fname']) || !empty($_SESSION['lname'])) {
        echo "<p>Name: " . $_SESSION['fname'] . " " . $_SESSION['lname'] . "</p>";
      }
      if ($_SESSION['type'] == 1) echo "<p>User Type: Premium</p>";
      else echo "<p>User Type: Standard</p>";
      if ($_SESSION['dob'] != "0000-00-00") echo "<p>Date of Birth: " . $_SESSION['dob'] . "</p>";
      ?>
            <form id="logout-form" method="post" action="logout.php">
                <input type="submit" class="btn btn-jam" name="logout" id="logout" value="Logout" />
            </form>
        </div>
    </div>

    <!-- Main content -->
    <div class="d-flex h-90">
        <div class="d-flex flex-column align-items-center flex-shrink-0 p-3 bg-light" style="width: 230px;">
            <p class='pt-5 display-5 text-center'>Welcome</p><img src=<?php echo $pfp_src ?> class="mt-2 mb-3 pfp rounded-circle" />
            <?php
      if (isset($_SESSION['logged_in'])) {
        if (!empty($_SESSION['fname']) || !empty($_SESSION['lname'])) {
          echo "<p class='h3'>" . $_SESSION['fname'] . "</p><p class='h5'>" . $_SESSION['lname'] . "</p>";
        } else echo "<p>" . $_SESSION['username'] . "</p>";
      } else echo "<p style=\"text-align: center\">Log in to access this feature of JAM!</p>";
      ?>
        </div>
        <div class="d-flex flex-column p-3">
            <div
                class="mt-4 d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                <!-- Archived Opportunities Section -->
                <h1 class="h2" id="dashboard-title">Archived Opportunities</h1>
                <img class="icon dashboard-chevron2" src="#" id="chevron" alt="chevron" />
                <div class="d-flex justify-content-end w-100 h-50">
                  <div class="dropdown mb-1">
                    <button class="btn dropdown-toggle border btn-outline-secondary" type="button" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">
                      Sort
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                      <form method="post" action="archived.php" id="sortForm">
                        <li><button class="dropdown-item" type="submit" form="sortForm" name="sort" value="0">Date</button></li>
                        <li><button class="dropdown-item" type="submit" form="sortForm" name="sort" value="1">Progress</button></li>
                        <li><button class="dropdown-item" type="submit" form="sortForm" name="sort" value="2">Name</button></li>
                      </form>
                    </ul>
                  </div>
                  <button type="button" class="btn btn-secondary" id="goto-dashboard" onclick="redirect('dashboard.php')">Return to Dashboard</button>
                </div>
            </div>

    <!-- Jars -->
    <div class="jar-row">
      <!-- Jar -->
      <?php
        foreach($jars as $jar) {
          render_archived_jar($jar['id'], $user_id, $jar['company'], $jar['date'], $jar['notes'], $jar['link'], $jar['progress']);
        }
      ?>
    </div>
</body>

</html>