<?php
/**
 * GroupsPost
 *
 * Модель для работы с записями группы
 *
 * @author yupe team <team@yupe.ru>
 * @link http://yupe.ru
 * @copyright 2009-2013 amyLabs && Yupe! team
 * @package yupe.modules.groups.models
 * @since 0.1
 *
 */

/**
 * This is the model class for table "groups_post".
 *
 * The followings are the available columns in table 'post':
 * @property string $id
 * @property string $group_id
 * @property string $create_user_id
 * @property string $update_user_id
 * @property integer $create_time
 * @property integer $update_time
 * @property string $slug
 * @property string $publish_time
 * @property string $title
 * @property string $quote
 * @property string $content
 * @property string $link
 * @property integer $status
 * @property integer $comment_status
 * @property integer $access_type
 * @property string $keywords
 * @property string $description
 * @property string $lang
 * @property string $create_user_ip
 * @property string $image
 * @property integer $category_id
 *
 * The followings are the available model relations:
 * @property User $createUser
 * @property User $updateUser
 * @property Groups $groups
 */
Yii::import('application.modules.groups.models.Groups');
Yii::import('application.modules.groups.events.*');
Yii::import('application.modules.groups.listeners.*');
Yii::import('application.modules.comment.components.ICommentable');

/**
 * Class GroupsPost
 */
class GroupsPost extends yupe\models\YModel implements ICommentable
{
    /**
     *
     */
    const STATUS_DRAFT = 0;
    /**
     *
     */
    const STATUS_PUBLISHED = 1;
    /**
     *
     */
    const STATUS_SCHEDULED = 2;
    /**
     *
     */
    const STATUS_MODERATED = 3;

    /**
     *
     */
    const STATUS_DELETED = 4;

    /**
     *
     */
    const ACCESS_PUBLIC = 1;
    /**
     *
     */
    const ACCESS_PRIVATE = 2;

    /**
     *
     */
    const COMMENT_YES = 1;
    /**
     *
     */
    const COMMENT_NO = 0;

    /**
     * @var
     */
    public $tagsItems;

    /**
     * @var
     */
    public $tags;

    /**
     * Returns the static model of the specified AR class.
     * @param  string $className
     * @return GroupsPost   the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{groups_post}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['group_id, slug,  title, content, status, publish_time', 'required', 'except' => 'search'],
            [
                'group_id, create_user_id, update_user_id, status, comment_status, access_type, create_time, update_time, category_id',
                'numerical',
                'integerOnly' => true
            ],
            [
                'group_id, create_user_id, update_user_id, create_time, update_time, status, comment_status, access_type',
                'length',
                'max' => 11
            ],
            ['lang', 'length', 'max' => 2],
            ['publish_time', 'length', 'max' => 20],
            ['slug', 'length', 'max' => 150],
            ['image', 'length', 'max' => 300],
            ['create_user_ip', 'length', 'max' => 20],
            ['description, title, link, keywords', 'length', 'max' => 250],
            ['quote', 'length', 'max' => 500],
            ['link', 'yupe\components\validators\YUrlValidator'],
            ['comment_status', 'in', 'range' => array_keys($this->getCommentStatusList())],
            ['access_type', 'in', 'range' => array_keys($this->getAccessTypeList())],
            ['status', 'in', 'range' => array_keys($this->getStatusList())],
            [
                'slug',
                'yupe\components\validators\YSLugValidator',
                'message' => Yii::t('GroupsModule.groups', 'Forbidden symbols in {attribute}')
            ],
            [
                'title, slug, link, keywords, description, publish_time',
                'filter',
                'filter' => [$obj = new CHtmlPurifier(), 'purify']
            ],
            ['slug', 'unique'],
            ['tags', 'safe'],
            [
                'id, group_id, create_user_id, update_user_id, create_time, update_time, slug, publish_time, title, quote, content, link, status, comment_status, access_type, keywords, description, lang',
                'safe',
                'on' => 'search'
            ],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
            'createUser' => [self::BELONGS_TO, 'User', 'create_user_id'],
            'updateUser' => [self::BELONGS_TO, 'User', 'update_user_id'],
            'group' => [self::BELONGS_TO, 'Groups', 'group_id'],
            'comments' => [
                self::HAS_MANY,
                'Comment',
                'model_id',
                'on' => 'model = :model AND comments.status = :status and level > 1',
                'params' => [
                    ':model' => 'GroupsPost',
                    ':status' => Comment::STATUS_APPROVED
                ],
                'order' => 'comments.id'
            ],
            'commentsCount' => [
                self::STAT,
                'Comment',
                'model_id',
                'condition' => 'model = :model AND status = :status AND level > 1',
                'params' => [
                    ':model' => 'GroupsPost',
                    ':status' => Comment::STATUS_APPROVED
                ]
            ],
            'category' => [self::BELONGS_TO, 'Category', 'category_id']
        ];
    }

    /**
     * @return array
     */
    public function scopes()
    {
        return [
            'published' => [
                'condition' => 't.status = :status',
                'params' => [':status' => self::STATUS_PUBLISHED],
            ],
            'public' => [
                'condition' => 't.access_type = :access_type',
                'params' => [':access_type' => self::ACCESS_PUBLIC],
            ],
            'moderated' => [
                'condition' => 't.status = :status',
                'params' => [':status' => self::STATUS_MODERATED]
            ],
            'recent' => [
                'order' => 'publish_time DESC'
            ]
        ];
    }

