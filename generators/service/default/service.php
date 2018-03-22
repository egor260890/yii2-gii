<?php
/**
 * This is the template for generating a controller class file.
 */

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\controller\Generator */

echo "<?php\n";
?>
<?php
$entitity_varname='$'.strtolower($entityName);
$repository_var_name=lcfirst(StringHelper::basename($generator->repositoryClass))
?>


<?php
$modelname=strtolower(StringHelper::basename($generator->modelClass));
?>
namespace <?= $generator->getServiceNamespace() ?>;

use <?=$generator->modelClass?>;
use <?=$generator->formClass?>;
use <?=$generator->repositoryClass?>;
use core\managers\TransactionManager;

class <?= StringHelper::basename($generator->serviceClass) ?>
{

    private $<?=$repository_var_name?>;
    private $transaction;

    public function __construct(<?=StringHelper::basename($generator->repositoryClass)?> $<?=$repository_var_name?>,TransactionManager $transaction)
    {
        $this->transaction=$transaction;
        $this-><?=$repository_var_name?>=$<?=$repository_var_name?>;
    }

    public function create(<?=StringHelper::basename($generator->formClass)?> $form):<?=$entityName."\n"?>
    {
        <?=$entitity_varname?>=<?=$entityName?>::create(
<?php $count=0; foreach ($properties as $property => $data):?><?php $count++;if($property=='id'){continue;}?>
            $form->get<?=ucfirst($property)?>()<?php if ($count<count($properties)): ?><?=','?><?php endif;?><?="\n"?>
<?php endforeach;?>
        );
        $this-><?=$repository_var_name?>->save(<?=$entitity_varname?>);
        return <?=$entitity_varname?>;
    }

    public function edit(<?=StringHelper::basename($generator->formClass)?> $form,$id)
    {
        <?=$entitity_varname?>=$this-><?=$repository_var_name?>->get($id);
        <?=$entitity_varname?>->edit(
<?php $count=0; foreach ($properties as $property => $data):?><?php $count++;if($property=='id'){continue;}?>
            $form->get<?=ucfirst($property)?>()<?php if ($count<count($properties)): ?><?=','?><?php endif;?><?="\n"?>
<?php endforeach;?>
        );
        $this-><?=$repository_var_name?>->save(<?=$entitity_varname?>);
    }

    public function remove($id){
        <?=$entitity_varname?>=$this-><?=$repository_var_name?>->get($id);
        $this-><?=$repository_var_name?>->remove(<?=$entitity_varname?>);
    }

    public function removeMultiple(Array $keys){
        $this->transaction->wrap(function()use($keys){
            foreach ($keys as $id){
                <?=$entitity_varname?>=$this-><?=$repository_var_name?>->get($id);
                $this-><?=$repository_var_name?>->remove(<?=$entitity_varname?>);
            }
        });
    }

    public function activate($id)
    {
        <?=$entitity_varname?> = $this-><?=$repository_var_name?>->get($id);
        <?=$entitity_varname?>->activate();
        $this-><?=$repository_var_name?>->save(<?=$entitity_varname?>);
    }

    public function draft($id)
    {
        <?=$entitity_varname?> = $this-><?=$repository_var_name?>->get($id);
        <?=$entitity_varname?>->draft();
        $this-><?=$repository_var_name?>->save($employee);
    }


    public function activateMultiple(Array $keys){
        $this->transaction->wrap(function ()use($keys){
            foreach ($keys as $id){
                $this->activate($id);
            }
        });
    }

    public function draftMultiple(Array $key){
        $this->transaction->wrap(function () use ($key){
            foreach ($key as $id){
                $this->draft($id);
            }
        });
    }


    public function move($id,$action){
        <?=$entitity_varname?>=$this->findModel($id);
        <?=$entitity_varname?>->moveTo($action);
    }

    public function findModel($id){
        if ((<?=$entitity_varname?> = $this-><?=$repository_var_name?>->get($id)) !== null) {
            return <?=$entitity_varname?>;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
