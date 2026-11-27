<?php

namespace Kaiserkiwi\NovaQueueManagement\Models;

use Illuminate\Database\PostgresConnection;
use Illuminate\Support\Str;
use Throwable;

class JobBatch extends \Illuminate\Database\Eloquent\Model
{
	protected $keyType = 'string';

	protected $guarded = [
		'id',
	];

	protected $casts = [
		'failed_job_ids' => 'array',
	];

	public $timestamps = false;

	public $incrementing = false;

	/**
	 * The "booting" method of the model.
	 */
	protected static function boot()
	{
		parent::boot();

		static::saving(function (self $jobBatch) {
			$timestamp = now()->getTimeStamp();
			$jobBatch->created_at = $jobBatch->created_at ?: $timestamp;
		});
	}

	/**
	 * Get the table associated with the model.
	 */
	public function getTable(): string
	{
		return config('nova-queue-management.tables.job_batches', parent::getTable());
	}

	/**
	 * Get value of options attribute.
	 *
	 * Laravel's DatabaseBatchRepository stores the options with serialize() and
	 * base64-encodes them on PostgreSQL only. No class is instantiated while
	 * reading them: callbacks are returned as their class name.
	 *
	 * @return array
	 */
	public function getOptionsAttribute($value)
	{
		$serialized = (string) $value;

		if ($this->getConnection() instanceof PostgresConnection && ! Str::contains($serialized, [':', ';'])) {
			$serialized = base64_decode($serialized);
		}

		try {
			$options = unserialize($serialized, ['allowed_classes' => false]);
		} catch (Throwable) {
			return [];
		}

		return is_array($options) ? $this->withClassNames($options) : [];
	}

	/**
	 * Replace the objects in the options (serialized closures and invokable callbacks) by their class name.
	 */
	protected function withClassNames(array $options): array
	{
		return array_map(function ($value) {
			if ($value instanceof \__PHP_Incomplete_Class) {
				return ((array) $value)['__PHP_Incomplete_Class_Name'];
			}

			return is_array($value) ? $this->withClassNames($value) : $value;
		}, $options);
	}
}
