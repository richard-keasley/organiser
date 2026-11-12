<?php $this->extend('layout');

$this->section('main'); ?>
<div class="row">

<section class="col-md-auto" style="max-width:20em"><div class="sticky-top">

<div id="reminders-container" class="my-1"><?php
echo $this->include('events/reminders');
?></div>

<div class="input-group my-1">
<input type="text" class="form-control" placeholder="search" onKeyup="apilib.showfilter(this, '#house-list li');">
<span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
</div>

<?php echo form_open(); ?>
<div class="row my-1">
<div class="col-auto"><?php
$attrs = [
	'class' => "form-control",
	'placeholder' => "new house",
];
echo new \App\Cells\input('newhouse', '', $attrs);
?></div>
<div class="col-auto"><?php
$attrs = [
	'class' => "btn btn-sm btn-success",
	'title' => "add new house",
	'type' => "submit"
];
$label = '<i class="bi bi-plus"></i>';
echo form_button($attrs, $label);
?></div>
</div>
<?php echo form_close(); ?>

</div></section>

<section class="col-md-auto" style="max-width:20em;">

<div>
<ul id="house-list" class="nav flex-column overflow-hidden"><?php
$format = '<li class="nav-item text-nowrap">%s</li>';
$attrs = ['class' => "nav-link"];
foreach($houses as $house) {
	$href = "view/{$house->HouseID}";
	$label = (string) ($house->HouseTitle ?? $house->HouseID);
	printf($format, anchor($href, $label, $attrs));
}
?></ul>
</div>
</section>

</div>
<?php $this->endSection();

if(ENVIRONMENT!='production') {
$this->section('footer'); ?>
<div class="row">
	<div class="col-6">Page: {elapsed_time} sec, {memory_usage} MB</div>
	<div class="col-6">Environment: <?php echo ENVIRONMENT; ?></div>
</div>
<?php $this->endSection();
}
