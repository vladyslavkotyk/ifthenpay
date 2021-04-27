<?php 

require("class/mbway.php");

define("MBWAY_KEY",                 "DTM-0000000"); // Key MbWay fornecida pelo IfThenpay
define("IFTHENPAY_MBWAY_CHANNEL",   "03"); // Canal predefinido na documentação do Ifthenpay

define("URL_PROTOCOL",              "http");

$mbway = new MBWay(MBWAY_KEY, IFTHENPAY_MBWAY_CHANNEL, URL_PROTOCOL);

// Id da compra, neste caso um número random
$phone_number           = "910000000";
$customer_email         = "teste@email.com";
$description            = "Compra teste";
$internal_reference     = rand(1000, 10000);
$order_value            = 0.01;

// Obter a referência
$status = $mbway->create($phone_number, $internal_reference, $order_value, $customer_email, $description);

// Guarda este ID numa database para futuro processamento de dados
$payment_id = $status["IdPedido"];

// Obter o status do pagamento
var_dump($mbway->status($payment_id));