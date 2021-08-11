<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileUploadRequest;
use Illuminate\Http\Request;
use vinicinbgs\Autentique\Documents;

class AutentiqueController extends Controller
{
    protected $documents;

    public function __construct()
    {
        $this->documents = new Documents(env('AUTENTIQUE_TOKEN'));
    }

    public function listAll($page = 1)
    {
        return $this->documents->listAll($page); // if not isset $page is equal 1
    }

    public function listById($documentId)
    {
        return $this->documents->listById($documentId);
    }

    public function create($name, $email, $pathWithName, $emailPrincipal = 'eloisactrindade21@gmail.com')
    {
        $attributes = [
            'document' => [
                'name' => $name,
            ],
            'signers' => [
                [
                    'email' => $email,
                    'action' => 'SIGN',
                    'positions' => [
                        [
                            'x' => '14', // Posição do Eixo X da ASSINATURA (0 a 100)
                            'y' => '36', // Posição do Eixo Y da ASSINATURA (0 a 100)
                            'z' => '1', // Página da ASSINATURA
                        ],
                    ],
                ],
                [
                    'email' => $emailPrincipal,
                    'action' => 'SIGN',
                    'positions' => [
                        [
                            'x' => '52', // Posição do Eixo X da ASSINATURA (0 a 100)
                            'y' => '36', // Posição do Eixo Y da ASSINATURA (0 a 100)
                            'z' => '1', // Página da ASSINATURA
                        ],
                    ],
                ],
            ],
            'file' => $pathWithName,
        ];

        return $this->documents->create($attributes);
    }
}
