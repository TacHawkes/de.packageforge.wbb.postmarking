<div id="assignMessageMarkingDiv" style="display: none;" >
	<fieldset>
		<legend>{lang}wbb.acp.massProcessing.assignMessageMarking{/lang}</legend>
		
		<div class="formRadio formGroup" id="markingIDDiv">
			<div class="formGroupLabel">
				<label>{lang}wbb.acp.massProcessing.assignMessageMarking.markingID{/lang}</label>
			</div>
			<div class="formGroupField">
				<fieldset>
					<legend>{lang}wbb.acp.massProcessing.assignMessageMarking.markingID{/lang}</legend>
						
					<div class="formField">
						<ul class="formOptionsLong">
							<li><label><input type="radio" name="markingID" value="0" {if $markingID == 0}checked="checked" {/if}/> <span>{lang}wcf.user.option.defaultMessageMarkingID.none{/lang}</span></label></li>
							{foreach from=$markings item=marking}
								<li><label><input type="radio" name="markingID" value="{@$marking->markingID}" {if $marking->markingID == $markingID}checked="checked" {/if}/> <span>{lang}{$marking->title}{/lang}</span></label></li>
							{/foreach}
						</ul>		
					</div>
					
					<div class="formFieldDesc" id="markingIDHelpMessage">
						<p>{lang}wbb.acp.massProcessing.assignMessageMarking.markingID.description{/lang}</p>
					</div>
					<script type="text/javascript">
						//<![CDATA[
							inlineHelp.register('markingID');
						//]]>
					</script>																		
				</fieldset>
			</div>
		</div>						
	</fieldset>
</div>

<script type="text/javascript">
	//<![CDATA[
	document.observe("dom:loaded", function() {
		// move div
		var assignMessageMarkingDiv = $('assignMessageMarkingDiv');
		// navigate to parent and yes... it's nasty
		$$('.formSubmit')[0].previous().down('.container-1').insert(assignMessageMarkingDiv);

		if ({$action} == 'assignMessageMarking') {
			assignMessageMarkingDiv.show();
		}
	});	
	
	// disable
	function disableAssignMessageMarking() {
		hideOptions('assignMessageMarkingDiv');
	}
	
	// enable
	function enableAssignMessageMarking() {
		disableAll();
		showOptions('assignMessageMarkingDiv');
	}
	//]]>
</script>