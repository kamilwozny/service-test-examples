<?php

namespace \Tests\Service;

use \Service\AssetHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author Kamil Woźny
 */
class AssetHelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AssetHelper
     */
    private $assetHelper;

    public function setUp()
    {
        parent::setUp();
        $requestStack      = $this->getRequestStackMock();
        $this->assetHelper = new AssetHelper($requestStack, '/kernel/path', 'platform1', 'notDefaultLogo.png', 'notDefaultMobileLogo.png');
    }

    public function testLogoPath()
    {
        //logo path
        $this->assertEquals('/uploads/platform1/notDefaultLogo.png', $this->assetHelper->getLogoPath());
        //absolute logo path
        $this->assertEquals('http://localhost:8000/uploads/platform1/notDefaultLogo.png', $this->assetHelper->getLogoPath(true));
    }

    public function testMobileLogoPath()
    {
        //mobile logo path
        $this->assertEquals('/uploads/platform1/notDefaultMobileLogo.png', $this->assetHelper->getMobileLogoPath());
        //absolute mobile logo path
        $this->assertEquals('http://localhost:8000/uploads/platform1/notDefaultMobileLogo.png', $this->assetHelper->getMobileLogoPath(true));
    }

    public function testDefaultLogoPaths()
    {
        //I has to recreate assetHelper to change file names to null
        $requestStack = $this->getRequestStackMock();
        $assetHelper  = new AssetHelper($requestStack, '/kernel/path', 'platform1', null, null);
        //default main logo path
        $this->assertEquals('/img/platforms/super_admin/logo.png', $assetHelper->getLogoPath());
        //default absolute main logo path
        $this->assertEquals('http://localhost:8000/img/platforms/super_admin/logo.png', $assetHelper->getLogoPath(true));

        //the same behaviour for default mobile logo path
        $this->assertEquals('/img/platforms/super_admin/logo.png', $assetHelper->getMobileLogoPath());
        $this->assertEquals('http://localhost:8000/img/platforms/super_admin/logo.png', $assetHelper->getMobileLogoPath(true));
    }

    private function getRequestStackMock()
    {
        $request = $this->getMockBuilder(Request::class)
                        ->disableOriginalConstructor()
                        ->getMock();

        $request->expects($this->once())
                ->method('getSchemeAndHttpHost')
                ->will($this->returnValue('http://localhost:8000'));

        $requestStack = $this->getMockBuilder(RequestStack::class)
                             ->disableOriginalConstructor()
                             ->getMock();

        $requestStack->expects($this->once())
                     ->method('getMasterRequest')
                     ->will($this->returnValue($request));

        return $requestStack;
    }
}
