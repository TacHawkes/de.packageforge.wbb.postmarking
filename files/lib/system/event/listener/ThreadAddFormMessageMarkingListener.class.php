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
	 * @see	AbstractMessageAddFormMessageMarkingListener::saveMessageObjectSetting()
	 */
	public function saveMessageObjectSetting($eventObj, $className, $markingID) {
		$postID = $className == 'ThreadAddForm' ? $eventObj->newThread->firstPostID : $eventObj->postID;
		
		$sql = "UPDATE	wbb".WBB_N."_post
			SET	markingID = ".$markingID."
			WHERE	postID = ".$postID;
		WCF::getDB()->sendQuery($sql);		
	}
}
