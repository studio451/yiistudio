<?

namespace admin\helpers;

use Yii;
use admin\models\Setting;
use yii\helpers\Url;

class Schema {

    public static function localBusiness() {

        $cache = Yii::$app->cache;

        $key = 'schema_local_business';

        $string = $cache->get($key);
        
        if ($string === false) {
            
            $string = '<span itemscope itemtype="http://schema.org/LocalBusiness">';
            
            if (Setting::get('contact_url')) {
                $string = '<a itemprop="url" style="text-transform:uppercase;" href="' . Url::to(['/'],true) . '"><span itemprop="name">' . Setting::get('contact_name') . '</span></a><br/>';
            }
            if (Setting::get('contact_addressLocality') && Setting::get('contact_streetAddress')) {
                $string .= 'Адрес: <span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"><span itemprop="addressLocality">' . Setting::get('contact_addressLocality') . '</span>, <span itemprop="streetAddress">' . Setting::get('contact_streetAddress') . '</span></span>, <a href="' . Url::to(['/contact'],true) . '" title="Контакты">cхема проезда<i class="fa fa-fw fa-map-marker"></i></a><br/>';
            }
            $string .= 'График работы: <time itemprop="openingHours" datetime="' . Setting::get('contact_openingHoursISO86') . '">' . Setting::get('contact_openingHours') . '</time><br/>';
            if (Setting::get('contact_telephone')) {
                $string .= 'Телефон: <span itemprop="telephone">' . Setting::get('contact_telephone') . '</span><br/>';
            }
            if (Setting::get('contact_email')) {
                $string .= 'Электронная почта: <a href="mailto:' . Setting::get('contact_email') . '"><span itemprop="email">' . Setting::get('contact_email') . '</span></a>';
            }

            $string .= '</span>';
            $cache->set($key, $string, 3600);
        }
        return $string;
    }

}
