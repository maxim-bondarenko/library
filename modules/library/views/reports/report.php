<div>
    <p><b>Вывод пяти случайных книг.</b></p>
    <?php
        $this->widget('bootstrap.widgets.TbGridView', array(
            'dataProvider' => $book_model->getFiveRandomBook(),
            'template' => "{items}",
            'columns' => array(
                array(
                    'name' => 'name',
                    'header' => 'Название Книги',
                    'htmlOptions' => array('color' =>'width: 60px'),
                ),
                array(
                    'name' => 'authors.author_name',
                    'header' => 'Автор',
                    'type' => 'raw',
                    'value'=>'implode(", ", CHtml::listData($data->authors, "id", "author_name"))',
                ),

            ),
        ));
    ?>

    <p><b> Вывод списка книг, находящихся на руках у читателей, и имеющих не менее трех со-авторов.</b></p>
    <?php
        $this->widget('bootstrap.widgets.TbGridView', array(
            'dataProvider' => $book_model->getBookWhereCountAuthor(),
            'template' => "{items}",
            'columns' => array(
                array(
                    'name' => 'name',
                    'header' => 'Название Книги',
                    'htmlOptions' => array('color' =>'width: 60px'),
                )
            ),
        ));
    ?>
    <p><b>Вывод списка авторов, чьи книги в данный момент читает более трех читателей.</b></p>
    <?php
    $this->widget('bootstrap.widgets.TbGridView', array(
        'dataProvider' => $authors_model->getAuthorsWhereReadReader(),
        'template' => "{items}",
        'columns' => array(
            array(
                'name' => 'author_name',
                'header' => 'Имя Автора',
                'htmlOptions' => array('color' =>'width: 60px'),
            )
        ),
    ));
    ?>
</div>