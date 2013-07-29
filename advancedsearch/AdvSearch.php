<?php

include_once("../DataImportWizard/UtilsForWizard.php");
include_once("../DAL/ExternalDBHandlers/ExternalMSSQL.php");

require(realpath(dirname(__FILE__)) . "/../vendor/autoload.php");

class AdvSearch {
	
	private $searchKeyWords;
	private $joinKeywords;
	private $whereVariable;
	private $whereRule;
	private $whereCondition;
	
	public function setSearchKeywords($searchKeyWords) {
		$this->searchKeyWords = $searchKeyWords;
	}
	
	public function getSearchKeywords() {
		return $this->searchKeyWords;
	}
	
	public function setJoinKeywords($joinKeywords) {
		$this->joinKeywords = $joinKeywords;
	}
	
	public function getJoinKeywords() {
		return $this->joinKeywords;
	}
	
	public function setWhereVariable($whereVariable) {
		$this->whereVariable = $whereVariable;
	}
	
	public function getWhereVariable() {
		return $this->whereVariable;
	}
	
	public function setWhereRule($whereRule) {
		$this->whereRule = $whereRule;
	}
	
	public function getWhereRule() {
		return $this->whereRule;
	}
	
	public function setWhereCondition($whereCondition) {
		$this->whereCondition = $whereCondition;
	}
	
	public function getWhereCondition() {
		return $this->whereCondition;
	}
	

