<fieldset>
	<legend>{lang}wbb.acp.massProcessing.conditions.marking{/lang}</legend>
	
	<div class="formElement formCheckbox">
		<div class="formField">
			<label><input onclick="if (this.checked) enableOptions('postMarkingID'); else disableOptions('postMarkingID');" type="checkbox" name="markingConditionActive" value="1" {if $markingConditionActive == 1}checked="checked" {/if}/> {lang}wbb.acp.massProcessing.conditions.marking.active{/lang}</label>
		</div>
	</div>
	
	<div class="formElement" id="postMarkingIDDiv">
		<div class="formFieldLabel">
			<label for="postMarkingID">{lang}wbb.acp.massProcessing.conditions.marking{/lang}</label>
		</div>
		<div class="formField">
			<select name="postMarkingID" id="postMarkingID">
				<option value="0"{if $postMarkingID == 0} selected="selected"{/if}>{lang}wcf.message.marking.markingID.none{/lang}</option>
				{foreach from=$markings item=marking}
					<option value="{@$marking->markingID}"
					{if $marking->markingID == $postMarkingID} selected="selected"{/if}>{lang}{$marking->title}{/lang}</option>
				{/foreach}
			</select>
		</div>
		<p class="formFieldDesc" id="postMarkingIDHelpMessage">
			{lang}wbb.acp.massProcessing.conditions.marking.description{/lang}
		</p>
		
		<script type="text/javascript">
			//<![CDATA[
				inlineHelp.register('postMarkingID');
			//]]>
		</script>	
	</div>
</fieldset>

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
	{if $markingConditionActive == 1}enableOptions('postMarkingID');{else}disableOptions('postMarkingID');{/if}
	
	document.observe("dom:loaded", function() {
		// move div
		var assignMessageMarkingDiv = $('assignMessageMarkingDiv');
		// navigate to parent and yes... it's nasty
		$$('.formSubmit')[0].previous().down('.container-1').insert(assignMessageMarkingDiv);

		if ('{$action}' == 'assignMessageMarking') {
			assignMessageMarkingDiv.show();
		}
	});	
	
	// disable
	function disableAssignMessageMarking() {
		hideOptions('assignMessageMarkingDiv');
	}
	
	// enable
	function enableAssignMessageMarking() {
		disableAssignMessageMarking();
		showOptions('assignMessageMarkingDiv');
	}
	//]]>
</script>