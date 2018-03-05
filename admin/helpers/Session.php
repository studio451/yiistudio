<?

namespace admin\helpers;

use Yii;

class Session {

    public static function has($key) {
        $session = Yii::$app->session;
        if ($session->has($key)) {
            return true;
        }
    }

    public static function get($key, $defaultValue = null) {
        $session = Yii::$app->session;
        if ($session->has($key)) {
            return $session->get($key);
        }
        return $defaultValue;
    }

    public static function set($key, $value = null) {
        $session = Yii::$app->session;
        if (!$value) {
            $session->remove($key);
        } else {
            $session->set($key, $value);
        }
    }

    public static function remove($key) {
        $session = Yii::$app->session;
        $session->remove($key);
    }

    public static function setByRequest($key, $defaultValue = null) {
        if (Yii::$app->request->get($key)) {
            Session::set($key, Yii::$app->request->get($key));
        } else {
            if (!Session::has($key)) {
                Session::set($key, $defaultValue);
            }
        }
    }

}
