<?php

if (!function_exists('onlyNumber')) {
    function onlyNumber(string $value): ?string
    {
        $value = preg_replace("/[^\d]/", "", $value);

        return !empty($value) ? $value : null;
    }
}

if (!function_exists('formatCpf')) {
    function formatCpf(string $value): string
    {
        $value = onlyNumber($value);
        $value = substr($value, 0, 11);

        if (strlen($value) == 11) {
            return substr($value, 0, 3) . '.' . substr($value, 3, 3) . '.' . substr($value, 6, 3) . '-' . substr($value, 9, 2);
        }

        return $value;
    }
}

if (!function_exists('formatCnpj')) {
    function formatCnpj(string $value): string
    {
        $value = onlyNumber($value);
        $value = substr($value, 0, 14);

        if (strlen($value) == 14) {
            return substr($value, 0, 2) . '.' . substr($value, 2, 3) . '.' . substr($value, 5, 3) . '/' . substr($value, 8, 4) . '-' . substr($value, 12, 2);
        }

        return $value;
    }
}

if (!function_exists('formatCpfCnpj')) {
    function formatCpfCnpj(string $value): string
    {
        $value = onlyNumber($value);

        return match(strlen($value)) {
            11 => formatCpf($value),
            14 => formatCnpj($value),
            default => $value,
        };
    }
}

if (!function_exists('formatPhone')) {
    function formatPhone(string $value): string
    {
        $value = onlyNumber($value);
        $value = substr($value, 0, 11);

        return match(strlen($value)) {
            11 => '(' . substr($value, 0, 2) . ') ' . substr($value, 2, 5) . '-' . substr($value, 7, 4),
            10 => '(' . substr($value, 0, 2) . ') ' . substr($value, 2, 4) . '-' . substr($value, 6, 4),
            default => $value,
        };
    }
}

if (!function_exists('formatPostalCode')) {
    function formatPostalCode(string $value): string
    {
        $value = onlyNumber($value);
        $value = substr($value, 0, 8);

        return match(strlen($value)) {
            8 => substr($value, 0, 5) . '-' . substr($value, 5, 3),
            default => $value,
        };
    }
}

if (!function_exists('removeAccent')) {
    function removeAccent(string $value): string
    {
        return preg_replace(
            ["/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ẽ|ë)/", "/(É|È|Ê|Ẽ|Ë)/", "/(í|ì|î|ĩ|ï)/", "/(Í|Ì|Î|Ĩ|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ũ|ü)/", "/(Ú|Ù|Û|Ũ|Ü)/", "/(ć|ĉ|ç|ḉ)/", "/(Ć|Ĉ|Ç|Ḉ)/", "/(ń|ǹ|ñ)/", "/(Ń|Ǹ|Ñ)/"],
            explode(" ", "a A e E i I o O u U c C n N"),
            $value
        );
    }
}

if (!function_exists('formatToFilename')) {
    /**
     * Função responsável por formatar uma string para ser aceito como nome de arquivo
     *
     * @param string $value Texto a ser formatado
     *
     * @access public
     * @return string
     */
    function formatToFilename(string $value): string
    {
        $value = removeAccent($value);
        $value = mb_strtolower($value, 'UTF-8');
        $replace = [' ', '/'];
        $value = str_replace($replace, "_", $value);

        $remove = ['.', ',', '~', '&', '$', '#', '@', '!', '%', '¨', '*', '=', '+', '§', 'º', 'ª', '?', '>', '<', '|'];
        $value = str_replace($remove, '', $value);

        if (empty($value)) {
            $value = 'filename';
        }

        return $value;
    }
}

if (!function_exists('formatStringToFloat')) {
    /**
     * Função responsável por converter um valor em string (0,00) para float (0.00)
     *
     * @param string $value Valor a ser convertido
     * @param int $precision Precisão das cadas decimais (Padrão: 2)
     *
     * @access public
     * @return float
     */
    function formatStringToFloat(string $value, int $precision = 2): float
    {
        $a = strripos($value, ',');
        $b = strripos($value, '.');
        $separator = $a > $b ? ',' : '.';
        $value = (float) preg_replace(["/[^0-9$separator-]/", "/[$separator]/"], ['', '.'], $value);

        return (float) number_format($value, $precision, '.', '');
    }
}

