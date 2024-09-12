<?php
// Execute lmstat command and capture output
$output = shell_exec('cat lmstat_o.txt');

// Send the output to the browser
echo $output;
?>
