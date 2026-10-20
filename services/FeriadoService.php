
<?php

class FeriadoService
{
    public function listar(int $ano): array
    {
        if ($ano < 1900 || $ano > 2199) {
            throw new InvalidArgumentException('Ano inválido.');
        }

        $pasta = __DIR__ . '/../cache';

        if (!is_dir($pasta) && !mkdir($pasta, 0755, true)) {
            throw new RuntimeException('Erro ao criar cache.');
        }

        $arquivo = $pasta . "/feriados_{$ano}.json";

        // Reutiliza os dados por 24 horas
        if (
            is_file($arquivo) &&
            time() - filemtime($arquivo) < 86400
        ) {
            $dados = json_decode(file_get_contents($arquivo), true);

            if (is_array($dados)) {
                return $dados;
            }
        }

        $url = "https://brasilapi.com.br/api/feriados/v1/{$ano}";

        $curl = curl_init($url);

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => false
        ]);

        $resposta = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($resposta === false || $status !== 200) {
            throw new RuntimeException('BrasilAPI indisponível.');
        }

        $feriados = json_decode($resposta, true);

        if (!is_array($feriados) || !array_is_list($feriados)) {
            throw new RuntimeException('Resposta inválida da BrasilAPI.');
        }

        foreach ($feriados as $feriado) {
            if (
                !is_array($feriado) ||
                !isset($feriado['date'], $feriado['name'])
            ) {
                throw new RuntimeException('Dados de feriados inválidos.');
            }
        }

        file_put_contents($arquivo, json_encode($feriados), LOCK_EX);

        return $feriados;
    }

    public function verificar(string $data): ?array
    {
        $dataObj = DateTimeImmutable::createFromFormat(
            '!Y-m-d',
            $data
        );

        if (!$dataObj || $dataObj->format('Y-m-d') !== $data) {
            throw new InvalidArgumentException('Data inválida.');
        }

        $feriados = $this->listar((int) $dataObj->format('Y'));

        foreach ($feriados as $feriado) {
            if ($feriado['date'] === $data) {
                return $feriado;
            }
        }

        return null;
    }
}
