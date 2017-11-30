# perunauthorize-simplesamlphp-module

Module for displaying users warning about unauthorized access to the services

## Installation

Once you have installed SimpleSAMLphp, installing this module is very simple. First of all, you will need to download Composer if you haven't already. After installing Composer, just execute the following command in the root of your SimpleSAMLphp installation:

1.Add follows repository to composer.json

```json
    "repositories":[
         {
                 "type": "git",       
                 "url": "https://github.com/CESNET/proxystatistics-simplesamlphp-module.git"      
         }
     ]
```
2.Install perunauthorize-simplesamlphp-module

`php composer.phar require cesnet/perunauthorize-simplesamlphp-module:dev-master`
