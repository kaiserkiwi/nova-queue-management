<?php

namespace Kaiserkiwi\NovaQueueManagement\Models;

class FailedJob extends \Illuminate\Database\Eloquent\Model
{
	protected $keyType = 'string';

	protected $guarded = [
		'id',
	];

	protected $appends = [
		'displayName',
		'maxTries',
		'delay',
	];

	protected $casts = [
		'payload' => 'array',
		'failed_at' => 'datetime',
	];

	public $timestamps = false;

	public $incrementing = false;

	/**
	 * The "booting" method of the model.
	 */
	protected static function boot()
	{
		parent::boot();

		static::saving(function (self $job) {
			$job->failed_at = $job->failed_at ?: now();
		});
	}

	/**
	 * Get the table associated with the model.
	 */
	public function getTable(): string
	{
		return config('nova-queue-management.tables.failed_jobs', parent::getTable());
	}

	/**
	 * Get value of displayName attribute.
	 */
	public function getDisplayNameAttribute(): string
	{
		return $this->payload['displayName'];
	}

	/**
	 * Get value of maxTries attribute.
	 */
	public function getMaxTriesAttribute(): int
	{
		return $this->payload['maxTries'] ?? 0;
	}

	/**
	 * Get value of delay attribute.
	 */
	public function getDelayAttribute(): int
	{
		return $this->payload['delay'] ?? 0;
	}
}
