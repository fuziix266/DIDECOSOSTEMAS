<?php
$url = 'http://localhost/didecosistemas/public/departamentos/discapacidad/solicitud-terapia-ocupacional';
$headers = get_headers($url, 1);
print_r($headers);
