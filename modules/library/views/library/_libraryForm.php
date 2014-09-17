<div class="form">
    <?php echo TbHtml::beginForm($request_url, 'post', array('enctype'=>'multipart/form-data')); ?>
    <div class="form-group has-success clearfix">
        <div class="wr col-sm-6">
            <?php echo TbHtml::dropDownList('reader_list', 'id', $reader_list, array(
                'empty' => 'Выберите Пользователя',
                'required'=>'true',
                'options'=>array(
                    isset($reader_id_checked) ? $reader_id_checked : ''  => array('selected'=>true)
                )
            )) ?>

        </div>
        <div class="wr col-sm-6">
            <?php echo TbHtml::dropDownList('books_list', 'id', $books_list, array(
                'empty' => 'Выберите Книгу',
                'required'=>'true',
                'options'=>array(
                     isset($book_id_checked) ? $book_id_checked : ''  => array('selected'=>true)
                )
            )) ?>
        </div>
        <div class="wr col-sm-6">
            <?php echo TbHtml::activeTextField($model, 'count_books',array(
                        'placeholder'=>'Количество',
                        'required'=>true,
                        'pattern'=>'[0-9]{1,2}',
                        'value'=> (isset($count)) ? $count : ''
            )); ?>
        </div>
    </div>
    <div>
        <?php echo TbHtml::submitButton('Выдать', array('color' => TbHtml::BUTTON_COLOR_PRIMARY)); ?>
    </div>
    <?php echo TbHtml::endForm(); ?>
</div>

