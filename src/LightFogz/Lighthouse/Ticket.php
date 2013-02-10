<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Ollie Maitland
 * Date: 09/02/13
 * Time: 17:33
 * To change this template use File | Settings | File Templates.
 */

namespace LightFogz\Lighthouse;

class Ticket
{
    public $title;
    public $created;
    public $creator;
    public $body;

    public function html()
    {
        return $this->body;
    }
}
