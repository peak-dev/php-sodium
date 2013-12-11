<?php
/*
 * php-sodium uses namespace sodium.
 * crypto() and nonce() methods throw \sodium\crypto_exception 
*/
try {

	$c = new \sodium\crypto();

	// Create a secret key
	$alice_secret = $c->keypair();

	// Create public key
	$alice_public = new \sodium\public_key();
	// Load binary key from alice_secret (pbin), false: expect binary, not key in hex
	$alice_public->load($alice_secret->pbin, false);

	// Alice's friend Bob 
	$bob_secret = $c->keypair();

	// Create public key from bob_secret (pbin)
	$bob_public = new \sodium\public_key();
	$bob_public->load($bob_secret->pbin, false);

	// Alice's message to Bob
	$message  = "Now Jesus did many other signs in the presence of the disciples,";
	$message .= "which are not written in this book; but these are written so that";
	$message .= "you may believe that Jesus is the Christ, the Son of God, and that";
	$message .= "by believing you may have life in his name. (ESV, John 20:30:31)";

	// Create a nonce
	$nonce = new \sodium\nonce();

	// Every call to $nonce->next() generates a new nonce! Important for crypto_box
	// Use Bob's public key to send to Bob 
	$encrypted_text = $c->box($message, $nonce->next(), $bob_public, $alice_secret);

	// Bob receives the $encrypted_text and 24 bytes nonce->nbin from Alice via the network
	$nonce_from_alice = $nonce->nbin;

	$bob_nonce = new \sodium\nonce();

	$message_decrypted = $c->box_open(

		  $encrypted_text
		, $bob_nonce->set_nonce($nonce_from_alice, true)
		, $alice_public
		, $bob_secret
	);

	echo "Messages encrypted/decrypted\n";
}

catch(\sodium\crypto_exception $e) {

	syslog(LOG_ERR, sprintf("Error: %s:%s : (%s) %s\n%s\n"

		, $e->getFile()
		, $e->getLine()
		, $e->getCode()
		, $e->getMessage()
		, $e->getTraceAsString()
	));
}
?>
