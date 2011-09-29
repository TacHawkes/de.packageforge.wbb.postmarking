<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/listener/AbstractMarkTeamMessageListener.class.php');

/**
 * Marks team member's posts
 *
 * @author      Oliver Kliebisch
 * @copyright   2011 Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package     de.packageforge.wbb.markteam
 * @subpackage  system.event.listener
 * @category    Burning Board
 */
class ThreadPageMarkTeamPostsListener extends AbstractMarkTeamMessageListener {
	/**
	 * @see AbstractMarkTeamMessageListener::appendMessageObjectList()
	 */
	public function appendMessageObjectList($eventObj, $className, $eventName) {
		/* as the post list is still not compliant to the database object list class
		 * an extra subquery needs to be wasted
		 */
		$eventObj->postList->sqlSelects .= "(
					SELECT	GROUP_CONCAT(DISTINCT groupID ORDER BY groupID ASC SEPARATOR ',') 
					FROM	wcf".WCF_N."_user_to_groups
					WHERE	userID = user.userID
				) AS groupIDs, ";	
	}
	
	/**
	 * @see AbstractMarkTeamMessageListener::getMessageObjects()
	 */
	public function getMessageObjects($eventObj, $className, $eventName) {
		return $eventObj->postList->posts;
	}
	
	/**
	 * @see AbstractMarkTeamMessageListener::getObjectID()
	 */
	public function getObjectID($object) {
		return $object->postID;
	}
	
	/**
	 * @see AbstractMarkTeamMessageListener::getMessageContainerSelector()
	 */
	public function getMessageContainerSelector($objectID) {
		return '#postRow'.$objectID;
	}
}
?>