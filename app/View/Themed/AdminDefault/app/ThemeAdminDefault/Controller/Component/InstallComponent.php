<?php
class InstallComponent extends Component {
    public function beforeInstall(&$Installer) {
        return true;
    }

    function afterInstall(&$Installer) {
        return true;
    }

    public function beforeUninstall(&$Installer) {
        return true;
    }

    public function afterUninstall(&$Installer) {
        return true;
    }
}