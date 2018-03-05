<?

namespace admin\models;

use Yii;

class TranslateSourceMessage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_translate_source_message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message'], 'string'],
            [['category'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category' => Yii::t('admin', 'Категория'),
            'message' => Yii::t('admin', 'Текст'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslateMessages()
    {
        return $this->hasMany(TranslateMessage::className(), ['id' => 'id']);
    }
}
