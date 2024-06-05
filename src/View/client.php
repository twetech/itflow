<!-- src/view/client.php -->
<?php
// src/View/client.php

$datatable_settings = "";

?>


<div class="row">

<!-- Notes -->

<div class="col-md-12">

    <div class="card mb-3 elevation-3 card-action">
        <div class="card-header">
            <h5 class="card-action-title"><i class="fa fa-fw fa-edit mr-2"></i>Quick Notes</h5>
            <div class="card-action-element">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item">
                        <a href="javascript:void(0);" class="card-collapsible"><i class="tf-icons bx bx-chevron-up"></i></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="collapse">
            <div class="card-body p-1">
                <textarea class="form-control" rows=8 id="clientNotes" placeholder="Enter quick notes here" onblur="updateClientNotes(<?= $client_id ?>)"><?= $client_notes ?></textarea>
            </div>
        </div>
    </div>

</div>


<div class="col-md-4">

    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title"><i class="fa fa-fw fa-users mr-2"></i>Important Contacts</h5>
        </div>
        <div class="card-body p-2">
            <table class="responsive table table-borderless table-sm">
                <?php
                ?>
            </table>
        </div>
    </div>
</div>

<div class="col-md-4">

    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title"><i class="fa fa-fw fa-exclamation-triangle text-warning mr-2"></i>Upcoming Expirations <small>(Within 90 Days)</small></h5></h5>
        </div>
        <div class="card-body p-2">

        </div>
    </div>
</div>

<!-- Stale Tickets -->

<div class="col-md-4">

    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title"><i class="fa fa-fw fa-life-ring mr-2"></i>Stale Tickets <small>(Not updated within 3 days)</small></h5>
        </div>
        <div class="card-body p-2">

            <table class="responsive table table-borderless table-sm">
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Recent Activities -->

<div class="col-md-12">

    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title"><i class="fa fa-fw fa-history mr-2"></i>Recent Activities <small>(Last 10 tasks)</small></h5>
        </div>
        <div class="card-body p-2">

            <table class="responsive table table-borderless table-sm">
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

</div>

<script>
function updateClientNotes(client_id) {
    var notes = document.getElementById("clientNotes").value;

    // Send a POST request to ajax.php as ajax.php with data client_set_notes=true, client_id=NUM, notes=NOTES
    jQuery.post(
        "/ajax/ajax.php",
        {
            client_set_notes: 'TRUE',
            client_id: client_id,
            notes: notes
        }
    )


}
</script>