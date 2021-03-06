<?php
/**
 * Desyncr\WtngrmTest\Service
 *
 * PHP version 5.4
 *
 * @category General
 * @package  Desyncr\WtngrmTest\Service
 * @author   Dario Cavuotti <dc@syncr.com.ar>
 * @license  https://www.gnu.org/licenses/gpl.html GPL-3.0+
 * @version  GIT:<>
 * @link     https://github.com/desyncr
 */
namespace Desyncr\WtngrmTest\Service;

use Desyncr\Wtngrm\Service\JobServiceBase;
use Desyncr\WtngrmTest\AbstractTest;

/**
 * Class AbstractServiceTest
 *
 * @category General
 * @package  Desyncr\WtngrmTest\Service
 * @author   Dario Cavuotti <dc@syncr.com.ar>
 * @license  https://www.gnu.org/licenses/gpl.html GPL-3.0+
 * @link     https://github.com/desyncr
 */
class AbstractServiceTest extends AbstractTest
{
    /**
     * getObject
     *
     * @return \Desyncr\Wtngrm\Service\JobServiceBase
     */
    public function getObject()
    {
        if ($this->object) {
            return $this->object;
        }

        $object = $this->getMock(
            'Desyncr\Wtngrm\Service\JobServiceBase'
        );

        $object->expects($this->any())
            ->method('dispatch')
            ->will($this->returnValue(1));

        return $this->object = $object;
    }

    /**
     * getOptionBaseMock
     *
     * @param array $methods Methods to mock
     *
     * @return mixed
     */
    public function getOptionBaseMock(array $methods)
    {
        return $this->getMock('Desyncr\Wtngrm\Options\OptionsBase', $methods);
    }

    /**
     * testSetOptions
     *
     * @covers Desyncr\Wtngrm\Service\AbstractService::setOptions
     *
     * @return null
     */
    public function testSetOptions()
    {
        $options = array(
            'timeout' => 10,
        );

        $object = new JobServiceBase();
        $this->assertNull($object->getOptions());

        $optionBaseMock = $this->getOptionBaseMock(
            array('setTimeout', 'getTimeout')
        );
        $optionBaseMock->expects($this->any())
            ->method('getTimeout')
            ->will($this->returnValue($options['timeout']));

        $object->setOptions(
            $optionBaseMock
        );
        $this->assertInstanceOf(
            'Desyncr\Wtngrm\Options\OptionsBase',
            $object->getOptions()
        );
        $object->getOptions()->setFromArray($options);

        $this->assertEquals(
            $options['timeout'],
            $object->getOptions()->get('timeout')
        );
    }

    /**
     * testSetOptionsNestedArray
     *
     * @covers Desyncr\Wtngrm\Service\AbstractService::setOptions
     *
     * @return null
     */
    public function testSetOptionsNestedArray()
    {
        $options = array(
            'names' => array(
                'John Doe',
                'Jane Doe',
                'Alice',
                'Bob'
            )
        );

        $optionBaseMock = $this->getOptionBaseMock(
            array('setNames', 'getNames')
        );
        $optionBaseMock->expects($this->any())
            ->method('getNames')
            ->will($this->returnValue($options['names']));
        $optionBaseMock->setFromArray($options);

        $this->getObject()->setOptions($optionBaseMock);

        $names = $optionBaseMock->getNames();
        $this->assertEquals($options['names'], $names);
        $this->assertEquals($options['names'][0], $names[0]);
    }

    /**
     * testSetOptionsNestedArrayLevel
     *
     * @covers Desyncr\Wtngrm\Service\AbstractService::setOptions
     *
     * @return null
     */
    public function testSetOptionsNestedArrayLevel()
    {
        $options = array(
            'servers' => array(
                'frontend' => array(
                    array('host' => '127.0.0.1', 'port' => 80),
                    array('host' => '127.0.0.1', 'port' => 81)
                ),
                'backend' => array(
                    array('host' => '127.0.0.1', 'port' => 8888)
                )
            )
        );

        $optionBaseMock = $this->getOptionBaseMock(
            array('setServers', 'getServers')
        );
        $optionBaseMock->expects($this->any())
            ->method('getServers')
            ->will($this->returnValue($options['servers']));
        $optionBaseMock->setFromArray($options);

        $object = new JobServiceBase();
        $object->setOptions($optionBaseMock);

        $this->assertInstanceOf(
            'Desyncr\Wtngrm\Options\OptionsBase',
            $object->getOptions()
        );
        $servers = $object->getOptions()->getServers();
        $this->assertEquals(
            $options['servers']['frontend'],
            $servers['frontend']
        );
        $this->assertEquals(
            $options['servers']['frontend'][1]['port'],
            $servers['frontend'][1]['port']
        );
        $this->assertEquals($options['servers'], $servers);
    }

    /**
     * testGetOption
     *
     * @covers Desyncr\Wtngrm\Service\ServiceBase::getOptions
     *
     * @return null
     */
    public function testGetOption()
    {
        $options = array(
            'servers' => array(
                'frontend' => array(
                    array('host' => '127.0.0.1', 'port' => 80)
                )
            )
        );

        $optionBaseMock = $this->getOptionBaseMock(
            array('setServers', 'getServers')
        );
        $optionBaseMock->expects($this->any())
            ->method('getServers')
            ->will($this->returnValue($options['servers']));
        $optionBaseMock->setFromArray($options);

        $object = new JobServiceBase();
        $object->setOptions($optionBaseMock);

        $this->assertInstanceOf(
            'Desyncr\Wtngrm\Options\OptionsBase',
            $object->getOptions()
        );
        $object->getOptions()->setFromArray($options);

        $this->assertEquals(
            $object->getOptions()->getServers(),
            $options['servers']
        );
    }

    /**
     * testAdd
     *
     * @covers Desyncr\Wtngrm\Service\JobServiceBase::add
     *
     * @return null
     */
    public function testAdd()
    {
        $key = 'job.service.key';
        $job = array();

        $object = new JobServiceBase();
        $jobObject = $object->add($key, $job);

        $this->assertEquals('Desyncr\Wtngrm\Job\JobBase', get_class($jobObject));
        $this->assertEquals($key, $jobObject->getId());
    }

    /**
     * testAddInvalidJob
     *
     * @covers Desyncr\Wtngrm\Service\JobServiceBase::add
     *
     * @return null
     */
    public function testAddInvalidJob()
    {
        $key = 'job.service.key';
        $job = new \StdClass;
        $this->setExpectedException('Exception');
        $object = new JobServiceBase();
        $object->add($key, $job);
    }

    /**
     * testDispatch
     *
     * @covers Desyncr\Wtngrm\Service\JobServiceBase::dispatch
     *
     * @return null
     */
    public function testDispatch()
    {
        $key = 'job.service.key';
        $job = array();
        $this->getObject()->add($key, $job);

        $this->assertEquals(1, $this->getObject()->dispatch());
    }

    /**
     * testServiceFactory
     *
     * @return mixed
     */
    public function testServiceFactory()
    {
        $serviceManager = $this->getServiceManager();
        $service = $serviceManager->get('Desyncr\Wtngrm\Service\ServiceBase');
        $this->assertInstanceOf(
            'Desyncr\Wtngrm\Service\ServiceBase',
            $service
        );
    }
}
