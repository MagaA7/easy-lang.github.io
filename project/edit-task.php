<?php

require 'authentication.php'; // admin authentication check 

// auth check
$user_id = $_SESSION['admin_id'];
$user_name = $_SESSION['name'];
$security_key = $_SESSION['security_key'];
if ($user_id == NULL || $security_key == NULL) {
    header('Location: index.php');
}

// check admin
$user_role = $_SESSION['user_role'];

$task_id = $_GET['task_id'];



if(isset($_POST['update_task_info'])){
    $obj_admin->update_task_info($_POST,$task_id, $user_role);
}

$page_name="Edit Task";
include("include/sidebar.php");

$sql = "SELECT * FROM task_info WHERE task_id='$task_id' ";
$info = $obj_admin->manage_all_info($sql);
$row = $info->fetch(PDO::FETCH_ASSOC);

?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


    <div class="row">
      <div class="col-md-12">
        <div class="well well-custom rounded-0">
          <div class="row">
            <div class="col-md-8 col-md-offset-2">
              <div class="well rounded-0">
                <h3 class="text-center bg-primary"  style="padding: 7px;">Project </h3><br>

                      <div class="row">
                        <div class="col-md-12">
                          <form class="form-horizontal" role="form" action="" method="post" autocomplete="off">
                            <div class="form-group">
			                    <label class="control-label text-p-reset">Name</label>
			                    <div class="">
			                      <input type="text" placeholder="Task Title" id="task_title" name="task_title" list="expense" class="form-control rounded-0" value="<?php echo $row['t_title']; ?>" <?php if($user_role != 1){ ?> readonly <?php } ?> val required>
			                    </div>
			                  </div>
			                  <div class="form-group">
			                    <label class="control-label text-p-reset">Text</label>
			                    <div class="">
			                      <textarea name="task_description" id="task_description" placeholder="Text" class="form-control rounded-0" rows="5" cols="5" <?php if($user_role==2){echo 'readonly';}?> ><?php echo $row['t_description']?>
                                 </textarea>
			                    </div>
                                  <label class="control-label text-p-reset">Translator's work</label>
                                  <div class="">
                                      <textarea name="translator_work_done" id = "translator_work_done" placeholder="Translator's work" class="form-control rounded-0" rows="5" cols="5"><?php echo $row['translator_work_done'] ?></textarea>
                                  </div>
			                  </div>
			                  <div class="form-group">
			                    <label class="control-label text-p-reset">Start Time</label>
			                    <div class="">
			                      <input type="text" name="t_start_time" id="t_start_time"  class="form-control rounded-0" value=" <?php echo $row['t_start_time']; ?>">
			                    </div>
			                  </div>
			                  <div class="form-group">
			                    <label class="control-label text-p-reset">End Time</label>
			                    <div class="">
			                      <input type="text" name="t_end_time" id="t_end_time" class="form-control rounded-0" value="<?php echo $row['t_end_time']; ?>">
			                    </div>
			                  </div>

			                  <div class="form-group">
			                    <label class="control-label text-p-reset">Translator</label>
			                    <div class="">
			                      <?php 
			                        $sql = "SELECT user_id, fullname FROM tbl_admin WHERE user_role = 2";
			                        $info = $obj_admin->manage_all_info($sql);   
			                      ?>
			                      <select class="form-control rounded-0" name="assign_to" id="aassign_to" <?php if($user_role != 1){ ?> disabled="true" <?php } ?>>
			                        <option value="">Select</option>

			                        <?php while($rows = $info->fetch(PDO::FETCH_ASSOC)){ ?>
			                        <option value="<?php echo $rows['user_id']; ?>" <?php
			                        	if($rows['user_id'] == $row['t_user_id']){
			                         ?> selected <?php } ?>><?php echo $rows['fullname']; ?></option>
			                        <?php } ?>
			                      </select>
			                    </div>
			                   
			                  </div>

			                   <div class="form-group">
			                    <label class="control-label text-p-reset">Status</label>
			                    <div class="">
			                      <select class="form-control rounded-0" name="status" id="status">
			                      	<option value="0" <?php if($row['status'] == 0){ ?>selected <?php } ?>>Incomplete</option>
			                      	<option value="1" <?php if($row['status'] == 1){ ?>selected <?php } ?>>In Progress</option>
			                      	<option value="2" <?php if($row['status'] == 2){ ?>selected <?php } ?>>Completed</option>
			                      </select>
			                    </div>
			                  </div>
                            
                            <div class="form-group">
                            </div>
                            <div class="form-group">
                              <div class="col-sm-offset-3 col-sm-3">
                                
                              </div>

                              <div class="col-sm-3">
                                <button type="submit" name="update_task_info" class="btn btn-primary-custom">Update Now</button>
                              </div>
                            </div>
                          </form> 
                        </div>
                      </div>

              </div>
            </div>
          </div>

        </div>
      </div>
    </div>


	<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script type="text/javascript">
  flatpickr('#t_start_time', {
    enableTime: true,
    onClose: function(selectedDates, dateStr, instance) {
      var endDatePicker = document.getElementById('t_end_time')._flatpickr;
      if (endDatePicker) {
        endDatePicker.set('minDate', dateStr);
        if (selectedDates[0] !== null && selectedDates[0] >= endDatePicker.selectedDates[0]) {
          endDatePicker.setDate(selectedDates[0].getTime() + 30 * 60 * 1000); // Set end time to be 30 minutes after start time
        }
      }
    }
  });

  flatpickr('#t_end_time', {
    enableTime: true,
    onClose: function(selectedDates, dateStr, instance) {
      var startDatePicker = document.getElementById('t_start_time')._flatpickr;
      if (startDatePicker) {
        startDatePicker.set('maxDate', dateStr);
        if (selectedDates[0] !== null && selectedDates[0] <= startDatePicker.selectedDates[0]) {
          startDatePicker.setDate(selectedDates[0].getTime() - 30 * 60 * 1000); // Set start time to be 30 minutes before end time
        }
      }
    }
  });
</script>



<?php

include("include/footer.php");

?>
