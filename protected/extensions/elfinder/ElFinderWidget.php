<?php

/**
 * Created by JetBrains PhpStorm.
 * User: z_bodya
 * Date: 6/20/12
 * Time: 7:41 PM
 * To change this template use File | Settings | File Templates.
 */
class ElFinderWidget extends CWidget {

    /**
     * Client settings.
     * More about this: https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
     * @var array
     */
    public $settings = array();
    public $connectorRoute = false;
    private $assetsDir;
    public $redactor = false;

    public function init() {

        $dir = dirname(__FILE__) . '/assets';
        $this->assetsDir = Yii::app()->assetManager->publish($dir);
        $cs = Yii::app()->getClientScript();

        // jQuery and jQuery UI
        $cs->registerCssFile($cs->getCoreScriptUrl() . '/jui/css/base/jquery-ui.css');
//        $cs->registerCssFile($this->assetsDir . '/smoothness/jquery-ui-1.8.21.custom.css');
        $cs->registerCssFile('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/smoothness/jquery-ui.css');
        $cs->registerCoreScript('jquery');
        $cs->registerCoreScript('jquery.ui');
//        $cs->registerScriptFile('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/jquery-ui.min.js');
        // elFinder CSS
        $cs->registerCssFile($this->assetsDir . '/css/elfinder.min.css');

        $cs->registerCssFile($this->assetsDir . '/css/theme.css');

        // elFinder JS
        $cs->registerScriptFile($this->assetsDir . '/js/elfinder.min.js');
        // elFinder translation
        //$cs->registerScriptFile($this->assetsDir . '/js/i18n/elfinder.ru.js');
        // set required options
        if (empty($this->connectorRoute))
            throw new CException('$connectorRoute must be set!');
        $this->settings['url'] = Yii::app()->createUrl($this->connectorRoute);
        $this->settings['lang'] = Yii::app()->language;
    }

    public function run() {
        $id = $this->getId();
        $settings = CJavaScript::encode($this->settings);
        $cs = Yii::app()->getClientScript();
        if (!$this->redactor) {

            $cs->registerScript('elFinder', "$('#$id').elfinder($settings).elfinder('instance');
                                         $('#elfinder-resize-type').buttonset();");
        } else {
            $cs->registerScript('elFinder', "$('#$id').dialogelfinder({
                url: 'php/connector.php',
                commandsOptions: {
                    getfile: {
                        oncomplete : 'close' // close/hide elFinder
                    }
                },
                getFileCallback: function(file) {
                    //console.log(file);
                    image = '<img src=\"'+file+'\">';
                   // RedactorPlugins.insertHtml(image);
                  el.insertHtml('<img src=\"'+file+'\">');
                  // console.log(dialog.url);
                } // pass callback to file manager
            });");
        }
//        $cs->registerScript('elFinder', '$().ready(function() {
//				var elf = $("#'.$id.'").elfinder({
//                                    ''
//				}).elfinder(\'instance\');
//			});');
        echo "<div id=\"$id\"></div>";
    }

}
