<?php
class InstallComponent extends Component {
	var $Controller = null;

    public function beforeInstall($Installer) {
        return true;
    }

    public function afterInstall($Installer) {
        return true;
    }

    public function beforeUninstall($Installer) {
        return true;
    }

    public function afterUninstall($Installer) {
        return true;
    }
}