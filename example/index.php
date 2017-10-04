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
            <th>Name</th>
            <th>AIN</th>
            <th>Status Steckdose</th>
            <th>akutelle Temperatur</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($deviceList as $device): ?>
        <tr>
            <td><?= $device->getName(); ?></td>
            <td><?= $device->getAin(); ?></td>
            <td><a href="index.php?ain=<?= $device->getAin() ?>" title="Status umschalten"><?= $device->isSwitchState() ? 'an' : 'aus'; ?></a></td>
            <td><?= $device->getTemperature(); ?>°C</td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>
