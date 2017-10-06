<?php
/**
 * Created by PhpStorm.
 * User: arp
 * Date: 04.10.2017
 * Time: 10:43
 */

require_once '../vendor/autoload.php';

class Page
{
    private $ha;
    private $boxIp;
    private $username;
    private $password;

    public function __construct()
    {
        $this->boxIp = "192.168.178.1";
        $this->username = "";
        $this->password = "avm";
        $this->ha = new \fb_http\HA\HomeAutomation($this->password, $this->boxIp, $this->username);
    }

    public function getList()
    {
        return $this->ha->getDeviceListInfos();
    }

    public function toggleSwitch($ain)
    {
        return $this->ha->setSwitchToggle($ain);
    }
}

$page = new Page();

if (!empty($_GET['ain'])) {
    $page->toggleSwitch($_GET['ain']);
    sleep(1);
}

$deviceList = $page->getList();

?>
<!doctype html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    <title>fb_http HomeAutomation Example</title>
</head>
<body>
<h1>Übersicht Aktoren</h1>
<table class="table table-sm table-hover table-striped">
    <thead>
        <tr>
            <th>Gerätetyp</th>
            <th>Name</th>
            <th>AIN</th>
            <th>Status Steckdose</th>
            <th>aktuelle Temperatur</th>
            <th>Soll Temperatur</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($deviceList as $device): ?>
        <tr>
            <td><span class="badge badge-info"><?= $device->deviceType ; ?></span></td>
            <td><?= $device->name ; ?></td>
            <td><?= $device->ain; ?></td>
            <td>
            <?php if ($device->funcs['switch']): ?>
                <a href="index.php?ain=<?= $device->ain ?>" title="Status umschalten"><?= $device->switch->state ? 'an' : 'aus'; ?></a>
            <?php else: ?>
                -/-
            <?php endif; ?>
            </td>

            <td>
            <?php if ($device->funcs['temperaturesensor']): ?>
                <?= $device->temperature->celsius; ?>°C
            <?php else: ?>
                k.A.
            <?php endif; ?>
            </td>

            <td>
                <?php if ($device->funcs['thermostat']): ?>
                    <?= $device->hkr->tSoll; ?>°C
                <?php else: ?>
                    -/-
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<a href="index.php" class="btn btn-primary">Refresh</a>
<hr />
<div id="accordion" role="tablist" style="margin-bottom: 3rem;">
<?php foreach ($deviceList as $k => $device): ?>
    <div class="card">
        <div class="card-header" role="tab" id="tabHead<?= $k; ?>">
            <h5 class="mb-0">
                <a data-toggle="collapse" href="#collapse<?= $k ?>" aria-expanded="false" aria-controls="collapse<?= $k ?>">
                    <?= $device->name ?>
                </a>
            </h5>
        </div>
        <div id="collapse<?= $k ?>" class="collapse" role="tabpanel" aria-labelledby="tabHead<?= $k; ?>" data-parent="#accordion">
            <div class="card-body">
                <pre>
                    <?= json_encode($device, JSON_PRETTY_PRINT); ?>
                </pre>
            </div>
        </div>

    </div>
<?php endforeach; ?>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"
        integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"
        integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1"
        crossorigin="anonymous"></script>
</body>
</html>
