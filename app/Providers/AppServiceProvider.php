<?php

namespace App\Providers;

use App\Models\MenuItem;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Event;
use Illuminate\Mail\Events\MessageSending;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('website_settings', function () {
            return Cache::rememberForever('core_site_settings', function () {
                return (object) Setting::pluck('value', 'key')->toArray();
            });
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production' || str_starts_with(config('app.url'), 'https://')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Dynamically override mail configuration from settings if stored in DB
        try {
            $mailHost = getSetting('mail_host');
            if ($mailHost) {
                $mailPort = (int) getSetting('mail_port', 587);
                $mailEnc = strtolower((string) getSetting('mail_encryption', 'tls'));
                $scheme = ($mailPort === 465 || $mailEnc === 'ssl') ? 'smtps' : null;

                config([
                    'mail.default' => getSetting('mail_driver', 'smtp'),
                    'mail.mailers.smtp.transport' => 'smtp',
                    'mail.mailers.smtp.host' => $mailHost,
                    'mail.mailers.smtp.port' => $mailPort,
                    'mail.mailers.smtp.encryption' => ($mailPort === 465 || $mailEnc === 'ssl') ? 'ssl' : 'tls',
                    'mail.mailers.smtp.scheme' => $scheme,
                    'mail.mailers.smtp.username' => getSetting('mail_username', 'resend'),
                    'mail.mailers.smtp.password' => getSetting('mail_password'),
                    'mail.mailers.smtp.timeout' => 30,
                    'mail.from.address' => getSetting('mail_from_address', config('mail.from.address')),
                    'mail.from.name' => getSetting('mail_from_name', config('mail.from.name')),
                ]);
            }
        } catch (\Throwable $e) {
            // DB not ready or running migrations
        }

        //Support for  older MySQL / MariaDB
        Schema::defaultStringLength(191);

        // Sharing variables only to the user layouts
        View::composer('templates.*.blades.layouts.user', function ($view) {
            // share unread messages to the layout
            $view->with('unread_notification_messages', Auth::check()
                ? Auth::user()->notificationMessages()->where('status', 'unread')->latest()->get()
                : collect());

            // share user menu items to the user layout
            $view->with('user_menu_items', Cache::remember('user_menu_items', 60 * 60, function () {
                return MenuItem::with('children')->where('type', 'user')
                    ->where('is_active', true)
                    ->where('parent_id', null)
                    ->orderBy('sort_order', 'asc')
                    ->get();
            }));
        });

        // Sharing variables only to the admin layout
        View::composer('templates.*.blades.admin.layouts.admin', function ($view) {
            // share admin menu items to the admin layout
            $view->with('admin_menu_items', Cache::remember('admin_menu_items', 60 * 60, function () {
                return MenuItem::with('children')->where('type', 'admin')
                    ->where('is_active', true)
                    ->where('parent_id', null)
                    ->orderBy('sort_order', 'asc')
                    ->get();
            }));
        });


        // Append date to outgoing emails

        // Register missing translation handler
        app('translator')->handleMissingKeysUsing(new \App\Listeners\LogMissingTranslation);

        if (config('site.append_date_to_emails') == 'enabled') {
            Event::listen(MessageSending::class, function (MessageSending $event) {
                $message = $event->message;

                $subject = $message->getSubject();

                if ($subject) {
                    $timestamp = now()->format('Y-m-d H:i:s');
                    $message->subject($subject . ' - ' . $timestamp);
                }
            });
        }

        //inject encrypted services for social login
        $services = ['google', 'github', 'facebook', 'twitter', 'linkedin', 'gitlab', 'bitbucket'];
        foreach ($services as $service) {

            $clientId = config("services.$service.client_id");
            $clientSecret = config("services.$service.client_secret");

            if ($clientId) {
                config()->set("services.$service.client_id", safeDecrypt($clientId));
            }

            if ($clientSecret) {
                config()->set("services.$service.client_secret", safeDecrypt($clientSecret));
            }
        }
    }
}
