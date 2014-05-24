<?php

 class HomeController{

 	private $mwConfig, $cmrToken, $client;

 	public function get(){
 		global $config;
 		// Step 1 - Get a request token
		list( $redir, $requestToken ) = $this->client->initiate();

		// Step 2 - Have the user authorize your app. Get a verifier code from them.
		// (if this was a webapp, you would redirect your user to $redir, then use the 'oauth_verifier'
		// GET parameter when the user is redirected back to the callback url you registered.
		echo "Point your browser to: $redir\n\n";
		print "Enter the verification code:\n";
		$fh = fopen( "php://stdin", "r" );
		$verifyCode = trim( fgets( $fh ) );

		// Step 3 - Exchange the request token and verification code for an access token
		$accessToken = $this->client->complete( $requestToken,  $verifyCode );

		// You're done! You can now identify the user, and/or call the API (examples below) with $accessToken


		// If we want to authenticate the user
		$identity = $this->client->identify( $accessToken );
		echo "Authenticated user {$identity->username}\n";

		// Do a simple API call
		echo "Getting user info: ";
		echo $this->client->makeOAuthCall(
			$accessToken,
			$config['wiki_url'] . 'api.php?action=query&meta=userinfo&uiprop=rights&format=json'
		);
 	}

 	public function __construct(){
 		global $config;
 		/* 
 		   Configure the connection to the wiki you want to use. Passing title=Special:OAuth as a
		   GET parameter makes the signature easier. Otherwise you need to call
		   $this->client->setExtraParam('title','Special:OAuth/whatever') for each step.
		   If your wiki uses wgSecureLogin, the canonicalServerUrl will point to http://
 		*/
 		$this->mwConfig = new MWOAuthClientConfig(
			$config['wiki_url'] . 'index.php?title=Special:OAuth', // url to use
			true, // do we use SSL? (we should probably detect that from the url)
			false // do we validate the SSL certificate? Always use 'true' in production.
		);

		$this->mwConfig->canonicalServerUrl = $config['canonical_server'];

		$this->cmrToken = new OAuthToken( $config['consumer_token'], $config['secret_token'] );

		$this->client = new MWOAuthClient( $this->mwConfig, $this->cmrToken );
 	}
 
 }