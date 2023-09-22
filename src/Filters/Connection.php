<?php

namespace Kaiserkiwi\NovaQueueManagement\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class Connection extends \Laravel\Nova\Filters\Filter
{
	/**
	 * Get the displayable name of the filter.
	 */
	public function name(): string
	{
		return __('Connection');
	}

	/**
	 * Apply the filter to the given query.
	 */
	public function apply(Request $request, $query, $value): Builder
	{
		return $query->where('connection', $value);
	}

	/**
	 * Get the filter's available options.
	 */
	public function options(Request $request): array
	{
		return config('nova-queue-management.models.failed_job')::orderBy('connection')
			->pluck('connection', 'connection')
			->toArray();
	}
}
