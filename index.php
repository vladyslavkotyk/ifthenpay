<?php 

require("class/multibanco.php");

define("MULTIBANCO_ENTIDADE",       12345);
define("MULTIBANCO_SUB_ENTIDADE",   123);


$multibanco = new Multibanco(MULTIBANCO_ENTIDADE, MULTIBANCO_SUB_ENTIDADE);

// Id da compra, neste caso um número random
$order_id       = rand(1000, 10000);
$order_value    = 39.99; 

// Obter a referência
echo $multibanco->generate($order_id, $order_value);