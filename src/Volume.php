<?php
namespace futureactivities\cloudinary;

use Craft;
use craft\base\FlysystemVolume;
use Enl\Flysystem\Cloudinary\ApiFacade as CloudinaryClient;
use Enl\Flysystem\Cloudinary\Converter\TruncateExtensionConverter;
use Enl\Flysystem\Cloudinary\CloudinaryAdapter;
use futureactivities\cloudinary\Plugin as Cloudinary;

class Volume extends FlysystemVolume
{
    // Static
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return 'Cloudinary';
    }

    // Properties
    // =========================================================================

    /**
     * @var bool Whether this is a local source or not. Defaults to false.
     */
    protected $isSourceLocal = false;

    /**
     * @var string Path to the root of this sources local folder.
     */
    public $subfolder = '';


    // Public Methods
    // =========================================================================

    public function getFileMetadata(string $uri): array
    {
        return parent::getFileMetadata($this->_removeExtension($uri));
    }

    /**
     * @inheritdoc
     */
    public function createFileByStream(string $path, $stream, array $config)
    {
        parent::createFileByStream($this->_removeExtension($path), $stream, $config);
    }

    /**
     * @inheritdoc
     */
    public function updateFileByStream(string $path, $stream, array $config)
    {
        parent::updateFileByStream($this->_removeExtension($path), $stream, $config);
    }

    /**
     * @inheritdoc
     */
    public function createDir(string $path)
    {
        parent::createDir($this->_removeExtension($path));
    }

    /**
     * @inheritdoc
     */
    public function fileExists(string $path): bool
    {
        return parent::fileExists($this->_removeExtension($path));
    }

    /**
     * @inheritdoc
     */
    public function folderExists(string $path): bool
    {
        return parent::folderExists($this->_removeExtension($path));
    }

    /**
     * @inheritdoc
     */
    public function renameFile(string $path, string $newPath)
    {
        parent::renameFile($this->_removeExtension($path), $this->_removeExtension($newPath));
    }

    /**
     * @inheritdoc
     */
    public function deleteFile(string $path)
    {
        parent::deleteFile($this->_removeExtension($path));
    }

    /**
     * @inheritdoc
     */
    public function copyFile(string $path, string $newPath)
    {
        parent::copyFile($this->_removeExtension($path), $this->_removeExtension($newPath));
    }

    /**
     * @inheritdoc
     */
    public function getFileStream(string $uriPath)
    {
        return parent::getFileStream($uriPath);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->foldersHaveTrailingSlashes = false;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();

        return $rules;
    }
    
    /**
     * @inheritdoc
     */
    public function getRootUrl()
    {
        return rtrim(rtrim($this->url, '/').'/'.$this->subfolder, '/').'/';
    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     *
     * @return CloudinaryAdapter
     */
    protected function createAdapter(): CloudinaryAdapter
    {
        $settings = Cloudinary::getInstance()->settings;
        
        $client = static::client([
            'cloud_name' => $settings->cloudName,
            'api_key' => $settings->apiKey,
            'api_secret' => $settings->apiSecret,
            'overwrite' => $settings->overwrite,
        ]);

        return new CloudinaryAdapter($client);
    }

    protected static function client(array $config = []): CloudinaryClient
    {
        return new CloudinaryClient($config, new TruncateExtensionConverter());
    }

    // Private Methods
    // =========================================================================

    private function _removeExtension(string $path)
    {
        $pathInfo = pathinfo($path);
        return implode('/', [
            $pathInfo['dirname'],
            $pathInfo['filename'],
        ]);
    }
}
