<?php

namespace App\Providers;

use App\Models\Announcement;
use App\Models\ClassSchedule;
use App\Models\QuarterlyGrade;
use App\Policies\AnnouncementPolicy;
use App\Policies\ClassSchedulePolicy;
use App\Policies\QuarterlyGradePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        ClassSchedule::class => ClassSchedulePolicy::class,
        Announcement::class => AnnouncementPolicy::class,
        QuarterlyGrade::class => QuarterlyGradePolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register policies
        Gate::policy(ClassSchedule::class, ClassSchedulePolicy::class);
        Gate::policy(Announcement::class, AnnouncementPolicy::class);
        Gate::policy(QuarterlyGrade::class, QuarterlyGradePolicy::class);
    }
}
