<?php
// src/DataTransformer/BookInputDataTransformer.php

namespace App\DataTransformer;

use App\Entity\Menu;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use ApiPlatform\Core\DataTransformer\DataTransformerInterface;

final class MenuInputDataTransformer implements DataTransformerInterface
{
    private $validator;
    
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }
    
    /**
     * {@inheritdoc}
     */
    public function transform($data, string $to, array $context = [])
    {
        //$errors = $this->validator->validate($data);
        //dd($errors);
        //dd($data);
        $menu = new Menu();
        $menu->setNom($data->nom);
        $menu->setFile($data->file);
        foreach ($data->menuBurgers as $value) {
            dd($value);
        }
        return $menu ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof Menu) {
          return false;
        }
        return Menu::class === $to && null !== ($context['input']['class'] ?? null);
    }
}