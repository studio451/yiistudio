<?

/**
 * $notification = new YandexNotification();
 * // Set notification properties as you need
 * // By default date is set to now and amount is initialized with a random float value
 * $notification->codepro = false;
 * $notification->label = 53243;
 * $notification->dispatch('https://test.site/pay', 'S4KDHyTSvH4AuimHP0N6ibsN');
 */

namespace admin\modules\payment\payment_systems\helpers;

use Yii;

class YandexNotification
{
	/** @var string Тип уведомления, фиксированное значение p2p-incoming */
	public $notification_type = 'p2p-incoming';
	/** @var string Идентификатор операции в истории счета получателя */
	public $operation_id = 'test-notification';
	/** @var amount Сумма операции */
	public $amount = 0;
        /** @var withdraw_amount Сумма, которая списана со счета отправителя */
	public $withdraw_amount = 0;
        /** @var sender
         *  Для переводов из кошелька — номер счета отправителя
         *  Для переводов с произвольной карты — параметр содержит пустую строку */
	public $sender = '';
	/** @var string Код валюты счета пользователя. Всегда 643 (рубль РФ согласно ISO 4217) */
	public $currency = 643;
	/** @var datetime Дата и время совершения перевода в формате RFC3339 */
	public $datetime = '';
	/** @var boolean Перевод еще не зачислен. Получателю нужно освободить место в кошельке или использовать код протекции (если codepro=true) */
	public $unaccepted = false;
        /** @var boolean Перевод защищен кодом протекции */
	public $codepro = false;
	/** @var string Дополнительные данные, например номер корзины */
	public $label = '';
	/** @var string SHA-1 hash параметров уведомления */
	public $sha1_hash = '';
	/** @var boolean Флаг означает, что уведомление тестовое (по умолчанию параметр отсутствует) */
	public $test_notification = true;
	
	public function __construct()
	{
		$this->datetime = date("Y-m-d\TH:i:sP");
		$this->amount = rand(1, 1000) + rand(1, 99)/100; 
	}
	
	/** @return YandexNotification */
	private function hash($notification_secret)
	{
		if (is_bool($this->codepro)) {
			$this->codepro = $this->codepro ? 'true' : 'false';	
		}
                if (is_bool($this->unaccepted)) {
			$this->unaccepted = $this->unaccepted ? 'true' : 'false';	
		}
		if (is_bool($this->test_notification)) {
			$this->test_notification = $this->test_notification ? 'true' : 'false';
		}
		$dataToSign = array();
		foreach (array(
			'notification_type', 'operation_id', 'amount', 
			'currency', 'datetime', 'sender', 'codepro', 
			'notification_secret', 'label'
		) as $key) {
			$dataToSign[] = $key == 'notification_secret' ? $notification_secret : $this->{$key};
		}
		$this->sha1_hash = sha1(implode('&', $dataToSign));
		return $this;
	}
	
	/**
	 * Signs and sends a notification, outputs all response headers and actual response 
	 * @param string $url
	 * @param string $notification_secret
	 */
	public function dispatch($url, $notification_secret)
	{
           
		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_URL => $url,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => false,
			CURLOPT_VERBOSE => false,
			CURLOPT_POSTFIELDS => http_build_query(get_object_vars($this->hash($notification_secret))),
			CURLOPT_CONNECTTIMEOUT => 60,
			CURLOPT_FAILONERROR => false,
			CURLOPT_HEADER => true,
		));
		
		echo curl_exec($ch);
		if (curl_error($ch)) {
			var_dump(curl_error($ch), curl_errno($ch));	
		}
		curl_close($ch);
	}
}