<?php

/*
 * This file is based on ianm/oauth-amazon.
 *
 * Copyright (c) 2021 IanM.
 *
 *  For the full copyright and license information, please view the LICENSE.md
 *  file that was distributed with this source code.
 */

namespace GBCLStudio\OAuthMinecraft;

use Flarum\Extend;
use FoF\OAuth\Extend as OAuthExtend;
use GBCLStudio\OAuthMinecraft\Providers\Minecraft;

return [
    (new Extend\Frontend('forum'))
        ->css(__DIR__.'/less/forum.less'),

    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js'),

    new Extend\Locales(__DIR__.'/locale'),

    (new OAuthExtend\RegisterProvider(Minecraft::class)),

];
