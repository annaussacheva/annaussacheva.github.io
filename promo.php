<?php

if($_SERVER['REQUEST_METHOD'] == "POST")
{
	$result = ['error' => 1];
	
	if(isset($_POST['action']))
	{
		$myConn = new mysqli("localhost", "promo", "123@qwe", "promo");

		$qResult = null;

		switch($_POST['action'])
		{
			case "promo":
				//$result['post'] = print_r($_POST, true);
			
				if(!isset($_POST['cardStart']) || !isset($_POST['cardEnd'])) break;
				
				$code;
				
				if($myConn->real_query("select code from codes where date is null limit 1") !== false)
				{
					$qResult = $myConn->store_result();
					
					$code = $qResult->fetch_array()['code'];
				}
				else
				{
					$result['error'] = $myConn->errno;
				}
				
				if($myConn->real_query("update codes set card_start = " . $_POST['cardStart'] . ", card_end = " . $_POST['cardEnd'] . ", date = now() where code = '" . $code . "'") !== false)
				{
					$result['promo'] = $code;
					$result['error'] = 0;
				}
				else
				{
					$result['error'] = $myConn->errno;
					$result['err_msg'] = $myConn->error;
				}

				break;
		}
		
		if($qResult !== null) $qResult->free();
		
		$myConn->close();
	}

	echo ")]}',\n" . json_encode($result);
}

return;