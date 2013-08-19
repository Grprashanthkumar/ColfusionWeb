<?php

require_once realpath(dirname(__FILE__)) . '/../config.php';
require_once realpath(dirname(__FILE__)) . '/DatasetFinder.php';
require_once realpath(dirname(__FILE__)) . '/../datasetModel/Relationship.php';
require_once realpath(dirname(__FILE__)) . '/../datasetModel/ColfusionLink.php';
require_once realpath(dirname(__FILE__)) . '/../datasetModel/Comment.php';
require_once realpath(dirname(__FILE__)) . '/TransformationHandler.php';

class RelationshipDAO 
{

    private $ezSql;

    public function __construct() {
        global $db;
        $this->ezSql = $db;
    }

    public function getRelationship($relId) {
        $sql = "SELECT name, description, user_id, user_login, sid1, sid2, tableName1, tableName2, creation_time 
            FROM  `colfusion_relationships` CR INNER JOIN  `colfusion_users` U ON CR.creator = U.user_id 
            WHERE CR.rel_id = '" . mysql_real_escape_string($relId) . "'";

        $relInfo = $this->ezSql->get_row($sql);
        if ($relInfo == null) {
            throw new Exception('Relationship Not Found');
        }

        $relationship = new Relationship();
        $relationship->rid = $relId;
        $relationship->name = $relInfo->name;
        $relationship->description = $relInfo->description;
        $relationship->creator = $relInfo->user_login;
        $relationship->createdTime = $relInfo->creation_time;

        $datasetFinder = new DatasetFinder();
        $fromDataset = $datasetFinder->findDatasetInfoBySid($relInfo->sid1);
        $toDataset = $datasetFinder->findDatasetInfoBySid($relInfo->sid2);

        $relationship->fromDataset = $fromDataset;
        $relationship->toDataset = $toDataset;
        $relationship->fromTableName = $relInfo->tableName1;
        $relationship->toTableName = $relInfo->tableName2;
        
        // $relationship->links[] = $this->GetLinksByRelId($relId);
        $relationship->links = $this->GetLinksByRelId($relId);

        return $relationship;
    }

    public function GetLinksByRelId($relId) {
        $links = array();

        $linksSql = "select cl_from, cl_to from `colfusion_relationships_columns` where rel_id = '" . mysql_real_escape_string($relId) . "'";
        $linkInfos = $this->ezSql->get_results($linksSql);

        foreach ($linkInfos as $linkInfo) {
            $rawLinkParts[] = $linkInfo->cl_from;
            $rawLinkParts[] = $linkInfo->cl_to;
        }
        
        $transHandler = new TransformationHandler();
        foreach ($linkInfos as $linkInfo) {
            $link = new ColfusionLink();
            $link->fromPart = $transHandler->decodeTransformationInput($linkInfo->cl_from, true);
            $link->toPart = $transHandler->decodeTransformationInput($linkInfo->cl_to, true);

            $link->fromPartEncoded = $linkInfo->cl_from;
            $link->toPartEncoded = $linkInfo->cl_to;

            $links[] = $link;
        }

        return $links;
    }

    public function deleteRelationship($relId, $userId) {
        
        // Check if deleter is creator.
        $sql = "select rel_id from colfusion_relationships where creator = '$userId'";
        $matchCreatorResult = $this->ezSql->get_results($sql);    
        if(!$matchCreatorResult){
            throw new Exception("You are not able to delete this relationship.");
        }
        
        $delSql = "delete from colfusion_relationships where creator = '$userId' and rel_id='$relId'";
        $this->ezSql->query($delSql);
    }

    public function getColumnInfo($cid){
        $sql = "select * from `colfusion_dnameinfo` where cid = $cid";   
        return $this->ezSql->get_row($sql);       
    }
      
    public function getComments($relId) {
        $sql = "SELECT confidence, comment, `when`, user_login, user_email, URV.user_id, rel_id 
            FROM  `colfusion_user_relationship_verdict` URV INNER JOIN  `colfusion_users` U ON URV.user_id = U.user_id 
            WHERE URV.rel_id = '" . mysql_real_escape_string($relId) . "'";

        $commentInfos = $this->ezSql->get_results($sql);

        foreach ($commentInfos as $commentInfo) {
            $comments[] = $this->mapDbCommentRowToComment($commentInfo);
        }

        return $comments;
    }