if (!function_exists('formatFloatToMoney')) {
    function formatFloatToMoney(float $value, $locale = 'pt_BR', $currency = 'BRL', int $precision = 2): string
    {
        $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);
        $formatter->setAttribute(NumberFormatter::FRACTION_DIGITS, $precision);

        return $formatter->formatCurrency($value, $currency);
    }
}

if (!function_exists('formatDigitableLine')) {
    /**
     * Função responsável por formatar uma string como uma linha digitável
     */
    function formatDigitableLine(string $value): string
    {
        $oldValue = substr(onlyNumber($value), 0, 48);
        $value = substr($oldValue, 0, 5);

        if (strlen($oldValue) >= 6) {
            $value .= "." . substr($oldValue, 5, 5);
        }
        if (strlen($oldValue) >= 11) {
            $value .= " " . substr($oldValue, 10, 5);
        }
        if (strlen($oldValue) >= 16) {
            $value .= "." . substr($oldValue, 15, 6);
        }
        if (strlen($oldValue) >= 22) {
            $value .= " " . substr($oldValue, 21, 5);
        }
        if (strlen($oldValue) >= 27) {
            $value .= "." . substr($oldValue, 26, 6);
        }
        if (strlen($oldValue) >= 33) {
            $value .= " " . substr($oldValue, 32, 1);
        }
        if (strlen($oldValue) >= 34) {
            $value .= " " . substr($oldValue, 33);
        }

        return $value;
    }
}

if (!function_exists('convertDate')) {
    /**
     * Função responsável por converter uma data de um formato para outro. Ex.: de Y-m-d para d/m/Y
     */
    function convertDate(string $date, string $from = 'Y-m-d', string $to = 'd/m/Y'): string
    {
        $dateTime = DateTime::createFromFormat($from, $date);

        if ($dateTime === false) {
            return $date;
        }

        return $dateTime->format($to);
    }
}

if (!function_exists('dateDiff')) {
    /**
     * Função responsável por calcular a diferença entre duas datas
     *
     * @param string $dateOne Data um
     * @param string $dateTwo Data dois
     * @param int $increment Valor a ser incrementado ao valor final (Padrão: 0)
     * @param string $type Tipo de comparação (D - Diferença em dias; M - Diferença em meses (Idenpendente do dia do mês); RealM/AccountingM - Diferença em meses (Considera o dia do mês para o cálculo); Y - Diferença em anos) (Padrão: M)
     *
     * @access public
     * @return string
     */
    function dateDiff(string $dateOne, string $dateTwo, int $increment = 0, string $type = 'M'): string
    {
        $diff = 0;

        switch ($type) {
            case 'D':
                $dateDiff = date_diff(date_create($dateOne), date_create($dateTwo));
                if ($dateDiff->invert == 0) {
                    $diff = $dateDiff->days;
                } else {
                    $diff = ($dateDiff->days * -1);
                }
                break;
            case 'M':
            case 'RealM':
            case 'AccountingM':
                $years = (date('Y', strtotime($dateTwo)) - date('Y', strtotime($dateOne)));
                $months = (date('m', strtotime($dateTwo)) - date('m', strtotime($dateOne)));
                $diff = ($months + ($years * 12));

                if ($type === 'RealM') {
                    if (date('d', strtotime($dateTwo)) < date('d', strtotime($dateOne))) {
                        $diff--;
                    }
                }

                if ($type === 'AccountingM') {
                    if (date('d', strtotime($dateTwo)) > date('d', strtotime($dateOne))) {
                        $diff++;
                    }
                }
                break;
            case 'Y':
                $diff = (date('Y', strtotime($dateTwo)) - date('Y', strtotime($dateOne)));
                break;
            default:
                return 0;
                break;
        }

        return ($diff += $increment);
    }
}

if (!function_exists('isJson')) {
    /**
     * Função responsável por verificar se uma string é um JSON
     *
     * @param string $string Texto a ser verificado
     *
     * @access public
     * @return bool
     */
    function isJson($string): bool
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}

