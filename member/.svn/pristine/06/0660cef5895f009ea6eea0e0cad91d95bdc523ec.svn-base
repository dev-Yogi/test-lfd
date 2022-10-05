<?php

class MyTokenPassportGenerator implements iTokenPassportGenerator
{
	/**
	 * Shows how to generate TokenPassport for SuiteTalk, called by PHP Toolkit before each request
	 */
	public function generateTokenPassport() {
		$consumer_key = "c0dcc15ba3dc98847ee1b5e96a3e5d081400840b70817c5ef9268ab1445003e5"; // Consumer Key shown once on Integration detail page
		$consumer_secret = "87d599ac7110243ab04ad41ab64b9cf5115355a92e88168127eba1ded5d2e17e"; // Consumer Secret shown once on Integration detail page
		// following token has to be for role having those permissions: Log in using Access Tokens, Web Services
		// tokens of account rgonzalez@aiminstitute.org
		$token = "6dccfe9189c0b367f493d49d338f460c4cec460509f07d884b97fb4305dcd04d"; // Token Id shown once on Access Token detail page
		$token_secret = "a4f107d2db101e1c98fe4d2846a49c7e8ec427baa5c44889f9edb3f480651c5a"; // Token Secret shown once on Access Token detail page
		
		$nonce = $this->generateRandomString();// CAUTION: this sample code does not generate cryptographically secure values
		$timestamp = time();

		$baseString = urlencode(NS_ACCOUNT) ."&". urlencode($consumer_key) ."&". urlencode($token) ."&". urlencode($nonce) ."&". urlencode($timestamp);
		$secret = urlencode($consumer_secret) .'&'. urlencode($token_secret);
		$method = 'sha1'; //can be sha256	
		$signature = base64_encode(hash_hmac($method, $baseString, $secret, true));
		
		$tokenPassport = new TokenPassport();
		$tokenPassport->account = NS_ACCOUNT;
		$tokenPassport->consumerKey = $consumer_key;
		$tokenPassport->token = $token;
		$tokenPassport->nonce = $nonce;                                    
		$tokenPassport->timestamp = $timestamp; 
		$tokenPassport->signature = new TokenPassportSignature();
		$tokenPassport->signature->_ = $signature;
		$tokenPassport->signature->algorithm = "HMAC-SHA1";  //can be HMAC-SHA256
		
		return $tokenPassport;
	}

	/**
	 * Not related to Token-Based Authentication, just displaying responses in this sample.
	 * It is assumed (and not checked) that $timeResponse is a response of getServerTime operation.
	 */
	public static function echoResponse($timeResponse) {
		if (!$timeResponse->getServerTimeResult->status->isSuccess) {
			echo "GET ERROR\n";
		} else {
			echo "GET SUCCESS, time:". $timeResponse->getServerTimeResult->serverTime. "\n";
		}
	}
	
	// CAUTION: it does not generate cryptographically secure values
	private function generateRandomString() {
		$length = 20;
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)]; // CAUTION: The rand function does not generate cryptographically secure values
			// Since PHP 7 the cryptographically secure random_int can be used
		}
		return $randomString;
	}
}
?> 