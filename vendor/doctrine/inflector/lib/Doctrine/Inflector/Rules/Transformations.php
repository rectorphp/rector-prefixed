<?php

declare (strict_types=1);
namespace _PhpScoper267b3276efc2\Doctrine\Inflector\Rules;

use _PhpScoper267b3276efc2\Doctrine\Inflector\WordInflector;
class Transformations implements \_PhpScoper267b3276efc2\Doctrine\Inflector\WordInflector
{
    /** @var Transformation[] */
    private $transformations;
    public function __construct(\_PhpScoper267b3276efc2\Doctrine\Inflector\Rules\Transformation ...$transformations)
    {
        $this->transformations = $transformations;
    }
    public function inflect(string $word) : string
    {
        foreach ($this->transformations as $transformation) {
            if ($transformation->getPattern()->matches($word)) {
                return $transformation->inflect($word);
            }
        }
        return $word;
    }
}
