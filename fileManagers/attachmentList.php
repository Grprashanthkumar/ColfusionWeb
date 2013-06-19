<?php
include_once('FileManagers.php');
require_once('../OriginalSmarty/OriginalSmarty.class.php');

$sid = $_POST['sid'];

$attachmentInfos = SourceDesAttachmentManager::getInstance()->getSourceAttachmentsInfo($sid);

// Assign model to template. 
$smarty = new OriginalSmarty();
$smarty->assign('attachmentInfos', $attachmentInfos);
$smarty->display('attachmentList.tpl');
?>
