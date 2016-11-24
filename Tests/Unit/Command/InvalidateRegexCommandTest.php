<?php

/*
 * This file is part of the FOSHttpCacheBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\HttpCacheBundle\Tests\Unit\Command;

use FOS\HttpCacheBundle\CacheManager;
use FOS\HttpCacheBundle\Command\InvalidateRegexCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class InvalidateRegexCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \RuntimeException
     */
    public function testExecuteNoParameters()
    {
        $invalidator = \Mockery::mock(CacheManager::class);

        $application = new Application();
        $application->add(new InvalidateRegexCommand($invalidator));

        $command = $application->find('fos:httpcache:invalidate:regex');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));
    }

    public function testExecuteParameter()
    {
        $invalidator = \Mockery::mock(CacheManager::class)
            ->shouldReceive('invalidateRegex')->once()->with('/my.*/path')
            ->getMock()
        ;

        $application = new Application();
        $application->add(new InvalidateRegexCommand($invalidator));

        $command = $application->find('fos:httpcache:invalidate:regex');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
            'regex' => '/my.*/path',
        ));

        // the only output should be generated by the listener in verbose mode
        $this->assertEquals('', $commandTester->getDisplay());
    }
}
