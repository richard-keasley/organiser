<?php

$member = [];
foreach($house->groups as $group) $member[] = $group['GroupID'];

foreach(model('Groups')->orderBy('GrpDes')->findAll() as $group) { ?>
<div class="form-check">
<label class="form-check-label" ><?php
	$ingroup = in_array($group['GroupID'], $member);
	$attrs = [
		'onChange' => "houseedit.groups.update(this)",
	];
	echo new \App\Cells\input\checkbox($group['GroupID'], $ingroup, $attrs);
	echo $group['GrpDes'];
?></label>
</div>
<?php }