    public function getComment($relId, $userId) {
        $relId = mysql_real_escape_string($relId);
        $userId = mysql_real_escape_string($userId);

        $sql = "SELECT confidence, comment, `when`, user_email, user_login, URV.user_id, rel_id 
            FROM  `colfusion_user_relationship_verdict` URV INNER JOIN  `colfusion_users` U ON URV.user_id = U.user_id 
            WHERE URV.rel_id = '$relId' and URV.user_id = '$userId'";

        $commentInfo = $this->ezSql->get_row($sql);
        return $this->mapDbCommentRowToComment($commentInfo);
    }

    public function addComment($relId, $userId, $confidence, $comment) {
        $relId = mysql_real_escape_string($relId);
        $userId = mysql_real_escape_string($userId);
        $confidence = mysql_real_escape_string($confidence);
        $comment = mysql_real_escape_string($comment);

        $sql = "insert into colfusion_user_relationship_verdict(rel_id, user_id, confidence, comment, `when`) 
            values('$relId', '$userId', '$confidence', '$comment', NOW())";

        return $this->ezSql->query($sql);
    }

    public function removeComment($relId, $userId) {
        $relId = mysql_real_escape_string($relId);
        $userId = mysql_real_escape_string($userId);

        $sql = "delete from colfusion_user_relationship_verdict where rel_id = '$relId' and user_id = '$userId'";

        return $this->ezSql->query($sql);
    }

    public function updateComment($relId, $userId, $confidence, $comment) {

        $relId = mysql_real_escape_string($relId);
        $userId = mysql_real_escape_string($userId);
        $confidence = mysql_real_escape_string($confidence);
        $comment = mysql_real_escape_string($comment);

        $sql = "update colfusion_user_relationship_verdict 
            set confidence = '$confidence', comment = '$comment' 
            where rel_id = '$relId' and user_id = '$userId'";

        return $this->ezSql->query($sql);
    }

    private function mapDbCommentRowToComment($dbCommentRow) {
        $comment = new Comment();
        $comment->rid = $dbCommentRow->rel_id;
        $comment->userId = $dbCommentRow->user_id;
        $comment->userName = $dbCommentRow->user_login;
        $comment->userEmail = $dbCommentRow->user_email;
        $comment->comment = $dbCommentRow->comment;
        $comment->commentTime = $dbCommentRow->when;
        $comment->confidence = $dbCommentRow->confidence;
        return $comment;
    }

    public function getRelIdsForSid($sid) {
        $sid = mysql_real_escape_string($sid);

        $query = "SELECT distinct rel_id 
            FROM  `colfusion_relationships`  
            WHERE  sid1 = $sid or sid2 = $sid";

        $queryResult =  $this->ezSql->get_results($query);

        $result = array();
        
        if($queryResult != null){
            foreach ($queryResult as $key => $rel) {
                $result[] = $rel->rel_id;
            }
        }

        return $result;
    }

    /**
     * Return average confidence of the relationship by given relationship id
     * @param  int $rel_id id of the relationship
     * @return float         average confidcen value
     */
    public function getRelationshipAverageConfidenceByRelId($rel_id) {
        $rel_id = mysql_real_escape_string($rel_id);
        

        $sql = "select avg(confidence) as avgconf 
            from colfusion_user_relationship_verdict 
            where rel_id = $rel_id";

        return $this->ezSql->get_row($sql)->avgconf;
    }

}

function testRelDAO() {
    $datasetFinder = new DatasetFinder();
    //var_dump($datasetFinder->findDatasetInfoBySid(1495));
    //var_dump($datasetFinder->findDatasetInfoBySid(1487));

    $relDAO = new RelationshipDAO();
    var_dump($relDAO->getRelationship(752));
    //var_dump($relDAO->getComments(1454));
    //var_dump($relDAO->getComment(1462, 20));
    //var_dump($relDAO->updateComment(1462, 20, 0.7, 'Test update'));
}

?>
