<div id="acos">
    <?php
        echo $this->Layout->menu($results,
            array(
                'id' => 'acos-ul',
                'model' => 'Aco',
                'element' => 'User.permission-node'
            )
        );
    ?>
</div>

<div id="aco-edit"></div>