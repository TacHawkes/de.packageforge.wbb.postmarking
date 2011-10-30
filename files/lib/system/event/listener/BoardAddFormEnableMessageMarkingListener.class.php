<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * Adds options for enabling/disabling the message markings for a board
 *
 * @author      Oliver Kliebisch
 * @copyright   2011 Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package     de.packageforge.wbb.postmarking
 * @subpackage  system.event.listener
 * @category    Burning Board
 */
class BoardAddFormEnableMessageMarkingListener implements EventListener {
	/**
	 * option value
	 *
	 * @var	integer
	 */
	protected $enableMessageMarking = 1;

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if (MODULE_DISPLAY_MESSAGE_MARKINGS == 1) {
			if($eventName == 'readFormParameters') {
				// read form parameters
				if(isset($_POST['enableMessageMarking'])) $this->enableMessageMarking = intval($_POST['enableMessageMarking']);
				else $this->enableMessageMarking = 0;
			}			
			else if ($eventName == 'readData') {
				if($className == 'BoardEditForm' && !count($_POST)) {
					// get old values
					$this->enableMessageMarking = $eventObj->board->enableMessageMarking;
				}
			}
			else if ($eventName == 'assignVariables') {
				// append tpl
				WCF::getTPL()->append('additionalSettings', '<div class="formElement">
							<div class="formField">
								<label><input type="checkbox" name="enableMessageMarking" value="1" '.($this->enableMessageMarking ? 'checked="checked"' : '').'/> '.WCF::getLanguage()->get('wbb.acp.board.enableMessageMarking').'</label>
							</div>
						</div>');
			}
			else if ($eventName == 'save') {
				$eventObj->additionalFields['enableMessageMarking'] = $this->enableMessageMarking;
				if (!($eventObj instanceof BoardEditForm)) {
					$this->enableMessageMarking = 1;
				}									
			}
		}
	}
}
?>