<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class APIDocsController extends AbstractController
{
    #[Route('/api-docs', name: 'api_docs')]
    public function index(): Response
    {
        $file = new File('api_docs/apiDocs.yaml');
        return $this->file($file, 'apiDocs.yaml', ResponseHeaderBag::DISPOSITION_INLINE);
    }
}
