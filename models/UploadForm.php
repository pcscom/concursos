<?php

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends \yii\db\ActiveRecord
{
    public function rules()
    {
      return [
        ['active', 'in', 'range' => [true, false]],            
        [['image_path'],'file'],
        [['name', 'image_path'], 'string', 'max' => 200],            
      ];
    }
    
    public function attributeLabels()
    {
       return [            
           'image_path' => 'Profile Picture ',            
       ];
    }
}
