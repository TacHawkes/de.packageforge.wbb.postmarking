<?php 
// change field's default value
$sql = "ALTER TABLE  	wbb".WBB_N."_post
	CHANGE  	markAsTeamMessage  
			markAsTeamMessage TINYINT( 1 ) NOT NULL DEFAULT  '1';"
WCF::getDB()->sendQuery($sql);

// update values
$sql = "UPDATE		wbb".WBB_N."_post
	SET		markAsTeamMessage = 1
	WHERE		time < (SELECT installDate FROM wcf".WCF_N."_package WHERE packageID = ".$this->installation->getPackageID().")";
WCF::getDB()->sendQuery($sql);
?>