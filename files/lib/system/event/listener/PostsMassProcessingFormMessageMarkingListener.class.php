<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/data/message/marking/MessageMarking.class.php');

/**
 * Assigns posts a message marking
 *
 * @author      Oliver Kliebisch
 * @copyright   2011 Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package     de.packageforge.wbb.postmarking
 * @subpackage  system.event.listener
 * @category    Community Framework
 */
class PostsMassProcessingFormMessageMarkingListener implements EventListener {
	/**
	 * marking id
	 *
	 * @var integer
	 */
	protected $markingID = 0;
	
	/**
	 * condition is active
	 * 
	 * @var integer
	 */
	protected $markingConditionActive = 0;
	
	/**
	 * post marking id
	 * 
	 * @var integer
	 */
	protected $postMarkingID = 0;

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if (MODULE_DISPLAY_MESSAGE_MARKINGS == 1) {
			if ($eventName == 'readParameters') {
				$eventObj->availableActions[] = 'assignMessageMarking';
			}
			else if ($eventName == 'readFormParameters') {
				if (isset($_POST['markingID'])) $this->markingID = intval($_POST['markingID']);
				if (isset($_POST['markingConditionActive'])) $this->markingConditionActive = intval($_POST['markingConditionActive']);
				if ($this->markingConditionActive) {
					if (isset($_POST['postMarkingID'])) $this->postMarkingID = intval($_POST['postMarkingID']);
				}
			}
			else if ($eventName == 'validate') {
				if ($eventObj->action == 'assignMessageMarking') {					
					if ($this->markingID != 0) {
						$allMarkings = MessageMarking::getCachedMarkings();						
						if (!isset($allMarkings[$this->markingID])) throw new UserInputException('markingID');
					}
				}
			}
			else if ($eventName == 'save') {
				if ($this->markingConditionActive) {					
					$eventObj->conditions->add("markingID = ".$this->postMarkingID);
				}
			}
			else if ($eventName == 'saved') {
				if ($eventObj->action == 'assignMessageMarking') {
					// get posts
					$posts = array();
					$sql = "SELECT		post.postID,
								GROUP_CONCAT(DISTINCT user_to_groups.groupID ORDER BY user_to_groups.groupID ASC SEPARATOR ',') AS groupIDs					
						FROM		wbb".WBB_N."_post post
						LEFT JOIN	wcf".WCF_N."_user_to_groups user_to_groups
						ON		(user_to_groups.userID = post.userID)
						".$eventObj->conditions->get()."
						GROUP BY	post.postID";
					$result = WCF::getDB()->sendQuery($sql);
					while ($row = WCF::getDB()->fetchArray($result)) {						
						$posts[$row['postID']] = $row;
					}
					
					// if id != 0 check if id is available for each post author
					if ($this->markingID != 0) {
						foreach ($posts as $key => $post) {
							if (!count(MessageMarking::getAvailableMarkings(explode(',', $post['groupIDs']), false))) {
								unset($posts[$key]);	
							}
						}
					}
					$postIDArray = array_keys($posts);
					unset ($posts);					

					if (count($postIDArray)) {
						// save assignment
						$sql = "UPDATE	wbb".WBB_N."_post
							SET	markingID = ".$this->markingID."
							WHERE	postID IN (".implode(',', $postIDArray).")";
						WCF::getDB()->sendQuery($sql);

						// set affected posts
						$eventObj->affectedPosts = count($postIDArray);
					}
				}
			}
			else if ($eventName == 'assignVariables') {
				WCF::getTPL()->append('additionalActions', '<li><label><input onclick="if (IS_SAFARI) enableAssignMessageMarking()" onfocus="enableAssignMessageMarking()" type="radio" name="action" value="assignMessageMarking" '.($eventObj->action == 'assignMessageMarking' ? 'checked="checked" ' : '').'/> '.WCF::getLanguage()->get('wbb.acp.massProcessing.action.assignMessageMarking').'</label></li>');

				// read and assign markings
				WCF::getTPL()->assign(array(
					'action' => $eventObj->action,
					'markings' => MessageMarking::getCachedMarkings(),
					'markingID' => $this->markingID,
					'markingConditionActive' => $this->markingConditionActive,
					'postMarkingID' => $this->postMarkingID,
					'errorField' => $eventObj->errorField,
					'errorType' => $eventObj->errorType
				));
				
				// due to wcf <-> wbb inconsistency we need to use js to insert the settings container
				// using the "wrong" template hook
				WCF::getTPL()->append('additionalConditions', WCF::getTPL()->fetch('postsMassProcessingAssignMessageMarking'));
			}
		}
	}
}
