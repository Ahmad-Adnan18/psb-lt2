<?php
// FILE: app/Providers/AuthServiceProvider.php
// GANTI SELURUH ISI FILE INI DENGAN KODE DI BAWAH.

namespace App\Providers;
use App\Events\StatusSantriDiperbarui; // <-- Import Event
use App\Listeners\KirimNotifikasiWhatsApp; // <-- Import Listener
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        // -->> TAMBAHKAN BARIS INI <<--
        StatusSantriDiperbarui::class => [
            KirimNotifikasiWhatsApp::class,
        ],
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Pastikan Anda memiliki kode ini di dalam method boot()
        // Ini adalah "jembatan" yang mendefinisikan apa itu 'admin'
        Gate::define('admin', function (User $user) {
            return $user->role === 'admin';
        });
    }
}
