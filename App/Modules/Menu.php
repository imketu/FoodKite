<?php
//GET route
$app->get('/menu/', function () {
    $template = <<<EOT
<!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8"/>
            <title>Slim Micro PHP 5 Framework</title>
        </head>
        <body>
           <h1>FuckedUp!</h1>
        </body>
    </html>
EOT;
    echo $template;
});
