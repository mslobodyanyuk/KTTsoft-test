<?php

return [

        /* my config variable path for upload folder where we create/save/update files structure */
            'uplPath' => public_path() . '/upl',

        /* name upload folder*/
            'uplName' => 'upl',

        /* limit count files containing in one folder */
            'countContentFiles' => '2',

        /* name of editor */
            'nameEditor' => 'editor1',

        /* text - editor content, editorContent */
            'editorContent' => '$_POST["nameEditor"]',

        ];