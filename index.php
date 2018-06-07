<?php

$pCfg = array(	'db_host' => 'pocdb',
					'db_db' => 'poc',
					'db_uid' => 'www',
					'db_pwd' => 'h0ckeypuck' );

function connect() {
	global $pCfg;
	$pCxn =	mysql_pconnect(	$pCfg['db_host'], $pCfg['db_uid'], $pCfg['db_pwd']) or trigger_error( mysql_error(), E_USER_ERROR);
	mysql_select_db(	$pCfg['db_db']) or trigger_error( mysql_error(), E_USER_ERROR);
	return;
} 

function initDB() {
	$sQry = "create table poc ( id bigint primary key auto_incrememt )";
	mysql_query( $sQry);
	return;
}

function addNewItm() {
	$lRet = 0;
	$sQry = "insert into poc";
//	echo "DEBUG - Insertion query:<br>".$sQry."<br><br>";
	if ( mysql_query( $sQry)) {
		$lRet = mysql_insert_id();
	}
	return( $lRet);
}

function genAddForm() {
	$sRet = "<form method=post action=index.php>\n";
	$sRet .= "<input type=hidden name=ctx value=itmadd>\n";
	$sRet .= "<table align=center>\n";
	$sRet .= "<tr><td align=center><input type=submit value=Add</td></tr>\n";
	$sRet .= "</table>\n";
	$sRet .= "</form>\n";
	return( $sRet);
}

function genListing() {
	$sRet = "<table align=center>\n";
	$sQry = "select * from poc order by id";
//	echo "<br>Query:<br>".$sQry."<br><br>\n";
	$pRslt = mysql_query( $sQry);
	while ( $pRow = mysql_fetch_assoc( $pRslt)) {
		$sRet .= sprintf( "<tr><td>%d</td></tr>\n", intval($pRow['id']));
	}
	mysql_free_result( $pRslt);
	$sRet .= "\n";
	$sRet .= "</table>\n";
	return( $sRet);
}

function genPage( $sCtx) {
	if ( $sCtx == "initdb") {
		initDB();
		addNewItm();
	} else if ( $sCtx == "itmadd") addNewItm();
	printf( "<html>\n");
	printf( "<body>\n");
	printf( "<table align=center>\n");
	printf( "<tr><td align=center><h1>Proof-of-Concept</h1></td></tr>\n");
	printf( "<tr><td align=center><i>... taking OpenShift for a spin ...</i></td></tr>\n");
	printf( "</table>\n");
	printf( "<table align=center>\n");
	printf( "<tr><td align=center>\n%s</td></tr>\n", genAddForm());
	printf( "</table>\n");
	printf( "<table align=center>\n");
	printf( "<tr><td align=center>\n%s</td></tr>\n", genListing());
	printf( "</table>\n");
	printf( "</body>\n");
	printf( "</html>\n");
	return;
}

$sCtx = "";
if ( !isset( $_POST['ctx'])) {
	if ( isset( $_GET['ctx'])) $sCtx = $_GET['ctx'];
} else $sCtx = $_POST['ctx'];
genPage( $sCtx);
?>