    /**
     * Условие для получения определённого количества записей:
     *
     * @param integer $count - количество записей
     *
     * @return self
     */
    public function limit($count = null)
    {
        $this->getDbCriteria()->mergeWith(
            [
                'limit' => $count,
            ]
        );

        return $this;
    }

    /**
     * Условие для сортировки по дате
     *
     * @param string $typeSort - типо сортировки
     *
     * @return self
     **/
    public function sortByPubDate($typeSort = 'ASC')
    {
        $this->getDbCriteria()->mergeWith(
            [
                'order' => $this->getTableAlias() . '.publish_time ' . $typeSort,
            ]
        );

        return $this;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('GroupsModule.groups', 'id'),
            'group_id' => Yii::t('GroupsModule.groups', 'Group'),
            'create_user_id' => Yii::t('GroupsModule.groups', 'Created'),
            'update_user_id' => Yii::t('GroupsModule.groups', 'Update user'),
            'create_time' => Yii::t('GroupsModule.groups', 'Created at'),
            'update_time' => Yii::t('GroupsModule.groups', 'Updated at'),
            'publish_time' => Yii::t('GroupsModule.groups', 'Date'),
            'slug' => Yii::t('GroupsModule.groups', 'Url'),
            'title' => Yii::t('GroupsModule.groups', 'Title'),
            'quote' => Yii::t('GroupsModule.groups', 'Quote'),
            'content' => Yii::t('GroupsModule.groups', 'Content'),
            'link' => Yii::t('GroupsModule.groups', 'Link'),
            'status' => Yii::t('GroupsModule.groups', 'Status'),
            'comment_status' => Yii::t('GroupsModule.groups', 'Comments'),
            'access_type' => Yii::t('GroupsModule.groups', 'Access'),
            'keywords' => Yii::t('GroupsModule.groups', 'Keywords'),
            'description' => Yii::t('GroupsModule.groups', 'description'),
            'tags' => Yii::t('GroupsModule.groups', 'Tags'),
            'image' => Yii::t('GroupsModule.groups', 'Image'),
            'category_id' => Yii::t('GroupsModule.groups', 'Category')
        ];
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria();

        $criteria->compare('t.id', $this->id, true);
        $criteria->compare('group_id', $this->group_id);
        $criteria->compare('t.create_user_id', $this->create_user_id, true);
        $criteria->compare('t.update_user_id', $this->update_user_id, true);
        $criteria->compare('t.create_time', $this->create_time);
        $criteria->compare('t.update_time', $this->update_time);
        $criteria->compare('t.slug', $this->slug, true);
        if ($this->publish_time) {
            $criteria->compare('DATE(from_unixtime(publish_time))', date('Y-m-d', strtotime($this->publish_time)));
        }
        $criteria->compare('title', $this->title, true);
        $criteria->compare('quote', $this->quote, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('link', $this->link, true);
        $criteria->compare('t.status', $this->status);
        $criteria->compare('comment_status', $this->comment_status);
        $criteria->compare('access_type', $this->access_type);
        $criteria->compare('t.category_id', $this->category_id, true);

        $criteria->with = ['createUser', 'updateUser', 'group'];

        return new CActiveDataProvider(
            'GroupsPost', [
                'criteria' => $criteria,
                'sort' => [
                    'defaultOrder' => 'publish_time DESC',
                ]
            ]
        );
    }

