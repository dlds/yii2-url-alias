<?php

namespace dlds\urlias\helpers;

use Yii;

use yii\helpers\ArrayHelper;
/**
 * This is the helper class for Layout.
 */
class UrlAliasHelper {

    /**
     * TODO: refractor this helper !!!
     * Retrieves value of given attr name from given data array
     * @param string $attr given attr name
     * @param array $models models to be loaded
     * @param type $data
     */
    public static function getAttrAlias($attr, $model, $models, $index)
    {
        if ($model->loadMultiple($models, \Yii::$app->request->post()))
        {
            $interpretation = ArrayHelper::getValue($models, $index, false);

            if ($interpretation)
            {
                return $interpretation->{$attr};
            }
        }
    }
}