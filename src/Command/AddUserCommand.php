<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AddUserCommand.
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa07081999@gmail.com>
 */
class AddUserCommand extends Command
{
    protected static $defaultName = 'app:add-user';

    /** @var EntityManagerInterface */
    private EntityManagerInterface $entityManager;

    /** @var UserPasswordEncoderInterface */
    private UserPasswordEncoderInterface $passwordEncoder;

    /**
     * UserCommand constructeur.
     * 
     * @param EntityManagerInterface       $entityManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        parent::__construct();
        $this->entityManager   = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setDescription('Ajouter des utilisateurs')
            ->addArgument('email', InputArgument::OPTIONAL, 'The email address of the new user')
            ->addArgument('firstName', InputArgument::OPTIONAL, 'The firstName of the new user')
            ->addArgument('lastName', InputArgument::OPTIONAL, 'The lastName of the new user')
            ->addArgument('password', InputArgument::OPTIONAL, 'The password of the new user')
            ->addOption('admin', null, InputOption::VALUE_NONE, 'If set, the user is created as an administrator')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * 
     * @return integer
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $user = new User();
        $helper = $this->getHelper('question');

        $email = new Question('Adresse email : ');
        $firstName = new Question('Prenom(s) : ');
        $lastName = new Question('Nom de famille : ');
        $password = new Question('Mot de passe : ');

        $user->setEmail($helper->ask($input, $output, $email))
            ->setFirstName($helper->ask($input, $output, $firstName))
            ->setLastName($helper->ask($input, $output, $lastName))
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->passwordEncoder->encodePassword($user, $helper->ask($input, $output, $password)))
        ;

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success("L'utilisateur " . $user->getFirstName() . " a bien été sauvegardé");

        return 0;
    }
}