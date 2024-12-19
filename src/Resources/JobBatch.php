<?php

namespace Kaiserkiwi\NovaQueueManagement\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class JobBatch extends Resource
{
	/**
	 * The model the resource corresponds to.
	 */
	public static $model = '';

	/**
	 * The columns that should be searched.
	 */
	public static $search = [
		'name',
	];

	/**
	 * Get the value that should be displayed to represent the resource.
	 */
	public function title(): string
	{
		return $this->name;
	}

	/**
	 * Get the search result subtitle for the resource.
	 */
	public function subtitle(): string
	{
		return implode(', ', [
			__('Total Jobs') . ': ' . $this->total_jobs,
			__('Pending Jobs') . ': ' . $this->pending_jobs,
			__('Failed Jobs') . ': ' . $this->failed_jobs,
		]);
	}

	/**
	 * Get the logical group associated with the resource.
	 */
	public static function group()
	{
		return __(config('nova-queue-management.navigation-group', static::$group));
	}

	/**
	 * Build an "index" query for the given resource.
	 */
	public static function indexQuery(NovaRequest $request, $query)
	{
		return $query->reorder(
			$request->get('orderBy') ?: 'created_at',
			$request->get('orderByDirection') ?: 'desc'
		);
	}

	/**
	 * Show menu item with badge if enabled in config and the optional threshold is reached
	 */
	public function menu(Request $request)
	{
		if (config('nova-queue-management.show_count_badge.job_batches', true) && static::$model::count() >= config('nova-queue-management.count_badge_threshold.job_batches', 0)) {
			return parent::menu($request)->withBadge(fn() => static::$model::count());
		}

		return parent::menu($request);
	}

	/**
	 * Get the fields displayed by the resource.
	 */
	public function fields(Request $request): array
	{
		return [
			ID::make()->sortable(),

			Text::make(__('Name'), 'name')
				->rules('required', 'string', 'max:255')
				->sortable(),

			Number::make(__('Total Jobs'), 'total_jobs')
				->rules('required')
				->sortable(),

			Number::make(__('Pending Jobs'), 'pending_jobs')
				->rules('required')
				->sortable(),

			Number::make(__('Failed Jobs'), 'failed_jobs')
				->rules('required')
				->sortable(),

			Code::make(__('Failed Job IDs'), 'failed_job_ids')->json()
				->rules('required')
				->hideFromIndex(),

			Code::make(__('Options'), 'options')->json()
				->hideFromIndex(),

			DateTime::make(__('Cancelled At'), 'cancelled_at', function () {
				if ($this->cancelled_at) {
					return Carbon::createFromTimestamp($this->cancelled_at)
						->setTimezone(config('app.timezone'));
				} else
					return null;
			})
				->hideWhenCreating()
				->hideWhenUpdating()
				->sortable(),

			Number::make(__('Cancelled At'), 'cancelled_at')
				->hideFromDetail()
				->hideFromIndex(),

			DateTime::make(__('Created At'), 'created_at', function () {
				return Carbon::createFromTimestamp($this->created_at)
					->setTimezone(config('app.timezone'));
			})
				->hideWhenCreating()
				->hideWhenUpdating()
				->sortable(),

			Number::make(__('Created At'), 'created_at')
				->hideFromDetail()
				->hideFromIndex(),

			DateTime::make(__('Finished At'), 'finished_at', function () {
				if ($this->finished_at) {
					return Carbon::createFromTimestamp($this->finished_at)
						->setTimezone(config('app.timezone'));
				} else
					return null;
			})
				->hideWhenCreating()
				->hideWhenUpdating()
				->sortable(),

			Number::make(__('Finished At'), 'finished_at')
				->hideFromDetail()
				->hideFromIndex(),
		];
	}

	/**
	 * Get the displayable label of the resource.
	 */
	public static function label(): string
	{
		return __('Job Batches');
	}

	/**
	 * Get the displayable singular label of the resource.
	 */
	public static function singularLabel(): string
	{
		return __('Job Batch');
	}

	/**
	 * Get the cards available for the request.
	 */
	public function cards(Request $request): array
	{
		return [];
	}

	/**
	 * Get the filters available for the resource.
	 */
	public function filters(Request $request): array
	{
		return [];
	}

	/**
	 * Get the lenses available for the resource.
	 */
	public function lenses(Request $request): array
	{
		return [];
	}

	/**
	 * Get the actions available for the resource.
	 */
	public function actions(Request $request): array
	{
		return [];
	}

	/**
	 * Allow the creation of new failed jobs within Laravel Nova
	 *
	 * @param Request $request
	 * @return bool
	 */
	public static function authorizedToCreate(Request $request): bool
	{
		return config('nova-queue-management.can_create.job_batches', false);
	}
}
