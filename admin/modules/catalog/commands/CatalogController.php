<?

namespace admin\modules\catalog\commands;

use Yii;
use yii\console\Controller;
use admin\modules\catalog\models\Item;
use admin\modules\catalog\models\Group;
/**
 * Default controller.
 */
class CatalogController extends Controller {

    public function options($actionID) {
        if ($actionID == 'recreate-groups') {
            return [
            ];
        }
        if ($actionID == 'resave-items') {
            return [
            ];
        }
    }

    public function actionRecreateGroups() {


        Yii::$app->db->createCommand()->truncateTable('admin_module_catalog_group')->execute();

        $items = Item::find()->all();
        foreach ($items as $item) {

            if ($item->status == Item::STATUS_ON && $item->available > 0) {
                //Ищем есть ли уже такая группа
                $group = Group::find()->where(['category_id' => $item->category_id, 'brand_id' => $item->brand_id, 'name' => $item->name])->one();
                if (!$group) {
                    //Если нет, то создаем новую группу
                    $group = new Group();
                    $group->category_id = $item->category_id;
                    $group->brand_id = $item->brand_id;
                    $group->name = $item->name;
                    $group->external_name = $item->external_name;
                    $group->save();
                }
                $item->group_id = $group->primaryKey;
            } else {
                $item->group_id = NULL;
            }

            Yii::$app->db->createCommand()->update(Item::tableName(), ['group_id' => $item->group_id], ['id' => $item->id])->execute();
        }

        $this->stdout(Yii::t('admin', 'Группы элементов пересозданы') . "\n");
        $this->stdout("DONE\n");
    }
    
    public function actionResaveItems() {


        Yii::$app->db->createCommand()->truncateTable('admin_module_catalog_group')->execute();

        $items = Item::find()->all();
        foreach ($items as $item) {
            if ($item->status == Item::STATUS_ON && $item->available > 0) {
                $item->save();
            } else {
                $item->group_id = NULL;
                Yii::$app->db->createCommand()->update(Item::tableName(), ['group_id' => $item->group_id], ['id' => $item->id])->execute();
            }            
        }

        $this->stdout(Yii::t('admin', 'Элементы пересохранены') . "\n");
        $this->stdout("DONE\n");
    }

}
