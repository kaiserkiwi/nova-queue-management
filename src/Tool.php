<?php

namespace Kaiserkiwi\NovaQueueManagement;

use Illuminate\Http\Request;
use Laravel\Nova\Nova;

class Tool extends \Laravel\Nova\Tool
{
	public function menu(Request $request)
	{
		return [];
	}

	/**
	 * Perform any tasks that need to happen when the tool is booted.
	 */
	public function boot(): void
	{
		$jobs = config('nova-queue-management.resources.job');
		$failedJobs = config('nova-queue-management.resources.failed_job');
		$jobBatches = config('nova-queue-management.resources.job_batches');

		$jobs::$model = config('nova-queue-management.models.job');
		$failedJobs::$model = config('nova-queue-management.models.failed_job');
		$jobBatches::$model = config('nova-queue-management.models.job_batches');

		Nova::resources([
			$jobs,
			$failedJobs,
			$jobBatches,
		]);
	}
}
