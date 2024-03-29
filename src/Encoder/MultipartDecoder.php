<?php
// api/src/Encoder/MultipartDecoder.php

namespace App\Encoder;

use App\Service\TransformImageService;
use Doctrine\DBAL\Types\BlobType;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

use function PHPSTORM_META\type;

final class MultipartDecoder implements DecoderInterface
{
    public const FORMAT = 'multipart';

    public function __construct(private RequestStack $requestStack,private TransformImageService $tis) {}
    
    /**
     * {@inheritdoc}
     */
    public function decode(string $data, string $format, array $context = []): ?array
    {
        $request = $this->requestStack->getCurrentRequest();
        if (null != $request->request->get('prix')) {
            $request->request->set('prix',floatval($request->request->get('prix')));
        }
        
        //dd($request);
        //$source = imagecreatefrompng($image_path);
        //dd($this->tis->createPreview($request->files->all()["file"]->getRealPath()));
        //dd($request->request->all());
        //$file = $request->files->all()["file"];
        //$blob = new BlobType();
        //$thumbed = base64_encode(fread(fopen($file, "r"),64));
        //dd($thumbed);

        if (!$request) {
            return null;
        }


        return array_map(static function (string|float $element) {
            // Multipart form values will be encoded in JSON.
            $decoded = json_decode($element, true);
            //dd($decoded);
            return \is_array($decoded) ? $decoded : $element;
        }, $request->request->all()) + $request->files->all();

    }

    /**
     * {@inheritdoc}
     */
    public function supportsDecoding(string $format): bool
    {
        return self::FORMAT === $format;
    }
}