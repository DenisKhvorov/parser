<?php
use yii\grid\GridView;
?>



<div class="row">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns'=>[
                'office_title'
        ]
    ]); ?>
</div>
