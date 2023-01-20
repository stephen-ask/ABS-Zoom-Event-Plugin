<?php
namespace Zoom\Base;

abstract class Cpt {

    public function __construct() {

        $name = $this->get_name();
        $args = $this->post_type();

        add_action( 'init', 
            function () use ( $name, $args ) {
                register_post_type( $name, $args );
                flush_rewrite_rules();
            } 
        );
    }

    public abstract function get_name();
    public abstract function post_type();
}
?>