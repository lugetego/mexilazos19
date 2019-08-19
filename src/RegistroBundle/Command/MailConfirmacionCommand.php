<?php

namespace RegistroBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use RegistroBundle\Entity\Registro;
use RegistroBundle\Form\RegistroType;


class MailConfirmacionCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('mail:confirmacion')
            ->setDescription('Pide solicitud de confirmacion')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
            ->addOption(
                'send',
                null,
                InputOption::VALUE_NONE,
                'Opción para enviar correos de solicitud de recomendación'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $output->writeln(array(
            '<info>Escuela de Verano 2019</>',

        ));


        $em = $this->getContainer()->get('doctrine')->getManager();
        $query = $em->createQuery(
            'SELECT r
            FROM RegistroBundle:Form r
            WHERE r.aceptado = TRUE AND r.confirmado IS NULL'
        );


        $registros = $query->getResult();


        $io = new SymfonyStyle($input, $output);
        $io->title('Solicitud de confirmacion de participacion');

        foreach($registros as $reg) {

            if($input->getOption('send')) {

             $mailer = $this->getContainer()->get('mailer');

                // Envía correo al participante
                $message = \Swift_Message::newInstance()
                    ->setSubject('Confirmación de Asistencia XVII Escuela de Verano')
                    ->setFrom('webmaster@matmor.unam.mx')
                    ->setTo(array($reg->getMail()))
                    ->setBcc(array('gerardo@matmor.unam.mx'))
                    ->setBody($this->getContainer()->get('templating')->render('form/mailAsistencia.txt.twig', array('entity' => $reg)));
                ;

             $mailer->send($message);

            }

            $output->writeln('<info>http://gaspacho.matmor.unam.mx/esver19/form/'.$reg->getSlug().'/'.$reg->getMail().'/confirma'.'</info>');

        }

    }

}
