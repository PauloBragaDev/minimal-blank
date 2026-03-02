<?php

namespace Source\Support;

use CoffeeCode\Uploader\File;
use CoffeeCode\Uploader\Image;
use CoffeeCode\Uploader\Media;

/**
 * FSPHP | Class Upload
 *
 * @author Robson V. Leite <cursos@upinside.com.br>
 * @package Source\Support
 */
class Upload
{
    /** @var Message */
    private $message;

    /**
     * Upload constructor.
     */
    public function __construct()
    {
        $this->message = new Message();
    }

    /**
     * @return Message
     */
    public function message(): Message
    {
        return $this->message;
    }

    /**
     * @param array $image
     * @param string $name
     * @param string $pathStore
     * @param int $width
     * @param bool $allowUpscale
     * @return null|string
     * @throws \Exception
     */
    public function image(array $image, 
                          string $name, 
                          string $pathStore,
                          int $width = CONF_IMAGE_SIZE, 
                          bool $allowUpscale = false): ?string
    {
        $upload = new Image(CONF_UPLOAD_DIR, CONF_UPLOAD_MEDIA_DIR . '/' . $pathStore);
        if (empty($image['type']) || !in_array($image['type'], $upload::isAllowed())) {
            $this->message->error("Você não selecionou uma imagem válida");
            return null;
        }

        return $upload->upload($image, $name, $width, CONF_IMAGE_QUALITY, $allowUpscale);
    }

    /**
     * @param array $file
     * @param string $name
     * @return null|string
     * @throws \Exception
     */
    public function file(array $file, string $name): ?string
    {
        $upload = new File(CONF_UPLOAD_DIR, CONF_UPLOAD_RESUME);
        if (empty($file['type']) || !in_array($file['type'], $upload::isAllowed())) {
            $this->message->error("Você não selecionou um arquivo válido");
            return null;
        }

        return $upload->upload($file, $name);
    }

    /**
     * @param array $file
     * @param string $name
     * @param string $path
     * @return null|string
     * @throws \Exception
     */
    public function fileCollaborators(array $file, string $name, string $path): ?string
    {
        $upload = new File(CONF_UPLOAD_DIR.'/'.CONF_UPLOAD_COLLABORATORS, $path);
        if (empty($file['type']) || !in_array($file['type'], $upload::isAllowed())) {
            $this->message->error("Você não selecionou um arquivo válido");
            return null;
        }

        return $upload->upload($file, $name);
    }

    /**
     * @param array $media
     * @param string $name
     * @return null|string
     * @throws \Exception
     */
    public function media(array $media, string $name): ?string
    {
        $upload = new Media(CONF_UPLOAD_DIR, CONF_UPLOAD_MEDIA_DIR);
        if (empty($media['type']) || !in_array($media['type'], $upload::isAllowed())) {
            $this->message->error("Você não selecionou uma mídia válida");
            return null;
        }

        return $upload->upload($media, $name);
    }

