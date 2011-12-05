<?php
    $links = array(
        array(__t('Published'), '/admin/comment/published'),
        array(__t('Unpublished (%s)', $countUnpublished), '/admin/comment/unpublished')
    );

    echo $this->Layout->toolbar($links);