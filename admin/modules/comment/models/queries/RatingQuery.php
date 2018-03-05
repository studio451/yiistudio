<?

namespace admin\modules\comment\models\queries;

class RatingQuery extends \yii\db\ActiveQuery {

    public function byId($id) {
        $this->andWhere(['id' => $id]);

        return $this;
    }

    public function byEntity($entity) {
        $this->andWhere(['entity' => $entity]);
        return $this;
    }

    public function byEntityId($entityId) {
        $this->andWhere(['entityId' => $entityId]);
        return $this;
    }

    public function bySession($session) {
        $this->andWhere(['session' => $session]);
        return $this;
    }
    
    public function currentUser() {
        $this->andWhere(['created_by' => \Yii::$app->user->id]);
        return $this;
    }

    public function byValue($value) {
        $this->andWhere(['value' => $value]);
        return $this;
    }

}
