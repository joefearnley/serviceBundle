<?php
	namespace peter\components\serviceBundle;
	/*
	$config = array(
		"service-name" => "bit.ly"(by default),
		"url" => "your-url",
		"api-key" => "your-api-key",
		"api-secret" => "your-api-secret"(optional)
	);
	*/

	class serviceBundle {
		public function __construct(array $config) {
			$this -> configs = $config;
		}
		
		public function sendReq() {
			switch($this -> configs["service-name"]) {
				case "mailgun":
					return $this -> sendMail();
					break;
				case "imgur":
					return $this -> uploadImg();
					break;
				case "bit.ly":
				case "goo.gl":
				case "McAf.ee":
					return $this -> generateUrl();
					break;
				default:
					return "unknown-service";
			}
		}
		
		private function sendMail() {
			$res = null;
			$httpConfig = [];

			if($this -> configs["service-name"] === "mailgun") {
				$httpConfig = ['defaults' => [
						'auth' => ['api', $this -> configs["api-key"]]
					]
				];
			}
			
			$httpClient = new \GuzzleHttp\Client($httpConfig);
			$httpClient -> setDefaultOption('verify', false);

			$res = $httpClient -> post('https://api.mailgun.net/v3/' . $this -> configs["domain-name"] . '.mailgun.org/messages', [
				'body'=>[
					'from' => $this -> configs["from"],
					'to' => $this -> configs["to"],
					'subject' => $this -> configs["subject"],
					'text' => $this -> configs["contents"]
				]
			]);
	
			return $res -> json();
		}
		
		private function uploadImg() {
			if(!file_exists($this -> configs["filePath"]))
				return "file not found";
			else {
				$imageFile = file_get_contents($this -> configs["filePath"]);
				
				if($this -> configs["service-name"] === "imgur") {
					$httpConfig = ['defaults' => [
							'headers' => ['Authorization' => 'Client-ID ' . $this -> configs["clientID"]]
						]
					];
				}

				$httpClient = new GuzzleHttp\Client($httpConfig);
				$httpClient -> setDefaultOption('verify', false);

				$res = $client -> post('https://api.imgur.com/3/image.json', [
					'body'=>[
						'image' => base64_encode($imageFile)
					]
				]);
	
				return $res -> json();
			}
		}
	}
?>