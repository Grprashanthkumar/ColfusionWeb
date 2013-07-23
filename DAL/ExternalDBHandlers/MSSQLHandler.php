<?php

require_once(realpath(dirname(__FILE__)) . "/DatabaseHandler.php");

class MSSQLHandler extends DatabaseHandler {

    public function __construct($user, $password, $database, $host, $port = 1433) {
        parent::__construct($user, $password, $database, $host, $port);
    }

    public function getConnection() {
        $pdo = new PDO("dblib:dbname=$this->database;host=$this->host:$this->port", $this->user, $this->password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec('SET QUOTED_IDENTIFIER ON');
        $pdo->exec('SET ANSI_WARNINGS ON');
        $pdo->exec('SET ANSI_PADDING ON');
        $pdo->exec('SET ANSI_NULLS ON');
        $pdo->exec('SET CONCAT_NULL_YIELDS_NULL ON');
        return $pdo;
    }

    public function importSqlFile($sid, $filePath) {
        ;
    }

    public function loadTables() {
        $pdo = $this->GetConnection();

        $stmt = $pdo->prepare("select table_schema, table_name from information_schema.tables");
        $stmt->execute();

        foreach ($stmt->fetchAll() as $row) {
            $tableNames[] = $row[0];
        }
        $stmt->closeCursor();

        return $tableNames;
    }

    public function getColumnsForSelectedTables($selectedTables) {
        $pdo = $this->GetConnection();

        foreach ($selectedTables as $selectedTable) {
            $stmt = $pdo->prepare("select COLUMN_NAME from INFORMATION_SCHEMA.COLUMNS where TABLE_NAME = ?");
            $stmt->execute(array($selectedTable));
            foreach ($stmt->fetchAll() as $row) {
                $columns[$selectedTable][] = $row[0];
            }
            $stmt->closeCursor();
        }

        return $columns;
    }

    public function getTableData($table_name, $perPage = 10, $pageNo = 1) {
        $pdo = $this->GetConnection();

        $sql = $this->wrapInLimit($pageNo, $perPage, $table_name);

        $res = $pdo->query($sql);
        foreach ($res->fetchAll() as $row) {
            $result[] = $row;
        }

        return $result;
    }

    private function wrapInLimit($startPoint, $perPage, $table) {
        $startPoint = $startPoint - 1;
        $top = $startPoint + $perPage;

        $query = <<<EOQ
SELECT * FROM
(
    SELECT TOP $top *, ROW_NUMBER() OVER (ORDER BY (SELECT 1)) AS rnum
    FROM $table
) a
WHERE rnum > $startPoint
EOQ;
        return $query;
    }

    public function getTotalNumberTuplesInTable($table_name) {
        $pdo = $this->GetConnection();


        $sql = "SELECT COUNT(*) as ct FROM $table_name ";
        $res = $pdo->query($sql);

        foreach ($res as $row) {
            if (isset($row))
                return $row["ct"];
            else
            //TODO: throw error here
                die('Table not found');
        }


     //   $stmt = $pdo->prepare("SELECT COUNT(*) as ct FROM [$table_name]");
     //   $stmt->execute();
     //   $row = $stmt->fetch(PDO::FETCH_BOTH);
     //   $tupleNum = (int) $row[0];
     //   $stmt->closeCursor();
     //   return $tupleNum;
    }
    
    // select - valid sql select part
    // from - tableName with alias if specified
    // where - valid SQL where part
    // group by - valid SQL group by
    // relationships - list of realtionship which should be used. If empty, all relationships between dataset will be used
    public function prepareAndRunQuery($select, $from, $where, $groupby, $perPage, $pageNo) {
    	$pdo = $this->GetConnection();
        
        $query = $select . " from " . $from . " ";
        
        if (isset($where))
            $query .= ' ' . $where . ' ';
         
        if (isset($groupby))
            $query .= ' '. $groupby . ' ';
        
        if (isset($perPage) && isset($pageNo)) {
             
            $startPoint = ($pageNo - 1) * $perPage;
            $query .= " LIMIT " . $startPoint . "," . $perPage;
        }
                        
        $res = $pdo->query($query);
        $result = array();
        while(($row = $res->fetch(PDO::FETCH_ASSOC))) {
            $result[] = $row;
        }
        
        return $result;
    }

// *******************************************************************
// ADD LINKED SERVER
// *******************************************************************

    public function AddLinkedServer($engine, $server, $port, $database, $user, $password){
        switch (strtolower($engine)) {
            case 'mysql': {
                $srvproduct = "MySQL";
                $provider = "MSDASQL";
                $provstr = "DRIVER={MySQL ODBC 5.2 Unicode Driver}; SERVER=$server;PORT=$port;DATABASE=$database;USER=$user;PASSWORD=$password;OPTION=3;";
                
                break;
            }
            case 'mssql':
            case 'sql server':
                
                break;
            case 'postgresql':
                
                break;
            case 'oracle':
                
                break;
            default:
                throw new Exception('Invalid DBMS in AddLinkedServer');
                break;
        }


        $pdo = $this->GetConnection();

echo 'connected\n';

        $query = "exec sp_addlinkedserver
@server = N'$database',
@srvproduct = N'$srvproduct',
@provider = N'$provider',
@provstr =N'$provstr';"; 


echo $query;

        if ($pdo->exec($query) !== false) {

        $query = "exec sp_addlinkedsrvlogin
@rmtsrvname = N'$database',
@locallogin = N'remoteUserTest',
@rmtuser = N'$user',
@rmtpassword = N'$password';";

            if ($pdo->exec($query)  === false) {
                throw new Exception($pdo->errorInfo());
            }

        }
        else {
            throw new Exception($pdo->errorInfo());
        }


        

    }

}

?>
