<?php

namespace Kaiserkiwi\NovaQueueManagement\Resources;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class FailedJob extends Resource
{
	/**
	 * The model the resource corresponds to.
	 */
	public static $model = '';

	/**
	 * The columns that should be searched.
	 */
	public static $search = [
		'queue', 'payload',
	];

	/**
	 * Get the value that should be displayed to represent the resource.
	 */
	public function title(): string
	{
		return $this->displayName;
	}

	/**
	 * Get the search result subtitle for the resource.
	 */
	public function subtitle(): string
	{
		return implode(', ', [
			__('Connection') . ': ' . $this->connection,
			__('Queue') . ': ' . $this->queue,
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
			$request->get('orderBy') ?: 'failed_at',
			$request->get('orderByDirection') ?: 'desc'
		);
	}

	/**
	 * Show menu item with badge if enabled in config and the optional threshold is reached
	 */
	public function menu(Request $request)
	{
		if (config('nova-queue-management.show_count_badge.failed_job', true) && static::$model::count() >= config('nova-queue-management.count_badge_threshold.failed_job', 0)) {
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

			Text::make(__('Connection'), 'connection')
				->rules('required', 'string', 'max:255')
				->sortable(),

			Text::make(__('Queue'), 'queue')
				->rules('required', 'string', 'max:255')
				->sortable(),

			Text::make(__('Name'), 'displayName')
				->hideWhenCreating()
				->hideWhenUpdating(),

			Code::make(__('Payload'), 'payload')->json()
				->rules('required')
				->hideFromIndex(),

			Textarea::make(__('Exception'), 'exception')->rows(10)
				->rules('required')
				->hideFromIndex(),

			Text::make(__('Exception'), 'exception', function () {
				return substr($this->exception, 0, strpos($this->exception, ' in '));
			})
				->hideWhenCreating()
				->hideWhenUpdating()
				->hideFromDetail()
				->asHtml(),

			DateTime::make(__('Failed At'), 'failed_at')
				->rules('required')
				->sortable(),
		];
	}

	/**
	 * Get the displayable label of the resource.
	 */
	public static function label(): string
	{
		return __('Failed Jobs');
	}

	/**
	 * Get the displayable singular label of the resource.
	 */
	public static function singularLabel(): string
	{
		return __('Failed Job');
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
		return [
			new \Kaiserkiwi\NovaQueueManagement\Filters\Connection,
			new \Kaiserkiwi\NovaQueueManagement\Filters\Queue('failed_job'),
		];
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
		return [
			(new \Kaiserkiwi\NovaQueueManagement\Actions\Retry)->canRun(function ($request, $job) {
				return config('nova-queue-management.overwrite_action_permission', false) || $request->user()->can('create', $job);
			})->showOnTableRow(),
		];
	}

	/**
	 * Allow the creation of new failed jobs within Laravel Nova
	 *
	 * @param Request $request
	 * @return bool
	 */
	public static function authorizedToCreate(Request $request): bool
	{
		return config('nova-queue-management.can_create.failed_job', false);
	}
}
