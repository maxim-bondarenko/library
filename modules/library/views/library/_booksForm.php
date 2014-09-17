<div class="form">
    <?php echo TbHtml::beginForm($request_url, 'post', array('enctype'=>'multipart/form-data')); ?>
    <div class="form-group has-success clearfix">
        <div class="wr col-sm-6">
            <?php echo TbHtml::activeTextField($model, 'name',array(
                'placeholder'=>'Наименование Книги',
                'required'=>'true',
                'autofocus'=>'true',
                'class'=>'form-control',
                'value'=> (isset($result)) ? $result->name : ''
            )); ?>

        </div>
        <div class="wr col-sm-6">
            <?php echo TbHtml::activeTextField($model, 'count_books',array(
                'placeholder'=>'Количество',
                'required'=>'true',
                'autofocus'=>'true',
                'class'=>'form-control',
                'pattern'=>'[0-9]{1,2}',
                'value'=> (isset($result)) ? $result->count_books : ''
            )); ?>

        </div>
        <div class="wr col-sm-6">
            <?php echo TbHtml::dropDownList('author_list', 'id', $author_list, array(
                'multiple'=>true,
                'required'=>'true',
            )) ?>
        </div>

    </div>
    <div>
        <?php echo TbHtml::submitButton('Сохранить', array('color' => TbHtml::BUTTON_COLOR_PRIMARY)); ?>
    </div>
    <?php echo TbHtml::endForm(); ?>
</div>


