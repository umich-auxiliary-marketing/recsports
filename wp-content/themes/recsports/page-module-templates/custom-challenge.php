<?
use Roots\Sage\Assets;
wp_enqueue_script('sage/challenge', Assets\asset_path('scripts/custom-challenge.js'), array('jquery'), null, true);

// An extra wrap helps us determine the kind of custom module.
?>
<div class="challenge">
	<dl class="process__step">
		<dt class="process__value"><span class="process__text paragraph--copy">Identify your type of group.</span></dt>
		<dd class="challenge__questionnaire">
			<div class="questionnaire__option">
				<input type="radio" name="challengeaudience" data-multiplier="1.0" id="challengeaudience--affiliated" value="U-M affiliated">
				<label for="challengeaudience--affiliated"><!--
				--><span class="questionnaire__main">My group is <strong>affiliated with U&#8209;M</strong>.</span>
					<span class="questionnaire__descriptor">Groups within the university: U&#8209;M student groups, departments, staff groups, affiliates, Michigan Medicine</span>
				</label>
			</div>
			<div class="questionnaire__option">
				<input type="radio" name="challengeaudience" data-multiplier="1.4" id="challengeaudience--community" value="Community">
				<label for="challengeaudience--community"><!--
				--><span class="questionnaire__main">My group is a <strong>community group</strong>.</span>
					<span class="questionnaire__descriptor">Educational or not-for-profit groups outside of the University</span>
				</label>
			</div>
			<div class="questionnaire__option">
				<input type="radio" name="challengeaudience" data-multiplier="2.25" id="challengeaudience--corporate" value="Corporate">
				<label for="challengeaudience--corporate"><!--
				--><span class="questionnaire__main">My group is a <strong>corporate group</strong>.</span>
					<span class="questionnaire__descriptor">Organizations that are for profit, excluding Michigan Medicine</span>
				</label>
			</div>
		</dd>
		<dt class="process__value"><span class="process__text paragraph--copy">Select a <a href="#programs">program type</a>.</span></dt>
		<dd class="challenge__questionnaire">
			<div class="questionnaire__option">
				<input type="radio" name="challengeprogramtype" data-multiplier="1.0" id="challengeprogramtype--challenge" value="Team Challenge">
				<label for="challengeprogramtype--challenge"><!--
				--><span class="questionnaire__main"><strong>Team Challenge</strong></span>
					<span class="questionnaire__descriptor">Builds skills in communication, cooperation and problem solving</span>
				</label>
			</div>
			<div class="questionnaire__option">
				<input type="radio" name="challengeprogramtype" data-multiplier="1.4" id="challengeprogramtype--tower" value="Team Tower">
				<label for="challengeprogramtype--tower"><!--
				--><span class="questionnaire__main"><strong>Team Tower</strong></span>
					<span class="questionnaire__descriptor">Enhances communication, focus and mutual support</span>
				</label>
			</div>
			<div class="questionnaire__option">
				<input type="radio" name="challengeprogramtype" data-multiplier="1.75" id="challengeprogramtype--ropes" value="High Adventure">
				<label for="challengeprogramtype--ropes"><!--
				--><span class="questionnaire__main"><strong>High Adventure</strong></span>
					<span class="questionnaire__descriptor">Improves mutual support and risk-taking</span>
				</label>
			</div>
		</dd>
		<dt class="process__value"><span class="process__text paragraph--copy">Pick a duration for your program.</dt>
		<dd class="challenge__questionnaire">
			<div class="questionnaire__option">
				<input type="radio" name="challengeduration" data-multiplier="0.8" id="challengeduration--mini" value="Mini Course">
				<label for="challengeduration--mini"><!--
				--><span class="questionnaire__main"><strong>Mini Course</strong></span>
					<span class="questionnaire__descriptor">2&thinsp;&frac12; hours or less</span>
				</label>
			</div>
			<div class="questionnaire__option">
				<input type="radio" name="challengeduration" data-multiplier="1.0" id="challengeduration--half" value="Half Day">
				<label for="challengeduration--half"><!--
				--><span class="questionnaire__main"><strong>Short Day</strong></span>
					<span class="questionnaire__descriptor">3 to 5 hours</span>
				</label>
			</div>
			<div class="questionnaire__option">
				<input type="radio" name="challengeduration" data-multiplier="1.75" id="challengeduration--full" value="Full Day">
				<label for="challengeduration--full"><!--
				--><span class="questionnaire__main"><strong>Full Day</strong></span>
					<span class="questionnaire__descriptor">6 to 7 hours</span>
				</label>
			</div>
		</dd>
		<dt class="process__value"><span class="process__text paragraph--copy">Select the season in which you&rsquo;d like to have your challenge.</dt>
		<dd class="challenge__questionnaire">
			<div class="questionnaire__option">
				<input type="radio" name="challengeseason" data-multiplier="1.0" id="challengeseason--inseason" value="In-season">
				<label for="challengeseason--inseason"><!--
				--><span class="questionnaire__main"><strong>In-season</strong> challenge in spring, summer or fall</span>
					<span class="questionnaire__descriptor">March 15 &ndash; November 15</span>
				</label>
			</div>
			<div class="questionnaire__option">
				<input type="radio" name="challengeseason" data-multiplier="0.75" id="challengeseason--offseason" value="Off-season">
				<label for="challengeseason--offseason"><!--
				--><span class="questionnaire__main"><strong>Off-season</strong> challenge in winter</span>
					<span class="questionnaire__descriptor">November 16 &ndash; March 14</span>
				</label>
			</div>
		</dd>
		<dt class="process__value"><span class="process__text paragraph--copy">Set the desired location.</dt>
		<dd class="challenge__questionnaire">
			<div class="questionnaire__option">
				<input type="radio" name="challengelocation" data-multiplier="1.0" id="challengelocation--basecamp" value="Radrick Challenge Program">
				<label for="challengelocation--basecamp"><!--
				--><span class="questionnaire__main"><strong>Radrick Adventure Leadership Center</strong> in Ann Arbor</span>
				</label>
			</div>
			<div class="questionnaire__option">
				<input type="radio" name="challengelocation" data-multiplier="2.25" id="challengelocation--offsite" value="Off-site">
				<label for="challengelocation--offsite"><!--
				--><span class="questionnaire__main"><strong>Off-site</strong> at some other location</span>
				</label>
			</div>
		</dd>
	</dl>
	<div class="challenge__unfinished paragraph--copy">
		<h2>Pick a choice for each of the questions above to calculate a rate.</h2>
		<p>Program rates are determined by your choices. You can submit an inquiry after a rate has been calculated.</p>
		<? //<p>Program rates are determined by these choices. If you&rsquo;d like, you can <a href="#" class="challenge__begin challenge__begin--link js--ignorejump gtm--formrevealed">submit an inquiry</a> with us instead.</p> ?>
	</div>
	<div class="challenge__finished ada--hidden">
		<div class="challenge__finishedcopy paragraph--copy">
			<h2 class="challenge__finishedheader">These options will cost <span class="challenge__price">$0.00</span> per person.</h2>
			<p>Ready to submit your inquiry? We just need some final information.</p>
		</div>
		<div class="challenge__beginbutton">
			<a href="#" class="challenge__begin challenge__begin--button button button--cta themed--cta js--ignorejump gtm--formrevealed">Finish Inquiry</a>
		</div>
	</div>
	<div class="challenge__form ada--hidden">
		<div class="form__shrinkwrap">
			<?= isset($meta[$pcb . "inquiry_form"]) ? do_shortcode("[formidable id=" . $meta[$pcb . "inquiry_form_id"][0] . "]") : "Site editors, check your Challenge Program inquiry form."; ?>
		</div>
	</div>
</div>
