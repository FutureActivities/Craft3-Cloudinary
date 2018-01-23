<?php

namespace futureactivities\cloudinary\models;

use craft\base\Model;

class Settings extends Model
{
    public $apiKey;
    public $apiSecret;
    public $cloudName;
    public $overwrite = true;

    public function rules()
    {
        return [
            [['apiKey', 'apiSecret', 'cloudName'], 'required'],
        ];
    }
}