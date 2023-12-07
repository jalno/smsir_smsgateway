<?php
namespace packages\smsir_smsgateway\listeners\settings;

use packages\sms\events\Gateways;
use packages\smsir_smsgateway\API;

class SMS {
	public function gatewaysList(Gateways $gateways): void {
		$gateway = new Gateways\Gateway('smsir');
		$gateway->setHandler(API::class);
		$gateway->addInput(array(
			'name' => 'smsir_apikey',
			'type' => 'string'
		));
		$gateway->addField(array(
			'name' => 'smsir_apikey',
			'label' => t('settings.sms.gateways.smsir.apikey'),
			'ltr' => true
		));
		$gateways->addGateway($gateway);
	}
}
