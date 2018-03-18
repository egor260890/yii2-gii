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
$modelname=strtolower(StringHelper::basename($generator->modelClass));
?>
namespace <?= $generator->getRepositoryNamespace() ?>;

use <?=$generator->modelClass?>;

class <?= StringHelper::basename($generator->repositoryClass) ?>
{

    public function get($id):<?= StringHelper::basename($generator->modelClass) ?>
    {
        if (!$<?=$modelname ?> = <?= StringHelper::basename($generator->modelClass) ?>::findOne($id)) {
            throw new NotFoundException('<?= StringHelper::basename($generator->modelClass) ?> is not found.');
        }
        return $<?=$modelname ?>;
    }


    public function save(<?= StringHelper::basename($generator->modelClass) ?>  $<?=$modelname ?>)
    {
        if (!$<?=$modelname ?>->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    public function remove(<?= StringHelper::basename($generator->modelClass) ?> $<?=$modelname ?>)
    {
        if (!$<?=$modelname ?>->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }

}
