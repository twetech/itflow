<?php
// src/View/simpleTable.php

$card_title = $card['title'];

$table_header_rows = $table['header_rows'];
$table_body_rows = $table['body_rows'];

?>

<div class="card">
    <div class="card-header">
        <h5 class="card-title"><?= $card_title ?></h5>
    </div>
    <div class="card-body">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <?php foreach ($table_header_rows as $header_row) : ?>
                        <th><?= $header_row ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($table_body_rows as $body_row) : ?>
                    <tr>
                        <?php foreach ($body_row as $cell) : ?>
                            <td><?= $cell ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
