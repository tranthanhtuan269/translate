<?php 
echo 'ALTER TABLE translate_texts PARTITION BY RANGE(category_id) (<br/>';
for($i = 0; $i < 60; $i++){
	echo 'PARTITION cat'. ($i + 1) .' VALUES LESS THAN ('. ($i + 2) .'),<br/>';
}
echo ');';

?>