<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Biditems Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\BidinfoTable&\Cake\ORM\Association\HasMany $Bidinfo
 * @property \App\Model\Table\BidrequestsTable&\Cake\ORM\Association\HasMany $Bidrequests
 *
 * @method \App\Model\Entity\Biditem get($primaryKey, $options = [])
 * @method \App\Model\Entity\Biditem newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Biditem[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Biditem|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Biditem saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Biditem patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Biditem[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Biditem findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BiditemsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('biditems');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->hasOne('Bidinfo', [
            'foreignKey' => 'biditem_id',
        ]);
        $this->hasMany('Bidrequests', [
            'foreignKey' => 'biditem_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 100)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('detail')
            ->maxLength('detail', 300)
            ->allowEmptyString('detail');

        // CRUD用のバリデーション
        $validator
            ->scalar('image')
            ->maxLength('image', 255)
            ->allowEmptyString('image');

        // 画像アップロード用のバリデーション
        $validator
            ->allowEmptyFile('image_file') // 必須にしたい場合は外せばおｋ
            ->add('image_file', 'uploadError', [
                'rule' => [ 'uploadError' ],
                'message' => 'ファイルアップロードに失敗しました。'
            ])
            // ファイルサイズのチェックは割愛
            // 上限を決めたい場合は先にWebサーバの設定を変更しないと
            // 2MBを超えるファイルをアップロードすると
            // 413 Request Entity Too Largeになる(´・ω・｀)
            ->add('image_file', 'extension', [
                'rule' => [ 'extension', [ 'jpg', 'jpeg', 'png', 'gif' ] ],
                'message' => 'ファイルの拡張子がjpg, jpeg, png, gif(大文字含む)のみアップロード可能です。',
                // 有効化していないと以降のバリデーションも走って
                // エラーメッセージが複数表示されるのでぶちゃいくになる
                'last' => true
                ]
            )
            ->add('image_file', 'mimeType', [
                'rule' => [ 'mimeType', [ 'image/jpeg', 'image/png', 'image/gif' ] ],
                'message' => 'ファイルが画像ではありません。'
                ]
            );

        $validator
            ->boolean('finished')
            ->requirePresence('finished', 'create')
            ->notEmptyString('finished');

        $validator
            ->dateTime('endtime')
            ->requirePresence('endtime', 'create')
            ->notEmptyDateTime('endtime');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
