<?php

use Fleet\Mail;

$klein->respond('POST', '/sendmail', function ($request, $response, $service, $app) use ($klein) {
	$name = $request->contactName;
	$email = $request->contactEmail;
	$subject = $request->contactSubject;
	$message = $request->contactMessage;

	if(strlen($name) > 1) {
		$message .= "\n\n" . $name;
	}

	$mail = new Mail("mattmezza@gmail.com", $subject, $message, $email, $email);

	$error = "Opss! There was a problem sending your message... Sorry for that 😔...";
	if(getenv("LC_ALL")=="it_IT") {
		$error = "Opss! C'è stato un problema nell'invio del messaggio... Mi dispiace 😞";
	}

	if($mail->send())
		echo "OK";
	else
		echo $error;
});
