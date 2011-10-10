<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * Adds options for enabling/disabling the message markings for a board
 *
 * @author      Oliver Kliebisch
 * @copyright   2011 Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package     de.packageforge.wbb.markteam
 * @subpackage  system.event.listener
 * @category    Burning Board
 */
class BoardAddFormEnableMessageMarkingsListener implements EventListener {
	/**
	 * option value
	 *
	 * @var	integer
	 */
	protected $enableMessageMarkings = 1;

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if (MODULE_USER_MARK_TEAM_MESSAGE == 1) {
			if($eventName == 'readFormParameters') {
				// read form parameters
				if(isset($_POST['enableMessageMarkings'])) $this->enableMessageMarkings = intval($_POST['enableMessageMarkings']);
				else $this->enableMessageMarkings = 0;
			}			
			else if ($eventName == 'readData') {
				if($className == 'BoardEditForm' && !count($_POST)) {
					// get old values
					$this->enableMessageMarkings = $eventObj->board->enableMessageMarkings;
				}
				// append tpl
				WCF::getTPL()->append('additionalSettings', '<div class="formElement">
							<div class="formField">
								<label><input type="checkbox" name="enableMessageMarkings" value="1" '.($this->enableMessageMarkings ? 'checked="checked"' : '').'/> '.WCF::getLanguage()->get('wbb.acp.board.enableMessageMarkings').'</label>
							</div>
						</div>');
			}
			else if ($eventName == 'save') {
				$eventObj->additionalFields['enableMessageMarkings'] = $this->enableMessageMarkings;
				if (!($eventObj instanceof BoardEditForm)) {
					$this->enableMessageMarkings = 1;
				}									
			}
		}
	}
}
?>