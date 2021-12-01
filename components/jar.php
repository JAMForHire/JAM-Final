<?php
function get_jars($conn, $user_id) {
  $sql = "SELECT * FROM jars WHERE user_id=:user_id";
  $stmt = $conn->prepare($sql);
  $stmt->execute(['user_id' => $user_id]);
  $result = $stmt->fetchAll();

  return $result;
}

function get_num_jars($conn, $user_id) {
  $sql = "SELECT COUNT(*) FROM jars WHERE user_id=:user_id";
  $stmt = $conn->prepare($sql);
  $stmt->execute(['user_id' => $user_id]);
  $result = $stmt->fetchAll()[0]['COUNT(*)'];

  function render_jar($id, $user_id, $name, $date, $notes, $link, $progress) {
    $modal = "modal" . $id;
    $selected = ["", "", "", ""];
    $jar_progress = "jar-0";

    if($progress == 1)$selected[1] = "selected";
    else if($progress == 2) {
      $selected[2] = "selected";
      $jar_progress = "jar-50";
    }
    else if($progress == 3) {
      $selected[3] = "selected";
      $jar_progress = "jar-100";
    }
    else $selected[0] = "selected";

    $jar_color = "";
    $jar_due_date_status = "";

    $days_till_due = (strtotime($date) - time()) / (60*60*24);
    if($days_till_due < 3) {
      $jar_color = "jar-red";
      $jar_due_date_status = "ALMOST DUE";
    }
    if($days_till_due < 0) {
      $jar_color = "jar-black";
      $jar_due_date_status = "EXPIRED";
    }

    echo "
      <div class='modal fade' id='$modal' tabindex='-1' role='dialog' aria-hidden='true'>
        <div class='modal-dialog modal-dialog-centered' role='document'>
          <!-- Main Modal Content -->
          <div class='modal-content'>
            <!-- Body text -->
            <div class='modal-body'>
              <h1 class='text-center mb-4'>$name</h1>
              <form method='POST' class=''>
                <input type='hidden' name='id' value='$id' />
                <!-- Company Input -->
                <div class='input-group mb-4'>
                  <div class='input-group-prepend'>
                    <span class='input-group-text'>Company:</span>
                  </div>
                  <input type='text' id='company' name='company' class='form-control' value='$name' required>
                </div>
                <!-- Due Date Input -->
                <div class='input-group mb-4'>
                  <div class='input-group-prepend'>
                    <span class='input-group-text'>Due Date:</span>
                  </div>
                  <input type='date' id='date' name='date' class='form-control' value='$date' required>
                </div>
                <!-- Notes Input -->
                <div class='input-group mb-4'>
                  <div class='input-group-prepend'>
                    <span class='input-group-text pr-5'>Notes:</span>
                  </div>
                  <textarea id='notes' rows='5' name='notes' class='form-control'>$notes</textarea>
                </div>
                <!-- Application Link Input -->
                <div class='input-group mb-4'>
                  <div class='input-group-prepend'>
                    <span class='input-group-text'>Link:</span>
                  </div>
                  <input type='text' id='app_link' name='link' class='form-control' value='$link' required>
                </div>
                <!-- Progress Selection -->
                <div class='input-group mb-4'>
                  <div class='input-group-append'>
                    <label class='input-group-text' for='progress'>Progress:</label>
                  </div>
                  <select id='progress' name='progress'
                    class='form-control custom-select text-left border-secondary border border-1 rounded text-secondary'
                    required>
                    <option $selected[0] value='$progress'>Choose...</option>
                    <option $selected[1] value='1'>Not Started</option>
                    <option $selected[2] value='2'>In Progress</option>
                    <option $selected[3] value='3'>Completed</option>
                  </select>
                </div>
                <div class='modal-footer'>
                  <input type='submit' name='delete' value='Delete' class='btn btn-danger' />
                  <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                  <input type='submit' class='btn btn-primary' />
                </div>
              </form>
            </div>
            <!-- Buttons for closing -->
          </div>
        </div>
      </div>

      <div id='jar_$id' class='jar-50 m-3' data-toggle='modal' data-target='#$modal'>
      $name       
      <span id='progress_$progress' class='p_label'></span>
      <span id='text_$progress' class='t_label'>
      ";
    $value = (int)$progress;
    if ($value == 1) {
      echo "Not Started";
    }
    if ($value == 2) {
      echo "In Progress";
    }
    if ($value == 3) {
      echo "Completed";
    }


    echo "</span></div>";
  }
}