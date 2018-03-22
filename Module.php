<?php

namespace egor260890\gii;
/**
 * Created by PhpStorm.
 * User: User
 * Date: 03.03.2018
 * Time: 18:09
 */
class Module extends \yii\gii\Module
{

    /**
     * Returns the list of the core code generator configurations.
     * @return array the list of the core code generator configurations.
     */
    protected function coreGenerators()
    {
        return [
            'model' => ['class' => 'yii\gii\generators\model\Generator'],
            'repository' => ['class' => 'egor260890\gii\generators\repository\Generator'],
            'entity' => ['class' => 'egor260890\gii\generators\entity\Generator'],
            'formFromEntity' => ['class' => 'egor260890\gii\generators\form\Generator'],
            'service' => ['class' => 'egor260890\gii\generators\service\Generator'],
            'crud' => ['class' => 'yii\gii\generators\crud\Generator'],
            'controller' => ['class' => 'yii\gii\generators\controller\Generator'],
            'form' => ['class' => 'yii\gii\generators\form\Generator'],
            'module' => ['class' => 'yii\gii\generators\module\Generator'],
            'extension' => ['class' => 'yii\gii\generators\extension\Generator'],
        ];
    }

}