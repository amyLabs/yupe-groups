<?php

/**
 * Group
 *
 * Модель для работы с группами
 *
 * @author yupe team <team@yupe.ru>
 * @link http://yupe.ru
 * @copyright 2009-2013 amyLabs && Yupe! team
 * @package yupe.modules.groups.models
 * @since 0.1
 *
 */

/**
 * This is the model class for table "groups".
 *
 * The followings are the available columns in table 'groups':
 *
 * @property string $id
 * @property string $name
 * @property string $description
 * @property string $icon
 * @property string $slug
 * @property integer $type
 * @property integer $status
 * @property string  $create_user_id
 * @property string  $update_user_id
 * @property integer $create_time
 * @property integer $update_time
 * @property string  $lang
 * @property integer $member_status
 * @property integer $post_status
 *
 * The followings are the available model relations:
 * @property User $createUser

 * @property GroupsPost[] $posts
 */
class Groups extends yupe\models\YModel
{
    /**
     * Константы статусов
     */
    const STATUS_BLOCKED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;
    const STATUS_MODERATED = 3;

    /**
     * Константы типов
     */
    const TYPE_PUBLIC = 1;
    const TYPE_PRIVATE = 2;

    /**
     * Returns the static model of the specified AR class.
     * @param  string $className
     * @return Group the static model class
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
        return '{{groups}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['name, description, slug', 'required', 'except' => 'search'],
            ['name, description, slug', 'required', 'on' => ['update', 'insert']],
            [
                'type, status, create_user_id, update_user_id, create_time, update_time, member_status, post_status',
                'numerical',
                'integerOnly' => true
            ],
            ['name, icon', 'length', 'max' => 250],
            ['slug', 'length', 'max' => 150],
            ['lang', 'length', 'max' => 2],
            ['create_user_id, update_user_id, create_time, update_time, status', 'length', 'max' => 11],
            [
                'slug',
                'yupe\components\validators\YSLugValidator',
                'message' => Yii::t('GroupsModule.groups', 'Illegal characters in {attribute}')
            ],
            ['type', 'in', 'range' => array_keys($this->getTypeList())],
            ['status', 'in', 'range' => array_keys($this->getStatusList())],
            ['member_status', 'in', 'range' => array_keys($this->getMemberStatusList())],
            ['post_status', 'in', 'range' => array_keys($this->getPostStatusList())],
            ['name, slug, description', 'filter', 'filter' => [new CHtmlPurifier(), 'purify']],
            ['slug', 'unique'],
            [
                'id, name, description, slug, type, status, create_user_id, update_user_id, create_time, update_time, lang',
                'safe',
                'on' => 'search'
            ],
        ];
    }

    /**
     * @return array
     */
    public function getPostStatusList()
    {
        return GroupsPost::model()->getStatusList();
    }

