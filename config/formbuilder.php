<?php

return [
	// IF THE INPUT JUST NEED FORM INPUT WITHOUT LABEL AND CONTAINER
	'useLabel' => true,
	'boldLabel' => true,

	// INFO TEXT UNDER INPUT FIELD
	'info' => null,

	// INFO TEXT TEMPLATE UNDER INPUT FIELD
	'infoTemplate' => '<span class="m-form__help"><<field>></span>',

	// FORM ALIGNMENT
	'formAlignment' => 'horizontal',

	// IF INPUT REQUIRED, THIS WILL BE SHOWN ON THE LABEL
	'requiredLabelText' => '<span class="status-decline">*</span>',

	// LABEL CONTAINER CLASS WHEN FORM ALIGNMENT IS VERTICAL
	'labelContainerClassVertical' => 'col-md-12',
	
	// INPUT CONTAINER CLASS WHEN FORM ALIGNMENT IS VERTICAL
	'inputContainerClassVertical' => 'col-md-12',

	// INPUT CONTAINER CLASS WHEN FORM ALIGNMENT IS HORIZONTAL
	'labelContainerClassHorizontal' => 'col-md-3',

	// INPUT CONTAINER CLASS WHEN FORM ALIGNMENT IS VERTICAL
	'inputContainerClassHorizontal' => 'col-md-9',

	'divContainerClass' => 'form-group mb-5',

	'formHasError' => 'has-danger',

	/*
		ADDONS CONFIG
		addonsConfig => [
			'text' => ''
			'position' => '' -> left or right
		]
	*/ 
	'addons' => [
		'text' => '',
		'position' => 'left',
		'class' => 'input-group-text'
	],

	'htmlOptions' => null,

	// INPUT PROPERTIES
	'elOptions' => [
		'class' => 'form-control form-control-solid',
	]
];