<?php

namespace dlds\urlias\models;

class UrlRule extends \dlds\urlias\models\base\UrlRule
{
    const STATUS_ACTIVE  = 1;

    const STATUS_PASSIVE = 0;

    public static function getRoute($route, $params = array(), $status = self::STATUS_ACTIVE)
    {
        return self::find()->where(
            'route = :ROUTE AND params = :PARAMS AND status = :STATUS',
            [
                ':ROUTE'  => $route,
                ':PARAMS' => serialize($params),
                ':STATUS' => $status
            ]
        )->one();
    }

    public static function getRouteBySlug($slug, $status = self::STATUS_ACTIVE)
    {
        return self::find()->where(
            'slug = :SLUG AND status = :STATUS',
            [
                ':SLUG'  => $slug,
                ':STATUS' => $status
            ]
        )->one();
    }
}