    /**
     * @return array
     */
    public function getMemberStatusList()
    {
        return GroupsMembers::model()->getStatusList();
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
            'createUser'   => [self::BELONGS_TO, 'User', 'create_user_id'],
            'updateUser'   => [self::BELONGS_TO, 'User', 'update_user_id'],
            'posts'        => [
                self::HAS_MANY,
                'GroupsPost',
                'group_id',
                'condition' => 't.status = :status',
                'params'    => [':status' => GroupsPost::STATUS_PUBLISHED]
            ],
            'GroupsMembers'   => [
                self::HAS_MANY,
                'GroupsMembers',
                'group_id',
                'joinType'  => 'left join',
                'condition' => 'GroupsMembers.status = :status',
                'params'    => [':status' => GroupsMembers::STATUS_ACTIVE]
            ],
            'members'      => [self::HAS_MANY, 'User', ['user_id' => 'id'], 'through' => 'GroupsMembers'],
            'postsCount'   => [
                self::STAT,
                'GroupsPost',
                'group_id',
                'condition' => 't.status = :status',
                'params'    => [':status' => GroupsPost::STATUS_PUBLISHED]
            ],
            'membersCount' => [
                self::STAT,
                'GroupsMembers',
                'group_id',
                'condition' => 'status = :status',
                'params'    => [':status' => GroupsMembers::STATUS_ACTIVE]
            ],
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
                'params'    => [':status' => self::STATUS_ACTIVE],
            ],
            'public'    => [
                'condition' => 'type = :type',
                'params'    => [':type' => self::TYPE_PUBLIC],
            ],
        ];
    }

    /**
     * Условие для получения группы по url
     *
     * @param string $url - url данного группы
     * @return self
     */
    public function getByUrl($url = null)
    {
        $this->getDbCriteria()->mergeWith(
            [
                'condition' => $this->getTableAlias() . '.slug = :slug',
                'params'    => [
                    ':slug' => $url,
                ],
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
            'id'             => Yii::t('GroupsModule.groups', 'id'),
            'name'           => Yii::t('GroupsModule.groups', 'Title'),
            'description'    => Yii::t('GroupsModule.groups', 'Description'),
            'icon'           => Yii::t('GroupsModule.groups', 'Icon'),
            'slug'           => Yii::t('GroupsModule.groups', 'URL'),
            'type'           => Yii::t('GroupsModule.groups', 'Type'),
            'status'         => Yii::t('GroupsModule.groups', 'Status'),
            'create_user_id' => Yii::t('GroupsModule.groups', 'Created'),
            'update_user_id' => Yii::t('GroupsModule.groups', 'Updated'),
            'create_time'    => Yii::t('GroupsModule.groups', 'Created at'),
            'update_time'    => Yii::t('GroupsModule.groups', 'Updated at'),
            'member_status'  => Yii::t('GroupsModule.groups', 'User status'),
            'post_status'    => Yii::t('GroupsModule.groups', 'Post status'),
        ];
    }

    /**
     * @return array customized attribute descriptions (name=>description)
     */
    public function attributeDescriptions()
    {
        return [
            'id'          => Yii::t('GroupsModule.groups', 'Post id.'),
            'name'        => Yii::t(
                'GroupsModule.groups',
                'Please enter a title of the group. For example: <span class="label label-default">My travel notes</span>.'
            ),
            'description' => Yii::t(
                'GroupsModule.groups',
                'Please enter a short description of the group. For example:<br /><br /> <pre>Notes on my travel there and back again. Illustrated.</pre>'
            ),
            'icon'        => Yii::t('GroupsModule.groups', 'Please choose an icon for the group.'),
            'slug'        => Yii::t(
                'GroupsModule.groups',
                'Please enter an URL-friendly name for the group.<br /><br /> For example: <pre>http://site.ru/groups/<span class="label label-default">travel-notes</span>/</pre> If you don\'t know how to fill this field you can leave it empty.'
            ),
            'type'        => Yii::t(
                'GroupsModule.groups',
                'Please choose a type of the group:<br /><br /><span class="label label-success">public</span> &ndash; anyone can create posts<br /><br /><span class="label label-info">private</span> &ndash; only you can create posts'
            ),
            'status'      => Yii::t(
                'GroupsModule.groups',
                'Please choose a status of the group:<br /><br /><span class="label label-success">active</span> &ndash; The group will be visible and it will be possible to create new records<br /><br /><span class="label label-warning">blocked</span> &ndash; The group will be visible but it would not be possible to create new records<br /><br /><span class="label label-danger">removed</span> &ndash; The group will be invisible'
            ),
        ];
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * @return CActiveDataProvider the data provider that can return the models
     *                             based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria();

        $criteria->select = 't.*, count(post.id) as postsCount, count(member.id) as membersCount';
        $criteria->join = ' LEFT JOIN {{groups_post}} post ON post.group_id = t.id
                            LEFT JOIN {{groups_members}} member ON member.group_id = t.id';
        $criteria->group = 't.id';

        $criteria->compare('t.id', $this->id, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('slug', $this->slug, true);
        $criteria->compare('type', $this->type);
        $criteria->compare('t.status', $this->status);
        $criteria->compare('create_user_id', $this->create_user_id, true);
        $criteria->compare('update_user_id', $this->update_user_id, true);
        $criteria->compare('create_time', $this->create_time);
        $criteria->compare('update_time', $this->update_time);

        $criteria->with = ['createUser', 'postsCount', 'membersCount'];

        $sort = new CSort();
        $sort->defaultOrder = [
            'postsCount' => CSort::SORT_DESC
        ];

        $sort->attributes = [
            'postsCount'   => [
                'asc'   => 'postsCount ASC',
                'desc'  => 'postsCount DESC',
                'label' => Yii::t('GroupsModule.groups', 'Posts count')
            ],
            'membersCount' => [
                'asc'   => 'membersCount ASC',
                'desc'  => 'membersCount DESC',
                'label' => Yii::t('GroupsModule.groups', 'Members count')
            ],
            '*', // add all of the other columns as sortable
        ];

        return new CActiveDataProvider(get_class($this), [
            'criteria'   => $criteria,
            'pagination' => ['pageSize' => 10],
            'sort'       => $sort
        ]);
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $module = Yii::app()->getModule('groups');

        return [
            'imageUpload'        => [
                'class'         => 'yupe\components\behaviors\ImageUploadBehavior',
                'attributeName' => 'icon',
                'minSize'       => $module->minSize,
                'maxSize'       => $module->maxSize,
                'types'         => $module->allowedExtensions,
                'uploadPath'    => $module->uploadPath,
                'defaultImage'  => Yii::app()->getTheme()->getAssetsUrl() . '/images/group-default.jpg',
            ],
            'CTimestampBehavior' => [
                'class'             => 'zii.behaviors.CTimestampBehavior',
                'setUpdateOnCreate' => true,
            ],
            'seo'                => [
                'class'  => 'vendor.chemezov.yii-seo.behaviors.SeoActiveRecordBehavior',
                'route'  => 'groups/group/view',
                'params' => [
                    'slug' => function ($data) {
                        return $data->slug;
                    }
                ],
            ],
        ];
    }

    /**
     * @return bool
     */
    public function beforeSave()
    {
        $this->update_user_id = Yii::app()->getUser()->getId();

        if ($this->isNewRecord) {
            $this->create_user_id = $this->update_user_id;
        }

        return parent::beforeSave();
    }

    /**
     * @return bool
     */
    public function beforeValidate()
    {
        if (!$this->slug) {
            $this->slug = yupe\helpers\YText::translit($this->name);
        }

        return parent::beforeValidate();
    }

    /**
     *
     */
    public function afterDelete()
    {
        Comment::model()->deleteAll(
            'model = :model AND model_id = :model_id',
            [
                ':model'    => 'Groups',
                ':model_id' => $this->id
            ]
        );

        return parent::afterDelete();
    }

    /**
     * @return array
     */
    public function getStatusList()
    {
        return [
            self::STATUS_BLOCKED   => Yii::t('GroupsModule.groups', 'Blocked'),
            self::STATUS_ACTIVE    => Yii::t('GroupsModule.groups', 'Active'),
            self::STATUS_DELETED   => Yii::t('GroupsModule.groups', 'Removed'),
            self::STATUS_MODERATED => Yii::t('GroupsModule.groups', 'Moderated'),
        ];
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        $data = $this->getStatusList();

        return isset($data[$this->status])
            ? $data[$this->status]
            : Yii::t('GroupsModule.groups', '*unknown*');
    }

    /**
     * @return array
     */
    public function getTypeList()
    {
        return [
            self::TYPE_PUBLIC  => Yii::t('GroupsModule.groups', 'Public'),
            self::TYPE_PRIVATE => Yii::t('GroupsModule.groups', 'Private'),
        ];
    }

    /**
     * @return string
     */
    public function getType()
    {
        $data = $this->getTypeList();

        return isset($data[$this->type])
            ? $data[$this->type]
            : Yii::t('GroupsModule.groups', '*unknown*');
    }

    /**
     * @param $userId
     * @param int $status
     * @return bool|int
     */
    public function userIn($userId, $status = GroupsMembers::STATUS_ACTIVE)
    {
        $groups = Yii::app()->getCache()->get("Groups::GroupsMembers::{$userId}");

        if (false === $groups) {

            $result = Yii::app()->getDb()->createCommand(
                'SELECT group_id, status FROM {{groups_members}} WHERE user_id = :userId'
            )->bindValue(':userId', (int)$userId)
                ->queryAll();

            $groups = [];

            foreach ($result as $data) {
                $groups[$data['group_id']] = $data['status'];
            }

            Yii::app()->getCache()->set("Groups::GroupsMembers::{$userId}", $groups);
        }

        if (false !== $status) {
            if (isset($groups[$this->id]) && (int)$groups[$this->id] === (int)$status) {
                return true;
            }

            return false;
        }

        return isset($groups[$this->id]) ? (int)$groups[$this->id] : false;
    }

    /**
     * @param $userId
     * @return CActiveRecord
     */
    public function getUserMembership($userId)
    {
        return GroupsMembers::model()->find(
            'user_id = :userId AND group_id = :groupId',
            [
                ':userId' => (int)$userId,
                ':groupId' => $this->id
            ]
        );
    }

    /**
     * @param $userId
     * @param $status
     * @return mixed
     */
    public function hasUserInStatus($userId, $status)
    {
        return Yii::app()->getDb()->createCommand(
            'SELECT count(id)
                FROM {{groups_members}}
                 WHERE user_id = :userId AND group_id = :groupId AND status = :status'
        )
            ->bindValue(':userId', (int)$userId)
            ->bindValue(':status', (int)$status)
            ->bindValue(':groupId', $this->id)
            ->queryScalar();
    }

    /**
     * @param $userId
     * @return bool
     */
    public function join($userId, $backendFlag = false)
    {
        if ($backendFlag == false && $this->isPrivate()) {
            return false;
        }

        if ($this->userIn((int)$userId)) {
            return true;
        }

        //check user status in group
        $member = $this->getUserMembership($userId);

        if (null === $member) {

            $member = new GroupsMembers();
            $member->group_id = $this->id;
            $member->user_id = (int)$userId;
            $member->status = (int)$this->member_status;

        } else {

            if ($member->isDeleted()) {
                $member->activate();
            } else {
                return false;
            }
        }

        if ($member->save()) {

            Yii::app()->eventManager->fire(GroupEvents::GROUP_JOIN, new GroupJoinLeaveEvent($this, $userId));

            Yii::app()->getCache()->delete("Groups::GroupsMembers::{$userId}");

            return true;
        }

        return false;
    }

    /**
     * @param $userId
     * @return bool|int
     */
    public function leave($userId)
    {
        if ($this->isPrivate()) {
            return false;
        }

        Yii::app()->getCache()->delete("Groups::GroupsMembers::{$userId}");

        Yii::app()->eventManager->fire(GroupEvents::GROUP_LEAVE, new GroupJoinLeaveEvent($this, $userId));

        return GroupsMembers::model()->updateAll(
            [
                'status'      => GroupsMembers::STATUS_DELETED,
            ],
            'user_id = :userId AND group_id = :groupId',
            [
                ':userId' => (int)$userId,
                ':groupId' => $this->id
            ]
        );
    }

    /**
     * @return GroupsMembers
     */
    public function getMembersList()
    {
        $members = new GroupsMembers('search');
        $members->unsetAttributes();
        $members->group_id = $this->id;
        $members->status = GroupsMembers::STATUS_ACTIVE;

        return $members;
    }

    /**
     * @return Post
     */
    public function getPosts()
    {
        $posts = new GroupsPost('search');
        $posts->unsetAttributes();
        $posts->group_id = $this->id;
        $posts->status = GroupsPost::STATUS_PUBLISHED;
        $posts->access_type = GroupsPost::ACCESS_PUBLIC;

        return $posts;
    }

    /**
     * @return mixed
     */
    public function getList()
    {
        return $this->published()->findAll(['order' => 'name ASC']);
    }

    /**
     * @param $user
     * @return mixed
     */
    public function getMembershipListForUser($user)
    {
        return $this->with('GroupsMembers')->published()->findAll(
            [
                'condition' => '(GroupsMembers.user_id = :userId AND GroupsMembers.status = :status)',
                'params'    => [
                    ':status' => GroupsMembers::STATUS_ACTIVE,
                    ':userId' => (int)$user
                ],
                'order'     => 'name ASC'
            ]
        );
    }

    /**
     * @param $id
     * @param array $with
     * @return mixed
     */
    public function get($id, array $with = ['posts', 'membersCount', 'createUser'])
    {
        return $this->with($with)->published()->findByPk((int)$id);
    }

    /**
     * @param $id
     * @param array $with
     * @return mixed
     */
    public function getBySlug($id, array $with = ['posts', 'membersCount', 'createUser'])
    {
        return $this->with($with)->getByUrl($id)->published()->find();
    }

    /**
     * @return bool
     */
    public function isPrivate()
    {
        return $this->type == self::TYPE_PRIVATE;
    }

    /**
     * @param $userId
     * @return bool
     */
    public function isOwner($userId)
    {
        return $this->create_user_id == $userId;
    }

    public function getPrivateGroupsForUser($userId)
    {
        return $this->published()->findAll('create_user_id = :id AND type = :type', [
                ':id'   => (int)$userId,
                ':type' => self::TYPE_PRIVATE
            ]);
    }

    /**
     * получаем список групп, в которых пользователь может добавлять записи
     */
    public function getListForUser($userId)
    {
        return CMap::mergeArray(
            $this->getMembershipListForUser($userId),
            $this->getPrivateGroupsForUser($userId)
        );
    }

    /**
     * Утверждаем группу
     * @return bool
     */
    public function approve()
    {
         $transaction = Yii::app()->db->beginTransaction();
        try
        {
            $this->status = self::STATUS_ACTIVE;
            if($this->save() && $this->join($this->create_user_id, true))
            {
                $transaction->commit();
                Yii::app()->user->setFlash(
                    yupe\widgets\YFlashMessages::SUCCESS_MESSAGE,
                    Yii::t('GroupsModule.groups', 'Group was approved!')
                );
            }
        }
        catch(Exception $e)
        {
            $transaction->rollback();
            Yii::log($e->__toString(), CLogger::LEVEL_ERROR);
        }

        return true;
    }
}
