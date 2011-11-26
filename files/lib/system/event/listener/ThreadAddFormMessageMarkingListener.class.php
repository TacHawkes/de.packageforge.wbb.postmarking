<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/listener/AbstractMessageAddFormMessageMarkingListener.class.php');

/**
 * Saves the settings value
 *
 * @author      Oliver Kliebisch
 * @copyright   2011 Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package     de.packageforge.wbb.postmarking
 * @subpackage  system.event.listener
 * @category    Burning Board
 */
class ThreadAddFormMessageMarkingListener extends AbstractMessageAddFormMessageMarkingListener {

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if ($eventObj->board->enableMessageMarking) {
			parent::execute($eventObj, $className, $eventName);
		}
	}

	/**
	 * @see	AbstractMessageAddFormMessageMarkingListener::saveMessageObjectSetting()
	 */
	public function saveMessageObjectSetting($eventObj, $className, $markingID) {
		switch ($className) {
			case 'ThreadAddForm':
				$postID = $eventObj->newThread->firstPostID;
				break;
			case 'PostAddForm':
			case 'PostQuickAddForm':
				$postID = $eventObj->newPost->postID;
				break;
			case 'PostEditForm':
				$postID = $eventObj->postID;
				break;
			default:
				$postID = $eventObj->postID;
				break;
		}		

		$sql = "UPDATE	wbb".WBB_N."_post
			SET	markingID = ".$markingID."
			WHERE	postID = ".$postID;
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * @see	AbstractMessageAddFormMessageMarkingListener::getMarkingID()
	 */
	public function getMarkingID($eventObj, $className) {
		return ($className == 'PostEditForm' ? $eventObj->post->markingID : WCF::getUser()->defaultMessageMarkingID);
	}
	
	/**
	 * @see	AbstractMessageAddFormMessageMarkingListener::getGroupIDs()
	 */
	public function getGroupID($eventObj, $className) {
		if ($className == 'PostEditForm') {
			// get group ids of author
			$sql = "SELECT 		GROUP_CONCAT(DISTINCT groupID ORDER BY groupID ASC SEPARATOR ',') AS groupIDs
				FROM		wcf".WCF_N."_user_to_groups
				WHERE		userID = ".$eventObj->post->userID;			
			$row = WCF::getDB()->getFirstRow($sql);
			
			if ($row) {
				return explode(',', $row['groupIDs']);
			}			
			else return array();
		}
		else {
			return null;	
		}		
	}
}
