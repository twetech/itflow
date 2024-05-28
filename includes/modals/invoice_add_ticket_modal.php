<?php require_once "/var/www/portal.twe.tech/includes/inc_all_modal.php"; ?>

<div class="modal" id="addTicketModal">
<div class="modal-dialog">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-fw fa-file-invoice mr-2"></i>Add Unbilled Ticket to Invoice</h5>
                <button type="button" class="close text-white" data-bs-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body bg-white">
                        <div class="card-datatable table-responsive container-fluid  pt-0">               
                    <table id=responsive class="responsive table table-striped">
                        <thead>
                            <tr>
                                <th>Ticket Number</th>
                                <th>Scope</th>
                                <th>Add to Invoice</th>
                            </tr>
                        </thead>
                        <?php while ($row = mysqli_fetch_array($sql_tickets_billable)) { 
                            $ticket_id = intval($row['ticket_id']);
                            $ticket_subject = nullable_htmlentities($row['ticket_subject']);
                            $ticket_number = intval($row['ticket_number']);
                            $ticket_prefix = nullable_htmlentities($row['ticket_prefix']);
                            $ticket_status = nullable_htmlentities($row['ticket_status']);

                            switch ($ticket_status) {
                                case 5:
                                    $ticket_status_class = 'badge-dark';
                                    break;
                                case 4:
                                    $ticket_status_class = 'badge-warning';
                                    break;
                                default:
                                    $ticket_status_class = 'badge-secondary';
                                    break;
                            }


                            ?>
                            <tr>
                                <td>
                                    <a href="ticket.php?ticket_id=<?= $ticket_id; ?>">
                                        <span class="badge badge-pill <?= $ticket_status_class?> p-3"><?= "$ticket_prefix$ticket_number"; ?></span>
                                    </a>
                                </td>   
                                <td><?= $ticket_subject ?></td>
                                <td><a href='ticket.php?ticket_id=<?= $ticket_id?>&invoice_id=<?= $invoice_id?>#addInvoiceFromTicketModal'>
                                    <i class="fas fa-fw fa-plus-circle"></i></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

