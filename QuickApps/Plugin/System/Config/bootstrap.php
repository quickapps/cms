<?php
	Cache::config('__theme_css__',
		array(
			'engine' => 'File',
			'path' => TMP . 'cache' . DS . 'persistent' . DS,
			'duration' => '+10 Years'
		)
	);