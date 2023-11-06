# Nova Queue Management for Laravel Nova 4

A simple management of your pending or failed jobs directly in Laravel Nova. With the ability to rerun failed jobs directly from the Nova interface.

**To use this package you need to the `database` queue driver.**

> This is the successor of the now abandoned [den1n/nova-queues](https://github.com/den1n/nova-queues) package. If you are using the old package, please remove it before installing this one. You definitely need this new package if you are using Laravel 10 or higher as the failed jobs model was incompatible with Laravel 10. This package also adds some new features and improvements. 

## Installation

Install package with Composer.

```sh
composer require kaiserkiwi/nova-queue-management
```

Publish package resources.

```sh
php artisan vendor:publish --provider=Kaiserkiwi\NovaQueueManagement\ServiceProvider
```

This will publish the following resources:

* Configuration file `config/nova-queue-management.php`.
* Translations `resources/lang/vendor/nova-queue-management`.
* Views `resources/views/vendor/nova-queue-management`.

Create database queue table if it's not exists.

```sh
php artisan queue:table
```

Migrate database.

```sh
php artisan migrate
```

Add instance of class `Kaiserkiwi\NovaQueueManagement\Tool` to your `App\Providers\NovaServiceProvider::tools()` method to display the jobs within your Nova resources.  
**If you come from den1n/nova-queues you have to remove the previous reference.**

```php
/**
 * Get the tools that should be listed in the Nova sidebar.
 *
 * @return array
 */
public function tools()
{
    return [
        new \Kaiserkiwi\NovaQueueManagement\Tool,
    ];
}
```

## Screenshots

### Jobs

![Jobs](https://raw.githubusercontent.com/kaiserkiwi/nova-queue-management/main/screens/jobs.png)

### Job Details

![Job Details](https://raw.githubusercontent.com/kaiserkiwi/nova-queue-management/main/screens/job-details.png)


### Failed Jobs

![Job Details](https://raw.githubusercontent.com/kaiserkiwi/nova-queue-management/main/screens/failed-jobs.png)

## Contributing

1. Fork it.
2. Create your feature branch: `git checkout -b my-new-feature`.
3. Commit your changes: `git commit -am 'Add some feature'`.
4. Push to the branch: `git push origin my-new-feature`.
5. Submit a pull request.

## Support

If you require any support open an issue on this repository.

## License

MIT
