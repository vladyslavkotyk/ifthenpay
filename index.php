<?php 

require("class/multibanco.php");

define("MULTIBANCO_ENTIDADE",       12354);
define("MULTIBANCO_SUB_ENTIDADE",   123);

define("IFTHENPAY_BACKOFFICE_KEY",  "1234-1234-1234-1234");

define("URL_PROTOCOL",              "http");


$multibanco = new Multibanco(MULTIBANCO_ENTIDADE, MULTIBANCO_SUB_ENTIDADE, URL_PROTOCOL);

// Id da compra, neste caso um número random
$order_id       = rand(1000, 10000);
$order_value    = 39.99;

// Obter a referência
$reference = $multibanco->generate($order_id, $order_value);

echo $reference;

// Obter o status do pagamento
var_dump($multibanco->status(IFTHENPAY_BACKOFFICE_KEY, $reference));