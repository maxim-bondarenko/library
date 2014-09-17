<?php

/**
 * This is the model class for table "books".
 *
 * The followings are the available columns in table 'books':
 * @property integer $id
 * @property string $name
 * @property string $creation_date
 * @property string $last_update
 * @property integer $count_books
 * @property integer $reserved_count
 *
 * The followings are the available model relations:
 * @property AuthorsBooksRelation[] $authorsBooksRelations
 * @property ReadersBooksRelation[] $readersBooksRelations
 */
class Books extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'books';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required'),
            array('count_books, reserved_count', 'numerical', 'integerOnly'=>true),
            array('name', 'length', 'max'=>255),
            array('creation_date', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, creation_date, last_update, count_books, reserved_count', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'authors'=>array(self::HAS_MANY,'Authors', 'author_id', 'through' => 'authorsBooksRelations'),
            'authorsBooksRelations' => array(self::HAS_MANY, 'AuthorsBooksRelation', 'book_id'),
            'readersBooksRelations' => array(self::HAS_MANY, 'ReadersBooksRelation', 'book_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'creation_date' => 'Creation Date',
            'last_update' => 'Last Update',
            'count_books' => 'Count Books',
            'reserved_count' => 'Reserved Count',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('creation_date',$this->creation_date,true);
        $criteria->compare('last_update',$this->last_update,true);
        $criteria->compare('count_books',$this->count_books);
        $criteria->compare('reserved_count',$this->reserved_count);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Books the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function getBooks()
    {
        $criteria = new CDbCriteria;
        $criteria->with=array('authors');

       return new CActiveDataProvider($this, array(
           'pagination'=>false,
            'criteria'=>$criteria,
        ));
    }

    /***
     * return book info by PK
     * @param $id
     * @return array|bool|CActiveRecord|mixed|null
     */
    public function getBookInfoById($id)
    {
        $criteria = new CDbCriteria;
        $criteria->with=array('authors');
        $result = self::model()->findByPk($id, $criteria);
        return (!is_null($result)) ? $result : false;
    }

    /**
     * return Book List
     * @return array
     */
    public function getBookList()
    {
        $result = self::model()->findAll();
        return CHtml::listData($result, 'id', 'name');
    }

    /**
     * check free book
     * @param $id
     * @param $count
     * @return bool
     */
    public function isFreeBook($id, $count)
    {
        $criteria=new CDbCriteria;
        $criteria->select='reserved_count, count_books';
        $result = self::model()->findByPk($id, $criteria);
        return (((int)$count + (int)$result->reserved_count) > (int)$result->count_books) ? false : true;
    }

    public function getFiveRandomBook()
    {
        $criteria = new CDbCriteria;
        $criteria->order = 'RAND()';
        $criteria->with=array('authors');
        $criteria->limit = 5;
        return new CActiveDataProvider($this, array(
            'pagination'    => false,
            'criteria'=>$criteria,
        ));
    }

    public function getBookWhereCountAuthor()
    {
        $criteria = new CDbCriteria;
        $criteria->with=array('authorsBooksRelations'=>array('joinType' => 'INNER JOIN'), 'readersBooksRelations'=>array('joinType' => 'INNER JOIN'));
        $criteria->group='`t`.`id`';
        $criteria->having = 'COUNT(`t1_c2`) = 3';

        return new CActiveDataProvider($this, array(
            'pagination'=>false,
            'criteria'=>$criteria,
        ));
    }

    public function searchPhrase($phrase)
    {
        $criteria=new CDbCriteria;
        $criteria->join         = 'INNER JOIN `search` ON `search`.`book_id` = `t`.`id` ';
        $criteria->condition    = 'MATCH(`search`.`text`) AGAINST("' .$phrase. '" IN BOOLEAN MODE) > 0';
        $criteria->group        ='`search`.`book_id`';
        $criteria->order        = "COUNT(*) DESC";

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
}