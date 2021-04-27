# Pagamentos Ifthenpay
Alternativa de PHP puro para o sistema de pagamentos MBWAY e Ref Multibanco [https://ifthenpay.com/](https://ifthenpay.com/).

Se houver necessidade escrevo uma versão do código em package composer
Qualquer dúvida é so criar um issue, poderei também escrever o código em node.js ou python num breve futuro.

[![ko-fi](https://ko-fi.com/img/githubbutton_sm.svg)](https://ko-fi.com/Y8Y14FZMA)

## Como gerar uma referência

```php
<?php
    
    require("class/multibanco.php");
    
    define("MULTIBANCO_ENTIDADE",       12345);
    define("MULTIBANCO_SUB_ENTIDADE",   123);

    define("URL_PROTOCOL",              "http");
    
    $multibanco = new Multibanco(MULTIBANCO_ENTIDADE, MULTIBANCO_SUB_ENTIDADE, URL_PROTOCOL);
    
    // Id da compra, neste caso um número random
    $order_id       = rand(1000, 10000);
    $order_value    = 39.99; 
    
    // Obter a referência
    echo $multibanco->generate($order_id, $order_value);

?>
```

## Obter o status de uma referência

```php
<?php
    
    require("class/multibanco.php");
    
    define("MULTIBANCO_ENTIDADE",       12345);
    define("MULTIBANCO_SUB_ENTIDADE",   123);

    define("URL_PROTOCOL",              "http");
    
    $multibanco = new Multibanco(MULTIBANCO_ENTIDADE, MULTIBANCO_SUB_ENTIDADE, URL_PROTOCOL);
    $reference  = "123 456 123";

    // Obter o estado do pagamento
    $data = $multibanco->status(IFTHENPAY_BACKOFFICE_KEY, $reference);

    var_dump($data);
?>
```

## Erros do status de pagamento multibanco

| Código | Mensagem |
| --- | --- |
| 0 | Sucesso.|
| 1 | Não existem pagamentos.|
| 2 | Erro nas Datas/Horas.|
| 3 | Chave inválida.|
| 9 | Erro desconhecido.|

## Gerar novo pagamento mbway

```php
<?php 

require("class/mbway.php");

define("MBWAY_KEY",                 "DTM-0000000"); // Key MbWay fornecida pelo IfThenpay
define("IFTHENPAY_MBWAY_CHANNEL",   "03");          // Canal predefinido na documentação do Ifthenpay

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

?>
```

## Obter informações sobre pagamento MBWay

```php
<?php 

require("class/mbway.php");

define("MBWAY_KEY",                 "DTM-0000000"); // Key MbWay fornecida pelo IfThenpay
define("IFTHENPAY_MBWAY_CHANNEL",   "03");          // Canal predefinido na documentação do Ifthenpay

define("URL_PROTOCOL",              "http");

$mbway = new MBWay(MBWAY_KEY, IFTHENPAY_MBWAY_CHANNEL, URL_PROTOCOL);

// Guardado numa database ou algo
$payment_id  = "jf7843hnfiernfui3wn";
$status      = $mbway->status($payment_id);

?>
```

## Erros do status de pagamento MbWay

| Código | Descrição |
| --- | --- |
| 000 | Operação financeira concluída com sucesso |
| 020 | Operação financeira cancelada pelo utilizador |
| 023 | Operação financeira devolvida pelo Comerciante |
| 048 | Operação financeira anulada pelo Comerciante |
| 100 | Não foi possível concluir a Operação |
| 104 | Operação financeira não permitida |
| 111 | O formato do número de telemóvel não se encontrava no formato correto |
| 113 | O número de telemóvel usado como identificador não foi encontrado |
| 122 | Operação recusada ao utilizador |
| 123 | Operação financeira não encontrada |
| 125 | Operação recusada ao utilizador |

## Como funciona uma referência MB 
A referência é composta sempre por 9 dígitos (em grupos de 3 facilita a visualização) e é composta do seguinte modo:

#### SSSDDDDCC

Em que:

```
SSS: três dígitos que identificam a subentidade (o vendedor). Este código é atribuído pela IFTHENPAY.
```

```
DDDD: ID - quatro dígitos que identificam o nº do documento/encomenda a pagar ou o nº do v/ cliente 
(conforme prefiram associar o pagamento a um documento ou a um cliente). 
Este ID terá que ter obrigatoriamente 4 dígitos, pelo que caso o nº do documento/encomenda ou o nº do 
cliente tenha mais que 4 dígito terá que utilizar apenas os 4 mais à direita, caso tenha menos de 4 dígitos
deverá preencher os restantes com zeros à esquerda.
```


```
CC: dois dígitos de controlo (check-digits). Serve para o terminal validar se a informação está correta. 
Nota: Se o dígito de controlo só tiver um algarismo terá que formatá-lo para 2 algarismos
colocando 0 (zero) á esquerda.
```
