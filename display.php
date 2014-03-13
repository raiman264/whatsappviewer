<?php

$bd = new SQLite3("msgstore.db");

if(@$_GET['view'] == 'media'){
	$conditions = "AND media_mime_type IS NOT NULL";
}else{
	$conditions = '';
}
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
.just_media{
	width: auto;
	display: inline-block;
}
.just_media span{
	display: block;
}
</style>

<?php
#$resultado = $bd->query("SELECT * FROM sqlite_master WHERE type='table';");
$resultado = $bd->query("SELECT * FROM chat_list;");
while ( $r = $resultado->fetchArray(SQLITE3_ASSOC)){

	if($bd->querySingle("SELECT COUNT(*) FROM messages WHERE key_remote_jid = '{$r['key_remote_jid']}' $conditions")<1){
		continue;
	}

	echo "<h2>".$r['key_remote_jid']."</h2>";

	$re2 = $bd->query("SELECT * FROM messages WHERE key_remote_jid = '{$r['key_remote_jid']}' $conditions ORDER BY received_timestamp;");

	while ( $r2 = $re2->fetchArray(SQLITE3_ASSOC)){ ?>
	<div class="msg <?=($r2['key_from_me'] ? 'own' : '')?> <?=@$_GET['view'] == 'media' ? 'just_media' : ''?>">
		<p><?php if($r2['media_mime_type'] == NULL){
			echo $r2['data'];
		}else{
			echo "<span>media-> {$r2['media_mime_type']}</span>";
			
			switch($r2['media_mime_type']){
				case 'audio/amr':
				case 'audio/mp4':
				case 'audio/3gpp':
					$data = "data:".$r2['media_mime_type'].';base64,'.base64_encode($r2['remote_resource']);
					echo '
					<audio controls>
					  	<source src="'.$data.'" type="'.$r2['media_mime_type'].'">
						audio element
					</audio>
					';
					break;
				default:
				$data = "data:".$r2['media_mime_type'].';base64,'.base64_encode($r2['raw_data']);
				echo "<img src='$data'>";
			} 
		}
		?></p>
		<small><?=date("d-m-Y H:i:s",$r2['received_timestamp'])?></small>
	</div>

<?php
	}

}


?>
