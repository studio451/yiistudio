<?
namespace admin\helpers;

use Yii;
use admin\models\Setting;

class Mail
{
    public static function send($toEmail, $subject, $template, $data = [], $options = [])
    {
         if($options['replyToAdminEmail'] === false)
         {
             return false;
         }
        
        if(!filter_var($toEmail, FILTER_VALIDATE_EMAIL) || !$subject || !$template){
            return false;
        }
        
        $data['subject'] = Setting::get('contact_name'). ': ' .trim($subject);       
        
        $message = Yii::$app->mailer->compose($template, $data)
            ->setTo($toEmail)
            ->setSubject($data['subject']);

        if(filter_var(Setting::get('contact_email'), FILTER_VALIDATE_EMAIL)){
            $message->setFrom(Setting::get('contact_email'));
        }

        if(Setting::get('replyToAdminEmail')){
            $message->setTo(Setting::get('admin_email'));
            $message->send();
            $message->setTo($toEmail);
        } 
        return $message->send();
    }
}