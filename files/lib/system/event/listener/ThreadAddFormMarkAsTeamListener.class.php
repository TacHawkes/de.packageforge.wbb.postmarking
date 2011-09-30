<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/listener/AbstractMessageAddFormMarkAsTeamListener.class.php');

/**
 * Saves the settings value
 *
 * @author      Oliver Kliebisch
 * @copyright   2011 Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package     de.packageforge.wbb.markteam
 * @subpackage  system.event.listener
 * @category    Burning Board
 */
class ThreadAddFormMarkAsTeamListener extends AbstractMessageAddFormMarkAsTeamListener {
	/**
	 * @see	AbstractMessageAddFormMarkAsTeamListener::saveMessageObjectSetting()
	 */
	public function saveMessageObjectSetting($eventObj, $className, $markAsTeamMessage) {
		$postID = $className == 'ThreadAddForm' ? $eventObj->newThread->firstPostID : $eventObj->postID;
		
		$sql = "UPDATE	wbb".WBB_N."_post
			SET	markAsTeamMessage = ".$markAsTeamMessage."
			WHERE	postID = ".$postID;
		WCF::getDB()->sendQuery($sql);
		
		if ($className == 'PostEditForm') {
			$this->saveSetting = false;
		}
	}
}
