<?php

 class HomeController{

 	/**
 	 * @var $mwConfig A variable for holding an instance of MWOAuthClientCOnfig
 	 * @var $cmrToken Token received generated by passing secret and consume_token to
 	 * 					to OAuthClient class
 	 * @var $client Instance of MWOAuthClass that is generated after passing both above vars into it
 	 * 				Used to interact with Wikimedia's OAuth API 	
 	 */	
 	// private $mwConfig, $cmrToken, $client;
 	private $mwClient;

 	/**
 	 * Function to handle the get request made to the server where this app reside
 	 * Since the App is built in a RESTful manner thats why this function is here
 	 * Initiaites the client and makes the redirect to and fro the wikimedia OAuth clientS
 	 * Also return the user info, <= todo after testing
 	 */	
 	public function get(){
 	// 	global $config;
 	// 	// Step 1 - Get a request token
		// list( $redir, $requestToken ) = $this->client->initiate();

		// // Step 2 - Have the user authorize your app. Get a verifier code from them.
		// // (if this was a webapp, you would redirect your user to $redir, then use the 'oauth_verifier'
		// // GET parameter when the user is redirected back to the callback url you registered.
		// echo "Point your browser to: $redir\n\n";
		// print "Enter the verification code:\n";
		// $fh = fopen( "php://stdin", "r" );
		// $verifyCode = trim( fgets( $fh ) );

		// // Step 3 - Exchange the request token and verification code for an access token
		// $accessToken = $this->client->complete( $requestToken,  $verifyCode );

		// // You're done! You can now identify the user, and/or call the API (examples below) with $accessToken


		// // If we want to authenticate the user
		// $identity = $this->client->identify( $accessToken );
		// echo "Authenticated user {$identity->username}\n";

		// // Do a simple API call
		// echo "Getting user info: ";
		// echo $this->client->makeOAuthCall(
		// 	$accessToken,
		// 	$config['wiki_url'] . 'api.php?action=query&meta=userinfo&uiprop=rights&format=json'
		// );
		// if(!isset($_SESSION['start'])){
		//    $this->mwClient = new MW_OAuth('WikidataAnnotationTool', 'wikidata', 'en');
  //  			$_SESSION['start'] = 1;
		// 	$this->mwClient->doAuthorizationRedirect();
		// } else {
		// 	$this->mwClient->fetchAccessToken();
		// 		$claim = array (
		// 			"prop" => 'P31' ,
		// 			"q" => 'Q4115189' ,
		// 			"target" => 'Q12345' ,
		// 			"type" => "item"
		// 		) ;
		// 		$this->mwClient->doesClaimExist($claim);

		// }
		echo 'Hello';
		die();
 	}

 	/**
 	 * Contructer of the HomeController sets up its instance vairable by using libb MWOAuthCLient by Stype
 	 * and the original OAuth Client, so basically constructes the class.
 	 */	
 	public function __construct(){
 	
 	// private $mwClient;	global $config;
 		/* 
 		   Configure the connection to the wiki you want to use. Passing title=Special:OAuth as a
		   GET parameter makes the signature easier. Otherwise you need to call
		   $this->client->setExtraParam('title','Special:OAuth/whatever') for each step.
		   If your wiki uses wgSecureLogin, the canonicalServerUrl will point to http://
 	// 	*/
 	// 	$this->mwConfig = new MWOAuthClientConfig(
		// 	$config['wiki_url'] . 'index.php?title=Special:OAuth', // url to use
		// 	true, // do we use SSL? (we should probably detect that from the url)
		// 	false // do we validate the SSL certificate? Always use 'true' in production.
		// );

		// $this->mwConfig->canonicalServerUrl = $config['canonical_server'];

		// $this->cmrToken = new OAuthToken( $config['consumer_token'], $config['secret_token'] );

		// $this->client = new MWOAuthClient( $this->mwConfig, $this->cmrToken );
 	}
 
 }