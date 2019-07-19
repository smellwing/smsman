<?php
//header('Content-type: application/vnd.ms-excel');

header('Content-type: application/csv');
header("Content-Disposition: attachment; filename=reporte_resultados.csv");
header("Pragma: no-cache");
header("Expires: 0");

?>
<?= $this->fetch('content') ?>
