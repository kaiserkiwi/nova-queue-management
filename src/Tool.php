<?php

namespace Kaiserkiwi\NovaQueueManagement;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
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
		$jobs = config('nova-queue-management.resources.job', Resources\Job::class);
		$failedJobs = config('nova-queue-management.resources.failed_job', Resources\FailedJob::class);
		$jobBatches = config('nova-queue-management.resources.job_batches', Resources\JobBatch::class);

		$jobs::$model = config('nova-queue-management.models.job', Models\Job::class);
		$failedJobs::$model = config('nova-queue-management.models.failed_job', Models\FailedJob::class);
		$jobBatches::$model = config('nova-queue-management.models.job_batches', Models\JobBatch::class);

		$resources = [
			$jobs,
			$failedJobs,
		];

		if (Schema::hasTable(config('nova-queue-management.tables.job_batches', 'job_batches'))) {
			$resources[] = $jobBatches;
		}

		Nova::resources($resources);
	}
}
