<?php
    $links = array();

    foreach ($this->data['NodeType']['viewModes'] as $vm) {
        $links[] = array(__t($vm), "/admin/node/types/display/{$this->data['NodeType']['id']}/{$vm}");
    }

    if (!empty($links)) {
        echo $this->Layout->toolbar($links);
    }