	public function doSearch() {
		global $db;
		
		if (!isset($this->searchKeyWords))
			return "";
		
		$chunks = array_map('trim', $this->searchKeyWords);
		
		$inCrit = implode("','", $chunks);


/*
		$sql = "
select distinct CONCAT(cd.sid, '.' , tableName) as sid, sidTo 
from colfusion_dnameinfo as cd LEFT JOIN
	(
	select sidFrom,  GROUP_CONCAT(distinct TRIM(sidTo)) as sidTo
	from (
			select sidTableFrom as sidFrom, sidTable2 as sidTo
			from (
					select distinct CONCAT(sid, '.' , tableName) as sidTableFrom
					from colfusion_dnameinfo di join
					colfusion_columnTableInfo cti on di.cid = cti.cid
					where dname_chosen in ('$inCrit') or dname_original_name in ('$inCrit')
				) as f, 
				(
					select CONCAT(sid1, '.' , tableName1) as sidTable1, CONCAT(sid2, '.' , tableName2)  as sidTable2
					from colfusion_relationships
				) as s
			where f.sidTableFrom = s.sidTable1 and s.sidTable2 in (select distinct CONCAT(sid, '.' , tableName) as sidTableFrom
					from colfusion_dnameinfo di join
					colfusion_columnTableInfo cti on di.cid = cti.cid
					where dname_chosen in ('$inCrit') or dname_original_name in ('$inCrit'))

		union

			select sidTableFrom as sidFrom, sidTable1 as sidTo
			from (
					select distinct CONCAT(sid, '.' , tableName) as sidTableFrom
					from colfusion_dnameinfo di join
					colfusion_columnTableInfo cti on di.cid = cti.cid
					where dname_chosen in ('$inCrit') or dname_original_name in ('$inCrit')
				) as f, 
				(
					select CONCAT(sid1, '.' , tableName1) as sidTable1, CONCAT(sid2, '.' , tableName2)  as sidTable2
					from colfusion_relationships
				) as s
			where f.sidTableFrom = s.sidTable2 and s.sidTable1 in (select distinct CONCAT(sid, '.' , tableName) as sidTableFrom
					from colfusion_dnameinfo di join
					colfusion_columnTableInfo cti on di.cid = cti.cid
					where dname_chosen in ('$inCrit') or dname_original_name in ('$inCrit'))
	) as u
	group by sidFrom
) tj on cd.sid = tj.sidFrom,
colfusion_columnTableInfo cti, colfusion_sourceinfo si
where (dname_chosen in ('$inCrit') or dname_original_name in ('$inCrit'))
and cd.cid = cti.cid
		and cd.sid = si.sid and si.Status = 'queued'";
		
	//	echo $sql;
		
		$rst = $db->get_results($sql);
		
		$res = $this->constructResultArray($rst, $chunks);
		
		
		
		return $res;
	}
	
	private function constructResultArray($sidDnames, $searchKeyWords) {
		
		global $db;
		
		$result = array();
		
		if (empty($sidDnames))
			return $result;
		
		do {
			$toJoin = array(); //search result entry
			
			array_push($toJoin, array_pop($sidDnames)); //take first from the sql query restult and remove it from the array
			
			if (is_null($toJoin[0]->sidTo)) {
				
				$sids = array($toJoin[0]->sid);
				
				$allColumns = $this->getAllColumnsFromJoin($sids);
				
				$schemaAndWhereKeyWordsIntersect = array_intersect($allColumns, $this->whereVariable);
				
				if (isset($this->whereVariable) || count($schemaAndWhereKeyWordsIntersect) == count($this->whereVariable)) {
				
					$oneResult = $this->constractOneSearchResult($sids, $searchKeyWords, $allColumns);
								
					array_push($result, $oneResult);
				}
				
				continue;
			}
			
			$resultOneRecord = array_merge(array($toJoin[0]->sid), spliti(',', $toJoin[0]->sidTo));
			
			$i = 1;
			do {
				$searchId = $resultOneRecord[$i];

				for ($j = count($sidDnames) - 1; $j >=0; $j--) {
					if ($sidDnames[$j]->sid == $searchId) {
						$resultOneRecord = $this->mergeDiff($resultOneRecord, spliti(',', $sidDnames[$j]->sidTo));
						array_splice($sidDnames, $j, 1);
					}
					else if (in_array($searchId, spliti(',', $sidDnames[$j]->sidTo))) {
						$resultOneRecord = $this->mergeDiff($resultOneRecord, array($sidDnames[$j]->sid));
						$resultOneRecord = $this->mergeDiff($resultOneRecord, spliti(',', $sidDnames[$j]->sidTo));
						array_splice($sidDnames, $j, 1);
					}					
				}
				
				$i++;	
			}
			while ($i < count($resultOneRecord) && !empty($sidDnames));
			
			$allColumns = $this->getAllColumnsFromJoin($resultOneRecord);
			
			$schemaAndWhereKeyWordsIntersect = array_intersect($allColumns, $this->whereVariable);
			
			if (isset($this->whereVariable) || count($schemaAndWhereKeyWordsIntersect) == count($this->whereVariable)) {
			
				$oneResult = $this->constractOneSearchResult($resultOneRecord, $searchKeyWords, $allColumns);
			
				array_push($result, $oneResult);
			}		
		}
		while (!empty($sidDnames));
			
		return $result;

		*/
	}
	
	private function constractOneSearchResult($sids, $searchKeyWords, $allColumns) {
		$oneResult = "";
		
		$oneResult->datasets = $this->getDatasets($sids);
		$oneResult->searchColumns = $this->getFoundSearchKeyWords($sids, $searchKeyWords);
		$oneResult->sisToSearch = implode(",", $sids);
		$oneResult->where = $this->getWherePart();
		$oneResult->allColumns = $allColumns;
		//TODO: implement
		$oneResult->commonColumns = array("add here explanation of relationships");
			
		$oneResult->joinQuery = $this->getJoinQuery($sids, $oneResult->searchColumns);
		$oneResult->joinResults = $this->getJoinResults($oneResult->joinQuery);
		
		$__SESSION['joinQuery'] = $oneResult->joinQuery;

		return $oneResult;
	}
	
	private function getWherePart() {
		$wherePart = "";

		if (isset($this->whereVariable)) {
			$wherePart = " where ";

			for($i = 0; $i < count($this->whereVariable); ++$i) {
				$wherePart .= chr(96) . $this->whereVariable[$i] . chr(96) . " " . $this->whereRule[$i] . " '" . $this->whereCondition[$i] . "' AND ";
			}

			$wherePart = substr($wherePart, 0, -5);
		};
		
		return $wherePart;
	}
	
