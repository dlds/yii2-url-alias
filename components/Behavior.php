<?php

/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2014 Digital Deals s.r.o. 
 * @license http://www.digitaldeals/license/
 */

namespace dlds\urlias\components;

use yii\helpers\ArrayHelper;

/**
 * Behavior class which handles url alias create
 *
 * Settings example:
 * -----------------
 *  'BehaviorName' => [
 *      'class' => \dlds\urlias\components\Behavior::classname(),
 *      'rules' => [
 *          'title' => ['app-language/view', 'id'],
 *      ],
 *  ],
 *
 * @author Jiri Svoboda <jiri.svoboda@dlds.cz>
 */
class Behavior extends \yii\base\Behavior {

    /**
     * @var array config
     */
    public $rules;

    /**
     * Validates interpreter together with owner
     */
    public function events()
    {
        return [
            \yii\db\ActiveRecord::EVENT_AFTER_INSERT => 'handleAfterSave',
            \yii\db\ActiveRecord::EVENT_AFTER_UPDATE => 'handleAfterSave',
        ];
    }

    /**
     * Handles saving of interpretation
     * @param type $event
     */
    public function handleAfterSave()
    {
        $this->_setAliases();
    }

    /**
     * Sets aliases
     */
    public function _setAliases()
    {
        foreach ($this->rules as $attr => $config)
        {
            $this->_setAlias($this->owner->{$attr}, $config);
        }
    }

    public function _setAlias($slug, $config)
    {
        $route = $this->_getRoute($config);
        $params = $this->_getParams($config);
        
        $rule = \dlds\urlias\models\UrlRule::getRoute($route, $params);

        if (!$rule)
        {
            $rule = new \dlds\urlias\models\UrlRule();
            $rule->route = $route;
            $rule->params = serialize($params);
        }

        $rule->slug = $slug;

        $rule->redirect = 0;
        $rule->status = 1;

        if ($rule->save())
        {
            \dlds\urlias\components\BaseUrlRule::removeCache($route, $params);
        }
    }

    public function _getRoute($config)
    {
        return ArrayHelper::getValue($config, 0, false);
    }

    public function _getParams($config)
    {
        $params = [];

        $attrs = ArrayHelper::getValue($config, 1, false);

        if ($attrs)
        {
            foreach ($attrs as $attr)
            {
                if (isset($this->owner->{$attr}))
                {
                    $params[$attr] = $this->owner->{$attr};
                }
            }
        }

        return $params;
    }

}

?>
