<?php

namespace App\Support;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\Setting;

class MenuBuilder
{
    public static function forUser(): array
    {
        $user = Auth::user();
        if (! $user) {
            return [];
        }

        $menus = config('menu', []);
        $result = [];

        foreach ($menus as $menu) {
            $filtered = self::filterMenu($menu, $user);
            if ($filtered !== null) {
                $result[] = $filtered;
            }
        }

        return $result;
    }

    private static function filterMenu(array $menu, $user): ?array
    {
        // 1. Cek permission
        if (! empty($menu['permission']) && ! $user->can($menu['permission'])) {
            return null;
        }

        // 2. Cek role
        if (! empty($menu['role']) && ! $user->hasAnyRole((array) $menu['role'])) {
            return null;
        }

        // 3. CEK FEATURE TOGGLE (Saklar Fitur)
        if (! empty($menu['feature']) && Setting::get($menu['feature'], '1') == '0') {
            return null;
        }

        // 4. Cek ketersediaan route
        if (! empty($menu['route'])) {
            if (! Route::has($menu['route'])) {
                return null;
            }
            $menu['url'] = route($menu['route']);
        }

        // 5. Proses children
        if (! empty($menu['children'])) {
            $children = [];
            foreach ($menu['children'] as $child) {
                $filtered = self::filterMenu($child, $user);
                if ($filtered !== null) {
                    $children[] = $filtered;
                }
            }
            if (empty($children)) {
                return null;
            }
            $menu['children'] = $children;
        }

        return $menu;
    }

    public static function isActive(array $menu): bool
    {
        if (! empty($menu['route']) && request()->routeIs($menu['route'])) {
            return true;
        }
        if (! empty($menu['children'])) {
            foreach ($menu['children'] as $child) {
                if (self::isActive($child)) {
                    return true;
                }
            }
        }
        return false;
    }
}