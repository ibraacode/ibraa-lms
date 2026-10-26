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
        // ================= HEADER / KATEGORI =================
        // Jika menu tidak punya 'route' (null), maka ini adalah Judul Kategori
        if (array_key_exists('route', $menu) && $menu['route'] === null) {
            // Cek apakah user punya permission untuk kategori ini
            if (! empty($menu['permission']) && ! $user->can($menu['permission'])) {
                return null; // Sembunyikan kategori jika tidak punya akses
            }
            return [
                'label' => $menu['label'],
                'is_header' => true,
            ];
        }

        // ================= PERMISSION =================
        if (! empty($menu['permission']) && ! $user->can($menu['permission'])) {
            return null;
        }

        // ================= ROLE =================
        if (! empty($menu['role']) && ! $user->hasAnyRole((array) $menu['role'])) {
            return null;
        }

        // ================= FEATURE TOGGLE =================
        if (! empty($menu['feature']) && Setting::get($menu['feature'], '1') == '0') {
            return null;
        }

        // ================= CEK ROUTE =================
        if (! empty($menu['route'])) {
            if (! Route::has($menu['route'])) {
                return null;
            }
            $menu['url'] = route($menu['route']);
        }

        // ================= PROSES CHILDREN =================
        if (! empty($menu['children'])) {
            $children = [];
            foreach ($menu['children'] as $child) {
                $filtered = self::filterMenu($child, $user);
                if ($filtered !== null) {
                    $children[] = $filtered;
                }
            }
            if (empty($children)) {
                return null; // Sembunyikan parent jika semua anaknya tersembunyi
            }
            $menu['children'] = $children;
        }

        return $menu;
    }

    public static function isActive(array $menu): bool
    {
        if (isset($menu['route']) && request()->routeIs($menu['route'])) {
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