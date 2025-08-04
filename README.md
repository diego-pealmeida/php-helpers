# 📦 PHP Helpers - pealmeida/php-helpers

Uma coleção de funções auxiliares úteis para projetos PHP. Esta biblioteca traz uma série de utilitários prontos para uso, como formatação de CPF/CNPJ, validações, manipulação de datas, strings e muito mais.

---

## 🧩 Instalação

Você pode instalar esta biblioteca via Composer:

```bash
composer require pealmeida/php-helpers
```

🚀 Como Usar
Após instalar com Composer, as funções já estarão disponíveis globalmente:

```php
$value = formatCpfCnpj("12345678900");
echo $value; // 123.456.789-00
```

## 🛠️ Funções Disponíveis

### 🔢 Strings e Formatações

- addSpacesString(string $text, int $every): string
    Adiciona espaços a cada X caracteres na string. 🔢 Strings e Formatações

- onlyNumber(string $value): ?string
    Remove todos os caracteres não numéricos de uma string.

- formatCpf(string $value): string
    Formata um CPF com máscara.

- formatCnpj(string $value): string
    Formata um CNPJ com máscara.

- formatCpfCnpj(string $value): string
    Detecta e formata CPF ou CNPJ automaticamente.

- formatPhone(string $value): string
    Formata um telefone brasileiro com DDD.

- formatPostalCode(string $value): string
    Formata um CEP (código postal brasileiro) com máscara.

- removeAccent(string $string): string
    Remove acentos de uma string.

- formatToFilename(string $string): string
    Converte string para nome de arquivo válido.

- formatStringToFloat(string $value): float
    Converte string com vírgula para float ("1.234,56" → 1234.56).

- formatFloatToMoney(float $value, string $locale, string $currency, int $precision): string
    Formata número float como valor monetário.

- formatDigitableLine(string $line): string
    Adiciona espaços e máscara em linha digitável de boleto.

- formatJsonString(string $json): string
    Retorna JSON formatado com indentação.

### 📅 Datas

- convertDate(string $date, string $from, string $to): string
    Converte datas entre formatos personalizados.

- dateDiff(string $start, string $end, string $unit = 'days'): int
    Calcula a diferença entre duas datas.

- incrementMonth(string $date, int $months): string
    Soma meses em uma data (Y-m-d).

- incrementBussinessDay(string $date, int $days): string
    Soma dias úteis em uma data.

- decrementBussinessDay(string $date, int $days): string
    Subtrai dias úteis de uma data.

- isLeapYear(int $year): bool
    Verifica se o ano é bissexto.

- monthNameBr(int $month): string
    Retorna o nome do mês em português.

### 🔐 Segurança e Tokens

- token64(int $bytes = 32): string
    Gera um token aleatório base64 seguro.

- passwordForce(string $password): int
    Mede a força de uma senha (retorna um score).

- generatePassword(int $length = 10): string
    Gera uma senha aleatória.

### 🆔 Geradores

- generateCodeNumeric(int $length): string
    Gera um código numérico com N dígitos.

- generateId(): string
    Gera um ID único com base em timestamp + random.

### 🧪 Utilidades Gerais

- isJson(string $string): bool
    Verifica se uma string é um JSON válido.

- detectCsvDelimiter(string $csv): string
    Detecta automaticamente o delimitador de um CSV.

- sumString(string $value): int
    Soma todos os dígitos numéricos de uma string.

### 🖼️ Imagem / Geolocalização

- imageCoordinates(string $imagePath): ?array
    Extrai coordenadas geográficas de uma imagem JPEG (EXIF).

- getCoordinates(string $value): array
    Converte formato de coordenadas EXIF para latitude e longitude decimal.

## 🧪 Exemplos de Uso

```php
echo formatPhone('11987654321');         // (11) 98765-4321
echo formatCpf('12345678900');           // 123.456.789-00
echo formatFloatToMoney(1234.56, 'pt_BR', 'BRL', 2);        // R$ 1.234,56
echo convertDate('25/07/2025', 'd/m/Y', 'Y-m-d'); // 2025-07-25
echo passwordForce('MinhaSenha123!');    // força da senha
```
