<?php

namespace App\Helpers;

class RouteHelper
{
    private static function getRouteUrl($model, $locale, $routeName = 'home')
    {
        switch ($routeName) {
            case ( 'properties.edit' || 'get.property.by.slug' || 'extend.form' || 'paypal.form'):
                return route($routeName, [$locale, $model]);
            case 'get.property.by.slug':
                return route($routeName, [$locale, $model->slug]);
            default:
                return route($routeName, $locale);
        }
    }

    public static function getUrl($args = [])
    {
        $routeName = \Route::currentRouteName();
        $locale = $args['locale'] ?? 'en';
        $model = $args['property'] ?? null;

       if ($routeName == 'paypal.form') {
            $model = $args['credit'] ?? null;
        } else {
            $model = null;
        }
        return self::getRouteUrl($model, $locale, $routeName);
    }
}