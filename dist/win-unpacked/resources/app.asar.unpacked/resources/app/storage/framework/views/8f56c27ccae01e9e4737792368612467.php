<!DOCTYPE html>
<html dir="rtl" lang="ar" >

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data</title>
    <style>
        body {
            margin: 20px;
            border: black solid 1px;
        }

        .header {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center; /* Ensure text is centered */
        }

        .header img {
            margin-right: 10px; /* Add some space between the image and the text */
        }
    </style>
</head>

<body>
 <div>
        <div class="header">
            <img width="100px" src="<?php echo e(asset('assets/icons/male-dark.svg')); ?>" alt="" />
            <h1><?php echo e($data->full_name); ?></h1>
        </div>

        <div>
            <p style="text-align: center ; font-weight: bold;">المعلومات العامة</p>
            <hr>
        </div>
        <ul>
            <li style="font-weight: bold;">الإسم الكامل : <span style="font-weight: normal;"><?php echo e($data->full_name); ?></span></li>
            <li style="font-weight: bold;">العمر: <span style="font-weight: normal;"><?php echo e($data->age); ?></span></li>
            <li style="font-weight: bold;">المدينة: <span style="font-weight: normal;"><?php echo e($data->city); ?></span></li>
            <li style="font-weight: bold;">الحالة الإجتماعية: <span style="font-weight: normal;"><?php echo e($data->marital_status); ?></span></li>
            <li style="font-weight: bold;">الرقم الهاتفي: <span dir="ltr" style="font-weight: normal;"><?php echo e($data->phone); ?></span></li>
            <li style="font-weight: bold;">العمل: <span style="font-weight: normal;"><?php echo e($data->job); ?></span></li>
        </ul>

        <div>
            <p style="text-align: center; font-weight: bold;">المواصفات</p>

            <hr>
        </div>

        <ul>
            <li style="font-weight: bold;">الطول: <span style="font-weight: normal;"><?php echo e($data->height); ?></span></li>
            <li style="font-weight: bold;">الوزن: <span style="font-weight: normal;"><?php echo e($data->weight); ?></span></li>
            <li style="font-weight: bold;">لون البشرة: <span style="font-weight: normal;"><?php echo e($data->skin_color); ?></span></li>
            <li style="font-weight: bold;">المستوى الدراسي: <span style="font-weight: normal;"><?php echo e($data->education_level); ?></span></li>
            <li style="font-weight: bold;">ملتحي: <span style="font-weight: normal;"><?php echo e($data->has_beard ? "نعم" : "لا"); ?></span></li>
            <li style="font-weight: bold;">عقيدتك: <span style="font-weight: normal;"><?php echo e($data->creed); ?></span></li>
            <li style="font-weight: bold;">تصلي الفريضة مع الجماعة: <span style="font-weight: normal;"><?php echo e($data->prays_with_jamaah ? "نعم" : "لا"); ?></span></li>
            <li style="font-weight: bold;">شيوخك ممن تأخد الفتوى: <span style="font-weight: normal;"><?php echo e($data->scholars_you_follow); ?></span></li>
            <li style="font-weight: bold;">تعاني من مرض معين: <span style="font-weight: normal;"><?php echo e($data->medical_illness ? "نعم" : "لا"); ?></span></li>
            <li style="font-weight: bold;">ستوفر سكنا مستقلا أم مع الوالدين: <span style="font-weight: normal;"><?php echo e($data->housing_situation); ?></span></li>
        </ul>

        <div>
            <p style="text-align: center; font-weight: bold;">الشروط</p>

            <hr>
        </div>

        <ul>
            <li style="font-weight: bold;">تقبل بها عاملة أو جامعية: <span style="font-weight: normal;"><?php echo e($data->accepts_working_wife ? "نعم" : "لا"); ?></span></li>
            <li style="font-weight: bold;">المدن التي تفضل الزواج منها: <span style="font-weight: normal;"><?php echo e($data->preferred_cities_for_marriage); ?></span></li>
            <li style="font-weight: bold;">تقبل مطلقة او ارملة: <span style="font-weight: normal;"><?php echo e($data->accepts_divorced_or_widowed ? "نعم" : "لا"); ?></span></li>
            <li style="font-weight: bold;">أقصى العمر الذي تقبل به: <span style="font-weight: normal;"><?php echo e($data->maximum_accepted_age); ?></span></li>
            <li style="font-weight: bold;">شروطك في الطرف الاخر: <span style="font-weight: normal;"><?php echo e($data->partner_requirements); ?></span></li>
            <li style="font-weight: bold;">شيء ما تود اخباره للطرف الاخر: <span style="font-weight: normal;"><?php echo e($data->message_to_partner); ?></span></li>
        </ul>
    </div>

</body>

</html><?php /**PATH /home/adam/Desktop/Archive/Currents/Minassah-Desktop/resources/views/pdf/applicantDocument.blade.php ENDPATH**/ ?>