<?php

namespace app\controllers;
use Yii;
use yii\data\SqlDataProvider;
use yii\data\ActiveDataProvider;
use app\models\Risk;
use app\models\RiskSearch;
use app\models\Dep;

use yii\filters\AccessControl;
use yii\behaviors\BlameableBehavior;

class ReportController extends \yii\web\Controller {


    public function behaviors() {

        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['sumdep','index','userreport','review'], //เฉพาะ action create,update
                'rules' => [
                    [
                        'allow' => true, //ยอมให้เข้าถึง
                        'roles' => ['@']//คนที่เข้าสู่ระบบ
                    ]
                ]
            ],
        ];
    }

public $enableCsrfValidation = false;
public function actionReview() {
        //$this->enableCsrfValidation = false;
        $searchModel = new RiskSearch();
       $date1= '2014-10-01';
       $date2=  date("Y-m-d");
       $user=Yii::$app->user->identity->id;
       if (Yii::$app->request->isPost) {
            $request = Yii::$app->request;
            $date1 = $request->post('date1');
            $date2 = $request->post('date2');
            $user=$request->post('user');
            }
$dataProvider = new ActiveDataProvider([
    'query' => Risk::find()->where(['user_id'=>$user])->andWhere(['between','date_stamp',$date1,$date2]),
    'key'=>'risk_id',
    'pagination' => [
       'pageSize' => 20,
    ],
]);
        return $this->render('review', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
            'date1'=>$date1,
            'date2'=>$date2,
            'user'=>$user
            
        ]);
    }
 public function actionInfo() {
       return $this->render('info');
    }

