<?php


namespace Eloyekunle\PermissionsBundle\Permission;


use Eloyekunle\PermissionsBundle\Util\YamlDiscovery;

class ModuleHandler implements ModuleHandlerInterface
{

    /** @var string */
    protected $definitionsPath;

    public function __construct(string $definitionsPath)
    {
        $this->definitionsPath = $definitionsPath;
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleList(): array
    {
        $modules = [];
        $definitionsFiles = YamlDiscovery::getFilesInPath(
          $this->definitionsPath
        );

        foreach ($definitionsFiles as $definitionsFile) {
            $module = YamlDiscovery::decode($definitionsFile);
            $moduleName = $module['name'];
            $modules[basename($definitionsFile, '.yaml')] = [
              'name' => $moduleName,
              'path' => $definitionsFile,
              'permissions' => $module['permissions'],
            ];
        }

        return $modules;
    }
}