    /**
     * Processa imagem para formato Web Story (9:16)
     * Redimensiona e corta a imagem para manter proporção 9:16
     * Garante tamanho mínimo de 1080x1920px
     * 
     * @param array $image Array do $_FILES
     * @param int $minWidth Largura mínima (padrão: 1080)
     * @param int $minHeight Altura mínima (padrão: 1920)
     * @return array|null Array atualizado do arquivo ou null em caso de erro
     */
    public function processWebStoryImage(array $image, int $minWidth = 1080, int $minHeight = 1920): ?array
    {
        // Verificar se GD está disponível
        if (!extension_loaded('gd')) {
            $this->message->error("Extensão GD não está disponível no servidor");
            return null;
        }

        // Verificar se o arquivo existe
        if (empty($image['tmp_name']) || !file_exists($image['tmp_name'])) {
            $this->message->error("Arquivo de imagem não encontrado");
            return null;
        }

        // Obter informações da imagem
        $imageInfo = @getimagesize($image['tmp_name']);
        if ($imageInfo === false) {
            $this->message->error("Não foi possível ler as informações da imagem");
            return null;
        }

        $originalWidth = $imageInfo[0];
        $originalHeight = $imageInfo[1];
        $mimeType = $imageInfo['mime'];

        // Proporção alvo (9:16)
        $targetRatio = 9 / 16;

        // Calcular dimensões finais mantendo proporção 9:16
        // Garantir que seja pelo menos o tamanho mínimo
        $finalWidth = max($minWidth, $originalWidth);
        $finalHeight = max($minHeight, $originalHeight);

        // Ajustar para manter proporção 9:16
        $currentRatio = $finalWidth / $finalHeight;
        
        if ($currentRatio > $targetRatio) {
            // Imagem está mais larga que o ideal, ajustar largura
            $finalWidth = (int)($finalHeight * $targetRatio);
        } else {
            // Imagem está mais alta que o ideal, ajustar altura
            $finalHeight = (int)($finalWidth / $targetRatio);
        }

        // Garantir tamanho mínimo novamente após ajuste de proporção
        if ($finalWidth < $minWidth) {
            $finalWidth = $minWidth;
            $finalHeight = (int)($finalWidth / $targetRatio);
        }
        if ($finalHeight < $minHeight) {
            $finalHeight = $minHeight;
            $finalWidth = (int)($finalHeight * $targetRatio);
        }

        // Criar imagem a partir do arquivo
        $sourceImage = null;
        switch ($mimeType) {
            case 'image/jpeg':
            case 'image/jpg':
                $sourceImage = @imagecreatefromjpeg($image['tmp_name']);
                break;
            case 'image/png':
                $sourceImage = @imagecreatefrompng($image['tmp_name']);
                break;
            case 'image/gif':
                $sourceImage = @imagecreatefromgif($image['tmp_name']);
                break;
            case 'image/webp':
                $sourceImage = @imagecreatefromwebp($image['tmp_name']);
                break;
            default:
                $this->message->error("Formato de imagem não suportado: {$mimeType}");
                return null;
        }

        if ($sourceImage === false) {
            $this->message->error("Não foi possível criar a imagem a partir do arquivo");
            return null;
        }

        // Calcular dimensões de crop para manter proporção
        $sourceRatio = $originalWidth / $originalHeight;
        
        if ($sourceRatio > $targetRatio) {
            // Imagem original é mais larga, cortar laterais
            $cropHeight = $originalHeight;
            $cropWidth = (int)($originalHeight * $targetRatio);
            $cropX = (int)(($originalWidth - $cropWidth) / 2);
            $cropY = 0;
        } else {
            // Imagem original é mais alta, cortar topo/baixo
            $cropWidth = $originalWidth;
            $cropHeight = (int)($originalWidth / $targetRatio);
            $cropX = 0;
            $cropY = (int)(($originalHeight - $cropHeight) / 2);
        }

        // Criar nova imagem com dimensões finais
        $newImage = imagecreatetruecolor($finalWidth, $finalHeight);
        
        // Manter transparência para PNG
        if ($mimeType === 'image/png') {
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
            $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
            imagefill($newImage, 0, 0, $transparent);
        } else {
            // Fundo branco para outros formatos
            $white = imagecolorallocate($newImage, 255, 255, 255);
            imagefill($newImage, 0, 0, $white);
        }

        // Redimensionar e cortar
        imagecopyresampled(
            $newImage,           // Imagem destino
            $sourceImage,        // Imagem origem
            0, 0,                // X, Y destino
            $cropX, $cropY,      // X, Y origem (crop)
            $finalWidth,         // Largura destino
            $finalHeight,        // Altura destino
            $cropWidth,          // Largura origem (crop)
            $cropHeight          // Altura origem (crop)
        );

        // Criar novo arquivo temporário
        $newTmpFile = tempnam(sys_get_temp_dir(), 'webstory_');
        if ($newTmpFile === false) {
            imagedestroy($sourceImage);
            imagedestroy($newImage);
            $this->message->error("Não foi possível criar arquivo temporário");
            return null;
        }

        // Salvar imagem processada
        $saved = false;
        switch ($mimeType) {
            case 'image/jpeg':
            case 'image/jpg':
                $saved = @imagejpeg($newImage, $newTmpFile, 90);
                break;
            case 'image/png':
                $saved = @imagepng($newImage, $newTmpFile, 9);
                break;
            case 'image/gif':
                $saved = @imagegif($newImage, $newTmpFile);
                break;
            case 'image/webp':
                $saved = @imagewebp($newImage, $newTmpFile, 90);
                break;
        }

        // Limpar memória
        imagedestroy($sourceImage);
        imagedestroy($newImage);

        if (!$saved) {
            @unlink($newTmpFile);
            $this->message->error("Não foi possível salvar a imagem processada");
            return null;
        }

        // Atualizar array do arquivo
        $processedImage = $image;
        $processedImage['tmp_name'] = $newTmpFile;
        $processedImage['size'] = filesize($newTmpFile);

        return $processedImage;
    }

    /**
     * @param string $filePath
     */
    public function remove(string $filePath): void
    {
        if (file_exists($filePath) && is_file($filePath)) {
            unlink($filePath);
        }
    }
}