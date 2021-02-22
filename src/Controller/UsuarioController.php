<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Usuario;
use App\Form\Type\UsuarioType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\File;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class UsuarioController extends AbstractController
{
    /**
     * @Route("/registrar", name="registrar")
     */
    public function registrar(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $roles = $this->getParameter('security.role_hierarchy.roles');
        $usuario = new Usuario();
        $form = $this->createFormBuilder($usuario)
                ->add('email', TextType::class)
                ->add('password', PasswordType::class, ['attr' => ['minLength' => 4]])
                ->add('nombre', TextType::class)
                ->add('apellidos', TextType::class)
                ->add('telefono', TextType::class)
                ->add('foto', FileType::class, [
                    'label' => 'Selecciona Foto',
                    'constraints' => [
                        new File ([
                            'maxSize' => '1024k',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png',
                                'image/gif'
                            ],
                            'mimeTypesMessage' => 'Formato de archivo no válido',
                        ])
                    ]
                ])
                ->add('roles', ChoiceType::class, array(
                    'attr' => array('class' => 'form-control',
                    'style' => 'margin:10px 10px;'),
                    'choices' =>
                    array
                    (
                        'ROLE_ADMINISTRADOR' => array
                        (
                            'Administrador'  => 'ROLE_ADMINISTRADOR',
                        ),
                        'ROLE_TECNICO' => array
                        (
                            'Técnico' => 'ROLE_TECNICO',
                        ),
                    ),
                    'multiple' => true,
                    'expanded' => true,
                    'required' => true,
                ))
                ->add('registrar', SubmitType::class, 
                array(
                    'attr' => array('class' => 'btn btn-primary btn-block', 'label' => 'Registrar')
                ))
                ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $usuario = $form->getData();
            $foto = $form->get('foto')->getData();
            if ($foto) {
                $nuevo_nombre = uniqid() . ' . ' . $foto->guessExtension();
                try {
                    $foto->move('imagenes/', $nuevo_nombre);
                    $usuario->setFoto($nuevo_nombre);
                } catch (FileException $e) {

                }
            }

            //Codificamos el password
            $usuario->setPassword($encoder->encodePassword($usuario, $usuario->getPassword()));
            
            //Guardamos el nuevo artículo en la base de datos
            $em = $this->getDoctrine()->getManager();
            $em->persist($usuario);
            $em->flush();
            $this->addFlash(
                'notice',
                'Usuario registrado correctamente!'
            );

            return $this->redirectToRoute('inicio');
        }
        
        return $this->render('usuario/registrar.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/usuarios", name="usuarios")
     * @IsGranted("ROLE_ADMINISTRADOR")
     */
    public function index(): Response
    {
        $repositorio = $this->getDoctrine()->getRepository(Usuario::class);
        $usuarios = $repositorio->findAll();
        return $this->render('usuario/usuarios.html.twig',
                        ['usuarios' => $usuarios]);
    }

    /**
     * @Route("/usuarios/ver_usuario/{id}", name="ver_usuario", requirements={"id"="\d+"})
     * @IsGranted("ROLE_ADMINISTRADOR")
     * @param int $id
     */
    public function ver_usuario(Usuario $usuario): Response
    {
        return $this->render('usuario/ver_usuario.html.twig',
                        ['usuario' => $usuario]);
    }

    /**
     * @Route("/usuarios/borrar/{id}", name="borrar_usuario")
     * @return Response
     * @IsGranted("ROLE_ADMINISTRADOR")
     */
    public function borrar_usuario(Usuario $usuario): Response 
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($usuario);
        $em->flush();
        $this->addFlash(
            'error',
            'Usuario borrado correctamente'
        );
        return $this->redirectToRoute('usuarios');
    }

    /**
     * @Route("/usuarios/insertar", name="insertar_usuario")
     * @IsGranted("ROLE_ADMINISTRADOR")
     */
    public function insertar_usuario(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $roles = $this->getParameter('security.role_hierarchy.roles');
        $usuario = new Usuario();
        $form = $this->createFormBuilder($usuario)
                ->add('email', TextType::class,)
                ->add('password', PasswordType::class)
                ->add('nombre', TextType::class)
                ->add('apellidos', TextType::class)
                ->add('telefono', TextType::class)
                ->add('foto', FileType::class, [
                    'label' => 'Selecciona Foto',
                    'constraints' => [
                        new File ([
                            'maxSize' => '1024k',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png',
                                'image/gif'
                            ],
                            'mimeTypesMessage' => 'Formato de archivo no válido',
                        ])
                    ]
                ])
                ->add('roles', ChoiceType::class, array(
                    'attr' => array('class' => 'form-control',
                    'style' => 'margin:10px 10px;'),
                    'choices' =>
                    array
                    (
                        'ROLE_ADMINISTRADOR' => array
                        (
                            'Administrador'  => 'ROLE_ADMINISTRADOR',
                        ),
                        'ROLE_TECNICO' => array
                        (
                            'Técnico' => 'ROLE_TECNICO',
                        ),
                    ),
                    'multiple' => true,
                    'expanded' => true,
                    'required' => true,
                ))
                ->add('registrar', SubmitType::class, 
                    array(
                        'attr' => array('class' => 'btn btn-primary btn-block', 'label' => 'Insertar usuario')
                    )
                )
                ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $usuario = $form->getData();
            $foto = $form->get('foto')->getData();
            if ($foto) {
                $nuevo_nombre = uniqid() . ' . ' . $foto->guessExtension();
                try {
                    $foto->move('imagenes/', $nuevo_nombre);
                    $usuario->setFoto($nuevo_nombre);
                } catch (FileException $e) {

                }
            }

            //Codificamos el password
            $usuario->setPassword($encoder->encodePassword($usuario, $usuario->getPassword()));
            
            //Guardamos el nuevo artículo en la base de datos
            $em = $this->getDoctrine()->getManager();
            $em->persist($usuario);
            $em->flush();
            $this->addFlash(
                'notice',
                'Usuario insertador correctamente'
            );

            return $this->redirectToRoute('usuarios');
        }
        return $this->render('usuario/insertar_usuario.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
