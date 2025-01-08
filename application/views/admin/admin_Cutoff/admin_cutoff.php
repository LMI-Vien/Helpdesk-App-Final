<style>

    .col-xs-6 {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 100%;
        margin-left: auto;
        margin-right: auto;
        margin-top: 10px;
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
        <div class="row">
            <div class="col-xs-6">
                <div class="box">
                    <div class="box-body">
                        <form method="POST" action="<?= base_url() ?>sys/admin/set_cutoff">
                            Set Cutoff Time:
                            <div class="row">
                                <div class="col-xs-8">
                                    <input type="time" name="cutoff" class="form-control" id="cutoff" required />
                                </div>
                                <div class="col-xs-4">
                                    <button id="button-set-cutoff" type="submit" class="btn btn-danger" href="<?= base_url() ?>sys/admin/bypass">Set Cutoff</button>
                                </div>
                            </div>
                        </form>
                        Bypass set cutoff time
                        <a id="button-bypass" class="<?= $bypass == 0 ? 'btn btn-danger' : 'btn btn-warning'; ?>" onClick="return confirm('Reopen ticket form creation?')" href="<?= base_url() ?>sys/admin/bypass"><?= $bypass == 0 ? 'Bypass Cutoff Time' : 'Close Bypass'; ?></a>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>