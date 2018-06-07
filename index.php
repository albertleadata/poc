<?php

$pCfg = array(	'db_host' => 'mariadb-persistent',
					'db_db' => 'poc',
					'db_uid' => 'www',
					'db_pwd' => 'h0ckeypuck' );
$pDB = null;

function getDBCxn() {
	global $pCfg;
	$pRet = null;
	$pCxn = mysqli_connect( $pCfg['db_host'], $pCfg['db_uid'], $pCfg['db_pwd'], $pCfg['db_db']);
	if ( !mysqli_connect_errno()) {
		$pRet = $pCxn;
	} else error_log( "PoC WebGUI ERR: ".mysqli_connect_error());
	return( $pRet);
}

function initDB() {
	global $pDB;
	if ( $pDB != null) {
		$sQry = "create table poc ( id bigint primary key auto_increment, created datetime )";
		mysqli_query( $pDB, $sQry);
	}
	return;
}

function addNewItm() {
	global $pDB;
	$lRet = 0;
	if ( $pDB != null) {
		$sQry = "insert into poc (created) values (now())";
	//	echo "DEBUG - Insertion query:<br>".$sQry."<br><br>";
		if ( mysqli_query( $pDB, $sQry)) {
			$lRet = mysqli_insert_id( $pDB);
		}
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
	global $pDB;
	$sRet = "<table align=center>\n";
	if ( $pDB != null) {
		$sQry = "select * from poc order by id";
	//	echo "<br>Query:<br>".$sQry."<br><br>\n";
		if ( $pRslt = mysqli_query( $pDB, $sQry)) {
			while ( $pRow = mysqli_fetch_assoc( $pRslt)) {
				$sRet .= sprintf( "<tr><td>%d</td></tr>\n", intval($pRow['id']));
			}
			mysqli_free_result( $pRslt);
		}
	} // else  printf( "ERR: %s\n", mysqli_connect_error());
	$sRet .= "</table>\n";
	return( $sRet);
}

function genPage( $sCtx) {
	global $pDB;
	$pDB = getDBCxn();
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
