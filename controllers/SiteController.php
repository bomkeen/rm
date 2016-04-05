<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Risk;

class SiteController extends Controller {
            public $enableCsrfValidation = false;


   public function behaviors() {

        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create','im','imdep', 'update','info', 'riskdep', 'riskteam'], //เฉพาะ action create,update
                'rules' => [
                    [
                        'allow' => true, //ยอมให้เข้าถึง
                        'roles' => ['@']//คนที่เข้าสู่ระบบ 
                    ]
                ]
            ],
        ];
    }

    public function actionMail($fullname) {
        Yii::$app->mailer->compose('@app/mail/layouts/register', [
                    'fullname' => 'อลิษา'
                ])
                ->setFrom(['bomkeendata@gmail.com' => 'พี่หมี'])
                ->setTo('iamaliz@gmail.com')
                ->setSubject('ส่งเมลได้แว้ว')
                ->send();
    }

   
    
    
    public function actionTop5sub() {
        $prorisk=0;
        $pro_risk_detail_id=0;
        $q2=[];
        $q3=[];
        $date1=  date("Y-m-d");
        $date2=  date("Y-m-d");
       $request = Yii::$app->request;
       $key=$request->get('key');
        
if (Yii::$app->request->isPost) {
       
    $request = Yii::$app->request;
    $date1=$request->post('date1');
    $date2=$request->post('date2');
    $key=$request->get('key');
            $prorisk=$request->post('prorisk');
            $pro_risk_detail_id=$request->post('pro_risk_detail_id');
        
            $c2 = Yii::$app->db->createCommand("select * FROM pro_risk_detail WHERE pro_risk_id=$prorisk");
        $q2 = $c2->queryAll(); 
}
           //$request = Yii::$app->request;
     if($key<>0){ 
         $request = Yii::$app->request;
           $prorisk=$request->get('prorisk');
           $pro_risk_detail_id=$request->get('pro_risk_detail_id');
           $date1=$request->get('date1');
           $date2=$request->get('date2');
           $key=$request->get('key');
           
           
           $c3 = Yii::$app->db->createCommand("SELECT 
COUNT(*) as n,r.severity_level as s
,psd.pro_risk_sub_detail_name 
FROM risk r
JOIN pro_risk_sub_detail as psd ON r.pro_risk_sub_detail_id=psd.pro_risk_sub_detail_id
where r.pro_risk_id= $prorisk 
and r.pro_risk_detail_id =$pro_risk_detail_id
and r.date_risk between '$date1' and '$date2'

GROUP BY r.severity_level 
ORDER BY n DESC limit 5");
        $q3 = $c3->queryAll(); 
     
       }
       
         
            
            
            return $this->render('top5sub',[
                'prorisk'=>$prorisk,
                'pro_de'=>$pro_risk_detail_id,
                'pro_risk_detail'=>$q2,
                'key'=>$key,
                'result'=>$q3,
                'date1'=>$date1,
                'date2'=>$date2,
            ]);
        }
    
public function actionIndex() {
        return $this->render('index');
    }
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
 public function actionTest() {
      
     $c = Yii::$app->db->createCommand("SELECT count(*) as dep_id,date_risk FROM risk group by date_stamp ORDER BY date_stamp");
        $events = $c->queryAll();
        
        $task=[];
        foreach ($events as $eve) {
            $event = new \yii2fullcalendar\models\Event();
            $event->id = 1;
            $event->title = $eve['dep_id'].' รายการ';
            $event->start = $eve['date_risk'];
            $task[] = $event;
            
        }
        return $this->render('test',[
            'events'=>$task,
        ]);
    }

    public function actionIm() {
      $c = Yii::$app->db->createCommand("SELECT count(*) as dep_id,date_risk FROM risk group by date_stamp ORDER BY date_stamp");
        $events = $c->queryAll();
        $task=[];
        foreach ($events as $eve) {
            $event = new \yii2fullcalendar\models\Event();
            $event->id = 1;
            $event->title = $eve['dep_id'].' รายการ';
            $event->start = $eve['date_risk'];
            $event->url =\yii\helpers\Url::to(['/report/matrixday','day'=>$eve['date_risk']]);
            $task[] = $event;
            
        }
        return $this->render('im',[
            'events'=>$task,
        ]);
    }
    
    public function actionImdep() {
        $session = Yii::$app->session;
        $dep = $session['dep'];
        $noti1 = [];
        $noti2 = [];
        $noti3 = [];
        if (Yii::$app->request->isPost) {
            $request = Yii::$app->request;
            $dep=$request->post('dep');
            }
        // calenda
        $c = Yii::$app->db->createCommand("SELECT count(*) as dep_id,date_risk,dep_id as dep FROM risk where dep_id=$dep group by date_stamp ORDER BY date_stamp");
        $events = $c->queryAll();
        $task=[];
        foreach ($events as $eve) {
            $event = new \yii2fullcalendar\models\Event();
            $event->id = 1;
            $event->title = $eve['dep_id'].' รายการ';
            $event->start = $eve['date_risk'];
            $event->url =\yii\helpers\Url::to(['/report/matrixdaydep','day'=>$eve['date_risk'],'dep'=>$eve['dep']]);
            $task[] = $event;
            
        }
        
        /// calendar end
        //
        ///// กล่องแรก
        
        $c1 = Yii::$app->db->createCommand("SELECT COUNT(*) AS total, 
SUM(CASE WHEN (follow_id <>1 or follow_id is NULL) THEN 1 ELSE 0 END) as un
,SUM(CASE WHEN date_stamp=DATE(now()) THEN 1 ELSE 0 END) as date
 FROM risk where dep_id =$dep");
        $q1 = $c1->queryAll();
        foreach ($q1 as $a1) {
            array_push($noti1, intval($a1['total']));
            array_push($noti2, intval($a1['un']));
            array_push($noti3, intval($a1['date']));
        }
       ///// หมดกล่องแรก
         $c2 = Yii::$app->db->createCommand("SELECT COUNT(*) as mn,m.code_color as color,m.color as cname FROM risk r,matrix m
WHERE m.born_id = r.born_id and m.severity_level = r.severity_level and r.dep_id=$dep
GROUP BY m.code_color");
        $q2 = $c2->queryAll();
        
        return $this->render('imdep',[
            'dep' => $dep,
            'events'=>$task,
            'noti1'=>$noti1,
            'noti2'=>$noti2,
            'noti3'=>$noti3,
            'q1'=>$q2,
        ]);
        
    }

    public function actionLogin() {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                        'model' => $model,
            ]);
        }
    }

    public function actionLogout() {
        Yii::$app->getUser()->logout();
        $session = \Yii::$app->session;
        $session->destroy();

        return $this->goHome();
    }

    public function actionContact() {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render('contact', [
                        'model' => $model,
            ]);
        }
    }

    public function actionAbout() {
        return $this->render('about');
    }

}
