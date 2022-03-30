<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;
use App\Filters\AuthGuard;
use App\Filters\IsLoggedIn;
use App\Filters\IsAdmin;
use App\Filters\Reviewer;
use App\Filters\IsGroup;

class Filters extends BaseConfig
{
    /**
     * Configures aliases for Filter classes to
     * make reading things nicer and simpler.
     *
     * @var array
     */
    public $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'authguard' => AuthGuard::class,
		'isAdmin' => IsAdmin::class,
		'reviewer' => Reviewer::class,
		'isGroup' => IsGroup::class,
    ];

    /**
     * List of filter aliases that are always
     * applied before and after every request.
     *
     * @var array
     */
    public $globals = [
        'before' => [
            // 'honeypot',
            // 'csrf',
            // 'invalidchars',
        ],
        'after' => [
            'toolbar',
            // 'honeypot',
            // 'secureheaders',
        ],
    ];

    /**
     * List of filter aliases that works on a
     * particular HTTP method (GET, POST, etc.).
     *
     * Example:
     * 'post' => ['csrf', 'throttle']
     *
     * @var array
     */
    public $methods = [];

    /**
     * List of filter aliases that should run on any
     * before or after URI patterns.
     *
     * Example:
     * 'isLoggedIn' => ['before' => ['account/*', 'profiles/*']]
     *
     * @var array
     */
    /* old admin
     * 'audits','audit','audit/new','audit/*__/review','audit/reviewed', 'audit/expiring', 'audit/*__/edit', 'audit/*__/delete',
     * 'accounts','account/*',
     * 'users/', 'user/*', 'user/*__/delete',
     * 'comment-*'
     */
     
    public $filters = [
        'isAdmin' => ['before' => 
            	        ['submit-account-form',
            	        'profile','profile/*',
            	        'question','question/*','questions','questions/*','update-question',
            	        'chases','chases/*','refresh-stats',]
            	    ],
        'reviewer' => ['before' => 
                        ['audit/*/review','comment-*',]
    	               ],
	   'isGroup' => ['before' =>
                        ['audit/new','audit/reviewed', 'audit/expiring', 'audit/*/edit', 'audit/*/delete','accounts','account/*', 'update-account', 'users/', 'user/*', 'user/*/delete','signup',
                        'group/*','groups', 'update-group', 'submit-group-form',
                        ]
	                ],
	   'authguard' => ['before' => 
                        ['','audits','audit',]
	                   ],
        ];
}
