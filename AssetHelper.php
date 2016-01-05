<?php

namespace \Service;

use Symfony\Component\HttpFoundation\RequestStack;

class AssetHelper
{
    /**
     * @var string
     */
    private $schemeAndHttpHost;
    private $kernel_root_dir;
    private $platform;
    private $logo_file_name;
    private $mobile_logo_file_name;

    public function __construct(RequestStack $requestStack, $kernel_root_dir, $platform, $logo_file_name, $mobile_logo_file_name)
    {
        $this->schemeAndHttpHost = $requestStack->getMasterRequest()->getSchemeAndHttpHost();
        $this->kernel_root_dir = $kernel_root_dir;
        $this->platform = $platform;
        $this->logo_file_name = $logo_file_name;
        $this->mobile_logo_file_name = $mobile_logo_file_name;
    }

    /**
     * Returns the logo path - if its null, then takes default logo from /img/platform/logo.png
     * @param bool|false $absolute
     *
     * @return string
     */
    public function getLogoPath($absolute = false)
    {
        return $this->getImagePath('logo.png', $this->logo_file_name, $absolute);
    }

    /**
     * Returns the mobile logo path - if its null, then takes default logo from /img/platform/logo.png
     * @param bool|false $absolute
     *
     * @return string
     */
    public function getMobileLogoPath($absolute = false)
    {
        return $this->getImagePath('mobile_logo.png', $this->mobile_logo_file_name, $absolute);
    }

    /**
     * @param $filename
     * @param string $defaultFilename
     * @param bool|false $absolute
     *
     * @return string
     */
    private function getImagePath($filename, $defaultFilename = null, $absolute = false) {
        // if its null, take default logo
        if($defaultFilename === null){
            $path = '/img/platforms/' . $this->platform . '/' . $filename;

            if(!file_exists($this->kernel_root_dir . '/../web' . $path)) {
                $path = '/img/platforms/super_admin/logo.png';
            }
        } else {
            $path = '/uploads/' . $this->platform . '/' . $defaultFilename;
        }

        if($absolute) {
            return $this->schemeAndHttpHost . $path;
        }

        return $path;
    }
}