	// returns an array of objects with sid and link title for each sid in input param
	private function getDatasets($sids) {
		global $db;
		
		$sidsNoTableName = array_map('UtilsForWizard::getWordUntilFirstDot', $sids);
		
		$sidsString = implode("','", $sidsNoTableName);
		
		$query = "select sid, Title as link_title
from colfusion_sourceinfo
where sid in ('$sidsString');";
		
		 return $rst = $db->get_results($query);
		
	}
	
	private function getFoundSearchKeyWords($sids, $searchKeyWords) {
		global $db;
		
		$sidsString = implode("','", $sids);
		$searchKeyWordsString = implode("','", $searchKeyWords);
		
		$query = "select distinct dname_chosen
from (
		select dname_chosen, CONCAT(sid, '.', tableName) as sidTable
		from colfusion_dnameinfo di, colfusion_columnTableInfo cti
		where di.cid = cti.cid
	) as t
where sidTable in ('$sidsString')
		and dname_chosen in ('$searchKeyWordsString');";
		
		$rst = $db->get_results($query);

		foreach ($rst as $row) {
			$result[] = $row->dname_chosen;
		}
		
		return $result;
	}
	
	private function getAllColumnsFromJoin($sids) {
		global $db;
	
		$sidsString = implode("','", $sids);
			
		$query = "select distinct dname_chosen
from (
		select dname_chosen, CONCAT(sid, '.', tableName) as sidTable
		from colfusion_dnameinfo di, colfusion_columnTableInfo cti
		where di.cid = cti.cid
	) as t
where sidTable in ('$sidsString');";
	
		$rst = $db->get_results($query);
	
		foreach ($rst as $row) {
			$result[] = $row->dname_chosen;
		}
	
		return $result;
	}
	
	private function mergeDiff($ar1, $ar2) {
		$values = array_values($ar2);
		
		for($i = 0; $i < count($values); ++$i) {
			if (!in_array($values[$i], $ar1)) {
				array_push($ar1, $values[$i]);
			}
		}
		
		return $ar1;
	}
	
