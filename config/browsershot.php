<?php

return [
    /*
     * The path to the node binary.
     */
    'node_path' => 'C:\\Program Files\\nodejs\\node.exe',

    /*
     * The path to the npm binary.
     */
    'npm_path' => 'C:\\Program Files\\nodejs\\npm.cmd',

    /*
     * The path to the chrome/chromium binary.
     *
     * You can use a puppeteer managed chrome version by setting this to null.
     * In this case, you need to make sure that you have run `npm install puppeteer`.
     */
    'chromium_path' => null,

    /*
     * Extra arguments to pass to the chrome/chromium binary.
     */
    'chromium_arguments' => [
        '--no-sandbox',
    ],

    /*
     * When taking a screenshot, this will be the default browser width in pixels.
     */
    'default_width' => 1920,

    /*
     * When taking a screenshot, this will be the default browser height in pixels.
     */
    'default_height' => 1080,
];
