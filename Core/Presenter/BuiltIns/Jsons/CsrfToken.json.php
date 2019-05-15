<?php

use Core\Presenter\BuiltIns\Presenters\CsrfTokenPresenter;

/** @var CsrfTokenPresenter $jp */
return [
	$jp->tokenFormName() => $jp->token(),
];
