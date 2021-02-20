<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LineasDeIncidenciaController extends AbstractController
{
    /**
     * @Route("/incidencias/{id}/comentario", name="lineas_de_incidencia")
     * IsGranted("ROLE_TECNICO")
     */
    public function index(): Response
    {
        return $this->render('lineas_de_incidencia/index.html.twig', [
            'controller_name' => 'LineasDeIncidenciaController',
        ]);
    }
}
