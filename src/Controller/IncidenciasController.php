<?php

namespace App\Controller;

use App\Entity\Incidencia;
use App\Entity\LineasDeIncidencia;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class IncidenciasController extends AbstractController
{
    /**
     * @Route("/incidencias", name="incidencias")
     * IsGranted("ROLE_TECNICO)
     */
    public function index(): Response
    {
        $repositorio = $this->getDoctrine()->getRepository(Incidencia::class);
        $incidencias = $repositorio->findAll();
        return $this->render('incidencias/index.html.twig',
                        ['incidencias' => $incidencias]);
    }

    /**
     * @Route("/incidencias/insertar", name="insertar_incidencia")
     * IsGranted("ROLE_TECNICO")
     */
    public function insertar_incidencia(Request $request): Response
    {
        $incidencia = new Incidencia();
        $lineaIncidencia = new LineasDeIncidencia();
        $form = $this->createFormBuilder($incidencia)
                ->add('titulo', TextType::class,)
                ->add('fecha_creacion', PasswordType::class)
                ->add('estado', ChoiceType::class, array(
                    'attr' => array('class' => 'form-control',
                    'style' => 'margin:10px 10px;'),
                    'choices' =>
                    array
                    (
                        'INICIADA' => array
                        (
                            'Iniciada'  => 'INICIADA',
                        ),
                        'EN_PROCESO' => array
                        (
                            'En Proceso' => 'EN_PROCESO',
                        ),
                        'RESUELTA' => array(
                            'Resuelta' => 'RESUELTA',
                        ),
                    ),
                    'multiple' => true,
                    'expanded' => true,
                    'required' => true,
                ))
                ->add('texto', TextType::class)
                ->add('cliente', EntityType::class)
                ->add('usuario', EntityType::class)
                ->add('incidencia', EntityType::class)
                ->add('insertar_incidencia', SubmitType::class, ['label' => 'Insertar incidencia'])
                ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $incidencia = $form->getData();
            $lineaIncidencia = $form->getData();
            //Guardamos el nuevo artículo en la base de datos
            $em = $this->getDoctrine()->getManager();
            $em->persist($incidencia, $lineaIncidencia);
            $em->flush();

            return $this->redirectToRoute('incidencias');
        }
        return $this->render('incidencias/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
