<?php

/**
 * This is the model class for table "readers_books_relation".
 *
 * The followings are the available columns in table 'readers_books_relation':
 * @property integer $id
 * @property integer $book_id
 * @property integer $reader_id
 *
 * The followings are the available model relations:
 * @property Books $book
 * @property Readership $reader
 */
class ReadersBooksRelation extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'readers_books_relation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('book_id, reader_id', 'required'),
            array('book_id, reader_id, count_books', 'numerical', 'integerOnly'=>true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, book_id, reader_id, count_books', 'safe', 'on'=>'search'),
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
            'book' => array(self::BELONGS_TO, 'Books', 'book_id'),
            'reader' => array(self::BELONGS_TO, 'Readership', 'reader_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'book_id' => 'Book',
            'reader_id' => 'Reader',
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
        $criteria->compare('book_id',$this->book_id);
        $criteria->compare('reader_id',$this->reader_id);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ReadersBooksRelation the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function getLibraryBook()
    {
        $criteria=new CDbCriteria;
        $criteria->with=array('book', 'reader');

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * return relation Data
     * @param $id
     * @return array|CActiveRecord|mixed|null
     */
    public function getDataRelationById($id)
    {
        $result = self::model()->with('book', 'reader')->findByPk($id);
        return (!is_null($result)) ? $result : false;
    }


    public function getBookWhereCountAuthor()
    {
        $criteria = new CDbCriteria;
        $criteria->with=array('book', 'reader' );
       /* $criteria->group='`t`.`id`';
        $criteria->having = 'COUNT(  `t1_c2` ) = 2';*/

        echo'<pre>';print_r(self::model()->findAll($criteria)); echo'</pre>';
        /*return new CActiveDataProvider($this, array(
            'pagination'=>false,
            'criteria'=>$criteria,
        ));*/

    }
}