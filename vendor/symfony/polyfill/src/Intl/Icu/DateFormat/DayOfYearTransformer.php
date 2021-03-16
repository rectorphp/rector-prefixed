<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RectorPrefix20210316\Symfony\Polyfill\Intl\Icu\DateFormat;

/**
 * Parser and formatter for day of year format.
 *
 * @author Igor Wiedler <igor@wiedler.ch>
 *
 * @internal
 */
class DayOfYearTransformer extends \RectorPrefix20210316\Symfony\Polyfill\Intl\Icu\DateFormat\Transformer
{
    /**
     * {@inheritdoc}
     */
    public function format(\DateTime $dateTime, int $length) : string
    {
        $dayOfYear = (int) $dateTime->format('z') + 1;
        return $this->padLeft($dayOfYear, $length);
    }
    /**
     * {@inheritdoc}
     */
    public function getReverseMatchingRegExp(int $length) : string
    {
        return '\\d{' . $length . '}';
    }
    /**
     * {@inheritdoc}
     */
    public function extractDateOptions(string $matched, int $length) : array
    {
        return [];
    }
}
