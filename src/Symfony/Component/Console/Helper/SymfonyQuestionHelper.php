<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Console\Helper;

use Makhan\Component\Console\Exception\LogicException;
use Makhan\Component\Console\Input\InputInterface;
use Makhan\Component\Console\Output\OutputInterface;
use Makhan\Component\Console\Question\ChoiceQuestion;
use Makhan\Component\Console\Question\ConfirmationQuestion;
use Makhan\Component\Console\Question\Question;
use Makhan\Component\Console\Style\MakhanStyle;

/**
 * Makhan Style Guide compliant question helper.
 *
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class MakhanQuestionHelper extends QuestionHelper
{
    /**
     * {@inheritdoc}
     */
    public function ask(InputInterface $input, OutputInterface $output, Question $question)
    {
        $validator = $question->getValidator();
        $question->setValidator(function ($value) use ($validator) {
            if (null !== $validator) {
                $value = $validator($value);
            }

            // make required
            if (!is_array($value) && !is_bool($value) && 0 === strlen($value)) {
                throw new LogicException('A value is required.');
            }

            return $value;
        });

        return parent::ask($input, $output, $question);
    }

    /**
     * {@inheritdoc}
     */
    protected function writePrompt(OutputInterface $output, Question $question)
    {
        $text = $question->getQuestion();
        $default = $question->getDefault();

        switch (true) {
            case null === $default:
                $text = sprintf(' <info>%s</info>:', $text);

                break;

            case $question instanceof ConfirmationQuestion:
                $text = sprintf(' <info>%s (yes/no)</info> [<comment>%s</comment>]:', $text, $default ? 'yes' : 'no');

                break;

            case $question instanceof ChoiceQuestion:
                $choices = $question->getChoices();
                $text = sprintf(' <info>%s</info> [<comment>%s</comment>]:', $text, $choices[$default]);

                break;

            default:
                $text = sprintf(' <info>%s</info> [<comment>%s</comment>]:', $text, $default);
        }

        $output->writeln($text);

        if ($question instanceof ChoiceQuestion) {
            $width = max(array_map('strlen', array_keys($question->getChoices())));

            foreach ($question->getChoices() as $key => $value) {
                $output->writeln(sprintf("  [<comment>%-${width}s</comment>] %s", $key, $value));
            }
        }

        $output->write(' > ');
    }

    /**
     * {@inheritdoc}
     */
    protected function writeError(OutputInterface $output, \Exception $error)
    {
        if ($output instanceof MakhanStyle) {
            $output->newLine();
            $output->error($error->getMessage());

            return;
        }

        parent::writeError($output, $error);
    }
}
