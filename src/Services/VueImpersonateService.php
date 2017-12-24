<?php

namespace OwenMelbz\VueImpersonate\Services;

use Exception;

class VueImpersonateService {

    protected $manager = null;

    public function __construct()
    {
        $this->manager = $manager = app('impersonate');
    }

    public function isImpersonating()
    {
        return $this->manager->isImpersonating();
    }

    public function getRoutes()
    {
        return collect([
            'take' => route('impersonate', '@userid'),
            'leave' => route('impersonate.leave'),
            'users' => $this->getUsersRoute(),
        ]);
    }

    public function getUsersRoute()
    {
        if (config('vue_impersonate.custom_route')) {
            return asset(
                config('vue_impersonate.custom_route')
            );
        }

        return route('impersonate.users');
    }

    public function getImpersonatable()
    {
        $model = config('auth.providers.' . config('vue_impersonate.provider') . '.model');
        $displayField = config('vue_impersonate.display_name_field');

        return $model::all()->filter(function ($user) {
            return $user->canBeImpersonated();
        })
        ->transform(function ($user) use ($displayField) {
            return [
                'id' => $user->getKey(),
                'display_name' => $user->{$displayField}
            ];
        });
    }

    public function render()
    {
        if (optional(request()->user())->canImpersonate()) {
            return view('vue_impersonate::vue-impersonate')
            ->with('is_impersonating', $this->isImpersonating())
            ->with('routes', $this->getRoutes())
            ->with('component_name', config('vue_impersonate.custom_directive'));
        }
    }

}