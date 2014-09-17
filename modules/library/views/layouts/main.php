<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Library</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <?php Yii::app()->bootstrap->register(); ?>
    <!-- Le styles -->
    <style type="text/css">
        body {
            padding-top: 60px;
            padding-bottom: 40px;
        }
        .sidebar-nav {
            padding: 9px 0;
        }

        @media (max-width: 980px) {
            /* Enable use of floated navbar text */
            .navbar-text.pull-right {
                float: none;
                padding-left: 5px;
                padding-right: 5px;
            }
        }
    </style>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->
</head>

<body>

<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container-fluid">
            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">

            </button>
            <a class="brand" href="/"><?php echo CHtml::encode(Yii::app()->name);?></a>
            <div class="nav-collapse collapse">

            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row-fluid">
        <div class="span9">
             <?php echo TbHtml::tabs(array(
                 array('label' => 'Книги', 'url' => Yii::app()->createUrl('library/library/tabs', array('tab_id' => 'books')), 'active' => (isset($_GET['tab_id']) && $_GET['tab_id'] == 'books') ? true : false),
                 array('label' => 'Авторы', 'url' => Yii::app()->createUrl('library/library/tabs', array('tab_id' => 'authors')), 'active' => (isset($_GET['tab_id']) && $_GET['tab_id'] == 'authors') ? true : false),
                 array('label' => 'Читатели', 'url' => Yii::app()->createUrl('library/library/tabs', array('tab_id' => 'readers')),'active' => (isset($_GET['tab_id']) && $_GET['tab_id'] == 'readers') ? true : false ),
                 array('label' => 'Библиотека', 'url' => Yii::app()->createUrl('library/library/tabs', array('tab_id' => 'library')), 'active' => (isset($_GET['tab_id']) && $_GET['tab_id'] == 'library') ? true : false ),
                 array('label' => 'Отчеты', 'url' => Yii::app()->createUrl('library/reports',  array('tab_id' => 'report')), 'active' => (isset($_GET['tab_id']) && $_GET['tab_id'] == 'report') ? true : false),
                 array('label' => 'Поиск', 'url' => Yii::app()->createUrl('library/search',  array('tab_id' => 'search')), 'active' => (isset($_GET['tab_id']) && $_GET['tab_id'] == 'search') ? true : false),
             ));?>
            <?php echo $content;?>
        </div>
    </div>

    <hr>

    <footer>
        <p> &copy; 2014 by Agilites. All rights reserved.</p>
    </footer>

</div>
</body>
</html>
