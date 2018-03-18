<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace egor260890\gii\generators\repository;

use Yii;
use yii\gii\CodeFile;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * This generator will generate a controller and one or a few action view files.
 *
 * @property array $actionIDs An array of action IDs entered by the user. This property is read-only.
 * @property string $controllerFile The controller class file path. This property is read-only.
 * @property string $controllerID The controller ID. This property is read-only.
 * @property string $controllerNamespace The namespace of the controller class. This property is read-only.
 * @property string $controllerSubPath The controller sub path. This property is read-only.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Generator extends \yii\gii\Generator
{
    public $modelClass;
    /**
     * @var string the controller class name
     */
  //  public $controllerClass;

    public $repositoryClass;


    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Repository Generator';
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'This generator helps you to quickly generate a new repository class.';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['repositoryClass','modelClass'], 'filter', 'filter' => 'trim'],
            [['repositoryClass','modelClass'], 'required'],
            ['repositoryClass', 'match', 'pattern' => '/^[\w\\\\]*Repository$/', 'message' => 'Only word characters and backslashes are allowed, and the class name must end with "Controller".'],
            ['repositoryClass', 'validateNewClass'],
        ]);
    }



    /**
     * @inheritdoc
     */
  //  public function requiredTemplates()
  //  {
  //      return [
  //          'controller.php',
  //          'view.php',
  //      ];
  //  }

    /**
     * @inheritdoc
     */
  // public function stickyAttributes()
  // {
  //     return ['baseClass'];
  // }

    /**
     * @inheritdoc
     */
    public function hints()
    {
        return [
            'modelClass' => 'Model class',
            'repositoryPath' => 'Repository path',
        ];
    }

    /**
     * @inheritdoc
     */
    public function successMessage()
    {
        return 'The repository has been generated successfully.' . $this->getLinkToTry();
    }

    /**
     * This method returns a link to try controller generated
     * @see https://github.com/yiisoft/yii2-gii/issues/182
     * @return string
     * @since 2.0.6
     */
    private function getLinkToTry()
    {
        if (strpos($this->repositoryNamespace, Yii::$app->controllerNamespace) !== 0) {
            return '';
        }
//переписать
        $actions = $this->getActionIDs();
        if (in_array('index', $actions, true)) {
            $route = $this->getControllerSubPath() . $this->getControllerID() . '/index';
        } else {
            $route = $this->getControllerSubPath() . $this->getControllerID() . '/' . reset($actions);
        }
        return ' You may ' . Html::a('try it now', Yii::$app->getUrlManager()->createUrl($route), ['target' => '_blank', 'rel' => 'noopener noreferrer']) . '.';
    }

    /**
     * @inheritdoc
     */
    public function generate()
    {
        $files = [];

        $files[] = new CodeFile(
            $this->getRepositoryFile(),
            $this->render('repository.php')
        );


        return $files;
    }


    /**
     * @return string the controller class file path
     */
    public function getRepositoryFile()
    {
        return Yii::getAlias('@' . str_replace('\\', '/', $this->repositoryClass)) . '.php';
    }

    /**
     * @return string the controller ID
     */
    public function getControllerID()
    {
        $name = StringHelper::basename($this->controllerClass);
        return Inflector::camel2id(substr($name, 0, strlen($name) - 10));
    }



    /**
     * @return string the namespace of the controller class
     */
    public function getRepositoryNamespace()
    {
       // return $this->repositoryPath;
        $name = StringHelper::basename($this->repositoryClass);
        return ltrim(substr($this->repositoryClass, 0, - (strlen($name) + 1)), '\\');
    }
}
