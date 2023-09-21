<?php

namespace Kaiserkiwi\NovaQueueManagement\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class Queue extends \Laravel\Nova\Filters\Filter
{
	/**
	 * Target model name.
	 */
	protected $modelName = '';

	/**
	 * Create instance of filter.
	 */
	public function __construct(string $modelName)
	{
		$this->modelName = $modelName;
	}

	/**
	 * Get the displayable name of the filter.
	 */
	public function name(): string
	{
		return __('Queue');
	}

	/**
	 * Apply the filter to the given query.
	 */
	public function apply(Request $request, $query, $value): Builder
	{
		return $query->where('queue', $value);
	}

	/**
	 * Get the filter's available options.
	 */
	public function options(Request $request): array
	{
		return config('nova-queue-management.models')[$this->modelName]::orderBy('queue')
			->pluck('queue', 'queue')
			->toArray();
	}
}
