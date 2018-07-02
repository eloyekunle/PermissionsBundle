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
            $modules[] = [
              'key' => basename($definitionsFile, '.yaml'),
              'name' => $moduleName,
              'permissions' => $this->parsePermissions($module['permissions']),
            ];
        }

        return $modules;
    }

    private function parsePermissions(array $permissions): array {
        $multiDimPermissions = [];
        foreach ($permissions as $key => $value) {
            $multiDimPermissions[] = ['key' => $key] + $value;
        }

        return $multiDimPermissions;
    }

    public function getModuleNames()
    {

    }
}