<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RectorPrefix20210317\Symfony\Component\Console\Descriptor;

use RectorPrefix20210317\Symfony\Component\Console\Application;
use RectorPrefix20210317\Symfony\Component\Console\Command\Command;
use RectorPrefix20210317\Symfony\Component\Console\Formatter\OutputFormatter;
use RectorPrefix20210317\Symfony\Component\Console\Helper\Helper;
use RectorPrefix20210317\Symfony\Component\Console\Input\InputArgument;
use RectorPrefix20210317\Symfony\Component\Console\Input\InputDefinition;
use RectorPrefix20210317\Symfony\Component\Console\Input\InputOption;
/**
 * Text descriptor.
 *
 * @author Jean-François Simon <contact@jfsimon.fr>
 *
 * @internal
 */
class TextDescriptor extends \RectorPrefix20210317\Symfony\Component\Console\Descriptor\Descriptor
{
    /**
     * {@inheritdoc}
     * @param \Symfony\Component\Console\Input\InputArgument $argument
     * @param mixed[] $options
     */
    protected function describeInputArgument($argument, $options = [])
    {
        if (null !== $argument->getDefault() && (!\is_array($argument->getDefault()) || \count($argument->getDefault()))) {
            $default = \sprintf('<comment> [default: %s]</comment>', $this->formatDefaultValue($argument->getDefault()));
        } else {
            $default = '';
        }
        $totalWidth = $options['total_width'] ?? \RectorPrefix20210317\Symfony\Component\Console\Helper\Helper::strlen($argument->getName());
        $spacingWidth = $totalWidth - \strlen($argument->getName());
        $this->writeText(\sprintf(
            '  <info>%s</info>  %s%s%s',
            $argument->getName(),
            \str_repeat(' ', $spacingWidth),
            // + 4 = 2 spaces before <info>, 2 spaces after </info>
            \preg_replace('/\\s*[\\r\\n]\\s*/', "\n" . \str_repeat(' ', $totalWidth + 4), $argument->getDescription()),
            $default
        ), $options);
    }
    /**
     * {@inheritdoc}
     * @param \Symfony\Component\Console\Input\InputOption $option
     * @param mixed[] $options
     */
    protected function describeInputOption($option, $options = [])
    {
        if ($option->acceptValue() && null !== $option->getDefault() && (!\is_array($option->getDefault()) || \count($option->getDefault()))) {
            $default = \sprintf('<comment> [default: %s]</comment>', $this->formatDefaultValue($option->getDefault()));
        } else {
            $default = '';
        }
        $value = '';
        if ($option->acceptValue()) {
            $value = '=' . \strtoupper($option->getName());
            if ($option->isValueOptional()) {
                $value = '[' . $value . ']';
            }
        }
        $totalWidth = $options['total_width'] ?? $this->calculateTotalWidthForOptions([$option]);
        $synopsis = \sprintf('%s%s', $option->getShortcut() ? \sprintf('-%s, ', $option->getShortcut()) : '    ', \sprintf('--%s%s', $option->getName(), $value));
        $spacingWidth = $totalWidth - \RectorPrefix20210317\Symfony\Component\Console\Helper\Helper::strlen($synopsis);
        $this->writeText(\sprintf(
            '  <info>%s</info>  %s%s%s%s',
            $synopsis,
            \str_repeat(' ', $spacingWidth),
            // + 4 = 2 spaces before <info>, 2 spaces after </info>
            \preg_replace('/\\s*[\\r\\n]\\s*/', "\n" . \str_repeat(' ', $totalWidth + 4), $option->getDescription()),
            $default,
            $option->isArray() ? '<comment> (multiple values allowed)</comment>' : ''
        ), $options);
    }
    /**
     * {@inheritdoc}
     * @param \Symfony\Component\Console\Input\InputDefinition $definition
     * @param mixed[] $options
     */
    protected function describeInputDefinition($definition, $options = [])
    {
        $totalWidth = $this->calculateTotalWidthForOptions($definition->getOptions());
        foreach ($definition->getArguments() as $argument) {
            $totalWidth = \max($totalWidth, \RectorPrefix20210317\Symfony\Component\Console\Helper\Helper::strlen($argument->getName()));
        }
        if ($definition->getArguments()) {
            $this->writeText('<comment>Arguments:</comment>', $options);
            $this->writeText("\n");
            foreach ($definition->getArguments() as $argument) {
                $this->describeInputArgument($argument, \array_merge($options, ['total_width' => $totalWidth]));
                $this->writeText("\n");
            }
        }
        if ($definition->getArguments() && $definition->getOptions()) {
            $this->writeText("\n");
        }
        if ($definition->getOptions()) {
            $laterOptions = [];
            $this->writeText('<comment>Options:</comment>', $options);
            foreach ($definition->getOptions() as $option) {
                if (\strlen($option->getShortcut()) > 1) {
                    $laterOptions[] = $option;
                    continue;
                }
                $this->writeText("\n");
                $this->describeInputOption($option, \array_merge($options, ['total_width' => $totalWidth]));
            }
            foreach ($laterOptions as $option) {
                $this->writeText("\n");
                $this->describeInputOption($option, \array_merge($options, ['total_width' => $totalWidth]));
            }
        }
    }
    /**
     * {@inheritdoc}
     * @param \Symfony\Component\Console\Command\Command $command
     * @param mixed[] $options
     */
    protected function describeCommand($command, $options = [])
    {
        $command->mergeApplicationDefinition(\false);
        if ($description = $command->getDescription()) {
            $this->writeText('<comment>Description:</comment>', $options);
            $this->writeText("\n");
            $this->writeText('  ' . $description);
            $this->writeText("\n\n");
        }
        $this->writeText('<comment>Usage:</comment>', $options);
        foreach (\array_merge([$command->getSynopsis(\true)], $command->getAliases(), $command->getUsages()) as $usage) {
            $this->writeText("\n");
            $this->writeText('  ' . \RectorPrefix20210317\Symfony\Component\Console\Formatter\OutputFormatter::escape($usage), $options);
        }
        $this->writeText("\n");
        $definition = $command->getDefinition();
        if ($definition->getOptions() || $definition->getArguments()) {
            $this->writeText("\n");
            $this->describeInputDefinition($definition, $options);
            $this->writeText("\n");
        }
        $help = $command->getProcessedHelp();
        if ($help && $help !== $description) {
            $this->writeText("\n");
            $this->writeText('<comment>Help:</comment>', $options);
            $this->writeText("\n");
            $this->writeText('  ' . \str_replace("\n", "\n  ", $help), $options);
            $this->writeText("\n");
        }
    }
    /**
     * {@inheritdoc}
     * @param \Symfony\Component\Console\Application $application
     * @param mixed[] $options
     */
    protected function describeApplication($application, $options = [])
    {
        $describedNamespace = $options['namespace'] ?? null;
        $description = new \RectorPrefix20210317\Symfony\Component\Console\Descriptor\ApplicationDescription($application, $describedNamespace);
        if (isset($options['raw_text']) && $options['raw_text']) {
            $width = $this->getColumnWidth($description->getCommands());
            foreach ($description->getCommands() as $command) {
                $this->writeText(\sprintf("%-{$width}s %s", $command->getName(), $command->getDescription()), $options);
                $this->writeText("\n");
            }
        } else {
            if ('' != ($help = $application->getHelp())) {
                $this->writeText("{$help}\n\n", $options);
            }
            $this->writeText("<comment>Usage:</comment>\n", $options);
            $this->writeText("  command [options] [arguments]\n\n", $options);
            $this->describeInputDefinition(new \RectorPrefix20210317\Symfony\Component\Console\Input\InputDefinition($application->getDefinition()->getOptions()), $options);
            $this->writeText("\n");
            $this->writeText("\n");
            $commands = $description->getCommands();
            $namespaces = $description->getNamespaces();
            if ($describedNamespace && $namespaces) {
                // make sure all alias commands are included when describing a specific namespace
                $describedNamespaceInfo = \reset($namespaces);
                foreach ($describedNamespaceInfo['commands'] as $name) {
                    $commands[$name] = $description->getCommand($name);
                }
            }
            // calculate max. width based on available commands per namespace
            $width = $this->getColumnWidth(\array_merge(...\array_values(\array_map(function ($namespace) use($commands) {
                return \array_intersect($namespace['commands'], \array_keys($commands));
            }, \array_values($namespaces)))));
            if ($describedNamespace) {
                $this->writeText(\sprintf('<comment>Available commands for the "%s" namespace:</comment>', $describedNamespace), $options);
            } else {
                $this->writeText('<comment>Available commands:</comment>', $options);
            }
            foreach ($namespaces as $namespace) {
                $namespace['commands'] = \array_filter($namespace['commands'], function ($name) use($commands) {
                    return isset($commands[$name]);
                });
                if (!$namespace['commands']) {
                    continue;
                }
                if (!$describedNamespace && \RectorPrefix20210317\Symfony\Component\Console\Descriptor\ApplicationDescription::GLOBAL_NAMESPACE !== $namespace['id']) {
                    $this->writeText("\n");
                    $this->writeText(' <comment>' . $namespace['id'] . '</comment>', $options);
                }
                foreach ($namespace['commands'] as $name) {
                    $this->writeText("\n");
                    $spacingWidth = $width - \RectorPrefix20210317\Symfony\Component\Console\Helper\Helper::strlen($name);
                    $command = $commands[$name];
                    $commandAliases = $name === $command->getName() ? $this->getCommandAliasesText($command) : '';
                    $this->writeText(\sprintf('  <info>%s</info>%s%s', $name, \str_repeat(' ', $spacingWidth), $commandAliases . $command->getDescription()), $options);
                }
            }
            $this->writeText("\n");
        }
    }
    /**
     * {@inheritdoc}
     * @param string $content
     * @param mixed[] $options
     */
    private function writeText($content, $options = [])
    {
        $this->write(isset($options['raw_text']) && $options['raw_text'] ? \strip_tags($content) : $content, isset($options['raw_output']) ? !$options['raw_output'] : \true);
    }
    /**
     * Formats command aliases to show them in the command description.
     * @param \Symfony\Component\Console\Command\Command $command
     */
    private function getCommandAliasesText($command) : string
    {
        $text = '';
        $aliases = $command->getAliases();
        if ($aliases) {
            $text = '[' . \implode('|', $aliases) . '] ';
        }
        return $text;
    }
    /**
     * Formats input option/argument default value.
     *
     * @param mixed $default
     */
    private function formatDefaultValue($default) : string
    {
        if (\INF === $default) {
            return 'INF';
        }
        if (\is_string($default)) {
            $default = \RectorPrefix20210317\Symfony\Component\Console\Formatter\OutputFormatter::escape($default);
        } elseif (\is_array($default)) {
            foreach ($default as $key => $value) {
                if (\is_string($value)) {
                    $default[$key] = \RectorPrefix20210317\Symfony\Component\Console\Formatter\OutputFormatter::escape($value);
                }
            }
        }
        return \str_replace('\\\\', '\\', \json_encode($default, \JSON_UNESCAPED_SLASHES | \JSON_UNESCAPED_UNICODE));
    }
    /**
     * @param (Command|string)[] $commands
     */
    private function getColumnWidth($commands) : int
    {
        $widths = [];
        foreach ($commands as $command) {
            if ($command instanceof \RectorPrefix20210317\Symfony\Component\Console\Command\Command) {
                $widths[] = \RectorPrefix20210317\Symfony\Component\Console\Helper\Helper::strlen($command->getName());
                foreach ($command->getAliases() as $alias) {
                    $widths[] = \RectorPrefix20210317\Symfony\Component\Console\Helper\Helper::strlen($alias);
                }
            } else {
                $widths[] = \RectorPrefix20210317\Symfony\Component\Console\Helper\Helper::strlen($command);
            }
        }
        return $widths ? \max($widths) + 2 : 0;
    }
    /**
     * @param InputOption[] $options
     */
    private function calculateTotalWidthForOptions($options) : int
    {
        $totalWidth = 0;
        foreach ($options as $option) {
            // "-" + shortcut + ", --" + name
            $nameLength = 1 + \max(\RectorPrefix20210317\Symfony\Component\Console\Helper\Helper::strlen($option->getShortcut()), 1) + 4 + \RectorPrefix20210317\Symfony\Component\Console\Helper\Helper::strlen($option->getName());
            if ($option->acceptValue()) {
                $valueLength = 1 + \RectorPrefix20210317\Symfony\Component\Console\Helper\Helper::strlen($option->getName());
                // = + value
                $valueLength += $option->isValueOptional() ? 2 : 0;
                // [ + ]
                $nameLength += $valueLength;
            }
            $totalWidth = \max($totalWidth, $nameLength);
        }
        return $totalWidth;
    }
}
