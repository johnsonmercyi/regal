<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Result Sheet</title>

  <link rel="stylesheet" href="{{ asset('assets/css/nursery-result-style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/util-classes.css') }}">
</head>

<body>

  <div class="container">
    <div class="result-sheet">
      <section class="header">
        <div class="school-name-logo-wrapper">
          <div class="logo">
            <img width="100%" src="http://localhost:8000/storage/images/RTNPS/photo/school/schoolLogo_01_10_20_03_11_2022.jpg">
          </div>
          <div class="school-name-title-wrapper">
            <span class="school-name">
              {{count($school) > 0 ? $school[0]->name : ""}}
            </span>
            <span class="title">
              PUPIL'S PROGRESS REPORT
            </span>
          </div>
        </div>


        <div class="details details-1">
          <table>
            <tr>
              <td class="item item-1">
                <div class="label label-1">NAME:</div>
                <div class="val val-1" style="width: 30vw; text-align: left;">{{$studentName[0]->name}}</div>
              </td>

              <td class="item item-2">
                <div class="label label-2">NURSERY:</div>
                <div class="val val-2">{{substr_replace($studentClass[0]->student_class, "", 0, 7)}}</div>
              </td>

              <td class="item item-2">
                <div class="label label-2">AGE:</div>
                <div class="val val-2">{{$age[0]->age}}</div>
              </td>

              <td class="item item-3">
                <div class="label label-3">WEIGHT:</div>
                <div class="label label-3">W1:</div>
                <div class="val val-3">{{count($shwData) ? $shwData[0]->w1 ? $shwData[0]->w1."kg" : "" : ""}}</div>
                <div class="label label-3">W2:</div>
                <div class="val val-2">{{count($shwData) ? $shwData[0]->w2 ? $shwData[0]->w2."kg" : "": ""}}</div>
              </td>

            </tr>

            <tr>
              <td class="item item-3">
                <div class="label label-3">HEIGHT:</div>
                <div class="label label-3">H1:</div>
                <div class="val val-3">{{count($shwData) ? $shwData[0]->h1 ? $shwData[0]->h1."cm" : "" : ""}}</div>
                <div class="label label-3">H2:</div>
                <div class="val val-2">{{count($shwData) ? $shwData[0]->h2 ? $shwData[0]->h2."cm" : "" : ""}}</div>
              </td>

              <td class="item item-1 ">
                <div class="label label-1">TERM:</div>
                <div class="val val-1">{{$term}}</div>
              </td>

              <td class="item item-2">
                <div class="label label-2">SESSION:</div>
                <div class="val val-2">{{$session[0]->sessionName}}</div>
              </td>

              <td class="item item-3">
                <div class="label label-3" style="width: 15vw;">NEXT TERM BEGINS:</div>
                <div class="val val-2">{{count($startDate) > 0 ? $startDate[0]->startDate : ""}}</div>
              </td>
            </tr>

            <tr>
              <td class="item item-3 width_100">
                <div class="label label-3" style="width: 40vw;">NUMBER OF TIMES SCHOOL OPENED:</div>
                <div class="val val-2" style="width: 40%; text-align: left;"></div>
              </td>
              <td class="item item-1 width_100">
                <div class="label label-1" style="width: 30vw;">NUMBER OF TIMES ABSENT:</div>
                <div class="val val-1" style="width: 50%; text-align: left;"></div>
              </td>
            </tr>

            <tr>
              <td class="item item-1 width_100">
                @foreach($classTeachers as $key => $category)

                @endforeach
                <div class="label label-1" style="width: 15vw;">CLASS TEACHER:</div>
                <div class="val val-1" style="width: 100%; text-align: left">

                  {{count($classTeacher) > 0 ? $classTeacher[0]->name : ""}}
                </div>
              </td>
            </tr>

            <tr>
              <td class="item item-1 width_100">
                <div class="label label-1" style="width: 30vw;">CLASS TEACHER'S REMARKS:</div>

                <div class="val val-1" style="width: 100%; text-align: left;">{{count($comments) > 0 ? $comments[0]->formTeacherComment : ""}}</div>
              </td>
            </tr>

            <tr>
              <td class="item item-1 width_100">
                <div class="label label-1" style="width: 30vw;">HEAD TEACHER'S REMARKS:</div>
                <div class="val val-1" style="width: 100%; text-align: left;">{{count($comments) > 0 ? $comments[0]->headTeacherComment : ""}}</div>
              </td>
            </tr>

            <tr>
              <td class="item item-1 width_100">
                <div class="label label-1" style="width: 30vw;">CLASS TEACHER'S SIGNATURE:</div>
                <div class="val val-1" style="width: 50%;"></div>
              </td>

              <td class="item item-1 width_100">
                <div class="label label-1" style="width: 30vw;">HEAD TEACHER'S SIGNATURE:</div>
                <div class="val val-1" style="width: 50%;"></div>
              </td>
            </tr>
          </table>
        </div>
      </section>

      <section class="content">
        @foreach($subjectCategories as $key => $category)
        <div class="scores scores-{{$key+1}}">
          <span class="subject-category">{{$category->title}}</span>
          <div class="subjects subjects-1">
            <!-- Subjects -->
            @foreach($subjects as $key => $subject)
            <?php
            if ($subject->subject_category_id === $category->id) { ?>
              <div class="subject subject-{{$key+1}}">
                <div class="bullet"></div>
                <span class="name">{{$subject->title}}</span>
                <span class="score">{{$subject->score}}</span>
              </div>
            <?php } ?>
            @endforeach
          </div>
        </div>
        @endforeach
      </section>

      <div class="footer">
        <div class="keys">
          <span class="title">KEY</span>
          <div class="wrapper wrapper-1">
            <span class="key key-1"><span>H1</span> - BEGINNING OF TERM</span>
            <span class="key key-1"><span>H2</span> - END OF TERM</span>
            <span class="key key-1"><span>W1</span> - BEGINNING OF TERM</span>
            <span class="key key-1"><span>W2</span> - END OF TERM</span>
          </div>
          <div class="wrapper wrapper-2">
            <span class="key key-1"><span>5</span> - EXCELLENT</span>
            <span class="key key-2"><span>4</span> - VERY GOOD</span>
            <span class="key key-3"><span>3</span> - GOOD</span>
            <span class="key key-4"><span>2</span> - BEGINING TO SHOW THE TRAIT</span>
          </div>

          <div class="wrapper wrapper-3">
            <span class="key key-5"><span>1</span> - NOT AWARE YET/YET TO LEARN HOW</span>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>

</html>