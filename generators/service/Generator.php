<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace egor260890\gii\generators\service;

use Yii;
use yii\base\Model;
use yii\gii\CodeFile;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\db\ActiveRecord;

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
    public $db = 'db';


    public $modelClass;
    /**
     * @var string the controller class name
     */
  //  public $controllerClass;

    public $serviceClass='core\services\MyService';

    public $formClass;

    public $repositoryClass;



    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Service Generator';
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'This generator helps you to quickly generate a new service class.';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['serviceClass','modelClass','formClass','repositoryClass'], 'filter', 'filter' => 'trim'],
            [['serviceClass','modelClass','formClass','repositoryClass'], 'required'],
            ['serviceClass', 'match', 'pattern' => '/^[\w\\\\]*Service$/', 'message' => 'Only word characters and backslashes are allowed, and the class name must end with "Service".'],
            [['modelClass'], 'validateClass', 'params' => ['extends' => ActiveRecord::className()]],
            [['formClass'], 'validateClass', 'params' => ['extends' => Model::className()]],
            ['serviceClass', 'validateNewClass'],
            [['formClass','repositoryClass'], 'validateModelClass', 'skipOnEmpty' => false],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function hints()
    {
        return [
            'modelClass' => 'Model class',
            'repositoryPath' => 'Service path',
        ];
    }

    /**
     * @inheritdoc
     */
    public function successMessage()
    {
        return 'The service has been generated successfully.';
    }



    /**
     * @inheritdoc
     */
    public function generate()
    {
        $db = $this->getDbConnection();
        $tableSchema = $db->getTableSchema($this->modelClass::tableName());
        $files = [];
        $params = [
            'entityName'=>StringHelper::basename($this->modelClass),
            'properties' => $this->generateProperties($tableSchema),
        ];

        $files[] = new CodeFile(
            $this->getServiceFile(),
            $this->render('service.php',$params)
        );


        return $files;
    }


    /**
     * @return string the controller class file path
     */
    public function getServiceFile()
    {
        return Yii::getAlias('@' . str_replace('\\', '/', $this->serviceClass)) . '.php';
    }


    /**
     * @return string the namespace of the controller class
     */
    public function getServiceNamespace()
    {
       // return $this->repositoryPath;
        $name = StringHelper::basename($this->serviceClass);
        return ltrim(substr($this->serviceClass, 0, - (strlen($name) + 1)), '\\');
    }


    public function validateModelClass()
    {
        if ($this->isReservedKeyword($this->modelClass)) {
            $this->addError('modelClass', 'Class name cannot be a reserved PHP keyword.');
        }
        if ((empty($this->tableName) || substr_compare($this->tableName, '*', -1, 1)) && $this->modelClass == '') {
            $this->addError('modelClass', 'Model Class cannot be blank if table name does not end with asterisk.');
        }
    }

    protected function generateProperties($table)
    {
        $properties = [];
        foreach ($table->columns as $column) {
            $columnPhpType = $column->phpType;
            if ($columnPhpType === 'integer') {
                $type = 'int';
            } elseif ($columnPhpType === 'boolean') {
                $type = 'bool';
            } else {
                $type = $columnPhpType;
            }
            $properties[$column->name] = [
                'type' => $type,
                'name' => $column->name,
                'comment' => $column->comment,
            ];
        }

        return $properties;
    }

    protected function getDbConnection()
    {
        return Yii::$app->get($this->db, false);
    }

}
