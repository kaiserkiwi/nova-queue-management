<?php

namespace Kaiserkiwi\NovaQueueManagement;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
	/**
	 * Bootstrap any application services.
	 */
	public function boot(): void
	{
		$this->publishResources();
		$this->loadMigrations();
		$this->loadTranslations();
	}

	/**
	 * Load package migrations files.
	 */
	protected function loadMigrations(): void
	{
		$this->loadMigrationsFrom(__DIR__ . '/../migrations');
	}

	/**
	 *  Publish package resources.
	 */
	protected function publishResources(): void
	{
		$this->publishes([
			__DIR__ . '/../config/nova-queue-management.php' => config_path('nova-queue-management.php'),
		], 'config');

		$this->publishes([
			__DIR__ . '/../resources/lang' => lang_path('vendor/nova-queue-management'),
		], 'lang');
	}

	/**
	 *  Load package translation files.
	 */
	protected function loadTranslations(): void
	{
		$this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'nova-queue-management');
		$this->loadJSONTranslationsFrom(__DIR__ . '/../resources/lang');
		$this->loadJsonTranslationsFrom(resource_path('lang/vendor/nova-queue-management'));
	}

	/**
	 * Register any application services.
	 */
	public function register(): void
	{
		$this->mergeConfigFrom(__DIR__ . '/../config/nova-queue-management.php', 'nova-queue-management');
	}
}
