<?php

declare (strict_types=1);
namespace Rector\BetterPhpDocParser\Tests\PhpDocParser\TagValueNodeReprint\Fixture\DoctrineJoinTable;

use _PhpScoper5b8c9e9ebd21\Doctrine\ORM\Mapping as ORM;
final class JoinTableWithJoinColumns
{
    /**
     * @ORM\JoinTable(name="PushCampaignCluster",
     *      joinColumns={
     *          @ORM\JoinColumn(name="pushCampaignId", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="clusterId", referencedColumnName="id")
     *      }
     * )
     */
    public $name;
}
