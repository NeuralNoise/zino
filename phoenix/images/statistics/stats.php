<?php
	set_include_path( '../../:./' );
	require_once 'libs/rabbit/rabbit.php';
	Rabbit_Construct('plain');

	global $libs;

	$libs->Load( 'libchart/classes/libchart' );
	$libs->Load( 'statistics' );

	$ttle="";

	if ($_GET[ 'name' ] == 'shouts' ) {
		$stat=Statistics_Get( 'shoutbox' , 'shout_created' ,$_GET['days']);
		$title = "new Shouts per day";	
	}
	else if ($_GET['name']=='users') {
		$stat=Statistics_Get( 'users' , 'user_created' , $_GET['days']);
		$title="new Users per day";
	}
	else if ( $_GET['name'] == 'images' ) {
		$stat=Statistics_Get( 'images' , 'image_created' , $_GET['days']);
		$title="new Images per day";
	}
	else if ($_GET['name']=='polls')	{
		$stat=Statistics_Get( 'polls' , 'poll_created' , $_GET['days']);
		$title="new Polls per day";
	}
	else if ($_GET['name']=='comments') {
		$stat=Statistics_Get('comments','comment_created' , $_GET['days']);
		$title="new Comments per day";
	}
	else if ($_GET['name']=='journals') {
		$stat=Statistics_Get('journals','journal_created' , $_GET['days']);
		$title="new Journals per day";
	}
	else if ($_GET['name']=='albums') {
		$stat=Statistics_Get('albums','album_created' , $_GET['days']);
		$title="new Albums per day";
	}

	if($title=="") exit(0);//Not valid get name

	if($_GET['days']==30) $x=500;
	else if($_GET['days']==60) $x=750;
	else if($_GET['days']==90) $x=1000;
	

	$chart=new LineChart($x,$x-250);	//1000 for 90 days,750 for 60 days 500 for 30 days		
	$dataSet=new XYDataSet();	
	
	$i=0;
	$lastday="";
	$lastmonth;
	foreach ($stat as $row) {	
		if ($i%10==0) {
			$date=new DateTime($row['day']);
			$label=$date->format('m-d');
		}
		else 
		{
			$label="";
			$date=new DateTime($row['day']);
		}

		if($lastday!="")
		{
			for($e=0;$e<((int)$date->format('d')-$lastday+((int)$date->format('m')-$lastmonth)*30)-1;$e++)
			$dataSet->addPoint(new Point("",0)); 

		}
		
		$dataSet->addPoint(new Point($label,$row['count'])); 

		$i++;
	
		$lastday=(int)$date->format('d');
		$lastmonth=(int)$date->format('m');
	}

	$chart->setDataSet($dataSet);
	$chart->SetTitle( $title );

	header( 'Content-type: image/png' );
	$chart->render();
			
	Rabbit_Destruct();
?>
