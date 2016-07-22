<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;

class MysqlConnection extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        //

//        return view('admin.connection.index');
        return view("widgets.mysql_connection", [
            'config' => $this->config,
        ]);
    }
}