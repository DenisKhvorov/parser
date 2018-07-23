<?php
use yii\grid\GridView;
?>



<div class="row">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'office_title',
//            'office_price',
        [
                'attribute'=>'office_price',
                'value'=>function($model){
                    return $model->price;
                }
        ],
            'office_numbers',
        ],
    ]); ?>
</div>
