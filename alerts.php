<?php include("header.php"); ?>

<?php 

$sql = mysqli_query($mysqli,"SELECT * FROM alerts WHERE alert_ack_date IS NULL AND company_id = $session_company_id ORDER BY alert_id DESC"); 

?>

<div class="card card-dark">
  <div class="card-header py-2">
    <h3 class="card-title mt-2"><i class="fa fa-fw fa-exclamation-triangle"></i> Alerts</h3>
    <div class="card-tools">
      <a href="post.php?ack_all_alerts" class="btn btn-primary"> <i class="fa fa-check"></i> Acknowledge All</a>
      <a href="alerts_archived.php" class="btn btn-secondary">Archived</a>
    </div>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-striped table-borderless table-hover">
        <thead>
          <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Alert</th>
            <th class="text-center">Ack</th>
          </tr>
        </thead>
        <tbody>
          <?php
      
          while($row = mysqli_fetch_array($sql)){
            $alert_id = $row['alert_id'];
            $alert_type = $row['alert_type'];
            $alert_message = $row['alert_message'];
            $alert_date = $row['alert_date'];

          ?>
          <tr class="row-danger">
            <td><?php echo $alert_date; ?></td>
            <td><?php echo $alert_type; ?></td>
            <td><?php echo $alert_message; ?></td>
            <td class="text-center"><a class="btn btn-success btn-sm" href="post.php?alert_ack=<?php echo $alert_id; ?>"><i class="fa fa-check"></a></td>
          </tr>

          <?php
          }
          ?>

        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include("footer.php");