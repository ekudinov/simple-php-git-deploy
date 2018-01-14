<?php
/**
 * author:evgeniy kudionov github@ekudinov.ru
 */

class Tlg {

	/**
	 * @var string base url for telegram api
	 */
	private static $base_tlg_api_url = 'https://api.telegram.org/';

	/**
	 * @var string url for request
	 */
	private $url;

	/**
	 * @var string channel chat id to send
	 */
	private $chat_id;

	/**
	 * @var string access bot api token
	 */
	private $bot_key_token;

	/**
	 * Tlg constructor.
	 *
	 * @param $args serialized array chat_id, bot_api_token
	 *
	 * if not defined return error
	 */
	public function __construct( $args ) {
		$arguments = unserialize($args);
		if ($arguments['chat_id'] && $arguments['bot_api_token'] ) {
			$this->chat_id       = $arguments['chat_id'];
			$this->bot_key_token = 'bot' . $arguments['bot_api_token'];
			$this->url           =  self::$base_tlg_api_url . $this->bot_key_token . '/sendMessage';
		} else {
			throw new InvalidArgumentException("No arguments for telegram client!");
		}
	}


	/**
	 * @param $msg send msg to telegram channel
	 *
	 * @return void
	 */
	public function send( $msg ) {

		$data = array(
			'chat_id' => $this->chat_id,
			'text'    =>  $msg
		);

		$request = stream_context_create(
			array(
				'http' => array(
					'method'  => 'POST',
					'header'  => 'Content-type: application/x-www-form-urlencoded',
					'content' => http_build_query( $data )
				)
			)
		);

		file_get_contents( $this->url, false, $request );
	}
}