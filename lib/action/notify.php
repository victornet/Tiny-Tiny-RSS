<?php

	// invoke notify 
	function action_notify_invoke($filter, $article) {

		_debug_suppress(false);

		$target = $filter['param'];
		$title = $article['title'];
		$link = $article['link'];

		if ((strpos($target, "mail:") !== false) || strpos($target, "mailto:") !== false) {

			/* send email */

			$recipient = substr($target, strpos($target, ":") + 1);
			$subject = _("[TTRSS] New article");
			$message = _("New article received:") . "\n\n" . $title . "\n" . $link;

			mail($recipient, $subject, $message);

			_debug("Sent mail to $recipient.");

		}

		if (strpos($target, "prowl:") !== false) {

			/*
			
			prowl me
			http://www.prowlapp.com/api.php		

			 */

			$token = substr($target, strpos($target, ":") + 1);
			$event = _("New article");

			$url = "https://api.prowlapp.com/publicapi/add";

			$priority = 2; 

			$data = array(
				"apikey" => $token,
				"application" => "ttrss",
				"event" => $event,
				"description" => $title,
				"priority" => $priority,
				"url" => $link
			);


			if (action_notify_invoke_curl($url, $data)) {
				_debug("Sent notification via prowl: $token");
			} else {
				_debug("Error sending notification via prowl: $token");
			}

		}

		if (strpos($target, "boxcar:") !== false) {

			/*

			boxcar.io 
			http://help.boxcar.io/knowledgebase/articles/306788-how-to-send-a-notification-to-boxcar-users

			*/
			$token = substr($target, strpos($target, ":") + 1);

			$url = "https://new.boxcar.io/api/notifications";

			$data = array(
				"user_credentials" => $token,
				"notification[title]" => $title,
				"notification[source_name]" => "ttrss",
				"notification[open_url]" => $link
			);


			if (action_notify_invoke_curl($url, $data, 201)) {
				_debug("Sent notification via boxcar: $token");
			} else {
				_debug("Error sending notification via boxcar: $token");
			}

		}

	}

	// invoke curl
	function action_notify_invoke_curl($url, $data, $successful = 200) {

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60); 

		$server_output = curl_exec($ch);
		$server_info = curl_getinfo($ch);
		$errno = curl_errno($ch);

		#print_r($server_output);
		#print_r($server_info);

		_debug("curl err: $errno");

		curl_close ($ch);

		return $server_info['http_code'] === $successful;

	}


?>
