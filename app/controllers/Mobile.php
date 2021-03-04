<?php


namespace App\Controllers;


use App\Models\Database\AppDatabase;
use system\Header;

class Mobile extends FileController
{
    public function site_webmanifest()
    {
        return $this->manifest_json();
    }

    public function manifest_json()
    {

        $mainfest = '{
      "name": "' . AppDatabase::getAppName() . '",
      "short_name":"' . AppDatabase::getAppAbbreviation() . '",
      "start_url": "/",
      "scope": "/",
      "dir": "ltr",
      "lang": "en",
      "shortcuts" : [
      {
        "name": "Users",
        "url": "/users",
        "description": "Search for users",
        "icons":[
        {
          "src": "/img/favicon/android-chrome-96x96.png",
          "sizes": "96x96",
          "type": "image/png"
        }
        ]
      },
      {
        "name": "Groups",
        "url": "/groups",
        "description": "Search for groups",
        "icons":[
        {
          "src": "/img/favicon/android-chrome-96x96.png",
          "sizes": "96x96",
          "type": "image/png"
        }
        ]
      },
      {
        "name": "Settings",
        "url": "/settings/application",
        "description": "Change application settings",
        "icons":[
        {
          "src": "/img/favicon/android-chrome-96x96.png",
          "sizes": "96x96",
          "type": "image/png"
        }
        ]
      }
    ],
      "icons":[
        {
          "src": "/img/favicon/android-chrome-192x192.png",
          "sizes": "192x192",
          "type": "image/png"
        },
        {
          "src": "/img/favicon/android-chrome-512x512.png",
          "sizes": "512x512",
          "type": "image/png"
        }
      ],
      "theme_color": "#ffffff",
      "background_color": "#ffffff",
      "display": "standalone"
        }';
        Header::sendFile($mainfest, Header::APPLICATION_JSON, false);
        exit;
    }
}