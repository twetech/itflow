<div class="modal" id="editEventModal<?php echo $event_id; ?>" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content bg-dark">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa fa-fw fa-calendar"></i> <?php echo $event_title; ?></h5>
        <button type="button" class="close text-white" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      
      <form action="post.php" method="post" autocomplete="off">
        <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
        
        <div class="modal-body bg-white">

          <div class="form-group">
            <label>Title <strong class="text-danger">*</strong></label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-angle-right"></i></span>
              </div>
              <input type="text" class="form-control" name="title" value="<?php echo $event_title; ?>" placeholder="Title of the event" required>
            </div>
          </div>

          <div class="form-group">
            <label>Description</label>
            <textarea class="form-control" rows="3" name="description" placeholder="Enter a description"><?php echo $event_description; ?></textarea>
          </div>
          
          <div class="form-group">
            <label>Calendar <strong class="text-danger">*</strong></label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
              </div>
              <select class="form-control select2" name="calendar" required>
                <?php 
                
                $sql_calendars_select = mysqli_query($mysqli,"SELECT * FROM calendars WHERE company_id = $session_company_id ORDER BY calendar_name ASC"); 
                while($row = mysqli_fetch_array($sql_calendars_select)){
                  $calendar_id_select = $row['calendar_id'];
                  $calendar_name_select = $row['calendar_name'];
                  $calendar_color_select = $row['calendar_color'];
                ?>
                  <option data-content="<i class='fa fa-circle mr-2' style='color:<?php echo $calendar_color_select; ?>;'></i> <?php echo $calendar_name_select; ?>"<?php if($calendar_id == $calendar_id_select){ echo "selected"; } ?> value="<?php echo $calendar_id_select; ?>"><?php echo $calendar_name_select; ?></option>
                
                <?php
                }
                ?>
              </select>
            </div>
          </div>
          
          <div class="form-group">
            <label>Start <strong class="text-danger">*</strong></label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-clock"></i></span>
              </div>
              <input type="datetime-local" class="form-control" name="start" value="<?php echo date('Y-m-d\TH:i:s', strtotime($event_start)); ?>" required>
            </div>
          </div>
          
          <div class="form-group">
            <label>End <strong class="text-danger">*</strong></label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-stopwatch"></i></span>
              </div>
              <input type="datetime-local" class="form-control" name="end" value="<?php echo date('Y-m-d\TH:i:s', strtotime($event_end)); ?>" required>
            </div>
          </div>
          
          <div class="form-group">
            <label>Repeat</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-recycle"></i></span>
              </div>
              <select class="form-control select2" name="repeat">
                <option <?php if(empty($event_repeat)){ echo "selected"; } ?> value="">Never</option>
                <option <?php if($event_repeat == "Day"){ echo "selected"; } ?>>Day</option>
                <option <?php if($event_repeat == "Week"){ echo "selected"; } ?>>Week</option>
                <option <?php if($event_repeat == "Month"){ echo "selected"; } ?>>Month</option>
                <option <?php if($event_repeat == "Year"){ echo "selected"; } ?>>Year</option>
              </select>
            </div>
          </div>

          <?php if(isset($client_id)){ ?>

          <input type="hidden" name="client" value="<?php echo $client_id; ?>">

          <?php }else{ ?>

          <div class="form-group">
            <label>Client</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-user"></i></span>
              </div>
              <select class="form-control select2" name="client">
                <option value="">- Client -</option>
                <?php 
                
                $sql_clients = mysqli_query($mysqli,"SELECT * FROM clients LEFT JOIN contacts ON primary_contact = contact_id WHERE clients.company_id = $session_company_id ORDER BY client_name ASC"); 
                while($row = mysqli_fetch_array($sql_clients)){
                  $client_id_select = $row['client_id'];
                  $client_name_select = $row['client_name'];
                  $contact_email_select = $row['contact_email'];
                ?>
                  <option <?php if($client_id == $client_id_select){ echo "selected"; } ?> value="<?php echo $client_id_select; ?>"><?php echo $client_name_select; ?></option>
                
                <?php
                }
                ?>
              </select>
            </div>
          </div>

          <?php } ?>

          <?php if(!empty($config_smtp_host)){ ?>
          <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="customControlAutosizing<?php echo $event_id; ?>" name="email_event" value="1" >
            <label class="custom-control-label" for="customControlAutosizing<?php echo $event_id; ?>">Email Event</label>
          </div>
          <?php } ?>

        </div>
        <div class="modal-footer bg-white">
          <a href="post.php?delete_event=<?php echo $event_id; ?>" class="btn btn-danger mr-auto"><i class="fa fa-trash text-white"></i></a>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" name="edit_event" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
