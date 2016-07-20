<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;

class FileSystems extends AbstractWidget
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
        return view("widgets.file_system", [
            'config' => $this->config,
        ]);
    }
}