    /**
     * @return CActiveDataProvider
     */
    public function allPosts()
    {
        $criteria = new CDbCriteria();
        $criteria->addCondition('t.status = :status');
        $criteria->addCondition('t.access_type = :access_type');
        $criteria->params = [
            ':status' => self::STATUS_PUBLISHED,
            ':access_type' => self::ACCESS_PUBLIC
        ];
        $criteria->with = ['group', 'createUser', 'commentsCount'];
        $criteria->order = 'publish_time DESC';

        return new CActiveDataProvider(
            'GroupsPost', ['criteria' => $criteria]
        );
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $module = Yii::app()->getModule('groups');

        return [
            'CTimestampBehavior' => [
                'class' => 'zii.behaviors.CTimestampBehavior',
                'setUpdateOnCreate' => true,
            ],
            'tags' => [
                'class' => 'vendor.yiiext.taggable-behavior.EARTaggableBehavior',
                'tagTable' => Yii::app()->db->tablePrefix . 'groups_tag',
                'tagBindingTable' => Yii::app()->db->tablePrefix . 'groups_post_to_tag',
                'tagModel' => 'Tag',
                'modelTableFk' => 'post_id',
                'tagBindingTableTagId' => 'tag_id',
                'cacheID' => 'cache',
            ],
            'imageUpload' => [
                'class' => 'yupe\components\behaviors\ImageUploadBehavior',
                'attributeName' => 'image',
                'minSize' => $module->minSize,
                'maxSize' => $module->maxSize,
                'types' => $module->allowedExtensions,
                'uploadPath' => $module->uploadPath,
            ],
            'seo'                => [
                'class'  => 'vendor.chemezov.yii-seo.behaviors.SeoActiveRecordBehavior',
                'route'  => 'groups/groupsPost/view',
                'params' => [
                    'slug' => function ($data) {
                        return $data->group->slug;
                    },
                    'postSlug' => function ($data) {
                        return $data->slug;
                    },

                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function generateFileName()
    {
        return md5($this->slug . microtime(true) . uniqid());
    }

    /**
     * @return bool
     */
    public function beforeSave()
    {
        $this->publish_time = strtotime($this->publish_time);

        $this->update_user_id = Yii::app()->user->getId();

        if ($this->getIsNewRecord()) {
            $this->create_user_id = $this->update_user_id;
            $this->create_user_ip = Yii::app()->getRequest()->userHostAddress;
        }

        if (!$this->tags) {
            $this->removeAllTags();
        } else {
            $this->setTags($this->tags);
        }

        return parent::beforeSave();
    }

    /**
     *
     */
    public function afterDelete()
    {
        Comment::model()->deleteAll(
            'model = :model AND model_id = :model_id',
            [
                ':model' => 'GroupsPost',
                ':model_id' => $this->id
            ]
        );

        parent::afterDelete();
    }

    /**
     * @return bool
     */
    public function beforeValidate()
    {
        if (!$this->slug) {
            $this->slug = yupe\helpers\YText::translit($this->title);
        }

        return parent::beforeValidate();
    }

    /**
     * @return array
     */
    public function getStatusList()
    {
        return [
            self::STATUS_DRAFT => Yii::t('GroupsModule.groups', 'Draft'),
            self::STATUS_PUBLISHED => Yii::t('GroupsModule.groups', 'Published'),
            self::STATUS_SCHEDULED => Yii::t('GroupsModule.groups', 'Scheduled'),
            self::STATUS_MODERATED => Yii::t('GroupsModule.groups', 'Moderated'),
            self::STATUS_DELETED => Yii::t('GroupsModule.groups', 'Deleted')
        ];
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        $data = $this->getStatusList();

        return isset($data[$this->status]) ? $data[$this->status] : Yii::t('GroupsModule.groups', '*unknown*');
    }

    /**
     * @return array
     */
    public function getAccessTypeList()
    {
        return [
            self::ACCESS_PRIVATE => Yii::t('GroupsModule.groups', 'Private'),
            self::ACCESS_PUBLIC => Yii::t('GroupsModule.groups', 'Public'),
        ];
    }

    /**
     * @return string
     */
    public function getAccessType()
    {
        $data = $this->getAccessTypeList();

        return isset($data[$this->access_type]) ? $data[$this->access_type] : Yii::t('GroupsModule.groups', '*unknown*');
    }

    /**
     * @return string
     */
    public function getCommentStatus()
    {
        $data = $this->getCommentStatusList();

        return isset($data[$this->comment_status]) ? $data[$this->comment_status] : Yii::t(
            'GroupsModule.groups',
            '*unknown*'
        );
    }

    /**
     * @return array
     */
    public function getCommentStatusList()
    {
        return [
            self::COMMENT_NO => Yii::t('GroupsModule.groups', 'Forbidden'),
            self::COMMENT_YES => Yii::t('GroupsModule.groups', 'Allowed'),
        ];
    }

    /**
     * after find event:
     *
     * @return parent::afterFind()
     **/
    public function afterFind()
    {
        $this->publish_time = date('d-m-Y H:i', $this->publish_time);

        return parent::afterFind();
    }

    /**
     * @param int $limit
     * @return string
     */
    public function getQuote($limit = 500)
    {
        return $this->quote
            ? : yupe\helpers\YText::characterLimiter(
                $this->content,
                (int)$limit
            );
    }

    /**
     * @param null $groupId
     * @param int $cache
     * @return mixed
     */
    public function getArchive($groupId = null, $cache = 3600)
    {
        $data = Yii::app()->getCache()->get("Groups::GroupsPost::archive::{$groupId}");

        if (false === $data) {

            $criteria = new CDbCriteria();

            if ($groupId) {
                $criteria->condition = 'group_id = :group_id';
                $criteria->params = [
                    ':group_id' => (int)$groupId
                ];
            }

            $models = $this->public()->published()->recent()->findAll($criteria);

            if (!empty($models)) {

                foreach ($models as $model) {

                    list($day, $month, $year) = explode('-', date('d-m-Y', strtotime($model->publish_time)));

                    $data[$year][$month][] = [
                        'title' => $model->title,
                        'slug' => $model->slug,
                        'publish_time' => $model->publish_time,
                        'quote' => $model->getQuote()
                    ];
                }
            } else {
                $data = [];
            }

            Yii::app()->getCache()->set("Groups::GroupsPost::archive::{$groupId}", $data, (int)$cache);
        }

        return $data;
    }

    /**
     * @param int $limit
     * @param $cacheTime
     * @return mixed
     */
    public function getStream($limit = 10, $cacheTime)
    {
        $data = Yii::app()->cache->get('Group::GroupsPost::Stream');

        if (false === $data) {
            $data = Yii::app()->db->createCommand()
                ->select('p.title, p.slug, max(c.creation_time) comment_time, count(c.id) as commentsCount')
                ->from('{{comment_comment}} c')
                ->join('{{groups_post}} p', 'c.model_id = p.id')
                ->where(
                    'c.model = :model AND p.status = :status AND c.status = :commentstatus AND c.id <> c.root',
                    [
                        ':model' => 'GroupsPost',
                        ':status' => GroupsPost::STATUS_PUBLISHED,
                        ':commentstatus' => Comment::STATUS_APPROVED
                    ]
                )
                ->group('c.model, c.model_id, p.title, p.slug')
                ->order('comment_time DESC')
                ->having('count(c.id) > 0')
                ->limit((int)$limit)
                ->queryAll();

            Yii::app()->cache->set('Group::GroupsPost::Stream', $data, (int)$cacheTime);
        }

        return $data;
    }

    /**
     * @param $id
     * @param array $with
     * @return mixed
     */
    public function get($id, array $with = [])
    {
        if (is_int($id)) {
            return GroupsPost::model()->public()->published()->with($with)->findByPk($id);
        }

        return GroupsPost::model()->public()->published()->with($with)->find(
            't.slug = :slug',
            [
                ':slug' => $id
            ]
        );
    }

    /**
     * @param $tag
     * @param array $with
     * @return mixed
     */
    public function getByTag($tag, array $with = ['group', 'createUser', 'commentsCount'])
    {
        return GroupsPost::model()->with($with)
            ->published()
            ->public()
            ->sortByPubDate('DESC')
            ->taggedWith($tag)->findAll();
    }

    /**
     * @param $groupId
     * @return GroupsPost
     */
    public function getForGroup($groupId)
    {
        $posts = new GroupsPost('search');
        $posts->unsetAttributes();
        $posts->group_id = (int)$groupId;
        $posts->status = GroupsPost::STATUS_PUBLISHED;
        $posts->access_type = GroupsPost::ACCESS_PUBLIC;

        return $posts;
    }

    /**
     * @param $categoryId
     * @return GroupsPost
     */
    public function getForCategory($categoryId)
    {
        $posts = new GroupsPost('search');
        $posts->unsetAttributes();
        $posts->category_id = (int)$categoryId;
        $posts->status = GroupsPost::STATUS_PUBLISHED;
        $posts->access_type = GroupsPost::ACCESS_PUBLIC;

        return $posts;
    }

    /**
     * @return mixed
     */
    public function getCategories()
    {
        return Yii::app()->db->createCommand()
            ->select('cc.name, bp.category_id, count(bp.id) cnt, cc.alias, cc.description')
            ->from('yupe_groups_post bp')
            ->join('yupe_category_category cc', 'bp.category_id = cc.id')
            ->where('bp.category_id IS NOT NULL')
            ->group('bp.category_id')
            ->having('cnt > 0')
            ->order('cnt DESC')
            ->queryAll();
    }

    /**
     * @return int|mixed
     */
    public function getCommentCount()
    {
        return $this->commentsCount > 0 ? $this->commentsCount : 0;
    }

    /**
     * @param array $post
     * @param $tags
     * @return bool
     */
    public function createPublicPost(array $post)
    {
        if (empty($post['group_id']) || empty($post['user_id'])) {
            $this->addError('group_id', Yii::t('GroupsModule.groups', "Group is empty!"));

            return false;
        }

        $group = Groups::model()->get((int)$post['group_id'], []);

        if (null === $group) {
            $this->addError('group_id', Yii::t('GroupsModule.groups', "You can't write in this group!"));

            return false;
        }

        if ($group->isPrivate() && !$group->isOwner($post['user_id'])) {
            $this->addError('group_id', Yii::t('GroupsModule.groups', "You can't write in this group!"));

            return false;
        }

        if (!$group->isPrivate() && !$group->userIn($post['user_id'])) {
            $this->addError('group_id', Yii::t('GroupsModule.groups', "You can't write in this group!"));

            return false;
        }

        $this->setAttributes($post);
        $this->setTags($post['tags']);
        $this->publish_time = date('d-m-Y h:i');
        $this->status = $post['status'] == self::STATUS_DRAFT ? self::STATUS_DRAFT : $group->post_status;

        return $this->save();
    }

    /**
     * @param $user
     * @return GroupsPost
     */
    public function getForUser($user)
    {
        $posts = new GroupsPost('search');
        $posts->unsetAttributes();
        $posts->create_user_id = (int)$user;

        return $posts;
    }

    /**
     * @param $postId
     * @param $userId
     * @return int
     */
    public function deleteUserPost($postId, $userId)
    {
        return $this->updateAll(
            ['status' => self::STATUS_DELETED],
            'create_user_id = :userId AND id = :id AND status != :status',
            [
                ':userId' => (int)$userId,
                ':id' => (int)$postId,
                ':status' => self::STATUS_PUBLISHED
            ]
        );
    }

    /**
     * @param $postId
     * @param $userId
     * @return CActiveRecord
     */
    public function findUserPost($postId, $userId)
    {
        return $this->find(
            'id = :id AND create_user_id = :userId AND status != :status',
            [
                ':userId' => (int)$userId,
                ':id' => (int)$postId,
                ':status' => self::STATUS_PUBLISHED
            ]
        );
    }

    /**
     * @return mixed|string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getLink()
    {
        return Yii::app()->createAbsoluteUrl('/group/post/show/', ['slug' => $this->slug]);
    }

    /**
     * @return bool
     */
    public function isPublished()
    {
        return $this->status == self::STATUS_PUBLISHED;
    }

    /**
     * @return bool
     */
    public function isDraft()
    {
        return $this->status == self::STATUS_DRAFT;
    }

    /**
     * @return bool
     */
    public function publish()
    {
        $transaction = Yii::app()->db->beginTransaction();

        try {
            $this->status = self::STATUS_PUBLISHED;
            $this->publish_time = date('d-m-Y h:i');
            if ($this->save()) {
                Yii::app()->eventManager->fire(
                    GroupEvents::POST_PUBLISH,
                    new GroupPostPublishEvent($this, Yii::app()->getUser())
                );
            }
            $transaction->commit();

            return true;
        } catch (Exception $e) {
            $transaction->rollback();
            Yii::log($e->__toString(), CLogger::LEVEL_ERROR);

            return true;
        }
    }

}
