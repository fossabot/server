<?php
/**
 * @package plugins.multiCenters
 * @subpackage Scheduler.FileSyncImport.Debug
 */

chdir(dirname( __FILE__ ) . "/../../../../batch");

require_once(__DIR__ . "/../../../../batch/bootstrap.php");

$iniFile = "batch_config.ini";		// should be the full file path

$kdebuger = new KGenericDebuger($iniFile);
$kdebuger->run('KAsyncFileSyncImportCloser');