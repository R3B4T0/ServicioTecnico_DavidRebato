<?php

namespace App\Controller;

use App\Entity\Incidencia;
use App\Entity\LineasDeIncidencia;
use App\Entity\Usuario;
use App\Entity\Cliente;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

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
        $form = $this->createFormBuilder($incidencia)
                ->add('titulo', TextType::class,)
                ->add('cliente', EntityType::class, ['class' => Cliente::class, 'choice_label' => 'nombre'])
                ->add('estado', ChoiceType::class, [
                    'choices' =>
                    [
                        'Iniciada' => 'INICIADA',
                        'En Proceso' => 'EN_PROCESO',
                        'Resuelta' => 'RESUELTA',
                    ],
                ])
                ->add('insertar_incidencia', SubmitType::class, 
                        array(
                            'attr' => array('class' => 'btn btn-primary btn-block', 'label' => 'Insertar incidencia')
                        ))
                ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $incidencia = $form->getData();
            //Guardamos el nuevo artículo en la base de datos
            $em = $this->getDoctrine()->getManager();
            $incidencia->setIdUsuario($this->getUser());
            $em->persist($incidencia);
            $em->flush();

            return $this->redirectToRoute('incidencias');
        }
        return $this->render('incidencias/insertar_incidencia2.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/incidencias/insertar/cliente/{id}", name="insertar_incidencia_cliente")
     * IsGranted("ROLE_TECNICO")
     */
    public function insertar_incidencia_cliente(Request $request, Cliente $cliente): Response
    {
        $incidencia = new Incidencia();
        $form = $this->createFormBuilder($incidencia)
                ->add('titulo', TextType::class,)
                ->add('estado', ChoiceType::class, [
                    'choices' =>
                    [
                        'Iniciada' => 'INICIADA',
                        'En Proceso' => 'EN_PROCESO',
                        'Resuelta' => 'RESUELTA',
                    ],
                ])
                ->add('insertar_incidencia', SubmitType::class, 
                        array(
                            'attr' => array('class' => 'btn btn-primary btn-block', 'label' => 'Insertar incidencia')
                        ))
                ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $incidencia = $form->getData();
            //Guardamos el nuevo artículo en la base de datos
            $em = $this->getDoctrine()->getManager();
            $incidencia->setIdUsuario($this->getUser());
            $incidencia->setCliente($cliente);
            $em->persist($incidencia);
            $em->flush();

            return $this->redirectToRoute('incidencias');
        }
        return $this->render('incidencias/insertar_incidencia.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/ver_incidencias/{id}", name="ver_incidencia")
     * @param int $id
     * IsGranted("ROLE_TECNICO")
     */
    public function ver_incidencia(Incidencia $incidencia)
    {
        $repositorio = $this->getDoctrine()->getRepository(LineasDeIncidencia::class);
        $lineaIncidencias = $repositorio->findAll();
        return $this->render('incidencias/ver_incidencia.html.twig', ['incidencia' => $incidencia, 'lineaIncidencia' => $lineaIncidencias]);
    }

    /**
     * @Route("/borrar_incidencia/borrar/{id}", name="borrar_incidencia")
     * @return Response
     * @IsGranted("ROLE_TECNICO")
     */
    public function borrar_incidencia(Incidencia $incidencia): Response 
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($incidencia);
        $em->flush();
        return $this->redirectToRoute('cliente');
    }

    /**
     * @Route("/insertar_comentario/{id}", name="insertar_comentario")
     * @IsGranted("ROLE_TECNICO")
     */
    public function insertar_comentario(Request $request, Incidencia $incidencia): Response
    {
        $lineaIncidencia = new LineasDeIncidencia();
        $form = $this->createFormBuilder($lineaIncidencia)
                ->add('texto', TextType::class,)
                ->add('insertar_comentario', SubmitType::class, 
                        array(
                            'attr' => array('class' => 'btn btn-primary btn-block', 'label' => 'Insertar comentario')
                        ))
                ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $lineaIncidencia = $form->getData();
            //Guardamos el nuevo artículo en la base de datos
            $em = $this->getDoctrine()->getManager();
            $lineaIncidencia->setIncidencia($incidencia);
            $em->persist($lineaIncidencia);
            $em->flush();

            return $this->redirectToRoute('incidencias');
        }
        return $this->render('incidencias/insertar_comentario.html.twig', ['form' => $form->createView(),]);
    }

    /**
     * @Route("/borrar_comentario/{id}", name="borrar_comentario")
     * @return Response
     * @IsGranted("ROLE_TECNICO")
     */
    public function borrar_comentario(LineasDeIncidencia $lineaIncidencia): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($lineaIncidencia);
        $em->flush();
        return $this->redirectToRoute('incidencias');
    }
}
