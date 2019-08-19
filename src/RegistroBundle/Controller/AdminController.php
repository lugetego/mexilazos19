<?php

namespace RegistroBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use RegistroBundle\Entity\Registro;
use RegistroBundle\Form\RegistroType;

/**
 * Register controller.
 *
 * @Route("/admin")

 * @Method("GET")
 * @Template()
 */

class AdminController extends Controller
{
    /**
     * Lists all Register entities.
     *
     * @Route("/", name="admin")
     * @Template("admin/login.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $registros = $em->getRepository('RegistroBundle:Form')->findAll();
        return $this->render('form/index.html.twig', array(
            'registros' => $registros,
        ));
    }

    /**
     * Displays a form to edit an existing Referencia entity.
     *
     * @Route("/{id}/eval", name="form_eval")
     * @Template("admin/eval.html.twig")
     * @Method({"GET", "POST"})
     */
    public function evalAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('RegistroBundle:Form')->find($id);

        $formEval = $this->createFormBuilder($entity)
            ->add('aceptado', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', array(
                'choices'=>array(
                    true=>'Si',
                    false=>'No'),
                'expanded'=>true,
                'required'=>false,
                'placeholder'=>false,
            ))
            ->add('confirmado', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', array(
                'choices'=>array(
                    true=>'Si',
                    false=>'No'),
                'expanded'=>true,
                'required'=>false,
                'placeholder'=>false,
            ))
            ->add('comentarios', 'Symfony\Component\Form\Extension\Core\Type\TextareaType',array('label'=>'Comentarios','required'=>false))
            ->getForm();

        $formEval->handleRequest($request);

        if ($formEval->isSubmitted() && $formEval->isValid()) {

                $em->persist($entity);
                $em->flush();

            return $this->redirectToRoute('form_show', array('id' => $id));

        }
        // $form   = $this->createForm($formEval, $entity);
        return $this->render('admin/eval.html.twig', array('form' => $formEval->createView(),'id'=> $id));
    }



    /**
     * Envio de correos a estudiantes
     *
     * @Route("/correo", name="form_correo")
     * @Method({"GET", "POST"})
     */
    public  function correoConfirmacionAction()
    {

        $em = $this->getDoctrine()
            ->getRepository('RegistroBundle:Form');

        $query = $em->createQueryBuilder('p')
            ->where('p.aceptado = :true')
            ->andwhere('p.confirmado is NULL')
            ->setParameter('true', true)
            ->getQuery();

        $entities = $query->getResult();

        $mailer = $this->get('mailer');

        foreach ($entities as $receipient) {
            $message = \Swift_Message::newInstance()
                ->setSubject('Confirmación de Asistencia XVIII Escuela de Verano')
                ->setFrom('webmaster@matmor.unam.mx')
                ->setTo(array($receipient->getMail()))
                ->setBcc(array('gerardo@matmor.unam.mx'))
                ->setBody($this->renderView('form/mailAsistencia.txt.twig', array('entity' => $receipient)))
            ;
            $mailer->send($message);
        }

        return $this->render('form/correo.html.twig', array(
            'entity' => $entities,
        ));
    }

    /**
     * Displays a list of mails
     *
     * @Route("/eval/mails", name="mails")
     * @Template()
     */
    public  function mailAction()
    {
        $em = $this->getDoctrine()->getManager();
        $registros = $em->getRepository('RegistroBundle:Form')->findAll();

        //return $this->render('CcmEmmbioBundle:Regs:mails.html.twig', array('entities' => $entities));

    }

}
