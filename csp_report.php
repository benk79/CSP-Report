<?php

	/*
	 *
	 */
	$email_to   = 'webmaster@example.com';
	$email_from = 'csp.report@example.com';
	$db_name    = 'csp_report';
	$user       = 'user';
	$password   = 'pass';

	/*
	 *
	 */
	$method = $_SERVER['REQUEST_METHOD'];
	$input  = json_decode(file_get_contents('php://input'),true);
	$dsn    = 'mysql:dbname=' . $db_name . ';host=localhost';
	$db     = New PDO($dsn, $user, $password);

	/*
	 *
	 */
	if ($method == 'POST' && $input['csp-report']) {

		$report = $input['csp-report'];
		
		$cols = array(
			'document-uri',
			'referrer',
			'violated-directive',
			'effective-directive',
			'original-policy',
			'disposition',
			'blocked-uri',
			'line-number',
			'source-file',
			'status-code',
		);
		
		$q = 'insert into csp_report set `time` = now(), `report` = ' . $db->quote(json_encode($report));
		$msg = "CSP Report Format\n";
		$f = array();
		
		foreach ($report as $k => $v) {
			if (in_array($k, $cols)) {
				$q .= ', `' .  str_replace('-', '_', $k) . '` = ';
				$q .= ($v === null ? 'NULL' : ($db->quote($v)));
			} else {
				$f[] = "New field: $k\n";
			}
		}
		$db->exec($q);
		
		if ($f)
			mail($email_to, 'CSP Report Format', $msg . implode("", $f), 'From: ' . $email_from);

	}
	else
	if ($input) {

		mail($email_to, 'Content Security Policy Report', print_r($input, true), 'From: ' . $email_from);

	}

?>
