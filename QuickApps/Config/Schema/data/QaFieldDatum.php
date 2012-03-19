<?php
class QaFieldDatum {
	public $table = 'field_data';
	public $records = array(
		array(
			'id' => '1',
			'field_id' => '1',
			'foreignKey' => '1',
			'belongsTo' => 'Node',
			'data' => '<h3>Content Boxes</h3>
<p>
	[content_box type=success]Maecenas pellentesque cursus auctor.[/content_box]</p>
<p>
	[content_box type=error]Nam sagittis nisl non turpis aliquam mollis. Suspendisse ac metus nisi, sed vulputate arcu.[/content_box]</p>
<p>
	[content_box type=alert]Cras interdum leo quis arcu sagittis pulvinar. Curabitur suscipit vulputate erat eu rhoncus. Morbi facilisis mi in ligula ornare ultricies.[/content_box]</p>
<p>
	[content_box type=bubble]Fusce interdum cursus turpis vitae gravida. Aenean aliquet venenatis posuere. Etiam gravida ullamcorper purus.[/content_box]</p>
<hr />
<h3>
	Buttons</h3>
<p>
	Using buttons Hooktags, you can easily create a variety of buttons. These buttons all stem from a single tag, but vary in color and size (each of which are adjustable using color=&rdquo;&quot; and size=&rdquo;&quot; parameters).<br />
	Allowed parameters:</p>
<ol>
	<li>
		<strong>size:</strong> big, small</li>
	<li>
		<strong>color:</strong>
		<ul>
			<li>
				small: black, blue, green, lightblue, orange, pink, purple, red, silver, teal</li>
			<li>
				big: blue, green, orange, purple, red, turquoise</li>
		</ul>
	</li>
	<li>
		<strong>link:</strong> url of your button</li>
	<li>
		<strong>target:</strong> open link en new window (_blank), open in same window (_self or unset parameter)</li>
</ol>
<h4>
	&nbsp;</h4>
<p>
	&nbsp;</p>
<h4>
	Small Buttons</h4>
<table style="width: 478px; height: 25px;">
	<tbody>
		<tr>
			<td>
				[button color=black]Button text[/button]</td>
			<td>
				[button color=blue]Button text[/button]</td>
		</tr>
		<tr>
			<td>
				[button color=green]Button text[/button]</td>
			<td>
				[button color=lightblue]Button text[/button]</td>
		</tr>
		<tr>
			<td>
				[button color=orange]Button text[/button]</td>
			<td>
				[button color=pink]Button text[/button]</td>
		</tr>
		<tr>
			<td>
				[button color=purple]Button text[/button]</td>
			<td>
				[button color=red]Button text[/button]</td>
		</tr>
		<tr>
			<td>
				[button color=silver]Button text[/button]</td>
			<td>
				[button color=teal]Button text[/button]</td>
		</tr>
	</tbody>
</table>
<h4>
	&nbsp;</h4>
<p>
	&nbsp;</p>
<h4>
	Big Buttons</h4>
<table style="width: 478px; height: 25px;">
	<tbody>
		<tr>
			<td>
				[button color=blue size=big]Button text[/button]</td>
			<td>
				[button color=green size=big]Button text[/button]</td>
		</tr>
		<tr>
			<td>
				[button color=orange size=big]Button text[/button]</td>
			<td>
				[button color=purple size=big]Button text[/button]</td>
		</tr>
		<tr>
			<td>
				[button color=red size=big]Button text[/button]</td>
			<td>
				[button color=turquoise size=big]Button text[/button]</td>
		</tr>
	</tbody>
</table>
<p>
	&nbsp;</p>
'
		),
		array(
			'id' => '2',
			'field_id' => '1',
			'foreignKey' => '2',
			'belongsTo' => 'Node',
			'data' => '<p>
	<strong>QuickApps CMS</strong> is a free open source CMS released under GPL license, and inspired by most of the popular cms.<br />
	Powered by CakePHP v2 <strong>MVC framework</strong> makes this cms the perfect development platform. A robust application fully expandable and capable of manage virtually any website, from very simplistic websites, as well as very complex web sites.</p>'
		),
		array(
			'id' => '3',
			'field_id' => '2',
			'foreignKey' => '3',
			'belongsTo' => 'Node',
			'data' => 'Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum turpis mi, pulvinar ac placerat ut, luctus vel arcu. Cras ac vulputate sed.'
		),
		array(
			'id' => '4',
			'field_id' => '3',
			'foreignKey' => '3',
			'belongsTo' => 'Node',
			'data' => 'Integer in augue a neque mollis semper eget nec est. Donec eros justo, ornare non sollicitudin ut, viverra nec ligula. Cras quis nisl magna. Vivamus tortor est, lobortis sit amet vehicula sed, porta vitae risus. Quisque sit amet justo elit. Fusce in eros augue, sed gravida ligula. Integer ac sem neque. Nulla vitae neque a nibh ultricies vehicula vel a massa.

Quisque at ante sit amet metus auctor dignissim nec nec est. Nullam et lacus a diam viverra suscipit vitae ut neque. Suspendisse in lacus vel ipsum lacinia rutrum id eget ligula. Vestibulum vehicula elit vel nunc ultricies scelerisque sagittis mi consectetur. Maecenas bibendum augue ut urna sodales molestie! Quisque ultrices hendrerit ipsum, ac dictum mi porta eget. Integer fringilla suscipit nisl, id hendrerit elit fringilla sed! Curabitur quis elit vitae est vulputate adipiscing nec a risus. Curabitur euismod sodales risus non commodo?Integer tincidunt dolor a urna convallis interdum. Curabitur quis velit et ante convallis venenatis. 

Ut nec ipsum et arcu ultrices mattis? Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam nec est neque. Donec vitae interdum velit? Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis venenatis faucibus odio, sed lobortis enim euismod et. Fusce vel risus et mauris feugiat consectetur. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.Aenean condimentum feugiat lectus eget porttitor. 

Sed volutpat pretium felis, ac pulvinar sapien dapibus quis.'
		),
		array(
			'id' => '5',
			'field_id' => '4',
			'foreignKey' => '3',
			'belongsTo' => 'Node',
			'data' => '1'
		),
		array(
			'id' => '6',
			'field_id' => '5',
			'foreignKey' => '3',
			'belongsTo' => 'Node',
			'data' => '7,8,9'
		),
	);

}