public function actionIndex() {
        $date1 = date("Y-m-d");
        $date2 = date("Y-m-d");
            if (Yii::$app->request->isPost) {
            $request = Yii::$app->request;
            $date1 = $request->post('date1');
            $date2 = $request->post('date2');
            }
              $sql = "select date_risk,
pr.pro_risk_name
,prd.pro_risk_detail_name
,prsd.pro_risk_sub_detail_name
,r.detail_prob
,dep.dep_name as dep_of_risk
,r.method
,f.follow_name
,p.name as name_report
,p2.name as name_edit
from risk r
LEFT OUTER JOIN pro_risk pr ON r.pro_risk_id=pr.pro_risk_id
LEFT OUTER JOIN pro_risk_detail prd ON r.pro_risk_detail_id=prd.pro_risk_detail_id
LEFT OUTER JOIN pro_risk_sub_detail prsd ON r.pro_risk_sub_detail_id=prsd.pro_risk_sub_detail_id
LEFT OUTER JOIN dep ON r.dep_id=dep.dep_id
LEFT OUTER JOIN profile p ON r.user_id=p.user_id
LEFT OUTER JOIN profile p2 ON r.edit_user_id=p2.user_id
LEFT OUTER JOIN follow f ON r.follow_id=f.follow_id where date_risk between '$date1' and '$date2'";

              $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
              $dataProvider = new \yii\data\ArrayDataProvider([
            //'key' => 'hoscode',//
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);
            $searchModel = new RiskSearch();

            return $this->render('index', [
                'date1' => $date1,
                'date2' => $date2,
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider, ]);
        }




        public function actionSumdep() {
        $date1 = date("Y-m-d");
        $date2 = date("Y-m-d");
            if (Yii::$app->request->isPost) {
            $request = Yii::$app->request;
            $date1 = $request->post('date1');
            $date2 = $request->post('date2');
            }
              $sql = "SELECT d.dep_name,COUNT(r.risk_id) as n,SUM(CASE WHEN r.follow_id = 1 THEN 1 ELSE 0 END ) as fix,SUM(CASE WHEN r.follow_id <>1 THEN 1 ELSE 0 END ) as nofix
FROM risk r JOIN dep d on d.dep_id=r.dep_id where r.date_risk between '$date1' and '$date2' GROUP BY r.dep_id";
              $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
              $dataProvider = new \yii\data\ArrayDataProvider([
            //'key' => 'hoscode',//
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);
            $searchModel = new RiskSearch();

            return $this->render('sumdep', [
                'date1' => $date1,
                'date2' => $date2,
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider, ]);
        }

        public function actionSumteam() {
        $date1 = date("Y-m-d");
        $date2 = date("Y-m-d");
            if (Yii::$app->request->isPost) {
            $request = Yii::$app->request;
            $date1 = $request->post('date1');
            $date2 = $request->post('date2');
            }
              $sql = "SELECT t.team_name,COUNT(r.risk_id) as n,SUM(CASE WHEN r.follow_id = 1 THEN 1 ELSE 0 END ) as fix,SUM(CASE WHEN r.follow_id <>1 THEN 1 ELSE 0 END ) as nofix
FROM risk r JOIN team t ON r.team_id=t.team_id where r.date_risk between '$date1' and '$date2' GROUP BY r.team_id";
              $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
              $dataProvider = new \yii\data\ArrayDataProvider([
            //'key' => 'hoscode',//
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);
            $searchModel = new RiskSearch();

            return $this->render('sumteam', [
                'date1' => $date1,
                'date2' => $date2,
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider, ]);
        }

        public function actionMatrixlink($born,$score) {

             $date1 = "2014-10-01";
        $date2 = date("Y-m-d");
            if (Yii::$app->request->isPost) {
            $request = Yii::$app->request;
            $date1 = $request->post('date1');
            $date2 = $request->post('date2');
            }

                     $sql = "SELECT m.code_color as color,m.color as cname,b.born_name,r.severity_level,
                  r.date_risk,pr.pro_risk_name,prd.pro_risk_detail_name,prsd.pro_risk_sub_detail_name,
                  r.detail_prob,dep.dep_name AS dep_of_risk,r.method,f.follow_name,p. NAME AS name_report,
                  p2. NAME AS name_edit,r.follow_id as follow_id FROM risk r
                  LEFT OUTER JOIN pro_risk pr ON r.pro_risk_id = pr.pro_risk_id
LEFT OUTER JOIN pro_risk_detail prd ON r.pro_risk_detail_id = prd.pro_risk_detail_id
LEFT OUTER JOIN pro_risk_sub_detail prsd ON r.pro_risk_sub_detail_id = prsd.pro_risk_sub_detail_id
LEFT OUTER JOIN dep ON r.dep_id = dep.dep_id
LEFT OUTER JOIN profile p ON r.user_id = p.user_id
LEFT OUTER JOIN profile p2 ON r.edit_user_id = p2.user_id
LEFT OUTER JOIN follow f ON r.follow_id = f.follow_id
LEFT OUTER JOIN born b on r.born_id=b.born_id
JOIN matrix m ON m.born_id = r.born_id and m.severity_level = r.severity_level
where r.born_id=$born AND m.score=$score and r.date_risk between '$date1' and '$date2' ORDER BY m.score DESC";
              $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
              $dataProvider = new \yii\data\ArrayDataProvider([
            //'key' => 'hoscode',//
            'allModels' => $rawData,
           //'pagination' => ['pageSize' => 5,],
        ]);
            $searchModel = new RiskSearch();

            return $this->render('matrixlink', [
                'date1' => $date1,
                'date2' => $date2,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider, ]);
        }



         public function actionMatrixall() {
        $date1 = date("Y-m-d");
        $date2 = date("Y-m-d");
            if (Yii::$app->request->isPost) {
            $request = Yii::$app->request;
            $date1 = $request->post('date1');
            $date2 = $request->post('date2');
            }
              $sql = "SELECT m.code_color as color,m.color as cname,b.born_name,r.severity_level,
                  r.date_risk,pr.pro_risk_name,prd.pro_risk_detail_name,prsd.pro_risk_sub_detail_name,
                  r.detail_prob,dep.dep_name AS dep_of_risk,r.method,f.follow_name,p. NAME AS name_report,
                  p2. NAME AS name_edit,r.follow_id as follow_id FROM risk r
                  LEFT OUTER JOIN pro_risk pr ON r.pro_risk_id = pr.pro_risk_id
LEFT OUTER JOIN pro_risk_detail prd ON r.pro_risk_detail_id = prd.pro_risk_detail_id
LEFT OUTER JOIN pro_risk_sub_detail prsd ON r.pro_risk_sub_detail_id = prsd.pro_risk_sub_detail_id
LEFT OUTER JOIN dep ON r.dep_id = dep.dep_id
LEFT OUTER JOIN profile p ON r.user_id = p.user_id
LEFT OUTER JOIN profile p2 ON r.edit_user_id = p2.user_id
LEFT OUTER JOIN follow f ON r.follow_id = f.follow_id
LEFT OUTER JOIN born b on r.born_id=b.born_id
JOIN matrix m ON m.born_id = r.born_id and m.severity_level = r.severity_level where r.date_risk between '$date1' and '$date2' ORDER BY m.score DESC";
              $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
              $dataProvider = new \yii\data\ArrayDataProvider([
            //'key' => 'hoscode',//
            'allModels' => $rawData,
           'pagination' => ['pageSize' => 10,],
        ]);
            $searchModel = new RiskSearch();

            return $this->render('matrixall', [
                'date1' => $date1,
                'date2' => $date2,
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider, ]);
        }

        public function actionTablesum() {
            $date1=  date("Y-m-d");
            $date2=  date("Y-m-d");

            if (Yii::$app->request->isPost) {
            $request = Yii::$app->request;
            $date1 = $request->post('date1');
            $date2 = $request->post('date2');
            }


            $clinic = Yii::$app->db->createCommand("SELECT
r.severity_level as level
,COUNT(*) as n
,SUM(r.num) as sum
FROM risk r
where r.clinic_id in (1) and r.date_risk between'$date1' and '$date2'
group by r.severity_level
ORDER BY r.severity_level ");
        $c1 = $clinic->queryAll();

         $clinic2 = Yii::$app->db->createCommand("SELECT
r.severity_level as level
,COUNT(*) as n
,SUM(r.num) as sum
FROM risk r
where r.clinic_id in (2) and r.date_risk between'$date1' and '$date2'
group by r.severity_level
ORDER BY r.severity_level ");
        $c2 = $clinic2->queryAll();

        $nonclinic = Yii::$app->db->createCommand("SELECT
r.severity_level as level
,COUNT(*) as n
,SUM(r.num) as sum
FROM risk r
where r.clinic_id in (3) and r.date_risk between '$date1' and '$date2'
group by r.severity_level
ORDER BY r.severity_level ");

        $nc = $nonclinic->queryAll();

        return $this->render('tablesum',[
            'c1'=>$c1,
            'c2'=>$c2,
            'nc'=>$nc,
            'date1'=>$date1,
            'date2'=>$date2,
        ]);
    }

        public function actionMatrixteam() {
        $date1 = date("Y-m-d");
        $date2 = date("Y-m-d");
        $session = \yii::$app->session;
        $team=$session['team'];
            if (Yii::$app->request->isPost) {
            $request = Yii::$app->request;
            $date1 = $request->post('date1');
            $date2 = $request->post('date2');
            }
              $sql = "SELECT m.code_color as color,m.color as cname,b.born_name,r.severity_level,
                  r.date_risk,pr.pro_risk_name,prd.pro_risk_detail_name,prsd.pro_risk_sub_detail_name,
                  r.detail_prob,dep.dep_name AS dep_of_risk,r.method,f.follow_name,p. NAME AS name_report,
                  p2. NAME AS name_edit,r.follow_id as follow_id FROM risk r
                  LEFT OUTER JOIN pro_risk pr ON r.pro_risk_id = pr.pro_risk_id
LEFT OUTER JOIN pro_risk_detail prd ON r.pro_risk_detail_id = prd.pro_risk_detail_id
LEFT OUTER JOIN pro_risk_sub_detail prsd ON r.pro_risk_sub_detail_id = prsd.pro_risk_sub_detail_id
LEFT OUTER JOIN dep ON r.dep_id = dep.dep_id
LEFT OUTER JOIN profile p ON r.user_id = p.user_id
LEFT OUTER JOIN profile p2 ON r.edit_user_id = p2.user_id
LEFT OUTER JOIN follow f ON r.follow_id = f.follow_id
LEFT OUTER JOIN born b on r.born_id=b.born_id
JOIN matrix m ON m.born_id = r.born_id and m.severity_level = r.severity_level
where r.date_risk between '$date1' and '$date2' and r.team_id=$team ORDER BY m.score DESC";
              $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
              $dataProvider = new \yii\data\ArrayDataProvider([
            //'key' => 'hoscode',//
            'allModels' => $rawData,
           'pagination' => ['pageSize' => 10,],
        ]);
            $searchModel = new RiskSearch();

            return $this->render('matrixteam', [
                'date1' => $date1,
                'date2' => $date2,
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider, ]);
        }

        public function actionMatrixdaydep($day,$dep) {
        $date1 = $day;
        $date2 = $day;
        $dep_id=$dep;
            if (Yii::$app->request->isPost) {
            $request = Yii::$app->request;
            $date1 = $request->post('date1');
            $date2 = $request->post('date2');
            }
              $sql = "SELECT m.code_color as color,m.color as cname,b.born_name,r.severity_level,
                  r.date_risk,pr.pro_risk_name,prd.pro_risk_detail_name,prsd.pro_risk_sub_detail_name,
                  r.detail_prob,dep.dep_name AS dep_of_risk,r.method,f.follow_name,p. NAME AS name_report,
                  p2. NAME AS name_edit,r.follow_id as follow_id FROM risk r
                  LEFT OUTER JOIN pro_risk pr ON r.pro_risk_id = pr.pro_risk_id
LEFT OUTER JOIN pro_risk_detail prd ON r.pro_risk_detail_id = prd.pro_risk_detail_id
LEFT OUTER JOIN pro_risk_sub_detail prsd ON r.pro_risk_sub_detail_id = prsd.pro_risk_sub_detail_id
LEFT OUTER JOIN dep ON r.dep_id = dep.dep_id
LEFT OUTER JOIN profile p ON r.user_id = p.user_id
LEFT OUTER JOIN profile p2 ON r.edit_user_id = p2.user_id
LEFT OUTER JOIN follow f ON r.follow_id = f.follow_id
LEFT OUTER JOIN born b on r.born_id=b.born_id
JOIN matrix m ON m.born_id = r.born_id and m.severity_level = r.severity_level where r.date_risk between '$date1' and '$date2' and r.dep_id=$dep_id ORDER BY m.score DESC";
              $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
              $dataProvider = new \yii\data\ArrayDataProvider([
            //'key' => 'hoscode',//
            'allModels' => $rawData,
           //'pagination' => ['pageSize' => 5,],
        ]);
            $searchModel = new RiskSearch();

            return $this->render('matrixdaydep', [
                'date1' => $date1,
                'date2' => $date2,
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider, ]);
        }


        public function actionMatrixday($day) {
        $date1 = $day;
        $date2 = $day;
            if (Yii::$app->request->isPost) {
            $request = Yii::$app->request;
            $date1 = $request->post('date1');
            $date2 = $request->post('date2');
            }
              $sql = "SELECT m.code_color as color,m.color as cname,b.born_name,r.severity_level,
                  r.date_risk,pr.pro_risk_name,prd.pro_risk_detail_name,prsd.pro_risk_sub_detail_name,
                  r.detail_prob,dep.dep_name AS dep_of_risk,r.method,f.follow_name,p. NAME AS name_report,
                  p2. NAME AS name_edit,r.follow_id as follow_id FROM risk r
                  LEFT OUTER JOIN pro_risk pr ON r.pro_risk_id = pr.pro_risk_id
LEFT OUTER JOIN pro_risk_detail prd ON r.pro_risk_detail_id = prd.pro_risk_detail_id
LEFT OUTER JOIN pro_risk_sub_detail prsd ON r.pro_risk_sub_detail_id = prsd.pro_risk_sub_detail_id
LEFT OUTER JOIN dep ON r.dep_id = dep.dep_id
LEFT OUTER JOIN profile p ON r.user_id = p.user_id
LEFT OUTER JOIN profile p2 ON r.edit_user_id = p2.user_id
LEFT OUTER JOIN follow f ON r.follow_id = f.follow_id
LEFT OUTER JOIN born b on r.born_id=b.born_id
JOIN matrix m ON m.born_id = r.born_id and m.severity_level = r.severity_level where r.date_risk between '$date1' and '$date2'  ORDER BY m.score DESC";
              $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
              $dataProvider = new \yii\data\ArrayDataProvider([
            //'key' => 'hoscode',//
            'allModels' => $rawData,
           //'pagination' => ['pageSize' => 5,],
        ]);
            $searchModel = new RiskSearch();

            return $this->render('matrixday', [
                'date1' => $date1,
                'date2' => $date2,
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider, ]);
        }

       public function actionMatrixdep() {
        $date1 = date("Y-m-d");
        $date2 = date("Y-m-d");
        $dep="";
            if (Yii::$app->request->isPost) {
            $request = Yii::$app->request;
            $date1 = $request->post('date1');
            $date2 = $request->post('date2');
            $dep=$request->post('dep');
            }
            $sql="SELECT m.code_color as color,m.color as cname,b.born_name,r.severity_level,
                  r.date_risk,pr.pro_risk_name,prd.pro_risk_detail_name,prsd.pro_risk_sub_detail_name,
                  r.detail_prob,dep.dep_name AS dep_of_risk,r.method,f.follow_name,p. NAME AS name_report,
                  p2. NAME AS name_edit,r.follow_id as follow_id FROM risk r
                  LEFT OUTER JOIN pro_risk pr ON r.pro_risk_id = pr.pro_risk_id
LEFT OUTER JOIN pro_risk_detail prd ON r.pro_risk_detail_id = prd.pro_risk_detail_id
LEFT OUTER JOIN pro_risk_sub_detail prsd ON r.pro_risk_sub_detail_id = prsd.pro_risk_sub_detail_id
LEFT OUTER JOIN dep ON r.dep_id = dep.dep_id LEFT OUTER JOIN profile p ON r.user_id = p.user_id
LEFT OUTER JOIN profile p2 ON r.edit_user_id = p2.user_id LEFT OUTER JOIN follow f ON r.follow_id = f.follow_id
LEFT OUTER JOIN born b on r.born_id=b.born_id JOIN matrix m ON m.born_id = r.born_id and m.severity_level = r.severity_level
where r.date_risk between '$date1' and '$date2'  ORDER BY m.score DESC";
              if ($dep != '') {
              $sql = "SELECT m.code_color as color,m.color as cname,b.born_name,r.severity_level,
                  r.date_risk,pr.pro_risk_name,prd.pro_risk_detail_name,prsd.pro_risk_sub_detail_name,
                  r.detail_prob,dep.dep_name AS dep_of_risk,r.method,f.follow_name,p. NAME AS name_report,
                  p2. NAME AS name_edit,r.follow_id as follow_id FROM risk r
                  LEFT OUTER JOIN pro_risk pr ON r.pro_risk_id = pr.pro_risk_id
LEFT OUTER JOIN pro_risk_detail prd ON r.pro_risk_detail_id = prd.pro_risk_detail_id
LEFT OUTER JOIN pro_risk_sub_detail prsd ON r.pro_risk_sub_detail_id = prsd.pro_risk_sub_detail_id
LEFT OUTER JOIN dep ON r.dep_id = dep.dep_id LEFT OUTER JOIN profile p ON r.user_id = p.user_id
LEFT OUTER JOIN profile p2 ON r.edit_user_id = p2.user_id LEFT OUTER JOIN follow f ON r.follow_id = f.follow_id
LEFT OUTER JOIN born b on r.born_id=b.born_id JOIN matrix m ON m.born_id = r.born_id and m.severity_level = r.severity_level
where r.date_risk between '$date1' and '$date2' and r.dep_id=$dep ORDER BY m.score DESC";}
        $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
              $dataProvider = new \yii\data\ArrayDataProvider([
            //'key' => 'hoscode',//
            'allModels' => $rawData,
           //'pagination' => ['pageSize' => 5,],
        ]);
            $searchModel = new RiskSearch();

            return $this->render('matrixdep', [
                'date1' => $date1,
                'date2' => $date2,
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                'dep' => $dep,
                ]);
        }

        /////////////////////////////////////////////////
        public function actionUserreport() {
        $date1 = date("Y-m-d");
        $date2 = date("Y-m-d");
        $user="";
            if (Yii::$app->request->isPost) {
            $request = Yii::$app->request;
            $date1 = $request->post('date1');
            $date2 = $request->post('date2');
            $user=$request->post('user');
            }
            $sql="SELECT m.code_color as color,m.color as cname,b.born_name,r.severity_level,
                  r.date_risk,pr.pro_risk_name,prd.pro_risk_detail_name,prsd.pro_risk_sub_detail_name,
                  r.detail_prob,dep.dep_name AS dep_of_risk,r.method,f.follow_name,p. NAME AS name_report,
                  p2. NAME AS name_edit,r.follow_id as follow_id FROM risk r
                  LEFT OUTER JOIN pro_risk pr ON r.pro_risk_id = pr.pro_risk_id
LEFT OUTER JOIN pro_risk_detail prd ON r.pro_risk_detail_id = prd.pro_risk_detail_id
LEFT OUTER JOIN pro_risk_sub_detail prsd ON r.pro_risk_sub_detail_id = prsd.pro_risk_sub_detail_id
LEFT OUTER JOIN dep ON r.dep_id = dep.dep_id LEFT OUTER JOIN profile p ON r.user_id = p.user_id
LEFT OUTER JOIN profile p2 ON r.edit_user_id = p2.user_id LEFT OUTER JOIN follow f ON r.follow_id = f.follow_id
LEFT OUTER JOIN born b on r.born_id=b.born_id JOIN matrix m ON m.born_id = r.born_id and m.severity_level = r.severity_level
where r.date_risk between '$date1' and '$date2' and r.user_id = '$user' ORDER BY m.score DESC";
              if ($user != '') {
              $sql = "SELECT m.code_color as color,m.color as cname,b.born_name,r.severity_level,
                  r.date_risk,pr.pro_risk_name,prd.pro_risk_detail_name,prsd.pro_risk_sub_detail_name,
                  r.detail_prob,dep.dep_name AS dep_of_risk,r.method,f.follow_name,p. NAME AS name_report,
                  p2. NAME AS name_edit,r.follow_id as follow_id FROM risk r
                  LEFT OUTER JOIN pro_risk pr ON r.pro_risk_id = pr.pro_risk_id
LEFT OUTER JOIN pro_risk_detail prd ON r.pro_risk_detail_id = prd.pro_risk_detail_id
LEFT OUTER JOIN pro_risk_sub_detail prsd ON r.pro_risk_sub_detail_id = prsd.pro_risk_sub_detail_id
LEFT OUTER JOIN dep ON r.dep_id = dep.dep_id LEFT OUTER JOIN profile p ON r.user_id = p.user_id
LEFT OUTER JOIN profile p2 ON r.edit_user_id = p2.user_id LEFT OUTER JOIN follow f ON r.follow_id = f.follow_id
LEFT OUTER JOIN born b on r.born_id=b.born_id JOIN matrix m ON m.born_id = r.born_id and m.severity_level = r.severity_level
where r.date_risk between '$date1' and '$date2' and r.user_id = '$user'  ORDER BY m.score DESC";}
        $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
              $dataProvider = new \yii\data\ArrayDataProvider([
            //'key' => 'hoscode',//
            'allModels' => $rawData,
           //'pagination' => ['pageSize' => 5,],
        ]);

            $searchModel = new RiskSearch();

            return $this->render('userreport', [
                'date1' => $date1,
                'date2' => $date2,
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                'user'=>$user

                ]);
        }
        ///////////////////////////////////////////

                public function actionTeamreport() {
        $date1 = date("Y-m-d");
        $date2 = date("Y-m-d");
        $user="";
            if (Yii::$app->request->isPost) {
            $request = Yii::$app->request;
            $date1 = $request->post('date1');
            $date2 = $request->post('date2');
            $user=$request->post('user');
            }
            $sql="SELECT m.code_color as color,m.color as cname,b.born_name,r.severity_level,
                  r.date_risk,pr.pro_risk_name,prd.pro_risk_detail_name,prsd.pro_risk_sub_detail_name,
                  r.detail_prob,dep.dep_name AS dep_of_risk,r.method,f.follow_name,p. NAME AS name_report,
                  p2. NAME AS name_edit,r.follow_id as follow_id FROM risk r
                  LEFT OUTER JOIN pro_risk pr ON r.pro_risk_id = pr.pro_risk_id
LEFT OUTER JOIN pro_risk_detail prd ON r.pro_risk_detail_id = prd.pro_risk_detail_id
LEFT OUTER JOIN pro_risk_sub_detail prsd ON r.pro_risk_sub_detail_id = prsd.pro_risk_sub_detail_id
LEFT OUTER JOIN dep ON r.dep_id = dep.dep_id LEFT OUTER JOIN profile p ON r.user_id = p.user_id
LEFT OUTER JOIN profile p2 ON r.edit_user_id = p2.user_id LEFT OUTER JOIN follow f ON r.follow_id = f.follow_id
LEFT OUTER JOIN born b on r.born_id=b.born_id JOIN matrix m ON m.born_id = r.born_id and m.severity_level = r.severity_level
where r.date_risk between '$date1' and '$date2' and r.user_id = '$user' ORDER BY m.score DESC";
              if ($user != '') {
              $sql = "SELECT m.code_color as color,m.color as cname,b.born_name,r.severity_level,
                  r.date_risk,pr.pro_risk_name,prd.pro_risk_detail_name,prsd.pro_risk_sub_detail_name,
                  r.detail_prob,dep.dep_name AS dep_of_risk,r.method,f.follow_name,p. NAME AS name_report,
                  p2. NAME AS name_edit,r.follow_id as follow_id FROM risk r
                  LEFT OUTER JOIN pro_risk pr ON r.pro_risk_id = pr.pro_risk_id
LEFT OUTER JOIN pro_risk_detail prd ON r.pro_risk_detail_id = prd.pro_risk_detail_id
LEFT OUTER JOIN pro_risk_sub_detail prsd ON r.pro_risk_sub_detail_id = prsd.pro_risk_sub_detail_id
LEFT OUTER JOIN dep ON r.dep_id = dep.dep_id LEFT OUTER JOIN profile p ON r.user_id = p.user_id
LEFT OUTER JOIN profile p2 ON r.edit_user_id = p2.user_id LEFT OUTER JOIN follow f ON r.follow_id = f.follow_id
LEFT OUTER JOIN born b on r.born_id=b.born_id JOIN matrix m ON m.born_id = r.born_id and m.severity_level = r.severity_level
where r.date_risk between '$date1' and '$date2' and r.user_id = '$user'  ORDER BY m.score DESC";}
        $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
              $dataProvider = new \yii\data\ArrayDataProvider([
            //'key' => 'hoscode',//
            'allModels' => $rawData,
           //'pagination' => ['pageSize' => 5,],
        ]);

            $searchModel = new RiskSearch();

            return $this->render('teamreport', [
                'date1' => $date1,
                'date2' => $date2,
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                'user'=>$user

                ]);
        }
        ///////////////////////////////////////////







              }
