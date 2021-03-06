<?php
namespace AssetCompress;

use AssetCompress\Filter\FilterRegistry;
use AssetCompress\AssetScanner;
use Cake\Core\App;
use Cake\Core\Configure;
use MiniAsset\AssetCollection;
use MiniAsset\AssetCompiler;
use MiniAsset\AssetConfig;
use MiniAsset\AssetTarget;
use MiniAsset\Factory as BaseFactory;
use MiniAsset\File\Local;
use MiniAsset\File\Remote;
use MiniAsset\Output\AssetCacher;
use MiniAsset\Output\AssetWriter;
use RuntimeException;

/**
 * A factory for various object using a config file.
 *
 * This class can make AssetCollections and FilterCollections based
 * on the configuration object passed to it.
 */
class Factory extends BaseFactory
{
    /**
     * Create an AssetWriter
     *
     * @param string $path The path to use
     * @return AssetCompress\AssetWriter
     */
    public function writer($path = TMP)
    {
        return parent::writer($path);
    }

    /**
     * Create an AssetCacher
     *
     * @return AssetCompress\AssetCacher
     */
    public function cacher($path = '')
    {
        if ($path == '') {
            $path = CACHE . 'asset_compress' . DS;
        }
        return parent::cacher($path);
    }

    public function scanner($paths)
    {
        return new AssetScanner($paths, $this->config->theme());
    }

    /**
     * Create a single filter
     *
     * @param string $name The name of the filter to build.
     * @param array $config The configuration for the filter.
     * @return AssetCompress\Filter\AssetFilterInterface
     */
    protected function buildFilter($name, $config)
    {
        $className = App::className($name, 'Filter');
        if (!class_exists($className)) {
            $className = App::className('AssetCompress.' . $name, 'Filter');
        }
        $className = $className ?: $name;
        return parent::buildFilter($className, $config);
    }

    /**
     * Create an AssetCompiler
     *
     * @param bool $debug Not used - Configure is used instead.
     * @return MiniAsset\AssetCompiler
     */
    public function compiler($debug = false)
    {
        return new AssetCompiler($this->filterRegistry(), Configure::read('debug'));
    }
}
