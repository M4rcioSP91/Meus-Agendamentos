<?php

require_once __DIR__ . '/../models/GaleriaModel.php';

class GaleriaController
{
    private $galeriaModel;

    public function __construct($pdo)
    {
        $this->galeriaModel = new GaleriaModel($pdo);
    }

    // Lista as imagens
    public function listar()
    {
        return $this->galeriaModel->listar();
    }

    // Faz o upload das imagens
    public function upload($arquivos)
    {
        $pastaUpload = __DIR__ . '/../uploads/galeria/';

        // Cria a pasta se não existir
        if (!is_dir($pastaUpload)) {
            mkdir($pastaUpload, 0755, true);
        }

        $permitidos = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];

        $tamanhoMaximo = 5 * 1024 * 1024;

        $resultado = [];

        foreach ($arquivos['tmp_name'] as $indice => $tmpName) {

            // Verifica erro
            if ($arquivos['error'][$indice] !== UPLOAD_ERR_OK) {

                $resultado[] = [
                    'sucesso' => false,
                    'mensagem' => 'Erro ao enviar a imagem.'
                ];

                continue;
            }

            $nomeOriginal = $arquivos['name'][$indice];
            $tamanho = $arquivos['size'][$indice];

            // Verifica tamanho
            if ($tamanho > $tamanhoMaximo) {

                $resultado[] = [
                    'sucesso' => false,
                    'mensagem' => "A imagem {$nomeOriginal} ultrapassa 5 MB."
                ];

                continue;
            }

            // Descobre o tipo real
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $tipo = finfo_file($finfo, $tmpName);
            finfo_close($finfo);

            // Verifica formato
            if (!in_array($tipo, $permitidos)) {

                $resultado[] = [
                    'sucesso' => false,
                    'mensagem' => "Formato não permitido: {$nomeOriginal}."
                ];

                continue;
            }

            // Define extensão
            switch ($tipo) {

                case 'image/jpeg':
                    $extensao = 'jpg';
                    break;

                case 'image/png':
                    $extensao = 'png';
                    break;

                case 'image/webp':
                    $extensao = 'webp';
                    break;

                default:
                    $extensao = 'jpg';
            }

            // Nome único
            $novoNome = uniqid('galeria_', true) . '.' . $extensao;

            $caminho = $pastaUpload . $novoNome;

            // Move a imagem
            if (move_uploaded_file($tmpName, $caminho)) {

                // Salva no banco
                $salvou = $this->galeriaModel->adicionar($novoNome);

                if ($salvou) {

                    $resultado[] = [
                        'sucesso' => true,
                        'mensagem' => "{$nomeOriginal} enviada com sucesso."
                    ];

                } else {

                    // Se falhar no banco, remove o arquivo
                    if (file_exists($caminho)) {
                        unlink($caminho);
                    }

                    $resultado[] = [
                        'sucesso' => false,
                        'mensagem' => "Erro ao salvar {$nomeOriginal}."
                    ];
                }

            } else {

                $resultado[] = [
                    'sucesso' => false,
                    'mensagem' => "Erro ao salvar {$nomeOriginal}."
                ];
            }
        }

        return $resultado;
    }

    // Exclui imagem
    public function excluir($id)
    {
        $imagem = $this->galeriaModel->buscarPorId($id);

        if (!$imagem) {

            return [
                'sucesso' => false,
                'mensagem' => 'Imagem não encontrada.'
            ];
        }

        $caminho = __DIR__ . '/../uploads/galeria/' . $imagem['imagem'];

        // Remove do banco
        $excluiu = $this->galeriaModel->excluir($id);

        if (!$excluiu) {

            return [
                'sucesso' => false,
                'mensagem' => 'Não foi possível excluir a imagem.'
            ];
        }

        // Remove arquivo
        if (file_exists($caminho)) {
            unlink($caminho);
        }

        return [
            'sucesso' => true,
            'mensagem' => 'Imagem excluída com sucesso.'
        ];
    }
}