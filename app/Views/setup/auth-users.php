<?php

$current_user = auth()->id();
$users = auth()->getProvider();
$user_list = $users
    ->withIdentities()
    ->findAll();
		
$tbody = []; $tr = [];
foreach($user_list as $user) {
	foreach(array_keys($user_fields) as $fldname) {
		if($fldname=='password') continue;
		
		$tr[$fldname] = [
			'data-fldname' => $fldname,
			'data' => $user->{$fldname}, 
		];
	}
			
	$icons = [];
	
	$icons[] = '<button class="btn btn-sm btn-primary" type="button" onClick="authedit.user.edit(this)"><i class="bi bi-pencil-square"></i></button>';
	
	if($user->id==$current_user) {
		$icons[] = '<span class="btn btn-sm"><i class="bi bi-person" title="this is you"></i></span>';
	}
	else {
		$icons[] = '<button class="btn btn-danger btn-sm" type="button" onClick="authedit.user.delete(this)"><i class="bi bi-trash"></i></button>';
	}
	$tr['_'] = implode('&nbsp;', $icons);
	$attrs = ['type'=>"hidden"];
	$tr['_'] .= new \App\Cells\input('id', $user->id, $attrs);
		
	$tbody[] = $tr;
}
	
if($tbody) {
	$table = new \App\Cells\Table('responsive');
	$table->autoHeading = false;
	echo $table->generate($tbody);
}
