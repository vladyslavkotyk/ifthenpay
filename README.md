# Pagamentos Ifthenpay
*Alternativa de PHP puro para o sistema de pagamentos MBWAY e Ref Multibanco [https://ifthenpay.com/](https://ifthenpay.com/)
Se houver necessidade escrevo uma versão do código em package composer*

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
cliente tenha mais que 4 dígito terá que utiliza    r apenas os 4 mais à direita, caso tenha menos de 4 dígitos
 deverá preencher os restantes com zeros à esquerda.
```


```
CC: dois dígitos de controlo (check-digits). Serve para o terminal validar se a informação está correta. 
Nota: Se o dígito de controlo só tiver um algarismo terá que formatá-lo para 2 algarismos
 colocando 0 (zero) á esquerda.
```
