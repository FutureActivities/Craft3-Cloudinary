<?php
namespace futureactivities\cloudinary;

use craft\events\RegisterComponentTypesEvent;
use craft\services\Volumes;
use craft\web\twig\variables\CraftVariable;
use yii\base\Event;
use futureactivities\cloudinary\models\Settings;
use futureactivities\cloudinary\variables\CloudinaryVariable;
use futureactivities\cloudinary\twigextensions\CloudinaryTwigExtension;

class Plugin extends \craft\base\Plugin
{
    public $hasCpSettings = true;
    
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        Event::on(Volumes::class, Volumes::EVENT_REGISTER_VOLUME_TYPES, function (RegisterComponentTypesEvent $event) {
            $event->types[] = Volume::class;
        });
        
        Event::on(CraftVariable::class, CraftVariable::EVENT_INIT, function (Event $event) {
            $variable = $event->sender;
            $variable->set('cloudinary', CloudinaryVariable::class);
        });
        
        \Craft::$app->view->twig->addExtension(new CloudinaryTwigExtension());
        
        \Cloudinary::config(array(
            "cloud_name" => $this->settings->cloudName, 
            "api_key" => $this->settings->apiKey, 
            "api_secret" => $this->settings->apiSecret 
        ));
    }
    
    protected function createSettingsModel()
    {
        return new Settings();
    }
    
    protected function settingsHtml()
    {
        return \Craft::$app->getView()->renderTemplate('cloudinary/settings', [
            'settings' => $this->getSettings()
        ]);
    }
}
