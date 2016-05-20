<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Bundle\SecurityBundle\Command;

use Makhan\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Makhan\Component\Console\Input\InputArgument;
use Makhan\Component\Console\Input\InputInterface;
use Makhan\Component\Console\Input\InputOption;
use Makhan\Component\Console\Output\OutputInterface;
use Makhan\Component\Console\Question\Question;
use Makhan\Component\Console\Style\MakhanStyle;
use Makhan\Component\Security\Core\Encoder\BCryptPasswordEncoder;

/**
 * Encode a user's password.
 *
 * @author Sarah Khalil <mkhalil.sarah@gmail.com>
 */
class UserPasswordEncoderCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('security:encode-password')
            ->setDescription('Encodes a password.')
            ->addArgument('password', InputArgument::OPTIONAL, 'The plain password to encode.')
            ->addArgument('user-class', InputArgument::OPTIONAL, 'The User entity class path associated with the encoder used to encode the password.', 'Makhan\Component\Security\Core\User\User')
            ->addOption('empty-salt', null, InputOption::VALUE_NONE, 'Do not generate a salt or let the encoder generate one.')
            ->setHelp(<<<EOF

The <info>%command.name%</info> command encodes passwords according to your
security configuration. This command is mainly used to generate passwords for
the <comment>in_memory</comment> user provider type and for changing passwords
in the database while developing the application.

Suppose that you have the following security configuration in your application:

<comment>
# app/config/security.yml
security:
    encoders:
        Makhan\Component\Security\Core\User\User: plaintext
        AppBundle\Entity\User: bcrypt
</comment>

If you execute the command non-interactively, the default Makhan User class
is used and a random salt is generated to encode the password:

  <info>php %command.full_name% --no-interaction [password]</info>

Pass the full user class path as the second argument to encode passwords for
your own entities:

  <info>php %command.full_name% --no-interaction [password] AppBundle\Entity\User</info>

Executing the command interactively allows you to generate a random salt for
encoding the password:

  <info>php %command.full_name% [password] AppBundle\Entity\User</info>

In case your encoder doesn't require a salt, add the <comment>empty-salt</comment> option:

  <info>php %command.full_name% --empty-salt [password] AppBundle\Entity\User</info>

EOF
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new MakhanStyle($input, $output);

        $input->isInteractive() ? $io->title('Makhan Password Encoder Utility') : $io->newLine();

        $password = $input->getArgument('password');
        $userClass = $input->getArgument('user-class');
        $emptySalt = $input->getOption('empty-salt');

        $encoder = $this->getContainer()->get('security.encoder_factory')->getEncoder($userClass);
        $bcryptWithoutEmptySalt = !$emptySalt && $encoder instanceof BCryptPasswordEncoder;

        if ($bcryptWithoutEmptySalt) {
            $emptySalt = true;
        }

        if (!$password) {
            if (!$input->isInteractive()) {
                $io->error('The password must not be empty.');

                return 1;
            }
            $passwordQuestion = $this->createPasswordQuestion($input, $output);
            $password = $io->askQuestion($passwordQuestion);
        }

        $salt = null;

        if ($input->isInteractive() && !$emptySalt) {
            $emptySalt = true;

            $io->note('The command will take care of generating a salt for you. Be aware that some encoders advise to let them generate their own salt. If you\'re using one of those encoders, please answer \'no\' to the question below. '.PHP_EOL.'Provide the \'empty-salt\' option in order to let the encoder handle the generation itself.');

            if ($io->confirm('Confirm salt generation ?')) {
                $salt = $this->generateSalt();
                $emptySalt = false;
            }
        } elseif (!$emptySalt) {
            $salt = $this->generateSalt();
        }

        $encodedPassword = $encoder->encodePassword($password, $salt);

        $rows = array(
            array('Encoder used', get_class($encoder)),
            array('Encoded password', $encodedPassword),
        );
        if (!$emptySalt) {
            $rows[] = array('Generated salt', $salt);
        }
        $io->table(array('Key', 'Value'), $rows);

        if (!$emptySalt) {
            $io->note(sprintf('Make sure that your salt storage field fits the salt length: %s chars', strlen($salt)));
        } elseif ($bcryptWithoutEmptySalt) {
            $io->note('Bcrypt encoder used: the encoder generated its own built-in salt.');
        }

        $io->success('Password encoding succeeded');
    }

    /**
     * Create the password question to ask the user for the password to be encoded.
     *
     * @return Question
     */
    private function createPasswordQuestion()
    {
        $passwordQuestion = new Question('Type in your password to be encoded');

        return $passwordQuestion->setValidator(function ($value) {
            if ('' === trim($value)) {
                throw new \Exception('The password must not be empty.');
            }

            return $value;
        })->setHidden(true)->setMaxAttempts(20);
    }

    private function generateSalt()
    {
        return base64_encode(random_bytes(30));
    }
}
