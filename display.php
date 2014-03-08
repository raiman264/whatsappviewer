<?php

$bd = new SQLite3("msgstore.db.sqlite");

?>

<style type="text/css">
body{
	background-color: darkgray;
}
.msg{
	padding: 1em;
	border-radius: 20px;
	background-color: whitesmoke;
	margin: .5em;
}

.msg.own{
	background-color: khaki;
}
</style>

<?php
#$resultado = $bd->query("SELECT * FROM sqlite_master WHERE type='table';");
$resultado = $bd->query("SELECT * FROM chat_list;");
while ( $r = $resultado->fetchArray(SQLITE3_ASSOC)){

	echo "<h2>".$r['key_remote_jid']."</h2>";

	$re2 = $bd->query("SELECT * FROM messages WHERE key_remote_jid = '{$r['key_remote_jid']}' ORDER BY received_timestamp;");

	while ( $r2 = $re2->fetchArray(SQLITE3_ASSOC)){ ?>
	<div class="msg <?=($r2['key_from_me'] ? 'own' : '')?>">
		<p><?=$r2['data']?></p>
		<small><?=date("d-m-Y H:i:s",$r2['received_timestamp'])?></small>
	</div>

<?php
	}

}


?>
