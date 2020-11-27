<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace _PhpScoperbd5d0c5f7638\Symfony\Component\Console\Helper;

use _PhpScoperbd5d0c5f7638\Symfony\Component\Console\Formatter\OutputFormatter;
use _PhpScoperbd5d0c5f7638\Symfony\Component\Console\Output\OutputInterface;
use _PhpScoperbd5d0c5f7638\Symfony\Component\Console\Question\ChoiceQuestion;
use _PhpScoperbd5d0c5f7638\Symfony\Component\Console\Question\ConfirmationQuestion;
use _PhpScoperbd5d0c5f7638\Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
/**
 * Symfony Style Guide compliant question helper.
 *
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class SymfonyQuestionHelper extends \_PhpScoperbd5d0c5f7638\Symfony\Component\Console\Helper\QuestionHelper
{
    /**
     * {@inheritdoc}
     */
    protected function writePrompt(\_PhpScoperbd5d0c5f7638\Symfony\Component\Console\Output\OutputInterface $output, \_PhpScoperbd5d0c5f7638\Symfony\Component\Console\Question\Question $question)
    {
        $text = \_PhpScoperbd5d0c5f7638\Symfony\Component\Console\Formatter\OutputFormatter::escapeTrailingBackslash($question->getQuestion());
        $default = $question->getDefault();
        switch (\true) {
            case null === $default:
                $text = \sprintf(' <info>%s</info>:', $text);
                break;
            case $question instanceof \_PhpScoperbd5d0c5f7638\Symfony\Component\Console\Question\ConfirmationQuestion:
                $text = \sprintf(' <info>%s (yes/no)</info> [<comment>%s</comment>]:', $text, $default ? 'yes' : 'no');
                break;
            case $question instanceof \_PhpScoperbd5d0c5f7638\Symfony\Component\Console\Question\ChoiceQuestion && $question->isMultiselect():
                $choices = $question->getChoices();
                $default = \explode(',', $default);
                foreach ($default as $key => $value) {
                    $default[$key] = $choices[\trim($value)];
                }
                $text = \sprintf(' <info>%s</info> [<comment>%s</comment>]:', $text, \_PhpScoperbd5d0c5f7638\Symfony\Component\Console\Formatter\OutputFormatter::escape(\implode(', ', $default)));
                break;
            case $question instanceof \_PhpScoperbd5d0c5f7638\Symfony\Component\Console\Question\ChoiceQuestion:
                $choices = $question->getChoices();
                $text = \sprintf(' <info>%s</info> [<comment>%s</comment>]:', $text, \_PhpScoperbd5d0c5f7638\Symfony\Component\Console\Formatter\OutputFormatter::escape(isset($choices[$default]) ? $choices[$default] : $default));
                break;
            default:
                $text = \sprintf(' <info>%s</info> [<comment>%s</comment>]:', $text, \_PhpScoperbd5d0c5f7638\Symfony\Component\Console\Formatter\OutputFormatter::escape($default));
        }
        $output->writeln($text);
        $prompt = ' > ';
        if ($question instanceof \_PhpScoperbd5d0c5f7638\Symfony\Component\Console\Question\ChoiceQuestion) {
            $output->writeln($this->formatChoiceQuestionChoices($question, 'comment'));
            $prompt = $question->getPrompt();
        }
        $output->write($prompt);
    }
    /**
     * {@inheritdoc}
     */
    protected function writeError(\_PhpScoperbd5d0c5f7638\Symfony\Component\Console\Output\OutputInterface $output, \Exception $error)
    {
        if ($output instanceof \Symfony\Component\Console\Style\SymfonyStyle) {
            $output->newLine();
            $output->error($error->getMessage());
            return;
        }
        parent::writeError($output, $error);
    }
}