	private function getJoinQuery($sids, $foundSearchKeyWords) {
		return ExternalMSSQL::getJoinQuery($sids, $foundSearchKeyWords, 10, 1);
	}
	
	
	private function getJoinResults($query) {
		return ExternalMSSQL::runQueryWithLinkedServers($query);
	}
	
// 	public function doSearch() {
	
			
// 		global $db;
	
// 		if (!isset($this->searchKeyWords))
// 			return "";
	
// 		//	$chunks = $this->searchKeyWords;
// 		// spliti(',', $this->searchKeyWords[0]);
	
// 		$chunks = array_map('trim', $this->searchKeyWords);
	
// 		$inCrit = implode("','", $chunks);
	
// 		// 		$sql = "select  sid, link_title, GROUP_CONCAT(distinct dname) as columns from colfusion_temporary,  colfusion_links where colfusion_temporary.sid = colfusion_links.link_id and".
// 		// 				" dname in ('" . $inCrit . "') group by sid, link_title";
	
// 		$sql = "select  ct.sid, ss.link_title, ss.searchColumns,  GROUP_CONCAT(distinct TRIM(dname)) as allColumns
// 		from colfusion_temporary as ct,
// 		(select  sid, link_title, GROUP_CONCAT(distinct TRIM(dname)) as searchColumns
// 		from colfusion_temporary,  colfusion_links
// 		where colfusion_temporary.sid = colfusion_links.link_id and
// 		dname in ('" . $inCrit . "')
// 		group by sid, link_title) ss
// 		where ct.sid = ss.sid
// 		group by sid, link_title";
	
	
// 		$rst = $db->get_results($sql);
	
// 		$res = $this->constructResultArray($rst);
	
// 		//	$res = $db->query("call doJoin(\"4\");");
// 		//	$res = $db->get_results("select * from resultDoJoin;");
// 		//	$res2 = $db->get_col("select * from resultDoJoin;");
	
	
// 		return $res;
// 	}
	
// 	private function constructResultArray($sidDnames) {
	
// 		global $db;
	
// 		$result = array();
	
// 		if (empty($sidDnames))
// 			return $result;
	
// 		do {
// 			$toJoin = array(); //search result entry
				
// 			array_push($toJoin, array_pop($sidDnames)); //take first from the sql query restult and remove it from the array
				
// 			$toJoinSearchColumns = spliti(',', $toJoin[0]->searchColumns);
// 			$toJoinAllColumns = spliti(',', $toJoin[0]->allColumns); // dnames which will be check to join on
	
// 			$commonColumns = array();
				
// 			//	$toJoin[0]->allColumns = $toJoinDnames;
				
// 			unset($toJoin[0]->searchColumns);
// 			unset($toJoin[0]->allColumns);
				
// 			for($i = count($sidDnames) - 1; $i >= 0; --$i) {
					
// 				$withJoinAllColumns = spliti(',', $sidDnames[$i]->allColumns); // dnames which will be check to join with
// 				$withJoinSearchColumns = spliti(',', $sidDnames[$i]->searchColumns);
	
// 				$arIner = array_intersect($toJoinAllColumns, $withJoinAllColumns);
	
// 				if (!empty($arIner)){
// 					unset($sidDnames[$i]->allColumns);
// 					unset($sidDnames[$i]->searchColumns);
	
// 					array_push($toJoin, $sidDnames[$i]);
						
						
// 					$toJoinSearchColumns = $this->mergeDiff($toJoinSearchColumns, $withJoinSearchColumns);
// 					$toJoinAllColumns = $this->mergeDiff($toJoinAllColumns, $withJoinAllColumns);
// 					$commonColumns = $this->mergeDiff($commonColumns, $arIner);
						
	
						
						
// 					array_splice($sidDnames, $i, 1);
// 				}
	
// 			}
				
// 			$oneResult = "";
// 			$oneResult->datasets = $toJoin;
// 			$oneResult->searchColumns = $toJoinSearchColumns;
// 			$oneResult->commonColumns = $commonColumns;
// 			$oneResult->allColumns = $toJoinAllColumns;
// 			$oneResult->joinResults = array();
				
// 			$values = array_values($toJoin);
// 			$sids = array();
// 			for($i = 0; $i < count($values); ++$i) {
// 				array_push($sids, $values[$i]->sid);
// 			}
				
// 			$sisToSearch = implode(",", $sids);
				
// 			$oneResult->sids = $sids;
// 			$oneResult->sisToSearch = $sisToSearch;
	
				
// 			$schemaAndWhereKeyWordsIntersect = array_intersect($toJoinAllColumns, $this->whereVariable);
				
// 			if (isset($this->whereVariable) || count($schemaAndWhereKeyWordsIntersect) == count($this->whereVariable)) {
	
					
// 				$wherePart = "";
	
// 				if (isset($this->whereVariable)) {
// 					$wherePart = " where ";
						
// 					for($i = 0; $i < count($this->whereVariable); ++$i) {
// 						$wherePart .= chr(96) . $this->whereVariable[$i] . chr(96) . " " . $this->whereRule[$i] . " '" . $this->whereCondition[$i] . "' AND ";
// 					}
						
						
// 					$wherePart = substr($wherePart, 0, -5);
// 				};
	
// 				//	$sqlQr = "call doJoin(\"222\")";
// 				$sqlQr = "call doJoin(\"". $sisToSearch. "\")";
// 				$res = $db->query($sqlQr);
	
// 				$sqlQr = "select * from resultDoJoin " . $wherePart . " limit 5;";
// 				$res = $db->get_results($sqlQr);
	
	
// 				//$res = $db->get_results($sqlQr);
	
// 				$oneResult->joinResults = $res;
// 				$oneResult->where = $wherePart;
// 			}
				
				
// 			array_push($result, $oneResult);
				
				
				
// 		}
// 		while (!empty($sidDnames));
			
// 		return $result;
// 	}
}

?>