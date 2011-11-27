<?php
class ThemeMakerShell extends AppShell {
    public function main($args) {
        
    }
    
    public function getOptionParser() {
        $parser = parent::getOptionParser();

        $parser->description(array('Create a new module structure'));
        $parser->addArguments( 
            array(
                'name' => array('help' => 'New theme name.', 'required' => true),
                'destination' => array('help' => 'Relative path where to save your new theme. (Relative to: ' . ROOT . ')', 'required' => false)
            )
        )->addOption(
            'zip', array('short' => 'z', 'help' => 'Generate a zip package')
        );

        return $parser;
    }
}