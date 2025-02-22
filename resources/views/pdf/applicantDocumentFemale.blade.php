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
            <img width="100px" src="{{ asset('assets/icons/female-dark.svg') }}" alt="" />
            <h1>{{ $data->full_name }}</h1>
        </div>

        <div>
            <p style="text-align: center ; font-weight: bold;">المعلومات العامة</p>
            <hr>
        </div>
        <ul>
            <li style="font-weight: bold;">الإسم الكامل : <span style="font-weight: normal;">{{ $data->full_name }}</span></li>
            <li style="font-weight: bold;">العمر: <span style="font-weight: normal;">{{ $data->age }}</span></li>
            <li style="font-weight: bold;">المدينة: <span style="font-weight: normal;">{{ $data->city }}</span></li>
            <li style="font-weight: bold;">الحالة الإجتماعية: <span style="font-weight: normal;">{{ $data->marital_status }}</span></li>
            <li style="font-weight: bold;">الرقم الهاتفي: <span dir="ltr" style="font-weight: normal;">{{ $data->phone }}</span></li>
            <li style="font-weight: bold;">العمل: <span style="font-weight: normal;">{{ $data->job }}</span></li>
        </ul>

        <div>
            <p style="text-align: center; font-weight: bold;">المواصفات</p>

            <hr>
        </div>

        <ul>
            <li style="font-weight: bold;">الطول: <span style="font-weight: normal;">{{ $data->height }}</span></li>
            <li style="font-weight: bold;">الوزن: <span style="font-weight: normal;">{{ $data->weight }}</span></li>
            <li style="font-weight: bold;">لون البشرة: <span style="font-weight: normal;">{{ $data->skin_color }}</span></li>
            <li style="font-weight: bold;">المستوى الدراسي: <span style="font-weight: normal;">{{ $data->education_level }}</span></li>
            <li style="font-weight: bold;">منتقبة: <span style="font-weight: normal;">{{ $data->wears_niqab ? "نعم" : "لا" }}</span></li>
            <li style="font-weight: bold;">عقيدتك: <span style="font-weight: normal;">{{ $data->creed }}</span></li>
            <li style="font-weight: bold;">تصلين الفريضة في وقتها: <span style="font-weight: normal;">{{ $data->prays_on_time ? "نعم" : "لا" }}</span></li>
            <li style="font-weight: bold;">شيوخك ممن تأخد الفتوى: <span style="font-weight: normal;">{{ $data->scholars_you_follow }}</span></li>
            <li style="font-weight: bold;">والداك يعارضان تزويجك: <span style="font-weight: normal;">{{ $data->medical_illness ? "نعم" : "لا" }}</span></li>
            
            <li style="font-weight: bold;">ستوفر سكنا مستقلا أم مع الوالدين: <span style="font-weight: normal;">{{ $data->housing_situation }}</span></li>
        
        </ul>

        <div>
            <p style="text-align: center; font-weight: bold;">الشروط</p>

            <hr>
        </div>

        <ul>
            <li style="font-weight: bold;">انت على استعداد للقرار في البيت: <span style="font-weight: normal;">{{ $data->willing_to_stay_home ? "نعم" : "لا" }}</span></li>
            <li style="font-weight: bold;">تقبلين مطلقا أو أرملا: <span style="font-weight: normal;">{{ $data->accepts_living_with_inlaws ? "نعم" : "لا" }}</span></li>
            <li style="font-weight: bold;">هل تقبلين بالسكن مع والدي الزوج: <span style="font-weight: normal;">{{ $data->accepts_divorced_or_widowed ? "نعم" : "لا" }}</span></li>
            <li style="font-weight: bold;"> أقصى العمر الذي تقبلين به: <span style="font-weight: normal;">{{ $data->maximum_accepted_age }}</span></li>
            <li style="font-weight: bold;">شروطك في الطرف الاخر: <span style="font-weight: normal;">{{ $data->partner_requirements }}</span></li>
            <li style="font-weight: bold;">شيء ما تود اخباره للطرف الاخر: <span style="font-weight: normal;">{{ $data->message_to_partner }}</span></li>
        </ul>
    </div>

</body>

</html>