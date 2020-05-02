<?php namespace Config;

use CodeIgniter\Config\BaseConfig;

use App\Filters\BookFilter;
use App\Filters\StatsFilter;
use App\Filters\DownloadedFilter;

class Filters extends BaseConfig
{
	// Makes reading things below nicer,
	// and simpler to change out script that's used.
	public $aliases = [
            'csrf'              => \CodeIgniter\Filters\CSRF::class,
            'toolbar'           => \CodeIgniter\Filters\DebugToolbar::class,
            'honeypot'          => \CodeIgniter\Filters\Honeypot::class,
            'BookFilter'        => BookFilter::class,
            'StatsFilter'       => StatsFilter::class,
            'DownloadedFilter'  => DownloadedFilter::class
	];

	// Always applied before every request
	public $globals = [
		'before' => [
			//'honeypot'
			// 'csrf',
		],
		'after'  => [
			'toolbar',
			//'honeypot'
		],
	];

	// Works on all of a particular HTTP method
	// (GET, POST, etc) as BEFORE filters only
	//     like: 'post' => ['CSRF', 'throttle'],
	public $methods = [];

	// List filter aliases and any before/after uri patterns
	// that they should run on, like:
	//    'isLoggedIn' => ['before' => ['account/*', 'profiles/*']],
	public $filters = [
            'BookFilter'        => [
                'before'        => [ 'books', 'books/*' ]
            ],
            'StatsFilter'       => [
                'before'        => [ 'user', 'user/*', 'users' ]
            ],
            'DownloadedFilter'  => [
                'before'        => [ 'list_all', 'show' ]
            ]
        ];
}
