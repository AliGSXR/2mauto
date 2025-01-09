<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AddUserCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;

        // Définir explicitement le nom de la commande ici
        $this->setName('app:add-user');
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Adds a new user to the system')
            ->addArgument('pseudo', InputArgument::REQUIRED, 'The pseudo of the user')
            ->addArgument('password', InputArgument::REQUIRED, 'The password for the user')
            ->addArgument('roles', InputArgument::OPTIONAL, 'Comma-separated roles for the user (default: ROLE_USER)', 'ROLE_USER');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $pseudo = $input->getArgument('pseudo');
        $password = $input->getArgument('password');
        $roles = explode(',', $input->getArgument('roles')); // Convert roles to an array

        // Check if a user with this pseudo already exists
        $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['pseudo' => $pseudo]);
        if ($existingUser) {
            $output->writeln('<error>A user with this pseudo already exists.</error>');
            return Command::FAILURE;
        }

        // Create a new user
        $user = new User();
        $user->setPseudo($pseudo);
        $user->setRoles($roles); // Set roles
        $hashedPassword = $this->passwordHasher->hashPassword($user, $password); // Encode the password
        $user->setPassword($hashedPassword);

        // Save to the database
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln('<info>New user created successfully!</info>');
        $output->writeln('<info>Pseudo: ' . $pseudo . '</info>');
        $output->writeln('<info>Roles: ' . implode(', ', $roles) . '</info>');

        return Command::SUCCESS;
    }


}
