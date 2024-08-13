<?php

namespace Kaiserkiwi\NovaQueueManagement\Models;

class JobBatch extends \Illuminate\Database\Eloquent\Model
{
	protected $guarded = [
		'id',
	];

	protected $casts = [
		'failed_job_ids' => 'array',
	];

	public $timestamps = false;

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
	 * Set value of options attribute.
	 */
	public function setOptionsAttribute(string $options): void
	{
		$this->attributes['options'] = base64_encode($options);
	}

	/**
	 * Get value of options attribute.
	 */
	public function getOptionsAttribute(): string
	{
		return base64_decode($this->options);
	}
}
