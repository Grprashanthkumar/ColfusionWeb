<?php

include_once(realpath(dirname(__FILE__)) . '/GlobalStatEngine.php');

header('Content-type: text/html; charset=utf-8');
if(isset($_GET["action"])){
    $action = $_GET["action"];
    $action();
};

// Expects sid in post
function GetGlobalStatisticsSummary() {
    $globalStatEngine = new GlobalStatEngine();

   
    // returned value from Engine, saved into variables in result
    $results = $globalStatEngine->GetGlobalStatisticsSummary();
  
    echo json_encode($results);
}
?>