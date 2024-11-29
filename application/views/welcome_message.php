<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>

	<style type="text/css">

	::selection { background-color: #E13300; color: white; }
	::-moz-selection { background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
		text-decoration: none;
	}

	a:hover {
		color: #97310e;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body {
		margin: 0 15px 0 15px;
		min-height: 96px;
	}

	p {
		margin: 0 0 10px;
		padding:0;
	}

	p.footer {
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}

	#container {
		margin: 10px;
		border: 1px solid #D0D0D0;
		box-shadow: 0 0 8px #D0D0D0;
	}
	</style>
</head>
<body>

<div id="container">
	<h1>I am decode here</h1>
<?php

#string unquie char count()
// $str = "GEEKSFORGEEKS";

// $result=[];
// $count=0;
// for ($i=0; $i < strlen($str); $i++) { 

// 	if (!in_array($str[$i], $result)) {
// 		$result[] = $str[$i];
// 		$count++;
// 	}
// }
// echo $count;

#length of the longest substring without repeating characters. 

$str = "GEEKSFORGEEKSA";



function longestUniqueSubstr($s) {
    $n = strlen($s);
    $res = 0;

    for ($i = 0; $i < $n; $i++) {
        // Initialize all characters as not visited
        $visited = array_fill(0, 256, false);

        for ($j = $i; $j < $n; $j++) {
            // If the current character is already visited, break the loop
            if ($visited[ord($s[$j])] === true) {
                break;
            } else {
                
                $res = max($res, $j - $i + 1);
                $visited[ord($s[$j])] = true;
            }
        }
    }
    return $res;
}

$s = "geeksforgeeksAA";
echo longestUniqueSubstr($s);
?>






</div>

</body>
</html>
