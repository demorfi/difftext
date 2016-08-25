<?php

/**
 * Auto loading of classes.
 *
 * @param string $className The class name
 * @return void
 */
function __autoload($className)
{
    include_once('classes' . DIRECTORY_SEPARATOR . $className . '.php');
}

$input = filter_input_array(
    INPUT_POST,
    [
        'text_1' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'text_2' => FILTER_SANITIZE_FULL_SPECIAL_CHARS
    ]
);
?>

<!--
* DiffText.
* User: demorfi@gmail.com
-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>DiffText</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <link rel="stylesheet" href="stylesheets/main.css" />
</head>
<body>
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="">DiffText</a>
        </div>
    </div>
</nav>
<div class="container">
    <div class="row">

        <?php if (isset($input['text_1'], $input['text_2'])) { ?>
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Comparison</div>
                    <div class="panel-body" id="diffResult">
                        <?php
                        $diffText = new DiffText($input['text_1'], $input['text_2']);

                        $structures = $diffText->diff();
                        while ($structures->valid()) {
                            $offers = $structures->getOffers();

                            printf(
                                '<div data-old="%s" data-new="%s" class="line-%s">',
                                $structures->getOld()->getText(),
                                $structures->getNew()->getText(),
                                $structures->getStat()
                            );

                            if ($offers->count() > 1) {
                                while ($offers->valid()) {

                                    printf(
                                        '<span data-old="%s" data-new="%s" class="offer-%s">',
                                        $offers->current()->getOld(),
                                        $offers->current()->getNew(),
                                        $offers->current()->getStat()
                                    );

                                    echo $offers->current()->getStat()->hasRemove()
                                        ? $offers->current()->getOld() : $offers->current()->getNew();

                                    echo '</span>';
                                    $offers->next();
                                }
                            } else {
                                $structure = $structures->getStat()->hasRemove()
                                    ? $structures->getOld() : $structures->getNew();

                                printf(
                                    '<span data-old="%s" data-new="%s" class="offer-%s">',
                                    $structures->getOld(),
                                    $structures->getNew(),
                                    $structures->getStat() . ($structure->isEmpty() ? ' new-line' : '')
                                );

                                echo $structure->isEmpty() ? '<br />' : $structure->getText();
                                echo '</span>';
                            }

                            echo '</div>';
                            $structures->next();
                        }
                        ?>
                    </div>
                    <div class="panel-footer text-right" id="differences">
                        Differences (<span><?php echo $diffText->getNumberOfDifferences(); ?></span>)
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="col-xs-12">
            <form method="POST">
                <div class="panel panel-default">
                    <div class="panel-heading">Text to compare</div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="text_1" class="sr-only">Text 1</label>
                            <textarea name="text_1" id="text_1" rows="5" class="form-control" placeholder="Your text 1"
                                      required
                                      autofocus><?php echo $input['text_1']; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="text_2" class="sr-only">Text 2</label>
                            <textarea name="text_2" id="text_2" rows="5" class="form-control" placeholder="Your text 2"
                                      required><?php echo $input['text_2']; ?></textarea>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <button type="reset" class="btn btn-default">Reset</button>
                        <button type="submit" class="btn btn-primary">Compare</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</body>

<script src="https://code.jquery.com/jquery-1.8.3.min.js"></script>
<script src="javascript/main.js"></script>
</html>
