<?php
	require (dirname(__FILE__) . "../../includes/db.php");
	//pre($_REQUEST);
	//exit();
	$phase = get_formvar("phase");
	if ($phase == "perform_backup") {
		get_formvars_perform_backup();
	}

	function get_formvars_perform_backup() {
        putenv('PATH=/usr/local/bin');
        $command = escapeshellcmd('/usr/bin/python /Users/matt.admin/projects/tariffs/db/backup.py');
        $output = shell_exec($command);
        echo ("Output" . $output);

        #header('Location: /load_history.html');
	}
?>