<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>M applicants</h1>
    @foreach ($m_applicants as $applicant)
        <p>{{ $applicant->full_name }}</p>
    @endforeach
</body>
</html>