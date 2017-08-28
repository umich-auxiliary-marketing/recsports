<?
use Roots\Sage\Assets;
wp_enqueue_script('sage/membership', Assets\asset_path('scripts/custom-membership.js'), array('jquery'), null, true);

// An extra wrap helps us determine the kind of custom module.
?>
<div class="membership">
	<div class="membership__hypothesis">
		<p class="membership__explanation">Rates and eligibility depend on how you&rsquo;re affiliated with the University. Choose the option below that most closely matches you.</p>

		<div class="membership__buttons">
			<div class="membership__option">
				<input type="radio" name="membertype" id="membertype--studentchoice" class="membership__option--additionals"><label for="membertype--studentchoice"><span class="questionnaire__main">I&rsquo;m a <strong>student</strong> or recent grad.</span></label>
				<div class="membership__secondary">
					<div class="membership__option">
						<input type="radio" name="studenttype" id="membertype--enrolledstudent"><label for="membertype--enrolledstudent"><span class="questionnaire__main">I&rsquo;m <strong>taking at least one class</strong> at U&#8209;M&ndash;Ann Arbor this term.</span></label>
					</div>
					<div class="membership__option">
						<input type="radio" name="studenttype" id="membertype--unenrolledstudent"><label for="membertype--unenrolledstudent"><span class="questionnaire__main">I&rsquo;m <strong>not enrolled</strong> at U&#8209;M&ndash;Ann Arbor this term.</span></label>
					</div>
				</div>
			</div>
			<div class="membership__option">
				<input type="radio" name="membertype" id="membertype--staff"><label for="membertype--staff"><span class="questionnaire__main">I&rsquo;m part of the <strong>faculty, staff or affiliates</strong> of the University.</span></label>
			</div>
			<div class="membership__option">
				<input type="radio" name="membertype" id="membertype--retiree"><label for="membertype--retiree"><span class="questionnaire__main">I&rsquo;m a <strong>retiree</strong> of U&#8209;M.</span></label>
			</div>
			<div class="membership__option">
				<input type="radio" name="alumchoice" id="membertype--alum"><label for="membertype--alum"><span class="questionnaire__main">I&rsquo;m an <strong>alum</strong> of the University.</span></label>
			</div>
			<div class="membership__option">
				<input type="radio" name="alumchoice" id="membertype--friend"><label for="membertype--friend"><span class="questionnaire__main">I&rsquo;m a  <strong>friend</strong> of someone affiliated with U&#8209;M, or I&rsquo;m only on campus for a short period of time.</span></label>
			</div>
			<div class="membership__option">
				<input type="radio" name="membertype" id="membertype--spousechoice" class="membership__option--additionals"><label for="membertype--spousechoice"><span class="questionnaire__main">I&rsquo;m the <strong>spouse or partner</strong> of someone that matches a previous description.</span></label>
				<div class="membership__secondary">
					<div class="membership__option">
						<input type="radio" name="spousetype" id="membertype--studentspouse"><label for="membertype--studentspouse"><span class="questionnaire__main">My spouse is a <strong>student</strong>.</span></label>
					</div>
					<div class="membership__option">
						<input type="radio" name="spousetype" id="membertype--staffspouse"><label for="membertype--staffspouse"><span class="questionnaire__main">My spouse is a <strong>U&#8209;M employee or faculty member</strong>.</span></label>
					</div>
					<div class="membership__option">
						<input type="radio" name="spousetype" id="membertype--alumspouse"><label for="membertype--alumspouse"><span class="questionnaire__main">My spouse is an <strong>alum</strong>.</span></label>
					</div>
					<div class="membership__option">
						<input type="radio" name="spousetype" id="membertype--retireespouse"><label for="membertype--retireespouse"><span class="questionnaire__main">My spouse is a <strong>retiree of U&#8209;M</strong>.</span></label>
					</div>
				</div>
			</div>
			<div class="membership__option">
				<input type="radio" name="membertype" id="membertype--childchoice" class="membership__option--additionals"><label for="membertype--childchoice"><span class="questionnaire__main">I&rsquo;m <strong>25 years old or younger</strong>.</span></label>
				<div class="membership__secondary">
					<div class="membership__option">
						<input type="radio" name="childtype" id="membertype--child"><label for="membertype--child"><span class="questionnaire__main">I&rsquo;m <strong>17 years old or younger and my parent is a member</strong>.</span></label>
					</div>
					<div class="membership__option">
						<input type="radio" name="childtype" id="membertype--youngadultmember"><label for="membertype--youngadultmember"><span class="questionnaire__main">I&rsquo;m between the ages of <strong>18 and 25 and my parent is a member</strong>.</span></label>
					</div>
					<div class="membership__option">
						<input type="radio" name="childtype" id="membertype--youngadult"><label for="membertype--youngadult"><span class="questionnaire__main">I&rsquo;m between the ages of <strong>18 and 25 and my parent is not a member</strong>.</span></label>
					</div>
				</div>
			</div>
			<div class="membership__option">
				<input type="radio" name="membertype" id="membertype--guest"><label for="membertype--guest"><span class="questionnaire__main">I&rsquo;m a <strong>guest</strong> of a Rec Sports member.</span></label>
			</div>
		</div>
	</div>
	<div class="membership__conclusion paragraph--copy">
		<h2 class="membership__identity">Your membership type couldn&rsquo;t be identified.</h2>

		<? // Eligibility descriptions. ?>
		<div class="membership__eligibility ada--hidden membertype--enrolledstudent">
			<p>Enrolled students are U&#8209;M&ndash;Ann Arbor students who are taking one credit hour or more during the current semester.</p>
		</div>

		<div class="membership__eligibility ada--hidden membertype--unenrolledstudent">
			<p>Unenrolled students are:</p>
			<ul>
				<li>U&#8209;M&ndash;Ann Arbor students who are taking no classes during the current semester</li>
				<li>Students enrolled on the Flint or Dearborn campuses</li>
				<li>Recent graduates (during the semester immediately following their graduation)</li>
			</ul>
		</div>

		<div class="membership__eligibility ada--hidden membertype--staff">
			<p>A member of faculty and staff is a permanent U&#8209;M employee from any campus, part- or full-time. (Temporary employees are considered <a href="#" class="membership__switch" data-membertype="friend">friends of the University</a>.)</p>
			<p>Affiliates of the University are:</p>
			<ul>
				<li>Visiting scholars</li>
				<li>Post-doctoral students</li>
				<li>Academic affiliates</li>
				<li>Research fellows</li>
				<li>U&#8209;M contractors</li>
				<li>U&#8209;M volunteers</li>
			</ul>
		</div>

		<div class="membership__eligibility ada--hidden membertype--retiree"></div>

		<div class="membership__eligibility ada--hidden membertype--alum">
			<p>An alum is one of the following:</p>
			<ul>
				<li>Any person registered with the Alumni Records Office as having received a U&#8209;M diploma</li>
				<li>Any current member of the U&#8209;M Alumni Association</li>
			</ul>
		</div>

		<div class="membership__eligibility ada--hidden membertype--friend">
			<p>A &ldquo;friend&rdquo; is a friend of a U&#8209;M student, employee, affiliate, retiree or alum. &ldquo;Friend&rdquo; status also applies to:</p>
			<ul>
				<li>Temporary employees</li>
				<li>Job candidates</li>
				<li>Guest speakers</li>
				<li>Prospective students and their families</li>
				<li>U&#8209;M conference attendees</li>
				<li>Summer camp participants</li>
			</ul>
		</div>

		<div class="membership__eligibility ada--hidden membertype--studentspouse"></div>

		<div class="membership__eligibility ada--hidden membertype--staffspouse"></div>

		<div class="membership__eligibility ada--hidden membertype--alumspouse"></div>

		<div class="membership__eligibility ada--hidden membertype--retireespouse"></div>

		<div class="membership__eligibility ada--hidden membertype--child">
			<p>A child is someone 25 years old or younger and whose parent is a Recreational Sports member&mdash;one membership for each child.</p>
		</div>

		<div class="membership__eligibility ada--hidden membertype--youngadultmember"></div>

		<div class="membership__eligibility ada--hidden membertype--youngadult"></div>

		<div class="membership__eligibility ada--hidden membertype--guest">
			<p>A guest is anyone sponsored by a U&#8209;M student, employee, or Recreational Sports member (maximum of four guests per sponsor).</p>
		</div>

		<? // End eligibility descriptions. ?>
		<p class="membership__identityreason">Not you? <a href="#" class="membership__reidentify js--ignorejump">Start over and try again.</a></p>

		<h2>Rates</h2>
		<div class="membership__rates ada--hidden membertype--enrolledstudent">
			<p><strong>Free!</strong> Part of your tuition goes toward facility access.</p>
		</div>

		<div class="membership__rates ada--hidden membertype--unenrolledstudent membertype--studentspouse">
			<div class="table__shrinkwrap">
				<table class="table__table--leftheader">
					<tbody>
						<tr>
							<td>1 month</td>
							<td>$40</td>
						</tr>
						<tr>
							<td>4 months</td>
							<td>$120</td>
						</tr>
						<tr>
							<td>12 months</td>
							<td>$240</td>
						</tr>
					</tbody>
				</table>
			</div>

			<p>Students can also purchase semester memberships.</p>
			<div class="table__shrinkwrap">
				<table class="table__table--leftheader">
					<tbody>
						<tr>
							<td>Spring semester</td>
							<td>$60</td>
						</tr>
						<tr>
							<td>Summer semester</td>
							<td>$60</td>
						</tr>
						<tr>
							<td>Spring and Summer semesters</td>
							<td>$120</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="membership__rates ada--hidden membertype--child membertype--youngadultmember">
			<div class="table__shrinkwrap">
				<table class="table__table--leftheader">
					<tbody>
						<tr>
							<td>1 month</td>
							<td>$40</td>
						</tr>
						<tr>
							<td>4 months</td>
							<td>$120</td>
						</tr>
						<tr>
							<td>12 months</td>
							<td>$240</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="membership__rates ada--hidden membertype--staff membertype--staffspouse">
			<div class="table__shrinkwrap">
				<table class="table__table--leftheader">
					<tbody>
						<tr>
							<td>1 month</td>
							<td>$60</td>
						</tr>
						<tr>
							<td>4 months</td>
							<td>$180</td>
						</tr>
						<tr>
							<td>12 months</td>
							<td>$360</td>
						</tr>
						<tr>
							<td>Perpetual</td>
							<td>$30/month</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="membership__rates ada--hidden membertype--retiree membertype--retireespouse">
			<div class="table__shrinkwrap">
				<table class="table__table--leftheader">
					<tbody>
						<tr>
							<td>1 month</td>
							<td>$30</td>
						</tr>
						<tr>
							<td>12 months</td>
							<td>$180</td>
						</tr>
						<tr>
							<td>Perpetual</td>
							<td>$15/month</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="membership__rates ada--hidden membertype--alum membertype--alumspouse membertype--friend">
			<div class="table__shrinkwrap">
				<table class="table__table--leftheader">
					<tbody>
						<tr>
							<td>1 month</td>
							<td>$70</td>
						</tr>
						<tr>
							<td>12 months</td>
							<td>$420</td>
						</tr>
						<tr>
							<td>Perpetual</td>
							<td>$35/month</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="membership__rates ada--hidden membertype--youngadult">
			<div class="table__shrinkwrap">
				<table class="table__table--leftheader">
					<tbody>
						<tr>
							<td>1 month</td>
							<td>$70</td>
						</tr>
						<tr>
							<td>12 months</td>
							<td>$420</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="membership__rates ada--hidden membertype--guest">
			<p>$13 for an all-day pass, good for the entire day on which it&rsquo;s purchased. Includes the use of a locker.</p>
		</div>

		<h2>Bring This to Apply</h2>
		<div class="membership__documents ada--hidden membertype--enrolledstudent">
			<p>There&rsquo;s no need to apply. Just show your U&#8209;M ID to the Welcome Center attendant.</p>
		</div>

		<div class="membership__documents ada--hidden membertype--unenrolledstudent membertype--staff">
			<p>Just bring your U&#8209;M ID.</p>
		</div>

		<div class="membership__documents ada--hidden membertype--retiree">
			<ul>
				<li>Photo ID</li>
				<li>
					Proof of your retiree status, like one of the following:
					<ul>
						<li>Your U&#8209;M ID with retiree affiliation</li>
						<li>A document showing your retiree status</li>
					</ul>
				</li>
			</ul>
		</div>

		<div class="membership__documents ada--hidden membertype--alum">
			<ul>
				<li>Photo ID</li>
				<li>
					Proof of your alum status, like one of the following:
					<ul>
						<li>Your U&#8209;M ID with alumni affiliation</li>
						<li>Your U&#8209;M Alumni Association membership card</li>
						<li>A copy of your degree or transcript</li>
					</ul>
				</li>
			</ul>
		</div>

		<div class="membership__documents ada--hidden membertype--friend">
			<ul>
				<li>Your photo ID</li>
				<li>
					Proof of your friend&rsquo;s sponsorship, like one of the following:
					<ul>
						<li>Your U&#8209;M friend with their U&#8209;M ID, in person</li>
						<li>A letter of introduction from your U&#8209;M friend and a copy of their U&#8209;M ID</li>
					</ul>
				</li>
			</ul>
		</div>

		<div class="membership__documents ada--hidden membertype--studentspouse membertype--staffspouse membertype--alumspouse membertype--retireespouse">
			<ul>
				<li>Your photo ID</li>
				<li>
					Proof of eligibility of your spouse or partner, like one of the following:
					<ul>
						<li>Your spouse or partner with their U&#8209;M ID, in person</li>
						<li>A letter of introduction from your spouse or partner and a copy of their U&#8209;M ID</li>
					</ul>
				</li>
				<li>Proof of marriage or partner status, like one of the following:</li>
					<ul>
						<li>Marriage certificate</li>
						<li>Your two driver&rsquo;s licenses</li>
						<li>Your two state IDs showing the same home address</li>
						<li>Joint tax return</li>
						<li>Utility bill with both names</li>
						<li>Documentation showing that one is carrying the other for benefits coverage</li>
					</ul>
				</li>
			</ul>
		</div>

		<div class="membership__documents ada--hidden membertype--child">
			<p>Parents with an active Recreational Sports membership may apply on behalf of their children. Bring your U&#8209;M ID.</p>
		</div>

		<div class="membership__documents ada--hidden membertype--youngadult membertype--youngadultmember">
			<ul>
				<li>Your photo ID</li>
				<li>
					Proof of parent&rsquo;s U&#8209;M affiliation, like one of the following:
					<ul>
						<li>Parent with their U&#8209;M ID, in person</li>
						<li>A letter of introduction from the parent and a copy of their U&#8209;M ID</li>
					</ul>
				</li>
				<li>Proof of your dependent status, like one of the following:</li>
					<ul>
						<li>Birth certificate</li>
						<li>Adoption paperwork</li>
						<li>Tax documentation</li>
						<li>Your two state IDs showing the same address</li>
						<li>Documentation showing that the U&#8209;M affiliated parent or the parent&rsquo;s spouse or partner is carrying you as a dependent for benefits coverage</li>
						<li>Health insurance documentation listing you as a dependent</li>
					</ul>
				</li>
			</ul>
		</div>

		<div class="membership__documents ada--hidden membertype--guest">
			<ul>
				<li>Your photo ID</li>
				<li>
					Proof of sponsorship, like one of the following:
					<ul>
						<li>Your U&#8209;M sponsor with their U&#8209;M ID, in person</li>
						<li>A letter of introduction from your U&#8209;M sponsor and a copy of their U&#8209;M ID</li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</div>
