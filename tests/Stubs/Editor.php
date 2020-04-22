<?php

namespace DavidIanBonner\Enumerated\Stubs;

use DavidIanBonner\Enumerated\Enum;

class Editor extends Enum
{
    const VSCODE = 'visual studio code';
    const SUBLIME = 'sublime text 3';
    const VIM = 'vim';

    protected $langKey = 'editor';
}
