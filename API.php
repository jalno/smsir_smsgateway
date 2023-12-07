<?php
namespace packages\smsir_smsgateway;

use packages\base\{Exception, Http, Json};
use packages\sms\{Sent, Gateway};

class API extends Gateway\Handler {

	/** @var string */
	private $apiKey;

	public function __construct(Gateway $gateway) {
		$this->apiKey = $gateway->param('smsir_apikey');
		if (!$this->apiKey) {
			throw new Exception('API Key is missing');
		}
	}

	/**
	 * Send the sms
	 * 
	 * @param Sent $sms
	 * @return int new status of sms
	 */
	public function send(Sent $sms): int {
		try {
			$response = (new http\Client())->post('https://api.sms.ir/v1/send/bulk', array(
				'header' => array(
					'Accept' => 'application/json',
					'X-API-KEY' => $this->apiKey,
				),
				'form_params' => array(
					'lineNumber' => $sms->sender_number->number,
					'MessageText' => $sms->text,
					'Mobiles' => [$sms->receiver_number],
				),
			));
			$body = $response->getBody();
			if ($body) {
				try {
					$decoded = Json\decode($body);
					if ((isset($decoded['status']) and $decoded['status'] == 1)) {
						return Sent::sent;
					}
				} catch (Json\JsonException $e) {}
			}
		} catch (\Exception $e) {}
		return Sent::failed;
	}
}
