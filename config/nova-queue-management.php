<?php

return [

	/**
	 * Used models.
	 */

	'models' => [
		'job' => \Kaiserkiwi\NovaQueueManagement\Models\Job::class,
		'failed_job' => \Kaiserkiwi\NovaQueueManagement\Models\FailedJob::class,
	],

	/**
	 * Resources used by Nova.
	 */

	'resources' => [
		'job' => \Kaiserkiwi\NovaQueueManagement\Resources\Job::class,
		'failed_job' => \Kaiserkiwi\NovaQueueManagement\Resources\FailedJob::class,
	],

	/**
	 * Names of database tables used by models.
	 */

	'tables' => [
		'jobs' => 'jobs',
		'failed_jobs' => 'failed_jobs',
	],

	/**
	 * The group name for the Nova navigation bar in which the package resources will be displayed.
	 */

	'navigation-group' => 'Queues',

	/**
	 * Overwrites the need of an action permission to retry a failed job.
	 */

	'overwrite_action_permission' => false,

	/**
	 * Allows or disallows the creation of new jobs and failed jobs within Laravel Nova.
	 */

	'can_create' => [
		'job' => false,
		'failed_job' => false,
	],

	/**
	 * Allows to show a badge with the number of jobs and failed jobs in the navigation bar.
	 */
	'show_count_badge' => [
		'job' => true,
		'failed_job' => true,
	],

	/**
	 * Sets the number of jobs and failed jobs to show the badge in the navigation bar, when a certain threshold is reached.
	 * 0 = Always show the badge.
	 */
	'count_badge_threshold' => [
		'job' => 0,
		'failed_job' => 0,
	],
];
