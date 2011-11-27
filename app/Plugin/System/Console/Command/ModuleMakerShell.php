<?php
class ModuleMakerShell extends AppShell {
    public function main() {
        pr($this->args);
    }

    public function getOptionParser() {
        $parser = parent::getOptionParser();

        $parser->description(array('Create a new module structure'));
        $parser->addArguments(
            array(
                'name' => array('help' => 'New name of your module in CamelCase.', 'required' => true),
                'destination' => array('help' => 'Relative path where to save your new module. (Relative to: ' . ROOT . ')', 'required' => false)
            )
        )->addOption(
            'zip', array('short' => 'z', 'help' => 'Generate a zip package')
        );

        return $parser;
    }
}