<?php

namespace VKauto\Workers\SetOnlineWorker;

use VKauto\Interfaces\WorkerInterface;
use VKauto\Auth\Account;
use VKauto\Utils\QueryBuilder;
use VKauto\Utils\Request;
use VKauto\Utils\Log;
use VKauto\CaptchaRecognition\Captcha;

class SetOnline implements WorkerInterface
{
	/**
	 * Состояние работы воркера
	 * @var boolean
	 */
	protected $workInProcess = false;

	/**
	 * Промежуток между запросами в секундах
	 * @var int
	 */
	public $seconds;

	/**
	 * Класс аккаунта, с которым работает воркер
	 * @var VKauto\Auth\Account
	 */
	public $account;

	public function __construct($minutes = 10, Account $account)
	{
		$this->seconds = $minutes * 60;
		$this->account = $account;
	}

	private function loop()
	{
		while ($this->workInProcess)
		{
			$response = Request::VK(QueryBuilder::buildURL('account.setOnline',
			[
				'access_token' => $this->account->access_token
			]), $this->account->captcha);

			if ($response->response == 1)
			{
				Log::write('Online status was successfully set.', ['SetOnline']);
			}
			else
			{
				Log::write('An error occured.', ['SetOnline', 'ERROR']);
				die;
			}

			sleep($this->seconds);
		}
	}

	public function start()
	{
		$this->workInProcess = true;
		Log::write('Worker started.', ['SetOnline']);
		$this->loop();
	}

	public function stop()
	{
		$this->workInProcess = false;
	}

	public static function needsAccountClass()
	{
		return true;
	}
}
