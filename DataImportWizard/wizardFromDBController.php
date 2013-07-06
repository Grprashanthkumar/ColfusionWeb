<?php

include_once('../config.php');
include_once('../DAL/QueryEngine.php');
include_once('../DAL/ExternalDBHandlers/ExternalDBs.php');
include_once('UtilsForWizard.php');
require_once(realpath(dirname(__FILE__)) . "/../DAL/ExternalDBHandlers/DatabaseHandler.php");
require_once(realpath(dirname(__FILE__)) . "/../DAL/ExternalDBHandlers/DatabaseHandlerFactory.php");

$sid = $_POST['sid'];
if (!isset($sid)) {
    die('Source not specified.');
}

if (isset($_SESSION["dbHandler_$sid"])) {
    $dbHandler = unserialize($_SESSION["dbHandler_$sid"]);
}

$action = $_GET["action"];
$action($sid, $dbHandler);
exit;

function TestConnection($sid) {
    // get submitted form information from dashboard.php
    $serverName = $_POST['serverName'];
    $userName = $_POST['userName'];
    $password = $_POST['password']; // controls how many tuples shown on each page
    $port = $_POST['port'];
    $driver = $_POST['driver'];
    $database = $_POST['database'];

    $serverName = $serverName ? $serverName : 'a fail host';
    $json = new stdClass();

    try {
        $dbHandler = DatabaseHandlerFactory::createDatabaseHandler($driver, $userName, $password, $database, $serverName, $port);
        $dbHandler->getConnection();
        $_SESSION["dbHandler_$sid"] = serialize($dbHandler);

        $json->isSuccessful = true;
        $json->message = "Connected successfully";
        echo json_encode($json);
    } catch (Exception $e) {
        $json->isSuccessful = false;
        $json->message = $e->getMessage();
        echo json_encode($json);
    }
}

function LoadDatabaseTables($sid, DatabaseHandler $dbHandler) {
    try {
        $tables = $dbHandler->loadTables();
        $json["isSuccessful"] = true;
        $json["data"] = $tables;
    } catch (Exception $e) {
        $json["isSuccessful"] = false;
    }
    echo json_encode($json);
}

function PrintTableForSchemaMatchingStep($sid, DatabaseHandler $dbHandler) {

    $selectedTables = $_POST["selectedTables"];
    try {
        $tablesColumns = $dbHandler->getColumnsForSelectedTables($selectedTables);
        $_SESSION["baseHeader_$sid"] = $tablesColumns;

        echo UtilsForWizard::PrintTableForSchemaMatchingStep($tablesColumns);
    } catch (Exception $e) {
        echo "<p style='color:red;'>Errors occur when matching schemas.</p>";
    }
}

function PrintTableForDataMatchingStep() {
    $spd = $_POST["schemaMatchingUserInputs"]["spd"];
    $drd = $_POST["schemaMatchingUserInputs"]["drd"];
    $start = $_POST["schemaMatchingUserInputs"]["start"];
    $end = $_POST["schemaMatchingUserInputs"]["end"];
    $location = $_POST["schemaMatchingUserInputs"]["location"];
    $aggrtype = $_POST["schemaMatchingUserInputs"]["aggrtype"];

    $baseHeader = $_SESSION["baseHeader_$sid"];

    if ($spd != "" && $spd != "other") {
        $tableName = UtilsForWizard::getWordUntilFirstDot($spd);
        $spd = UtilsForWizard::stripWordUntilFirstDot($spd);
        $baseHeader[$tableName] = array_diff($baseHeader[$tableName], array($spd));
    }

    if ($drd != "" && $drd != "other") {
        $tableName = UtilsForWizard::getWordUntilFirstDot($drd);
        $drd = UtilsForWizard::stripWordUntilFirstDot($drd);
        $baseHeader[$tableName] = array_diff($baseHeader[$tableName], array($drd));
    }

    if ($start != "" && $start != "other") {
        $tableName = UtilsForWizard::getWordUntilFirstDot($start);
        $start = UtilsForWizard::stripWordUntilFirstDot($start);
        $baseHeader[$tableName] = array_diff($baseHeader[$tableName], array($start));
    }

    if ($end != "" && $end != "other") {
        $tableName = UtilsForWizard::getWordUntilFirstDot($end);
        $end = UtilsForWizard::stripWordUntilFirstDot($end);
        $baseHeader[$tableName] = array_diff($baseHeader[$tableName], array($end));
    }

    if ($location != "" && $location != "other") {
        $tableName = UtilsForWizard::getWordUntilFirstDot($location);
        $location = UtilsForWizard::stripWordUntilFirstDot($location);
        $baseHeader[$tableName] = array_diff($baseHeader[$tableName], array($location));
    }

    if ($aggrtype != "" && $aggrtype != "other") {
        $tableName = UtilsForWizard::getWordUntilFirstDot($aggrtype);
        $aggrtype = UtilsForWizard::stripWordUntilFirstDot($aggrtype);
        $baseHeader[$tableName] = array_diff($baseHeader[$tableName], array($aggrtype));
    }

    $_SESSION['$normalizer_header'] = $baseHeader;

    echo UtilsForWizard::PrintTableForDataMatchingStep($baseHeader);
}

//TODO: the logic should be moved to some other classes
function Execute() {
    global $db;

    $inputData = $_POST;

    $sid = getSid();
    $queryEngine = new QueryEngine();

    $queryEngine->simpleQuery->addSourceDBInfo($sid, $inputData["server"], $inputData["port"], $inputData["user"], $inputData["password"], $inputData["database"], $inputData["driver"]);

    UtilsForWizard::processSchemaMatchingUserInputsStoreDB($sid, $inputData["schemaMatchingUserInputs"]);
    UtilsForWizard::processDataMatchingUserInputsStoreDB($sid, $inputData["dataMatchingUserInputs"]);

    $queryEngine->simpleQuery->setSourceTypeBySid($sid, 'database');

    $resultJson = new stdClass();
    $resultJson->isSuccessful = true;
    $resultJson->message = 'Success!';
    echo json_encode($resultJson);
}

/* * ************************************************************************************************** */

function getSid() {
    // determine which step of the submit process we are on
    if (isset($_POST["sid"]))
        $sid = $_POST["sid"];
    else if (isset($_GET["sid"]))
        $sid = $_GET["sid"];
    else {
        echo json_encode("no sid");
        die();
    }

    return $sid;
}

?>