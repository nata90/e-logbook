<?php

namespace app\modules\logbook\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\logbook\models\Kinerja;
use app\modules\pegawai\models\JabatanPegawai;
use app\modules\app\models\AppUser;
use yii\helpers\ArrayHelper;
use yii\web\Session;

/**
 * KinerjaSearch represents the model behind the search form of `app\modules\logbook\models\Kinerja`.
 */
class KinerjaSearch extends Kinerja
{
    public $date_start;
    public $date_end;
    public $range_date;
    public $list_pegawai;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_kinerja', 'id_pegawai', 'jumlah', 'approval', 'user_approval'], 'integer'],
            [['tanggal_kinerja', 'id_tugas', 'deskripsi', 'tgl_approval', 'create_date','date_start','date_end','range_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Kinerja::find();

        $id_user = Yii::$app->user->id;
        $user = AppUser::findOne($id_user);
        $list_pegawai_dinilai = JabatanPegawai::find()
        ->select(['data_pegawai.id_pegawai','data_pegawai.nama'])
        ->leftJoin('data_pegawai','jabatan_pegawai.id_pegawai = data_pegawai.id_pegawai')
        ->where(['jabatan_pegawai.id_penilai'=>$user->pegawai_id, 'jabatan_pegawai.status_jbt'=>1])
        ->orderBy('data_pegawai.nama ASC')
        ->all();

        $listPegawai = ArrayHelper::map($list_pegawai_dinilai,'id_pegawai','id_pegawai');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_kinerja' => $this->id_kinerja,
            'tanggal_kinerja' => $this->tanggal_kinerja,
            'id_pegawai' => $this->id_pegawai,
            'jumlah' => $this->jumlah,
            'approval' => $this->approval,
            'user_approval' => $this->user_approval,
            'tgl_approval' => $this->tgl_approval,
            'create_date' => $this->create_date,
        ]);


        $query->andFilterWhere(['like', 'id_tugas', $this->id_tugas])->andFilterWhere(['like', 'deskripsi', $this->deskripsi])->andFilterWhere(['IN', 'id_pegawai', $listPegawai]);

        if($this->range_date != null){
            $explode = explode('-',$this->range_date);
            $date_start = date('Y-m-d', strtotime(trim($explode[0])));
            $date_end = date('Y-m-d', strtotime(trim($explode[1])));
            $query->andFilterWhere(['between', 'tanggal_kinerja', $date_start, $date_end]);
        }

        $query->orderBy('tanggal_kinerja ASC');

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchStaff($params)
    {
        $query = Kinerja::find();
        $session = new Session;
        $session->open();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 500,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_kinerja' => $this->id_kinerja,
            'id_pegawai' => $this->id_pegawai,
            'jumlah' => $this->jumlah,
            'approval' => 1,
            'user_approval' => $this->user_approval,
            'tgl_approval' => $this->tgl_approval,
            'create_date' => $this->create_date,
        ]);

        if($this->list_pegawai != null){
            $query->andFilterWhere(['like', 'id_tugas', $this->id_tugas])->andFilterWhere(['like', 'deskripsi', $this->deskripsi])->andFilterWhere(['IN', 'id_pegawai', $this->list_pegawai]);
        }

        if($this->range_date != null){
            $explode = explode('-',$this->range_date);
            $date_start = date('Y-m-d', strtotime(trim($explode[0])));
            $date_end = date('Y-m-d', strtotime(trim($explode[1])));
            $query->andFilterWhere(['between', 'tanggal_kinerja', $date_start, $date_end]);

            $session['rangedate'] = $this->range_date;
        }
        

        $query->orderBy('tanggal_kinerja ASC');



        return $dataProvider;
    }

    public function searchHarikerja($params)
    {
        $id_user = Yii::$app->user->id;
        $user = AppUser::findOne($id_user);
        $query = Kinerja::find();
        $query->select(['tanggal_kinerja']);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_pegawai' => $user->pegawai_id,
        ]);

        if($this->range_date != null){
            $explode = explode('-',$this->range_date);
            $date_start = date('Y-m-d', strtotime(trim($explode[0])));
            $date_end = date('Y-m-d', strtotime(trim($explode[1])));
            $query->andFilterWhere(['between', 'tanggal_kinerja', $date_start, $date_end]);
        }
        

        $query->groupBy(['tanggal_kinerja']);



        return $dataProvider;
    }

    public function searchRekap(){
        $id_user = Yii::$app->user->id;
        $user = AppUser::findOne($id_user);

        $query = Kinerja::find()->leftJoin('tugas', 'kinerja.id_tugas = tugas.id_tugas')->leftJoin('kategori', 'tugas.id_kategori = kategori.id_kategori');

        $query->select(['kategori.nama_kategori AS nama_kategori','SUM(kinerja.jumlah) AS jumlah', 'kategori.poin_kategori AS poin_kategori']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andWhere(['kinerja.approval'=>1]);
        $query->andWhere(['kinerja.id_pegawai'=>$user->pegawai_id]);

        if($this->range_date != null){
            $explode = explode('-',$this->range_date);
            $date_start = date('Y-m-d', strtotime(trim($explode[0])));
            $date_end = date('Y-m-d', strtotime(trim($explode[1])));
            $query->andFilterWhere(['between', 'tanggal_kinerja', $date_start, $date_end]);
        }
        

        $query->groupBy('tugas.id_kategori');



        return $dataProvider;
    }

    public function searchRekapPerPegawai(){

        $query = Kinerja::find()->leftJoin('tugas', 'kinerja.id_tugas = tugas.id_tugas')->leftJoin('kategori', 'tugas.id_kategori = kategori.id_kategori');

        $query->select(['kategori.nama_kategori AS nama_kategori','SUM(kinerja.jumlah) AS jumlah', 'kategori.poin_kategori AS poin_kategori']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andWhere(['kinerja.approval'=>1]);
        $query->andWhere(['kinerja.id_pegawai'=>$this->id_pegawai]);

        if($this->range_date != null){
            $explode = explode('-',$this->range_date);
            $date_start = date('Y-m-d', strtotime(trim($explode[0])));
            $date_end = date('Y-m-d', strtotime(trim($explode[1])));
            $query->andFilterWhere(['between', 'tanggal_kinerja', $date_start, $date_end]);
        }
        

        $query->groupBy('tugas.id_kategori');



        return $dataProvider;
    }

    public function searchTugas(){
        $id_user = Yii::$app->user->id;
        $user = AppUser::findOne($id_user);

        $query = Kinerja::find()->leftJoin('tugas', 'kinerja.id_tugas = tugas.id_tugas')->leftJoin('kategori', 'tugas.id_kategori = kategori.id_kategori');

        $query->select(['tugas.nama_tugas AS nama_tugas','SUM(kinerja.jumlah) AS jumlah', 'kategori.poin_kategori AS poin_kategori']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 500,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andWhere(['kinerja.approval'=>1]);
        $query->andWhere(['kinerja.id_pegawai'=>$user->pegawai_id]);

        if($this->range_date != null){
            $explode = explode('-',$this->range_date);
            $date_start = date('Y-m-d', strtotime(trim($explode[0])));
            $date_end = date('Y-m-d', strtotime(trim($explode[1])));
            $query->andFilterWhere(['between', 'tanggal_kinerja', $date_start, $date_end]);
        }
        

        $query->groupBy('kinerja.id_tugas');



        return $dataProvider;
    }

}