if (!function_exists('detectCsvDelimiter')) {
    /**
     * @param string $csvFile Caminho do arquivo CSV
     * @return string Delimitador do arquivo CSV
     */
    function detectCsvDelimiter($csvFile): string
    {
        // Lista os delimitadores
        $delimiters = [";" => 0, "," => 0, "\t" => 0, "|" => 0];

        $handle = fopen($csvFile, "r");
        $firstLine = fgets($handle);
        fclose($handle);
        // Percorre os delimitadores e verifica qual o mais usado
        foreach ($delimiters as $delimiter => &$count) {
            // Conta o número de ocorrências de cada delimitador
            $count = count(str_getcsv($firstLine, $delimiter));
        }
        // Retorna o delimitador com maior ocorrência
        return array_search(max($delimiters), $delimiters);
    }
}

if (!function_exists('token64')) {
    /**
     * Função responsável por gerar um token aleatório em base64
     *
     * @access public
     * @return string
     */
    function token64(): string
    {
        $token = '';
        $hash = [1, 2, 3, 9, 8, 7, 6, 5, 4, 5, 8, 2, 9, 3, 6, 4, 7, 1, 3, 5, 7, 9, 5, 1];
        for ($i = 0; $i < 6; $i++) {
            $number = $hash[random_int(0, 23)];
            $token = $token . $number;
        }

        return base64_encode($token);
    }
}

