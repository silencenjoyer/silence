<?php
/**
 * @var Throwable $throwable
 */
?>

<style>
    @import 'https://fonts.googleapis.com/css?family=VT323';
    body,
    h1,
    h2,
    h3,
    h4,
    p,
    a {
        color: #e0e2f4;
    }

    body,
    p {
        font: normal 20px/1.25rem "VT323", monospace;
    }

    h1 {
        font: normal 2.75rem/1.05em "VT323", monospace;
    }

    h2 {
        font: normal 2.25rem/1.25em "VT323", monospace;
    }

    h3 {
        font: lighter 1.5rem/1.25em "VT323", monospace;
    }

    h4 {
        font: lighter 1.125rem/1.2222222em "VT323", monospace;
    }

    body {
        background: #0414a7;
    }

    .container {
        width: 90%;
        margin: auto;
    }

    .bsod {
        padding-top: 10%;
    }
    .bsod .neg {
        text-align: center;
        color: #0414a7;
    }
    .bsod .neg .bg {
        background: #aaaaaa;
        padding: 0 15px 2px 13px;
    }
    .bsod .title {
        margin-bottom: 50px;
    }
    .pre-class {
        font: lighter 1.125rem/1.2222222em "VT323", monospace;
        overflow-x: scroll;
    }
</style>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo sprintf('%s: %d', $throwable->getMessage(), $throwable->getCode() ?: 500) ?></title>
    </head>
    <body>
        <main class="bsod container">
            <h1 class="neg title"><span class="bg"><?php echo $throwable::class ?></span></h1>
            <p>An internal server error occured:</p>
            <p>
                * Code: <?php echo $throwable->getCode() ?><br/>
                * Message: <?php echo $throwable->getMessage() ?><br/>
                * File: <?php echo $throwable->getFile() ?><br/>
                * Line: <?php echo $throwable->getLine() ?><br/>
            </p>

            <p>Trace:</p>
            <p>
                <pre class="pre-class"><?php echo $throwable->getTraceAsString(); ?></pre>
            </p>

            <?php while ($throwable->getPrevious() !== null): ?>
                <p>Previous Trace:</p>

                <?php $throwable = $throwable->getPrevious(); ?>
                <p>
                    <pre class="pre-class"><?php echo $throwable->getTraceAsString(); ?></pre>
                </p>
            <?php endwhile; ?>
        </main>
    </body>
</html>
