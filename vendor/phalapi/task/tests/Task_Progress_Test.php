<?php
/**
 * PhpUnderControl_TaskProgress_Test
 *
 * 针对 ../Progress.php Task_Progress 类的PHPUnit单元测试
 *
 * @author: dogstar 20150519
 */

require_once dirname(__FILE__) . '/bootstrap.php';

class PhpUnderControl_TaskProgress_Test extends PHPUnit_Framework_TestCase
{
    public $taskProgress;

    protected function setUp()
    {
        parent::setUp();

        $this->taskProgress = new PhalApi\Task\Progress();
    }

    protected function tearDown()
    {
    }


    /**
     * @group testRun
     */ 
    public function testRun()
    {
        $rs = $this->taskProgress->run();
    }

}
