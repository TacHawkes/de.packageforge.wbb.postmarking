<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/listener/AbstractMessageMarkingListener.class.php');

/**
 * Marks posts
 *
 * @author      Oliver Kliebisch
 * @copyright   2011 Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package     de.packageforge.wbb.postmarking
 * @subpackage  system.event.listener
 * @category    Burning Board
 */
class ThreadPageMessageMarkingListener extends AbstractMessageMarkingListener {
	/**
	 * @see AbstractMessageMarkingListener::appendMessageObjectList()
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
	 * @see AbstractMessageMarkingListener::getMessageObjects()
	 */
	public function getMessageObjects($eventObj, $className, $eventName) {
		return $eventObj->postList->posts;
	}
	
	/**
	 * @see AbstractMessageMarkingListener::getObjectID()
	 */
	public function getObjectID($object) {
		return $object->postID;
	}
	
	/**
	 * @see AbstractMessageMarkingListener::getMessageContainerSelector()
	 */
	public function getMessageContainerSelector($objectID) {
		return '#postRow'.$objectID;
	}
}
