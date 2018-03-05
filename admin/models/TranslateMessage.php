<?

namespace admin\models;

use Yii;

class TranslateMessage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_translate_message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'language'], 'required'],
            [['id'], 'integer'],
            [['translation'], 'string'],
            [['language'], 'string', 'max' => 16],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => TranslateSourceMessage::className(), 'targetAttribute' => ['id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'language' => Yii::t('admin', 'Язык'),
            'translation' => Yii::t('admin', 'Перевод'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslateSourceMessage()
    {
        return $this->hasOne(TranslateSourceMessage::className(), ['id' => 'id']);
    }
}
