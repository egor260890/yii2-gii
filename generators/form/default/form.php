<?php

use yii\helpers\StringHelper;
use yii\helpers\Inflector;
/**
 * This is the template for generating the model class of a specified table.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\model\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $queryClassName string query class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $properties array list of properties (property => [type, name. comment]) */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */

echo "<?php\n";
?>
<?php
$entitity_varname='$'.strtolower($entityName);
?>

namespace <?= $generator->getFormNamespace() ?>;

use Yii;
use yii\base\Model;
use <?=$generator->modelClass?>;

/**
* This is the form class for model "<?= $generator->modelClass ?>".
*
*/
class <?=StringHelper::basename($className)?> extends <?= '\\' . ltrim($generator->baseClass, '\\') . "\n" ?>
{

<?php foreach ($properties as $property => $data): ?><?php if($property=='id'){continue;}?>
    private $<?=$property?>;
<?php endforeach; ?>

    public function __construct(<?=$entityName?> <?=$entitity_varname?>=null ,array $config = []){
        if(<?=$entitity_varname?>){
<?php foreach ($properties as $property => $data):?><?php if($property=='id'){continue;}?>
            $this-><?=$property?>=<?=$entitity_varname?>-><?=$property?>;
<?php endforeach;?>
        }
        return parent::__construct();
    }

    public function rules()
    {
        return [<?= empty($rules) ? '' : ("\n            " . implode(",\n            ", $rules) . ",\n        ") ?>];
    }

    public function attributeLabels()
    {
        return [
<?php foreach ($labels as $name => $label): ?>
        <?= "'$name' => " . $generator->generateString($label) . ",\n" ?>
<?php endforeach; ?>
        ];
    }

<?php foreach ($properties as $property => $data):?><?php if($property=='id'){continue;}?>
    public function get<?=ucfirst($property)?>(){
        return $this-><?=$property?>;
    }

    public function set<?=ucfirst($property)?>($<?=$property?>){
        $this-><?=$property?>=$<?=$property?>;
    }

<?php endforeach; ?>


}



