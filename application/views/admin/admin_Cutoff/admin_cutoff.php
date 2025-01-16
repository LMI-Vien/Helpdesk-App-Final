<style>

    .col-xs-6 {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 100%;
        margin-left: auto;
        margin-right: auto;
        /* margin-top: 10px; */
    }

    .box {
        width: 440px;
        padding: 20px;
    }

    form {
        margin-bottom: 20px;
    }

    #button-bypass {
        width: 100%;
    }

    #button-set-cutoff {
        width: 100%;
    }

    #schedule-table {
        width: 550px;
    }

    tr:hover {
        background-color: #edf1f5;
    }

</style>


<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Cutoff
            <small>Control Panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href=""><i class="fa fa-dashboard"></i> Home </a></li>
            <li class="active">Cutoff</li>
        </ol>
    </section>
    <section class="content">
        <!-- <?= $date; ?> -->
        <div class="row">
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-xs-6">
                        <div class="box">
                            <div class="box-body">
                                <form method="POST" action="<?= base_url() ?>sys/admin/set_cutoff">
                                    <div class="row">
                                        <div class="col-xs-8">
                                            Open Time:
                                            <input type="time" name="open_time" class="form-control" id="cutoff" value="<?= $bypass->open_time; ?>" required />
                                        </div>
                                    </div>
                                    <br>
                                    Cutoff Time:
                                    <div class="row">
                                        <div class="col-xs-8">
                                            <input type="time" name="cutoff_time" class="form-control" id="open_ticket" value="<?= $bypass->cutoff_time; ?>" required />
                                        </div>
                                        <div class="col-xs-4">
                                            <button id="button-set-cutoff" type="submit" class="btn btn-danger" href="<?= base_url() ?>sys/admin/bypass">Set Cutoff</button>
                                        </div>
                                    </div>
                                </form>
                                <a id="button-bypass" class="<?= $bypass->bypass == 0 ? 'btn btn-danger' : 'btn btn-warning'; ?>" href="<?= base_url() ?>sys/admin/bypass"><?= $bypass->bypass == 0 ? 'Bypass Cutoff Time' : 'Close Bypass'; ?></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="box">
                            <div class="box-body">
                                <h3>Schedule Cutoff</h3>
                                <form method="POST" action="<?= base_url('sys/admin/schedule_cutoff'); ?>">
                                    Open Time:
                                    <input type="time" class="form-control" name="open_time" required />
                                    <br>
                                    Cutoff Time:
                                    <input type="time" class="form-control" name="cutoff_time" required />
                                    <br>
                                    Date:
                                    <input type="date" class="form-control" name="date" required />
                                    <br>
                                    End Date:
                                    <input type="date" class="form-control" name="end_date" />
                                    <br>
                                    <button class="btn btn-danger">Set Cutoff Time</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="box" id="schedule-table">
                    <div class="box-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Date</th>
                                    <th scope="col">End Date</th>
                                    <th scope="col">Enable Time</th>
                                    <th scope="col">Cutoff Time</th>
                                    <th scope="col" colspan=2>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($schedule as $row): ?>
                                    <?php if($row['date'] == NULL): ?>
                                        <tr>
                                            <th>Standard</th>
                                            <th><?= $row['end_date'] != "0000-00-00" ? $row['end_date'] : "-" ; ?></th>
                                            <th><?= $row['open_time']; ?></th>
                                            <th><?= $row['cutoff_time']; ?></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    <?php else: ?>
                                        <tr>
                                            <td><?= $row['date']; ?></td>
                                            <td><?= $row['end_date'] != "0000-00-00" ? $row['end_date'] : "-" ; ?></td>
                                            <td><?= date_format(date_create($row['open_time']), 'h:i A'); ?></td>
                                            <td><?= date_format(date_create($row['cutoff_time']), 'h:i A'); ?></td>
                                            <td>
                                                <button class="btn btn-alert" data-toggle="modal" data-target="#editModal<?= $row['recid']; ?>">Edit</button>
                                                <a class="btn btn-danger" onclick="return confirm('Are you sure to delete the schedule?')" href="<?= base_url('sys/admin/delete_schedule_cutoff/' . $row['recid']); ?>">Delete</a>
                                            </td>
                                        </tr>
                                        
                                        <div class="modal fade" id="editModal<?= $row['recid']; ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 class="modal-title">Edit schedule</h3>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST" action="<?= base_url('sys/admin/edit_schedule_cutoff/' . $row['recid']); ?>">
                                                            <input type="hidden" name="recid" value="<?= $row['recid']; ?>" />
                                                            Open Time:
                                                            <input type="time" class="form-control" name="new_open_time" value="<?= $row['open_time']; ?>" required />
                                                            <br>
                                                            Cutoff Time:
                                                            <input type="time" class="form-control" name="new_cutoff_time" value="<?= $row['cutoff_time']; ?>" required />
                                                            <br>
                                                            Date:
                                                            <input type="date" class="form-control" name="new_date" value="<?= $row['date']; ?>" required />
                                                            <br>
                                                            End Date:
                                                            <input type="date" class="form-control" name="new_end_date" value="<?= $row['end_date'] != '0000-00-00' ? $row['end_date'] : ''; ?>" />
                                                            <br>
                                                            <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button class="btn btn-danger">Save changes</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                <?php endforeach; ?>
                            </tbody>
                       </table>
                    </div>
                </div>
            </div>
        </div>
            
    </section>
</div>

<style>
    .swal-wide {
        width: 400px !important;
        font-size: 1.4rem; 
    }
</style>

<script>
    $(document).ready(function () {
        $('#cutoffForm').on('submit', function (e) {
            e.preventDefault();
            
            $.post($(this).attr('action'), $(this).serialize(), function (response) {
                Swal.fire({
                    title: 'Success!',
                    text: 'Cutoff time has been set successfully!',
                    timer: 2000, 
                    icon: 'success',
                    confirmButtonText: 'OK',
                    customClass: {
                        popup: 'swal-wide' 
                    },
                }).then(() => {
                    // Optional: Redirect after showing notification
                    window.location.href = "<?= base_url() ?>sys/admin/cutoff";
                });
            }).fail(function () {
                Swal.fire({
                    title: 'Error!',
                    text: 'Something went wrong. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    customClass: {
                        popup: 'swal-wide' 
                    },
                });
            });
        });

        $('#button-bypass').on('click', function (e) {
            e.preventDefault(); // Prevent default link behavior

            // Get the link URL
            const href = $(this).attr('href');

            Swal.fire({
                title: 'Are you sure?',
                text: "Reopen ticket form creation?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#228B22',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, proceed!',
                cancelButtonText: 'Cancel',
                customClass: {
                    popup: 'swal-wide' 
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        });
    });
</script>