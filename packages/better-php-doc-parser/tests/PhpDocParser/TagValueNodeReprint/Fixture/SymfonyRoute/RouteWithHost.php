<?php

declare (strict_types=1);
namespace Rector\BetterPhpDocParser\Tests\PhpDocParser\TagValueNodeReprint\Fixture\SymfonyRoute;

use _PhpScoperbd5d0c5f7638\Symfony\Component\Routing\Annotation\Route;
final class RouteWithHost
{
    /**
     * @Route("/user", name="user_index", host="%test%", methods={"GET"})
     */
    public function run()
    {
    }
}