if (!function_exists('incrementMonth')) {
    /**
     * Função responsável por incrementar meses exatos a uma data (Ex.: 2022-01-31 + 1 mês = 2022-02-28)
     *
     * @param string $date Data a ser incrementada
     * @param int|string Dia padrão para o incremento, será o dia utilizado para setar no próximo mês caso o mesmo possua tal dia (Caso não informado pega o da data)
     * @param int $months Quantidade de meses a incrementar (Padrão: 1)
     *
     * @access public
     * @return string
     */
    function incrementMonth(string $date, $day = null, int $months = 1)
    {
        if (empty($day)) {
            $day = date('d', strtotime($date));
        }

        $year = date('Y', strtotime($date));
        $month = date('m', strtotime($date));
        $newDate = date('Y-m-' . str_pad($day, 2, '0', STR_PAD_LEFT), strtotime($year . '-' . $month . '-01' . " +$months months"));

        $diff = dateDiff(date('Y-m-d', strtotime($date)), date('Y-m-d', strtotime($newDate)));

        if ($diff == $months) {
            return $newDate;
        } else {
            $soma = (int)$month + $months;
            if ($soma > 12) {
                $diffYears = (int)($soma / 12);
                $year += $diffYears;
                $month = $soma - ($diffYears * 12);
                return date('Y-m-t', strtotime($year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-01'));
            }
            $month += $months;
            return date('Y-m-t', strtotime($year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-01'));
        }
    }
}

if (!function_exists('passwordForce')) {
    /**
     * Calcula o nível de força de uma senha (1 - Fraca; 2 - Regular; 6 - Forte; 8 - Muito Forte)
     *
     * @param string $password Senha
     *
     * @access public
     * @return int
     */
    function passwordForce($password)
    {
        $pointLength = 1;
        $pointHas = 1;
        $hasNumber = preg_match('/[0-9]/', $password);
        $hasLower = preg_match('/[a-z]/', $password);
        $hasUpper = preg_match('/[A-Z]/', $password);
        $hasSymbol = preg_match('/[!@#$%&]/', $password);

        if (strlen($password) <= 3) {
            $pointLength = 1;
        } else if (strlen($password) <= 7) {
            $pointLength = 2;
        } else if (strlen($password) <= 12) {
            $pointLength = 6;
        } else {
            $pointLength = 8;
        }

        if ($hasNumber && $hasLower && $hasUpper && $hasSymbol) {
            $pointHas = 8;
        } else if ($hasNumber && $hasLower && $hasUpper) {
            $pointHas = 6;
        } else if (($hasNumber && $hasLower) || ($hasNumber && $hasUpper) || ($hasLower && $hasUpper)) {
            $pointHas = 2;
        }

        return ($pointLength + $pointHas) / 2;
    }
}

if (!function_exists('generateCodeNumeric')) {
    /**
     * Função responsável por gerar um código numérico aleatório
     *
     * @param int $length Tamanho do código gerado
     * @return string
     */
    function generateCodeNumeric(int $length): string
    {
        $numero = '';
        for ($x = 0; $x < $length; $x++) {
            $numero .= rand(0, 9);
        }
        return $numero;
    }
}

if (!function_exists('generateId')) {
    /**
     * Função responsável por gerar um ID baseado em microtime
     *
     * @return string
     */
    function generateId(): string
    {
        return substr(str_replace(',', '', number_format(microtime(true) * 1000000, 0)), 0, 15);
    }
}

if (!function_exists('generatePassword')) {
    /**
     * Função responsável por gerar uma senha aletatória
     *
     * @param int $length Tamanho da senha
     * @param int $contain Tipos de caracteres que a senha deve conter (1 - Números; 2 - Letras; 3 - Números e Letras; 4 - Números, Letras e Simbolos('!@#$%&().'))
     *
     * @return string
     */
    function generatePassword(int $length, int $contain = 3)
    {
        $letrasMinusculas = explode(' ', 'a b c d e f g h i j k l m n o p q r s t u v w x y z');
        $letrasMaiusculas = explode(' ', 'A B C D E F G H I J K L M N O P Q R S T U V W X Y Z');
        $simbolos = explode(' ', '! @ # $ % &');
        $numeros = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];

        switch ($contain) {
            case 1:
                $hash = [$numeros];
                break;
            case 2:
                $hash = [$letrasMinusculas, $letrasMaiusculas];
                break;
            case 4:
                $hash = [$letrasMinusculas, $numeros, $simbolos, $letrasMaiusculas];
                break;
            default:
                $hash = [$letrasMinusculas, $numeros, $letrasMaiusculas];
                break;
        }


        $password = '';
        for ($x = 0; $x < $length; $x++) {
            $position = rand(0, (count($hash) - 1));
            $subposition = rand(0, (count($hash[$position]) - 1));
            $password .= $hash[$position][$subposition];
        }

        return $password;
    }
}

if (!function_exists('monthNameBr')) {
    function monthNameBr(int|string $month, $abbreviated = false): string
    {
        $month = str_pad($month, 2, '0', STR_PAD_LEFT);

        $month = match($month) {
            '01' => 'Janeiro',
            '02' => 'Fevereiro',
            '03' => 'Março',
            '04' => 'Abril',
            '05' => 'Maio',
            '06' => 'Junho',
            '07' => 'Julho',
            '08' => 'Agosto',
            '09' => 'Setembro',
            '10' => 'Outubro',
            '11' => 'Novembro',
            '12' => 'Dezembro',
            default => '',
        };

        if ($month && $abbreviated) {
            $month = substr($month, 0, 3);
        }

        return $month;
    }
}

if (!function_exists('incrementBussinessDay')) {
    /**
     * Função responsável por incrementar dias úteis a uma data inicial
     *
     * @param string $date Data inicial para o calculo (Obrigatório)
     * @param int $days Quantidade de dias a ser incrementado (Obrigatório)
     * @param array $holidays Array com os feriados do ano/mês, as datas devem vir no formato Y-m-d (Opcional)
     */
    function incrementBussinessDay(string $date, int $days, array $holidays = []): string
    {
        for ($i = 1; $i <= $days; $i++) {
            $date = date('Y-m-d', strtotime($date . ' +1 day'));

            // Tratando feriados, sabados e domingos
            $weekDay = date('w', strtotime($date));

            if (in_array($weekDay, [0, 6]) || in_array($date, $holidays)) {
                $days++;
            }
        }

        return $date;
    }
}

if (!function_exists('decrementBussinessDay')) {
    /**
     * Função responsável por decrementar dias úteis a uma data inicial
     *
     * @param string $date Data inicial para o calculo (Obrigatório)
     * @param string $days Quantidade de dias a ser decrementado (Obrigatório)
     * @param array $holidays Array com os feriados do ano/mês, as datas devem vir no formato Y-m-d (Opcional)
     */
    function decrementBussinessDay(string $date, int $days, $holidays = [])
    {
        for ($i = 1; $i <= $days; $i++) {
            $date = date('Y-m-d', strtotime($date . ' -1 day'));

            // Tratando feriados, sabados e domingos
            $weekDay = date('w', strtotime($date));

            if (in_array($weekDay, [0, 6]) || in_array($date, $holidays)) {
                $days++;
            }
        }

        return $date;
    }
}

if (!function_exists('isLeapYear')) {
    function isLeapYear(int $year = 0): bool
    {
        $div = $year % 4;

        if ($div === 0) {
            $div = $year % 100;
            if ($div === 0) {
                $div = $year % 400;
                if ($div === 0) {
                    return true;
                }
            } else {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('imageCoordinates')) {
    function imageCoordinates(string $path): array
    {
        $fopen = fopen($path, 'rb');
        $exif = exif_read_data($fopen);
        $lat = getCoordinates($exif["GPSLatitude"], $exif['GPSLatitudeRef']);
        $long = getCoordinates($exif["GPSLongitude"], $exif['GPSLongitudeRef']);

        return [
            'lat' => $lat,
            'long' => $long
        ];
    }
}

if (!function_exists('getCoordinates')) {
    function getCoordinates($coordenada, $hemisferio): int|float
    {
        for ($i = 0; $i < 3; $i++) {
            $part = explode('/', $coordenada[$i]);
            if (count($part) == 1) {
                $coordenada[$i] = $part[0];
            } else if (count($part) == 2) {
                $coordenada[$i] = floatval($part[0]) / floatval($part[1]);
            } else {
                $coordenada[$i] = 0;
            }
        }
        list($degrees, $minutes, $seconds) = $coordenada;
        $sign = ($hemisferio == 'W' || $hemisferio == 'S') ? -1 : 1;
        $coord = $sign * ($degrees + $minutes / 60 + $seconds / 3600);
        return $coord;
    }
}

if (!function_exists('sumString')) {
    /**
     * Função responsável por somar os caracteres
     *
     * @param string $number Número a ter seus caracteres somados
     *
     * @access public
     * @return int
     */
    function sumString(string $number): int
    {
        $data = str_split($number);
        $soma = 0;

        foreach ($data as $n) {
            $soma += (int)$n;
        }

        return $soma <= 9 ? $soma : sumString($number);
    }
}

if (!function_exists('formatJsonString')) {
    function formatJsonString(string|array|object $data, int $step = 1, $br = "\r\n"): string
    {
        if (is_string($data) && !isJson($data) || (!is_string($data) && !is_object($data) && !is_array($data))) {
            return '"' . $data . '"';
        }

        if (!is_string($data)) {
            $data = json_encode($data);
        }

        $return = '';
        $spaces = $step * 4;
        $dataObject = json_decode($data);

        if (is_object($dataObject)) {
            $dataArray = json_decode(json_encode($dataObject), true);

            $return .= "{" . $br;
            $inputs = [];
            foreach ($dataArray as $key => $value) {
                $input = '';
                $input .= addSpacesString($input, $spaces);

                if (is_array($dataObject->$key) || is_object($dataObject->$key)) {
                    $input .= '"' . $key . '": ' . formatJsonString($value, ($step + 1), $br);
                    $inputs[] = $input;
                    continue;
                }

                if (is_bool($value)) {
                    $value = $value ? 'true' : 'false';
                } else if (!is_null($value)) {
                    if ((int)$value == $value) {
                        $value = (int)$value;
                    } else if ((float)$value == $value) {
                        $value = (float)$value;
                    } else {
                        $value = '"' . $value . '"';
                    }
                } else {
                    $value = 'null';
                }

                $input .= '"' . $key . '": ' . $value;
                $inputs[] = $input;
            }
            $return .= implode(',' . $br, $inputs);
            $return .= $br;
            $return = addSpacesString($return, ($spaces - 4));
            $return .= "}";
        } else if (is_array($dataObject)) {
            $return .= "[" . $br;
            $inputs = [];
            foreach ($dataObject as $value) {
                $input = '';
                $input .= addSpacesString($input, $spaces);
                $input .= formatJsonString($value, ($step + 1), $br);
                $inputs[] = $input;
            }
            $return .= implode(',' . $br, $inputs);
            $return .= $br;
            $return = addSpacesString($return, ($spaces - 4));
            $return .= "]";
        }

        return $return;
    }
}

if (!function_exists('addSpacesString')) {
    /**
     * Função responsável por adicionar espaços a uma string
     *
     * @param string $string Texto a ser adicionado os espaços
     * @param int $spaces Quantidade de espaços
     *
     * @access public
     * @return string
     */
    function addSpacesString(string $string, int $spaces = 1): string
    {
        if ($spaces <= 0) {
            return $string;
        }

        $string = str_pad($string, (strlen($string) + $spaces), ' ', STR_PAD_RIGHT);
        $string = str_replace(' ', '&nbsp;', $string);

        return $string;
    }
}
