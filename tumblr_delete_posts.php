<?php
//
// THIS WILL DELETE ALL YOUR TUMBLR POSTS!
//
// (To setup, just fill in EMAIL, PASSWORD and URL)
//
// Written by David Ulevitch on Friday, May 28, 2010
// http://david.ulevitch.com
// david@ulevitch.com

// Authorization info
$tumblr_email    = 'EMAIL';
$tumblr_password = 'PASSWORD';
$tumblr_url 	 = 'URL';

while (true) {
	$doc = new DOMDocument();
	@$doc->loadHTMLFile($tumblr_url);
	$ids = array();
	foreach ($doc->getElementsByTagName('post') as $node) {
		$ids[] = $node->getAttribute('id');
	}
	if (count($ids) <= 0) {
		echo "I don't see any posts to delete!\n";
		break;
	}
	foreach ($ids as $id) {
		echo "Deleting Post ID: $id ... ";
		// Prepare POST request
		$request_data = http_build_query(
		    array(
				'email'     => $tumblr_email,
				'password'  => $tumblr_password,
				'post-id'	=> $id
		    )
		);
		// Send the POST request (with cURL)
		$c = curl_init('http://www.tumblr.com/api/delete');
		curl_setopt($c, CURLOPT_POST, true);
		curl_setopt($c, CURLOPT_POSTFIELDS, $request_data);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($c);
		$status = curl_getinfo($c, CURLINFO_HTTP_CODE);
		curl_close($c);

		// Check for success
		if ($status == 201 || $status == 200) {
		    echo "Success!\n";
		} else if ($status == 403) {
		    echo "Bad email or password\n";
		} else {
		    echo "Error: $status: $result\n";
		}
	}
}
echo "Done!\n";
?>