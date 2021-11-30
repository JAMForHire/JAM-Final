<?php 
  function render_jar($id, $name, $date, $notes, $link, $progress) {
    $modal = "modal" . $id;
    echo "
      <div class='modal fade' id='$modal' tabindex='-1' role='dialog' aria-hidden='true'>
        <div class='modal-dialog modal-dialog-centered' role='document'>
          <!-- Main Modal Content -->
          <div class='modal-content'>
            <div class='modal-header border-0'>
              <!-- Heading -->
              <h5 class='modal-title' id='modalTitle'>JAM Jar</h5>
            </div>
            <!-- Body text -->
            <div class='modal-body'>
              <h1 class='text-center mb-4'>$name</h1>
              <form class=''>
                <!-- Company Input -->
                <div class='input-group mb-4'>
                  <div class='input-group-prepend'>
                    <span class='input-group-text'>Company:</span>
                  </div>
                  <input type='text' id='company' class='form-control' placeholder='$name' required>
                </div>
                <!-- Due Date Input -->
                <div class='input-group mb-4'>
                  <div class='input-group-prepend'>
                    <span class='input-group-text'>Due Date:</span>
                  </div>
                  <input type='text' id='date' class='form-control' placeholder='$date' required>
                </div>
                <!-- Notes Input -->
                <div class='input-group mb-4'>
                  <div class='input-group-prepend'>
                    <span class='input-group-text pr-5'>Notes:</span>
                  </div>
                  <textarea id='notes' rows='5' class='form-control' placeholder='$notes'></textarea>
                </div>
                <!-- Application Link Input -->
                <div class='input-group mb-4'>
                  <div class='input-group-prepend'>
                    <span class='input-group-text'>Link:</span>
                  </div>
                  <input type='text' id='app_link' class='form-control' placeholder='$link' required>
                </div>
                <!-- Progress Selection -->
                <div class='input-group mb-4'>
                  <div class='input-group-append'>
                    <label class='input-group-text' for='progress'>Progress:</label>
                  </div>
                  <select id='progress'
                    class='form-control custom-select text-left border-secondary border border-1 rounded text-secondary'
                    required>
                    <option selected value='$progress'>Choose...</option>
                    <option value='1'>Not Started</option>
                    <option value='2'>In Progress</option>
                    <option value='3'>Completed</option>
                  </select>
                </div>
              </form>
            </div>
            <!-- Buttons for closing -->
            <div class='modal-footer'>
              <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
              <button type='button' class='btn btn-primary' data-dismiss='modal'>Confirm</button>
            </div>
          </div>
        </div>
      </div>

      <div id='jar_$id' class='jar-50' data-toggle='modal' data-target='#$modal'>
        Company $id
      </div>
    ";
  }
?>