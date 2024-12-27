<?php

?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Closed MSRF List
            <small>Control Panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href=""><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Tickets</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <table id="closed_msrf" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Ticket ID</th>
                                    <th>Requestor Name</th>
                                    <th>Subject</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>ICT Approval Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">

$(document).ready(function() {
    let table = $('#closed_msrf').DataTable({
        "info": false,
        "serverSide": true,
        "processing": true,
        "searching": true,
        "ajax": {
            "url": "<?= base_url(); ?>DataTables/get_closed_msrf",
            "type": "POST",
        },
        "columns": [
            { "data": "ticket_id" },
            { "data": "requestor_name" },
            { "data": "subject" },
            { "data": "priority" },
            { "data": "status" },
            { "data": "it_approval_status" }
        ],
        "responsive": true,
        "autoWidth": false,
        "lengthChange": false,
    });
});

</script>