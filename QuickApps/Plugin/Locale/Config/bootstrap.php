<?php
	Cache::config('i18n',
		array(
			'engine' => 'File',
			'path' => TMP . 'cache' . DS . 'i18n' . DS,
			'duration' => '+10 Years'
		)
	);